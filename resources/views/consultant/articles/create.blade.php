@extends('layouts.consultant') {{-- أو layouts.app --}}
@section('title', 'Write New Article')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0 text-primary"><i class="fas fa-edit me-2"></i> Write New Article</h2>
        <a href="{{ route('consultant.articles.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to My Articles
        </a>
    </div>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            Article Details
        </div>
        <div class="card-body">
            @include('partials._alerts')
            <form action="{{ route('consultant.articles.store') }}" method="POST" enctype="multipart/form-data">
                {{--
                    نفترض أن _form.blade.php لا يحتاج لمتغير $article عند الإنشاء.
                    تأكد أن _form.blade.php لا يحتوي على حقول خاصة بالأدمن فقط (مثل اختيار المؤلف).
                    الكنترولر سيقوم بتعيين UserID تلقائيًا للمستخدم الحالي.
                --}}
                @include('consultant.articles._form') {{-- أو include('articles._form') إذا كان مشتركًا --}}
            </form>
        </div>
    </div>
@endsection