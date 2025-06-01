@extends('layouts.app')
@section('title', $article->Title ?? 'Article Details')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-9"> {{-- عمود أعرض لعرض المقال --}}
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center bg-light">
                    <span class="badge bg-info">{{ $article->Type }}</span>
                    <div>
                         {{-- أزرار التعديل/الحذف للمؤلف أو الأدمن --}}
                         @canany(['update', 'delete'], $article)
                             @can('update', $article)
                              <a href="{{ route('articles.edit', $article) }}" class="btn btn-warning btn-sm me-1" title="Edit"><i class="fas fa-edit"></i> Edit</a>
                             @endcan
                             @can('delete', $article)
                             <form action="{{ route('articles.destroy', $article) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                 @csrf
                                 @method('DELETE')
                                 <button type="submit" class="btn btn-danger btn-sm" title="Delete"><i class="fas fa-trash"></i> Delete</button>
                             </form>
                             @endcan
                         @endcanany
                         <a href="{{ route('articles.index') }}" class="btn btn-secondary btn-sm ms-2"><i class="fas fa-arrow-left"></i> Back</a>
                    </div>
                </div>
                <div class="card-body p-4 p-md-5">
                    <h1 class="card-title h2 mb-3">{{ $article->Title }}</h1>
                    <p class="card-subtitle mb-4 text-muted small">
                        Published by {{ $article->user->username ?? 'N/A' }}
                        on {{ $article->Date ? $article->Date->format('F j, Y') : 'N/A' }}
                    </p>

                    @if ($article->{'Article Photo'})
                        <div class="mb-4 text-center">
                            <img src="{{ asset('storage/' . $article->{'Article Photo'}) }}" alt="{{ $article->Title }}" class="img-fluid rounded shadow-sm mb-3" style="max-height: 400px;">
                        </div>
                    @endif

                    {{-- محتوى المقال --}}
                    <div class="article-content">
                         {{-- استخدام nl2br مع المسافات البيضاء للحفاظ على تنسيق الفقرات --}}
                         <p style="white-space: pre-wrap;">{!! nl2br(e($article->Description)) !!}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- يمكنك إضافة ستايل لـ .article-content في ملف CSS إذا أردت تنسيقًا إضافيًا للمحتوى --}}
@push('styles')
<style>
    .article-content p {
        line-height: 1.7;
        margin-bottom: 1rem;
    }
</style>
@endpush