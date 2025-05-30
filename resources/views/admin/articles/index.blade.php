@extends('layouts.admin')
@section('title', 'Manage Articles')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0 text-primary"><i class="fas fa-newspaper me-2"></i> {{ __('Manage Articles') }}</h2>
        <a href="{{ route('admin.articles.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Add New Article
        </a>
    </div>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <span class="card-title mb-0">Articles List</span>
             {{-- Search Form --}}
             <form action="{{ route('admin.articles.index') }}" method="GET" class="d-inline-flex ms-auto">
                <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Search articles..." value="{{ request('search') }}">
                <select name="type" class="form-select form-select-sm me-2">
                    <option value="">All Types</option>
                    <option value="استشاري" {{ request('type') == 'استشاري' ? 'selected' : '' }}>استشاري</option>
                    <option value="نصائح" {{ request('type') == 'نصائح' ? 'selected' : '' }}>نصائح</option>
                </select>
                <button type="submit" class="btn btn-secondary btn-sm">Search</button>
            </form>
        </div>

        <div class="card-body p-0">
             <div class="m-3"> @include('partials._alerts') </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Title</th>
                            <th>Author</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- !!! التعديل هنا: استخدام $articles بدلاً من $users --}}
                        @forelse ($articles as $article)
                            <tr>
                                {{-- !!! التعديل هنا: استخدام $article --}}
                                <td class="ps-3">{{ Str::limit($article->Title, 50) }}</td>
                                <td>{{ $article->user->username ?? 'N/A' }}</td>
                                <td><span class="badge bg-info">{{ $article->Type }}</span></td>
                                <td>{{ $article->Date ? $article->Date->format('Y-m-d') : 'N/A' }}</td>
                                <td class="text-end pe-3">
                                    <div class="btn-group btn-group-sm">
                                        {{-- !!! التعديل هنا: استخدام $article --}}
                                        <a href="{{ route('admin.articles.show', $article) }}" class="btn btn-info" title="View"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('admin.articles.edit', $article) }}" class="btn btn-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" title="Delete"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    No articles found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div> {{-- نهاية card-body --}}

        @if ($articles->hasPages()) {{-- !!! التعديل هنا: استخدام $articles --}}
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-center">
                    {{-- !!! التعديل هنا: استخدام $articles --}}
                    {{ $articles->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif

    </div> {{-- نهاية card --}}
@endsection