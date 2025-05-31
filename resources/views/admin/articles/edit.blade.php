@extends('layouts.admin')
@section('title', 'Edit Article: ' . Str::limit($article->Title, 30))

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0 text-primary">
            <i class="fas fa-newspaper me-2"></i> Edit Article: {{ Str::limit($article->Title, 40) }}
        </h2>
         <a href="{{ route('admin.articles.index') }}" class="btn btn-sm btn-outline-secondary">
             <i class="fas fa-arrow-left me-1"></i> Back to List
        </a>
    </div>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <i class="fas fa-edit me-2"></i> Edit Article Details
        </div>
        <div class="card-body">
             @include('partials._alerts')

             <form action="{{ route('admin.articles.update', $article) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- تضمين النموذج الجزئي للمقالات وتمرير البيانات --}}
                @include('admin.articles._form', ['article' => $article, 'authors' => $authors])

            </form>
        </div>
    </div>
@endsection