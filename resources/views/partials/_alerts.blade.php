{{-- يعرض رسائل الحالة (success, error, warning, info) --}}

@if (session('success'))
    <div class="mb-4 px-4 py-3 leading-normal text-green-700 bg-green-100 border border-green-400 rounded-lg" role="alert">
        <p>{{ session('success') }}</p>
    </div>
@endif

@if (session('error'))
    <div class="mb-4 px-4 py-3 leading-normal text-red-700 bg-red-100 border border-red-400 rounded-lg" role="alert">
        <p>{{ session('error') }}</p>
    </div>
@endif

@if (session('warning'))
     <div class="mb-4 px-4 py-3 leading-normal text-yellow-700 bg-yellow-100 border border-yellow-400 rounded-lg" role="alert">
        <p>{{ session('warning') }}</p>
    </div>
@endif

@if (session('info'))
     <div class="mb-4 px-4 py-3 leading-normal text-blue-700 bg-blue-100 border border-blue-400 rounded-lg" role="alert">
        <p>{{ session('info') }}</p>
    </div>
@endif

{{-- عرض أخطاء التحقق من الصحة العامة (غير مرتبطة بحقل معين) --}}
@if ($errors->any() && !$errors->hasBags()) {{-- تأكد من عدم عرض الأخطاء العامة إذا كانت هناك أخطاء حقول --}}
    <div class="mb-4 px-4 py-3 leading-normal text-red-700 bg-red-100 border border-red-400 rounded-lg" role="alert">
        <p class="font-bold">{{ __('Whoops! Something went wrong.') }}</p>
        <ul class="mt-2 list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif