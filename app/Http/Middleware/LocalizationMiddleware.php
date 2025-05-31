<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LocalizationMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Session::has('locale') && in_array(Session::get('locale'), config('app.available_locales'))) {
            App::setLocale(Session::get('locale'));
        } else if (in_array(config('app.fallback_locale'), config('app.available_locales'))) {
            // إذا لم تكن هناك لغة في الجلسة، استخدم اللغة الافتراضية
            App::setLocale(config('app.fallback_locale'));
            Session::put('locale', config('app.fallback_locale'));
        }
        // يمكنك إضافة منطق للتعرف على لغة المتصفح هنا إذا أردت

        return $next($request);
    }
}