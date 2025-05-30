@extends('layouts.admin')
{{-- !!! تعديل: استخدام بيانات المقال في العنوان --}}
@section('title', 'Article Details: ' . Str::limit($article->Title, 30))

@section('header')
    {{-- استخدام H2 بسيط للعنوان داخل الهيدر الذي يوفره الـ layout --}}
     {{-- !!! تعديل: استخدام بيانات المقال --}}
    <h2 class="h4 mb-0 text-primary">
        <i class="fas fa-newspaper me-2"></i> Article Details: {{ Str::limit($article->Title, 40) }}
    </h2>
@endsection

@section('content')
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-info-circle me-2"></i>Article Information</span>
             {{-- !!! تعديل: عرض نوع المقال --}}
            <span class="badge bg-info">{{ $article->Type }}</span>
        </div>
        <div class="card-body">
            {{-- عرض صورة المقال إن وجدت --}}
            @if($article->{'Article Photo'})
                <div class="mb-4 text-center">
                    <img src="{{ asset('storage/' . $article->{'Article Photo'}) }}" alt="{{ $article->Title }}" class="img-fluid rounded shadow-sm mx-auto" style="max-height: 300px;">
                </div>
            @endif

            <dl class="row">
                 {{-- !!! تعديل: عرض بيانات المقال --}}
                <dt class="col-sm-3">Title</dt>
                <dd class="col-sm-9">{{ $article->Title }}</dd>

                <dt class="col-sm-3">Author</dt>
                {{-- !!! تعديل: الوصول للمؤلف عبر العلاقة --}}
                <dd class="col-sm-9">
                    @if($article->user)
                        <a href="{{ route('admin.users.show', $article->user) }}">{{ $article->user->username }}</a>
                    @else
                        N/A
                    @endif
                </dd>

                <dt class="col-sm-3">Type</dt>
                <dd class="col-sm-9">{{ $article->Type }}</dd>

                <dt class="col-sm-3">Description</dt>
                {{-- !!! تعديل: عرض وصف المقال --}}
                <dd class="col-sm-9" style="white-space: pre-wrap;">{{ $article->Description }}</dd> {{-- white-space للحفاظ على التنسيق --}}

                <dt class="col-sm-3">Published At</dt>
                <dd class="col-sm-9">{{ $article->Date ? $article->Date->format('Y-m-d H:i') : 'N/A' }} ({{ $article->Date ? $article->Date->diffForHumans() : '' }})</dd>

                <dt class="col-sm-3">Last Updated</dt>
                <dd class="col-sm-9">{{ $article->updated_at->format('Y-m-d H:i') }} ({{ $article->updated_at->diffForHumans() }})</dd>
            </dl>
        </div>
    </div>

    {{-- أزرار الإجراءات --}}
    <div class="mt-4 d-flex justify-content-end gap-2">
         {{-- !!! تعديل: استخدام مسارات المقال --}}
        <a href="{{ route('admin.articles.edit', $article) }}" class="btn btn-warning">
            <i class="fas fa-edit me-1"></i> Edit Article
        </a>
        <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" onsubmit="return confirm('Are you sure?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash me-1"></i> Delete Article
            </button>
        </form>
        <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">
             <i class="fas fa-arrow-left me-1"></i> Back to List
        </a>
    </div>

@endsection