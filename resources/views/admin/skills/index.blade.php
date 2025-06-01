@extends('layouts.admin')
@section('title', 'Manage Skills')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0 text-primary"><i class="fas fa-star me-2"></i> {{ __('Manage Skills') }}</h2>
        <a href="{{ route('admin.skills.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Add New Skill
        </a>
    </div>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <span class="card-title mb-0">Skills List</span>
            <form action="{{ route('admin.skills.index') }}" method="GET" class="d-inline-flex ms-auto">
                <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Search skills..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-secondary btn-sm">Search</button>
            </form>
        </div>

        <div class="card-body p-0">
             <div class="m-3"> @include('partials._alerts') </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Skill Name</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($skills as $skill) {{-- استخدام $skills و $skill --}}
                            <tr>
                                <td class="ps-3">{{ $skill->Name }}</td>
                                <td class="text-end pe-3">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.skills.edit', $skill) }}" class="btn btn-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('admin.skills.destroy', $skill) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure? This might affect users with this skill.');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger" title="Delete"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted py-4">No skills found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($skills->hasPages()) {{-- استخدام $skills --}}
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-center">
                    {{ $skills->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
@endsection