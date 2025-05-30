@extends('layouts.consultant') {{-- أو layouts.app --}}
@section('title', 'Edit Article: ' . Str::limit($article->Title, 30))

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0 text-primary"><i class="fas fa-edit me-2"></i> Edit Article</h2>
        <a href="{{ route('consultant.articles.index') }}" class="btn btn-sm btn-outline-secondary">
             <i class="fas fa-arrow-left me-1"></i> Back to My Articles
        </a>
    </div>
@endsection

@section('content')
    <div class="card shadow-sm">
         <div class="card-header">
            Update Article Details
        </div>
        <div class="card-body">
            @include('partials._alerts')
            <form action="{{ route('consultant.articles.update', $article) }}" method="POST" enctype="multipart/form-data">
                 @csrf
                 @method('PUT')
                 {{--
                    تمرير $article إلى النموذج الجزئي لملء الحقول بالبيانات الحالية.
                    تأكد أن النموذج الجزئي لا يسمح بتغيير المؤلف (UserID).
                 --}}
                 @include('consultant.articles._form', ['article' => $article]) {{-- أو include('articles._form') --}}
             </form>
        </div>
    </div>
@endsection