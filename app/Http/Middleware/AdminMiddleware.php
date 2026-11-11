<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ဝင်ရောက်ထားသော User သည် Login ဝင်ထားပြီး Admin (`is_admin == 1`) ဟဟုတ် စစ်ဆေးခြင်း
        if (auth()->check() && auth()->user()->is_admin) {
            return $next($request);
        }

        // Admin မဟုတ်ပါက ပင်မစာမျက်နှာ သို့မဟုတ် Login သို့ လမ်းကြောင်းလွှဲခြင်း
        return redirect('/')->with('error', 'Unauthorized access! Admin only.');
    }
}