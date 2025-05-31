{{-- resources/views/admin/upgrade_requests/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'Account Upgrade Requests')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0 text-primary"><i class="fas fa-user-shield me-2"></i> Account Upgrade Requests</h2>
        {{-- لا يوجد زر إنشاء هنا عادةً --}}
    </div>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex flex-wrap justify-content-between align-items-center gap-2">
            <span class="card-title mb-0 me-2">Pending & Processed Requests</span>
            {{-- Filter Form --}}
            <form action="{{ route('admin.upgrade-requests.index') }}" method="GET" class="d-inline-flex flex-grow-1 flex-wrap justify-content-end gap-2">
                <select name="status" class="form-select form-select-sm" style="max-width: 180px;" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
                <select name="role" class="form-select form-select-sm" style="max-width: 200px;" onchange="this.form.submit()">
                    <option value="">All Requested Roles</option>
                    <option value="خبير استشاري" {{ request('role') == 'خبير استشاري' ? 'selected' : '' }}>Consultant</option>
                    <option value="مدير شركة" {{ request('role') == 'مدير شركة' ? 'selected' : '' }}>Company Manager</option>
                </select>
                <a href="{{ route('admin.upgrade-requests.index') }}" class="btn btn-outline-secondary btn-sm" title="Clear Filters">Clear</a>
            </form>
        </div>

        <div class="card-body p-0">
            <div class="m-3"> @include('partials._alerts') </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">User</th>
                            <th>Current Role</th>
                            <th>Requested Role</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Requested At</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- يفترض أن الكنترولر يمرر $upgradeRequests --}}
                        @forelse ($upgradeRequests as $request)
                            <tr>
                                <td class="ps-3">
                                    @if($request->user)
                                        <a href="{{ route('admin.users.show', $request->user) }}" title="View User">{{ $request->user->username }}</a>
                                        <small class="d-block text-muted">{{ $request->user->email }}</small>
                                    @else
                                        <span class="text-danger">User Deleted</span>
                                    @endif
                                </td>
                                <td><span class="badge bg-secondary">{{ $request->user->type ?? 'N/A' }}</span></td>
                                <td><span class="badge bg-info text-dark">{{ $request->requested_role }}</span></td>
                                <td>{{ Str::limit($request->reason, 40) ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge
                                        {{ match ($request->status) {
                                            'pending' => 'bg-warning text-dark',
                                            'approved' => 'bg-success',
                                            'rejected' => 'bg-danger',
                                            default => 'bg-secondary',
                                        } }}">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </td>
                                <td>{{ $request->created_at->format('Y-m-d') }}</td>
                                <td class="text-end pe-3">
                                    <div class="btn-group btn-group-sm">
                                        {{-- رابط لعرض تفاصيل الطلب لمراجعته والموافقة/الرفض --}}
                                        <a href="{{ route('admin.upgrade-requests.show', $request) }}" class="btn btn-info" title="Review Request"><i class="fas fa-eye"></i></a>
                                        @if($request->status === 'pending')
                                            {{-- يمكنك إضافة أزرار موافقة/رفض سريعة هنا إذا أردت --}}
                                        @endif
                                        <form action="{{ route('admin.upgrade-requests.destroy', $request) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this request record? This will not change user role.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Delete Request Record"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    No upgrade requests found matching your criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($upgradeRequests->hasPages())
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-center">
                    {{ $upgradeRequests->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
@endsection