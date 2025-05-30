@extends('layouts.admin')
@section('title', 'Pending Company Requests')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0 text-primary"><i class="fas fa-hourglass-half me-2"></i> {{ __('Pending Company Requests') }}</h2>
        {{-- لا يوجد زر إضافة هنا عادةً --}}
    </div>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <span class="card-title mb-0">Requests Awaiting Approval</span>
             {{-- يمكنك إضافة فلترة بسيطة هنا إذا أردت --}}
        </div>

        <div class="card-body p-0">
            <div class="m-3"> @include('partials._alerts') </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Company Name</th>
                            <th>Requested By (Manager)</th>
                            <th>Email</th>
                            <th>Requested At</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- المتغير يجب أن يكون $pendingCompanies أو اسم مشابه من الكنترولر --}}
                        @forelse ($pendingCompanies as $company)
                            <tr>
                                <td class="ps-3">{{ $company->Name }}</td>
                                {{-- افتراض وجود علاقة user في مودل Company --}}
                                <td>{{ $company->user->username ?? 'N/A' }}</td>
                                <td>{{ $company->Email ?? 'N/A' }}</td>
                                <td>{{ $company->created_at->format('Y-m-d H:i') }}</td>
                                <td class="text-end pe-3">
                                    <div class="btn-group btn-group-sm">
                                        {{-- رابط لمراجعة التفاصيل --}}
                                        <a href="{{ route('admin.company-requests.show', $company) }}" class="btn btn-info" title="Review Details"><i class="fas fa-eye"></i></a>
                                        {{-- زر موافقة سريع --}}
                                        <form action="{{ route('admin.company-requests.update', $company) }}" method="POST" class="d-inline" onsubmit="return confirm('Approve this company request?');">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="action" value="approve">
                                            <button type="submit" class="btn btn-success" title="Approve"><i class="fas fa-check"></i></button>
                                        </form>
                                         {{-- زر رفض سريع --}}
                                        <form action="{{ route('admin.company-requests.update', $company) }}" method="POST" class="d-inline" onsubmit="return confirm('Reject this company request?');">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="action" value="reject">
                                            <button type="submit" class="btn btn-danger" title="Reject"><i class="fas fa-times"></i></button>
                                        </form>
                                         {{-- زر حذف الطلب (اختياري) --}}
                                         <form action="{{ route('admin.company-requests.destroy', $company) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this pending request entirely?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-secondary" title="Delete Request"><i class="fas fa-trash-alt"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No pending company requests found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div> {{-- نهاية card-body --}}

        @if ($pendingCompanies->hasPages())
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-center">
                    {{ $pendingCompanies->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div> {{-- نهاية card --}}
@endsection