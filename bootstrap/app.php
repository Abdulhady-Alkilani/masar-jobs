<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware; // تأكد من وجود هذا الاستيراد
use App\Http\Middleware\LocalizationMiddleware;   // !!! أضف هذا السطر: استيراد الميدلوير الخاص بك !!!

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) { // دالة تعديل الميدلوير
        // !!! أضف الكود التالي هنا لتسجيل الميدلوير في مجموعة 'web' !!!
        $middleware->web(append: [
            // يمكنك ترك الميدلوير الافتراضية إذا كانت موجودة في مشروعك، مثل:
            // \App\Http\Middleware\HandleInertiaRequests::class,
            // \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,

            // إضافة الميدلوير الخاص باللغة
            LocalizationMiddleware::class,
        ]);

        // إذا كان لديك ميدلوير أخرى تريد تسجيلها (مثل isAdmin)، يمكنك فعل ذلك هنا أيضًا:
        // $middleware->alias([
        //     'isAdmin' => \App\Http\Middleware\IsAdminMiddleware::class,
        //     'isConsultant' => \App\Http\Middleware\IsConsultantMiddleware::class,
        //     // ...
        // ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();