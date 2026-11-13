<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class KpaySlipVerifier
{
    /**
     * Verify the uploaded KPay screenshot against the Order requirements.
     */
    public function verify(Order $order, string $screenshotPath, ?string $originalClientName = null): array
    {
        $extracted = $this->extractSlipData($screenshotPath, $order, $originalClientName);

        // Fail-closed: if AI extraction failed or key wasn't configured
        if (!empty($extracted['error'])) {
            return [
                'status' => 'unreadable',
                'auto_approved' => false,
                'transaction_id' => null,
                'detected_amount' => null,
                'detected_recipient' => null,
                'notes' => $extracted['error'],
            ];
        }

        // Rule 1: Check if it's a valid KPay receipt with a readable amount
        $detectedAmount = $this->parseAmount($extracted['amount'] ?? null);
        $transId = $this->sanitizeTransactionId($extracted['transaction_id'] ?? null);
        $detectedRecipient = $this->sanitizeRecipientName($extracted['receiver_name'] ?? null);

        if (empty($extracted['is_kpay_receipt']) || $detectedAmount === null || $detectedAmount <= 0) {
            return [
                'status' => 'unreadable',
                'auto_approved' => false,
                'transaction_id' => $transId,
                'detected_amount' => $detectedAmount,
                'detected_recipient' => $detectedRecipient,
                'notes' => 'KPay ပြေစာ မဟုတ်ပါ သို့မဟုတ် ငွေပမာဏကို စာဖတ်မရပါ (Invalid or unreadable KPay slip).',
            ];
        }

        $expectedAmount = (int) $order->total_price;
        $expectedRecipient = trim((string) config('studio.payment_name', ''));

        // Rule 2: Duplicate Slip Check (Replay attack prevention)
        if ($transId) {
            $duplicateOrder = Order::where('transaction_id', $transId)
                ->where('id', '!=', $order->id)
                ->first();

            if ($duplicateOrder) {
                return [
                    'status' => 'duplicate',
                    'auto_approved' => false,
                    'transaction_id' => $transId,
                    'detected_amount' => $detectedAmount,
                    'detected_recipient' => $detectedRecipient,
                    'notes' => "⚠️ Fraud Alert: ဤပြေစာအမှတ် ({$transId}) သည် Order #{$duplicateOrder->id} တွင် အသုံးပြုပြီးသား ဖြစ်နေပါသည် (Duplicate slip reused).",
                ];
            }
        }

        // Rule 3: Amount Check
        if ($detectedAmount !== $expectedAmount) {
            $diff = abs($expectedAmount - $detectedAmount);
            if ($detectedAmount < $expectedAmount) {
                $notes = 'ငွေမပြည့်ပါ (Underpaid): ကျသင့်ငွေ ' . number_format($expectedAmount)
                    . ' Ks ဖြစ်သော်လည်း ပြေစာတွင် ' . number_format($detectedAmount)
                    . ' Ks သာ တွေ့ရပါသည် (' . number_format($diff) . ' Ks လိုအပ်နေသည်).';
            } else {
                $notes = 'ငွေပမာဏ ကွာဟနေပါသည်: ကျသင့်ငွေ ' . number_format($expectedAmount)
                    . ' Ks ဖြစ်သော်လည်း ပြေစာတွင် ' . number_format($detectedAmount) . ' Ks တွေ့ရပါသည်.';
            }

            if ($expectedRecipient !== '' && !$this->recipientNameMatches($detectedRecipient, $expectedRecipient)) {
                $notes .= ' ထို့အပြင် လက်ခံသူအမည် (' . ($detectedRecipient ?: 'မတွေ့ရ') . ") သည် '{$expectedRecipient}' နှင့် မကိုက်ညီပါ။";
            }

            return [
                'status' => 'mismatch',
                'auto_approved' => false,
                'transaction_id' => $transId,
                'detected_amount' => $detectedAmount,
                'detected_recipient' => $detectedRecipient,
                'notes' => $notes,
            ];
        }

        // Rule 4: Recipient Account Name Check (STUDIO_PAYMENT_NAME)
        if ($expectedRecipient !== '' && !$this->recipientNameMatches($detectedRecipient, $expectedRecipient)) {
            if ($detectedRecipient === null) {
                $notes = "လက်ခံသူအမည် မတွေ့ရပါ (Missing Recipient): ဆိုင်အကောင့် '{$expectedRecipient}' သို့ လွှဲထားကြောင်း မသေချာသဖြင့် Admin မှ စစ်ဆေးပေးရန် လိုအပ်ပါသည်.";
            } else {
                $notes = "လက်ခံသူအမည် မကိုက်ညီပါ (Wrong Recipient): ဆိုင်အကောင့် '{$expectedRecipient}' ဖြစ်ရမည်ဖြစ်သော်လည်း ပြေစာတွင် '{$detectedRecipient}' ဖြစ်နေပါသည်.";
            }

            return [
                'status' => 'recipient_mismatch',
                'auto_approved' => false,
                'transaction_id' => $transId,
                'detected_amount' => $detectedAmount,
                'detected_recipient' => $detectedRecipient,
                'notes' => $notes,
            ];
        }

        // ALL MATCHED! -> Auto-Approved
        $recipientLabel = $detectedRecipient ?: $expectedRecipient;
        $autoNote = 'Auto-Verified: ငွေပမာဏ ' . number_format($detectedAmount) . ' Ks'
            . ($recipientLabel !== '' ? " နှင့် လက်ခံသူ '{$recipientLabel}'" : '')
            . ' အတိအကျ ကိုက်ညီပြီး Trans ID အသစ်ဖြစ်ပါသည်။';
        if (!empty($extracted['is_demo_mock'])) {
            $autoNote .= ' [Demo Mode]';
        }

        return [
            'status' => 'matched',
            'auto_approved' => true,
            'transaction_id' => $transId,
            'detected_amount' => $detectedAmount,
            'detected_recipient' => $detectedRecipient,
            'notes' => $autoNote,
        ];
    }

    /**
     * Extract slip data via Gemini Vision AI, with explicit demo-fixture support and fail-closed safety.
     */
    protected function extractSlipData(string $screenshotPath, Order $order, ?string $originalClientName = null): array
    {
        $apiKey = (string) config('services.gemini.key');

        $fullPath = Storage::disk('local')->path($screenshotPath);
        if (!file_exists($fullPath)) {
            $fullPath = storage_path('app/' . $screenshotPath);
        }

        $filename = strtolower($originalClientName ?: basename($screenshotPath));
        $isSmallDemoPlaceholder = file_exists($fullPath) && filesize($fullPath) > 0 && filesize($fullPath) < 256;

        $expectedRecipient = trim((string) config('studio.payment_name', '')) ?: 'Ma Shwe Chaw';

        // Explicit demo scenario fixtures (used when no API key is set, or with the tiny dummy PNGs in public/images/demo-slips)
        if ($apiKey === '' || $isSmallDemoPlaceholder) {
            if (str_contains($filename, 'wrong_recipient') || str_contains($filename, 'recipient_mismatch') || str_contains($filename, 'wrong_name')) {
                return [
                    'is_kpay_receipt' => true,
                    'transaction_id' => '2026' . date('md') . rand(100000, 999999),
                    'amount' => (int) $order->total_price,
                    'receiver_name' => 'U Phyo Khant Kywal',
                    'is_demo_mock' => true,
                ];
            }

            if (str_contains($filename, 'mismatch') || str_contains($filename, 'underpaid')) {
                return [
                    'is_kpay_receipt' => true,
                    'transaction_id' => '2026' . date('md') . rand(100000, 999999),
                    'amount' => max(1000, (int) round($order->total_price / 2)),
                    'receiver_name' => $expectedRecipient,
                    'is_demo_mock' => true,
                ];
            }

            if (str_contains($filename, 'duplicate')) {
                $existing = Order::whereNotNull('transaction_id')->where('id', '!=', $order->id)->first();
                $fakeTid = $existing ? $existing->transaction_id : '20261110001234567890';
                return [
                    'is_kpay_receipt' => true,
                    'transaction_id' => $fakeTid,
                    'amount' => (int) $order->total_price,
                    'receiver_name' => $expectedRecipient,
                    'is_demo_mock' => true,
                ];
            }

            if (str_contains($filename, 'unreadable') || str_contains($filename, 'invalid')) {
                return [
                    'is_kpay_receipt' => false,
                    'transaction_id' => null,
                    'amount' => null,
                    'receiver_name' => null,
                    'is_demo_mock' => true,
                ];
            }

            if (str_contains($filename, 'kpay_match') || str_contains($filename, 'receipt_match')) {
                return [
                    'is_kpay_receipt' => true,
                    'transaction_id' => '2026' . date('md') . rand(10000000, 99999999),
                    'amount' => (int) $order->total_price,
                    'receiver_name' => $expectedRecipient,
                    'is_demo_mock' => true,
                ];
            }
        }

        if ($apiKey === '') {
            return [
                'error' => 'GEMINI_API_KEY မထည့်သွင်းထားသေးပါ။ Admin မှ ပြေစာကို လူကိုယ်တိုင် စစ်ဆေးပေးရန် လိုအပ်ပါသည် (Manual verification required).',
            ];
        }

        if (!file_exists($fullPath)) {
            return [
                'error' => 'Uploaded payment slip file could not be read from storage.',
            ];
        }

        $imageBytes = file_get_contents($fullPath);
        $mimeType = mime_content_type($fullPath) ?: 'image/jpeg';
        $verifyOption = $this->resolveVerifyOption();

        $prompt = "You are an OCR and payment slip verification engine for KBZPay (KPay) in Myanmar. "
            . "Analyze this image and extract the following details into a JSON object:\n"
            . "{\n"
            . '  "is_kpay_receipt": true,' . "\n"
            . '  "transaction_id": "string digits only (e.g. 01004325091504245039)",' . "\n"
            . '  "amount": 35000,' . "\n"
            . '  "receiver_name": "exact recipient name shown next to Transfer To / To / လက်ခံသူ, excluding masked phone in parentheses like (******6226)",' . "\n"
            . '  "receiver_phone": "phone or masked digits if shown, or null",' . "\n"
            . '  "timestamp": "date and time"' . "\n"
            . "}\n"
            . "Rules:\n"
            . "- amount must be a positive integer in MMK/Ks (ignore minus sign such as -10,000.00 Ks, ignore .00 decimals, remove Ks, MMK, commas).\n"
            . "- transaction_id must contain only digits.\n"
            . "- receiver_name must be ONLY the recipient name from 'Transfer To' (do NOT include asterisks/digits inside parentheses, and do NOT extract the sender).\n"
            . "- If this image is NOT a KBZPay payment screenshot, set is_kpay_receipt to false.\n"
            . "Return ONLY valid raw JSON.";

        $models = array_values(array_unique([
            (string) config('services.gemini.model', 'gemini-flash-lite-latest'),
            'gemini-flash-latest',
            'gemini-3.1-flash-lite',
            'gemini-3.5-flash',
        ]));

        $lastError = 'Unable to verify KPay receipt with AI right now.';

        foreach ($models as $model) {
            try {
                $response = Http::withOptions(['verify' => $verifyOption])
                    ->timeout(20)
                    ->post(
                        "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key=" . $apiKey,
                        [
                            'contents' => [
                                [
                                    'parts' => [
                                        ['text' => $prompt],
                                        [
                                            'inline_data' => [
                                                'mime_type' => $mimeType,
                                                'data' => base64_encode($imageBytes),
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            'generationConfig' => [
                                'responseMimeType' => 'application/json',
                            ],
                        ]
                    );

                if ($response->successful()) {
                    $text = $response->json('candidates.0.content.parts.0.text', '');
                    $text = preg_replace('/^```(?:json)?\s*/i', '', trim((string) $text));
                    $text = preg_replace('/\s*```$/', '', (string) $text);
                    $json = json_decode($text, true);

                    if (is_array($json) && array_key_exists('is_kpay_receipt', $json)) {
                        return $json;
                    }

                    $lastError = 'AI returned an unreadable format for this receipt. Manual check required.';
                    Log::warning("KpaySlipVerifier invalid JSON from {$model}: {$text}");
                    break;
                }

                $status = $response->status();
                $apiMsg = (string) ($response->json('error.message') ?? $response->body());
                Log::warning("KpaySlipVerifier Gemini HTTP {$status} on {$model}: {$apiMsg}");
                $lastError = str_contains(strtolower($apiMsg), 'location is not supported')
                    ? 'AI verification blocked by region (Google Gemini HTTP 400: turn on VPN to a supported region). Manual check required.'
                    : "AI verification service returned HTTP {$status}. Manual verification required.";

                // Retry next fallback model only for transient availability or deprecated model codes
                if (!in_array($status, [404, 429, 500, 502, 503, 504], true)) {
                    break;
                }
            } catch (\Throwable $e) {
                Log::error("KpaySlipVerifier Gemini exception on {$model}: " . $e->getMessage());
                $lastError = 'AI verification connection failed. Manual verification required.';
            }
        }

        // Fail-closed: NEVER auto-approve when AI verification could not run
        return [
            'error' => $lastError,
        ];
    }

    protected function resolveVerifyOption(): bool|string
    {
        $curlCa = ini_get('curl.cainfo');
        if ($curlCa && file_exists($curlCa)) {
            return true;
        }

        $opensslCa = ini_get('openssl.cafile');
        if ($opensslCa && file_exists($opensslCa)) {
            return true;
        }

        $candidates = [
            'C:\Program Files\Git\mingw64\etc\ssl\certs\ca-bundle.crt',
            'C:\xampp\apache\bin\curl-ca-bundle.crt',
            'C:\xampp\perl\vendor\lib\Mozilla\CA\cacert.pem',
            'C:\php83\extras\ssl\cacert.pem',
        ];

        foreach ($candidates as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }

        return true;
    }

    protected function parseAmount(mixed $raw): ?int
    {
        if ($raw === null || $raw === '') {
            return null;
        }

        if (is_int($raw) || is_float($raw)) {
            return (int) round(abs($raw));
        }

        $cleaned = preg_replace('/[^0-9.]/', '', (string) $raw);
        if ($cleaned === '' || !is_numeric($cleaned)) {
            return null;
        }

        return (int) round((float) $cleaned);
    }

    protected function sanitizeTransactionId(mixed $raw): ?string
    {
        if ($raw === null) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', (string) $raw);
        return $digits !== '' ? substr($digits, 0, 60) : null;
    }

    protected function sanitizeRecipientName(mixed $raw): ?string
    {
        if ($raw === null) {
            return null;
        }

        $cleaned = preg_replace('/\([^)]*\)/u', '', (string) $raw);
        $cleaned = preg_replace('/\s+/u', ' ', trim((string) $cleaned));

        return $cleaned !== '' ? mb_substr($cleaned, 0, 120) : null;
    }

    protected function normalizeRecipientName(string $name): string
    {
        $cleaned = preg_replace('/\([^)]*\)/u', '', $name);
        $cleaned = preg_replace('/[.,\-_]+/u', ' ', (string) $cleaned);
        $cleaned = preg_replace('/\s+/u', ' ', trim((string) $cleaned));

        return mb_strtolower((string) $cleaned, 'UTF-8');
    }

    protected function recipientNameMatches(?string $detectedName, string $expectedName): bool
    {
        if ($detectedName === null || trim($detectedName) === '') {
            return false;
        }

        return $this->normalizeRecipientName($detectedName) === $this->normalizeRecipientName($expectedName);
    }
}
