@extends('layouts.app') {{-- أو layouts.consultant إذا كان لديك --}}
@section('title', 'Edit Article: ' . $article->Title)

@section('content')
<div class="container mt-4">
     <div class="row justify-content-center">
        <div class="col-lg-9">
             <div class="card shadow-sm">
                <div class="card-header">
                    <i class="fas fa-edit me-2"></i> Edit Article: {{ $article->Title }}
                </div>
                <div class="card-body">
                    @include('partials._alerts')
                    <form action="{{ route('articles.update', $article) }}" method="POST" enctype="multipart/form-data">
                         @csrf
                         @method('PUT')
                         {{-- تضمين النموذج الجزئي (تأكد أنه يستخدم Bootstrap) --}}
                         {{-- نفترض أن _form موجود في resources/views/articles/ --}}
                         @include('articles._form', ['article' => $article])
                     </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection