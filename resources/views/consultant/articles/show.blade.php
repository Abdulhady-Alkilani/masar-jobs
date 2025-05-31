@extends('layouts.consultant') {{-- أو layouts.app --}}
@section('title', 'Article Details: ' . Str::limit($article->Title, 30))

@section('header')
     <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0 text-primary">
            <i class="fas fa-newspaper me-2"></i> Article Details
        </h2>
        <div>
             <a href="{{ route('articles.show', $article) }}" class="btn btn-sm btn-outline-info me-2" target="_blank">
                 <i class="fas fa-eye me-1"></i> View Public Page
             </a>
             <a href="{{ route('consultant.articles.index') }}" class="btn btn-sm btn-outline-secondary">
                 <i class="fas fa-arrow-left me-1"></i> Back to My Articles
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>{{ $article->Title }}</span>
            <span class="badge bg-info">{{ $article->Type }}</span>
        </div>
        <div class="card-body">
            {{-- عرض الصورة --}}
            @if($article->{'Article Photo'})
                <div class="mb-3 text-center">
                    <img src="{{ asset('storage/' . $article->{'Article Photo'}) }}" alt="{{ $article->Title }}" class="img-fluid rounded shadow-sm mx-auto" style="max-height: 250px;">
                </div>
            @endif

            {{-- عرض الوصف --}}
            <h5 class="card-title">Description</h5>
            <p class="text-muted mb-3" style="white-space: pre-wrap;">{{ $article->Description }}</p>

            <hr>

            {{-- معلومات إضافية --}}
            <dl class="row mt-3">
                <dt class="col-sm-3">Author:</dt>
                <dd class="col-sm-9">{{ $article->user->username ?? 'N/A' }}</dd> {{-- يفترض أنه أنت --}}

                <dt class="col-sm-3">Published Date:</dt>
                <dd class="col-sm-9">{{ $article->Date ? $article->Date->format('Y-m-d H:i') : 'N/A' }}</dd>

                <dt class="col-sm-3">Last Updated:</dt>
                <dd class="col-sm-9">{{ $article->updated_at->format('Y-m-d H:i') }}</dd>
            </dl>
        </div>
         {{-- أضف إحصائيات المشاهدة أو التعليقات هنا إذا أردت --}}
    </div>

     {{-- أزرار الإجراءات --}}
    <div class="mt-4 d-flex justify-content-end gap-2">
        <a href="{{ route('consultant.articles.edit', $article) }}" class="btn btn-warning">
            <i class="fas fa-edit me-1"></i> Edit Article
        </a>
        <form action="{{ route('consultant.articles.destroy', $article) }}" method="POST" onsubmit="return confirm('Are you sure?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash me-1"></i> Delete Article
            </button>
        </form>
    </div>
@endsection