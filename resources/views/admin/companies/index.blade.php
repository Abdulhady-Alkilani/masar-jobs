@extends('layouts.admin')
@section('title', 'Manage Companies')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0 text-primary"><i class="fas fa-building me-2"></i> {{ __('Manage Companies') }}</h2>
        <a href="{{ route('admin.companies.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Add New Company
        </a>
    </div>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center flex-wrap">
            <span class="card-title mb-0 me-2">Companies List</span>
             {{-- Search/Filter Form --}}
             <form action="{{ route('admin.companies.index') }}" method="GET" class="d-inline-flex flex-grow-1 flex-wrap justify-content-end gap-2">
                <input type="text" name="search" class="form-control form-control-sm" style="max-width: 200px;" placeholder="Search name, email..." value="{{ request('search') }}">
                 <select name="status" class="form-select form-select-sm" style="max-width: 150px;">
                    <option value="">All Statuses</option>
                    <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                 </select>
                <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
                 <a href="{{ route('admin.companies.index') }}" class="btn btn-outline-secondary btn-sm" title="Clear Filter">Clear</a>
            </form>
        </div>

        <div class="card-body p-0">
             <div class="m-3"> @include('partials._alerts') </div>
             <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Name</th>
                            <th>Manager</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($companies as $company)
                            <tr>
                                <td class="ps-3">{{ $company->Name }}</td>
                                <td>{{ $company->user->username ?? 'N/A' }}</td>
                                <td>{{ $company->Email ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge {{ $company->Status === 'Approved' ? 'bg-success' : ($company->Status === 'Pending' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                        {{ $company->Status ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>{{ $company->created_at->format('Y-m-d') }}</td>
                                <td class="text-end pe-3">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.companies.show', $company) }}" class="btn btn-info" title="View"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('admin.companies.edit', $company) }}" class="btn btn-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('admin.companies.destroy', $company) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" title="Delete"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No companies found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($companies->hasPages())
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-center">
                    {{ $companies->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
@endsection