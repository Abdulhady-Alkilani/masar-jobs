{{-- resources/views/consultant/articles/index.blade.php --}}
@extends('layouts.consultant') {{-- أو layouts.app إذا لم يكن هناك layout خاص --}}
@section('title', 'My Articles')

@section('header')
    {{-- استخدام نظام Grid في Header لتوزيع العنوان والأزرار (اختياري) --}}
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0 text-primary"><i class="fas fa-newspaper me-2"></i> My Articles</h2>
        <a href="{{ route('consultant.articles.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Write New Article
        </a>
    </div>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center flex-wrap"> {{-- جعل الـ header متجاوبًا --}}
            <span class="card-title mb-0 me-2">Your Published Articles</span>
            {{-- يمكنك إضافة فلترة بسيطة هنا إذا لزم الأمر --}}
            {{-- <form action="{{ route('consultant.articles.index') }}" method="GET" class="d-inline-flex ms-auto">
                <select name="type" class="form-select form-select-sm me-2" onchange="this.form.submit()">
                    <option value="">All Types</option>
                    <option value="استشاري" {{ request('type') == 'استشاري' ? 'selected' : '' }}>استشاري</option>
                    <option value="نصائح" {{ request('type') == 'نصائح' ? 'selected' : '' }}>نصائح</option>
                </select>
                <a href="{{ route('consultant.articles.index') }}" class="btn btn-outline-secondary btn-sm" title="Clear Filter">Clear</a>
            </form> --}}
        </div>

        <div class="card-body p-0">
            <div class="m-3"> @include('partials._alerts') </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Title</th>
                            <th>Type</th>
                            <th>Published Date</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($articles as $article) {{-- يفترض أن الكنترولر يمرر $articles --}}
                            <tr>
                                <td class="ps-3">
                                    {{-- رابط للعرض العام أو الخاص بالاستشاري إذا وجد --}}
                                    <a href="{{ route('articles.show', $article) }}" title="{{ $article->Title }}">
                                        {{ Str::limit($article->Title, 60) }}
                                    </a>
                                </td>
                                <td><span class="badge bg-info text-dark">{{ $article->Type }}</span></td> {{-- استخدام text-dark مع bg-info --}}
                                <td>{{ $article->Date ? $article->Date->format('Y-m-d') : 'N/A' }}</td>
                                <td class="text-end pe-3">
                                    <div class="btn-group btn-group-sm" role="group">
                                        {{-- رابط للعرض العام --}}
                                        <a href="{{ route('articles.show', $article) }}" class="btn btn-outline-secondary" title="View Public Page"><i class="fas fa-eye"></i></a>
                                        {{-- رابط التعديل --}}
                                        <a href="{{ route('consultant.articles.edit', $article) }}" class="btn btn-outline-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                        {{-- زر الحذف --}}
                                        <form action="{{ route('consultant.articles.destroy', $article) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this article?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5"> {{-- زيادة padding --}}
                                    <p class="mb-2">You haven't written any articles yet.</p>
                                    <a href="{{ route('consultant.articles.create') }}" class="btn btn-success btn-sm"> {{-- زر أوضح --}}
                                        <i class="fas fa-plus me-1"></i> Write Your First Article
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div> {{-- نهاية card-body --}}

        @if ($articles->hasPages())
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-center">
                    {{-- إضافة query string للـ pagination للحفاظ على الفلاتر إن وجدت --}}
                    {{ $articles->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div> {{-- نهاية card --}}
@endsection

{{-- تأكد من تضمين Font Awesome في layout إذا أردت استخدام الأيقونات --}}