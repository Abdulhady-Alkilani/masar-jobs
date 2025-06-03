{{-- resources/views/partials/_alerts.blade.php (الكود المصحح) --}}

{{-- رسالة النجاح --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- رسالة الخطأ --}}
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-times-circle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- رسالة التحذير --}}
@if (session('warning'))
     <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- رسالة المعلومات --}}
@if (session('info'))
     <div class="alert alert-info alert-dismissible fade show" role="alert">
        <i class="fas fa-info-circle me-2"></i>
        {{ session('info') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- عرض أخطاء التحقق من الصحة العامة (غير مرتبطة بحقل معين أو بـ error bag محدد) --}}
{{-- يتم عرضها فقط إذا كان هناك أخطاء بشكل عام، ولا توجد error bags مخصصة --}}
@if ($errors->any() && empty($errors->getBags())) {{-- !!! هذا هو السطر الذي تم تصحيحه !!! --}}
    <div class="alert alert-danger mt-3" role="alert">
        <h4 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i> {{ __('general.something_went_wrong_errors') ?? 'Please correct the following errors:' }}</h4>
        <hr class="my-2">
        <ul class="mb-0 list-unstyled small">
            @foreach ($errors->all() as $error)
                <li><i class="fas fa-times-circle fa-xs me-1 text-danger"></i> {{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif