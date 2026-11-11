<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'transaction_id')) {
                $table->string('transaction_id', 60)->nullable()->index()->after('payment_screenshot');
            }
            if (!Schema::hasColumn('orders', 'slip_detected_amount')) {
                $table->integer('slip_detected_amount')->nullable()->after('transaction_id');
            }
            if (!Schema::hasColumn('orders', 'slip_verification_status')) {
                $table->string('slip_verification_status', 30)->nullable()->after('slip_detected_amount');
            }
            if (!Schema::hasColumn('orders', 'slip_verification_notes')) {
                $table->text('slip_verification_notes')->nullable()->after('slip_verification_status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $columns = [];
            foreach (['transaction_id', 'slip_detected_amount', 'slip_verification_status', 'slip_verification_notes'] as $col) {
                if (Schema::hasColumn('orders', $col)) {
                    $columns[] = $col;
                }
            }
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};

