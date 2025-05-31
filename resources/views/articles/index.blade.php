@extends('layouts.app') {{-- استخدام layout المستخدمين المسجلين --}}

@section('title', 'Articles & Advice')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 text-primary"><i class="fas fa-newspaper me-2"></i> Articles & Advice</h1>
        {{-- زر إضافة مقال جديد (يظهر فقط للمصرح لهم) --}}
        @can('create', App\Models\Article::class) {{-- مثال استخدام Policy --}}
            <a href="{{ route('articles.create') }}" class="btn btn-success btn-sm">
                <i class="fas fa-plus me-1"></i> Write New Article
            </a>
        @endcan
    </div>

    @include('partials._alerts') {{-- عرض التنبيهات --}}

    {{-- يمكنك إضافة قسم للبحث أو الفلترة هنا --}}
    {{-- <div class="mb-3"> ... Search Form ... </div> --}}

    {{-- شبكة عرض المقالات --}}
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4"> {{-- استخدام Grid من Bootstrap --}}
        @forelse ($articles as $article)
            <div class="col">
                <div class="card h-100 shadow-sm"> {{-- Card لكل مقال --}}
                    @if ($article->{'Article Photo'})
                        <a href="{{ route('articles.show', $article) }}">
                            <img src="{{ asset('storage/' . $article->{'Article Photo'}) }}" class="card-img-top" alt="{{ $article->Title }}" style="height: 200px; object-fit: cover;">
                        </a>
                    @else
                         <a href="{{ route('articles.show', $article) }}">
                            <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fas fa-image fa-3x text-light"></i>
                            </div>
                         </a>
                    @endif
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">
                            <a href="{{ route('articles.show', $article) }}" class="text-decoration-none text-dark stretched-link">
                                {{ $article->Title }}
                            </a>
                        </h5>
                        <p class="card-text text-muted small mb-2">
                            <span class="badge bg-info text-dark me-2">{{ $article->Type }}</span>
                            By: {{ $article->user->username ?? 'N/A' }}
                        </p>
                        <p class="card-text small flex-grow-1">
                            {{ Str::limit($article->Description, 120) }}
                        </p>
                    </div>
                    <div class="card-footer bg-white border-top pt-2 pb-2"> {{-- تعديل footer --}}
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                {{ $article->Date ? $article->Date->format('M d, Y') : '' }}
                            </small>
                             {{-- أزرار التعديل/الحذف للمؤلف أو الأدمن --}}
                             @canany(['update', 'delete'], $article)
                                 <div class="btn-group btn-group-sm"> {{-- تجميع الأزرار --}}
                                    {{-- !!! إضافة أيقونة العرض العام (Eye) !!! --}}
                                     <a href="{{ route('articles.show', $article) }}" class="btn btn-outline-secondary" title="View Public"><i class="fas fa-eye"></i></a>
                                    @can('update', $article)
                                        {{-- !!! إضافة أيقونة التعديل (Edit) !!! --}}
                                        <a href="{{ route('articles.edit', $article) }}" class="btn btn-outline-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                    @endcan
                                    @can('delete', $article)
                                    <form action="{{ route('articles.destroy', $article) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        {{-- !!! إضافة أيقونة الحذف (Trash) !!! --}}
                                        <button type="submit" class="btn btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
                                    </form>
                                    @endcan
                                 </div>
                             @else
                                {{-- زر عرض للجميع إذا لم يكن المستخدم هو المالك/الأدمن --}}
                                 <a href="{{ route('articles.show', $article) }}" class="btn btn-sm btn-outline-primary" title="Read More"><i class="fas fa-book-open me-1"></i> Read</a>
                             @endcanany
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                 <div class="alert alert-secondary text-center" role="alert">
                    No articles found.
                     @can('create', App\Models\Article::class)
                        <a href="{{ route('articles.create') }}" class="alert-link">Write the first one?</a>
                    @endcan
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination Links --}}
    <div class="mt-4 d-flex justify-content-center">
        {{ $articles->links('pagination::bootstrap-5') }}
    </div>

</div>
@endsection

{{-- تأكد من تضمين Font Awesome في layout لاستخدام الأيقونات (fas fa-...) --}}