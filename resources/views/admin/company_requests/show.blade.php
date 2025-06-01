{{-- resources/views/admin/company_requests/show.blade.php (بالـ Bootstrap) --}}
@extends('layouts.admin') {{-- يرث الـ Layout الصحيح --}}
@section('title', 'Review Company Request: ' . ($company->Name ?? 'N/A'))

@section('header')
    <h2 class="h4 mb-0 text-primary">
        <i class="fas fa-file-alt me-2"></i> Review Company Request: {{ $company->Name ?? 'N/A' }}
    </h2>
@endsection

@section('content')
    <div class="card shadow-sm mb-4"> {{-- استخدام Card --}}
        <div class="card-header">
            <i class="fas fa-building fa-fw me-2"></i> Company Details Submitted
        </div>
        <div class="card-body">
            <dl class="row"> {{-- استخدام Definition List و Grid --}}
                <dt class="col-sm-3">Company Name</dt>
                <dd class="col-sm-9">{{ $company->Name ?? 'N/A' }}</dd>

                <dt class="col-sm-3">Submitted By (Manager)</dt>
                <dd class="col-sm-9">
                    @if($company->user)
                        <a href="{{ route('admin.users.show', $company->user) }}" class="link-primary">{{ $company->user->username ?? 'N/A' }}</a> {{-- كلاس Bootstrap للرابط --}}
                    @else
                        N/A
                    @endif
                </dd>

                <dt class="col-sm-3">Company Email</dt>
                <dd class="col-sm-9">{{ $company->Email ?? 'N/A' }}</dd>

                <dt class="col-sm-3">Company Phone</dt>
                <dd class="col-sm-9">{{ $company->Phone ?? 'N/A' }}</dd>

                <dt class="col-sm-3">Country</dt>
                <dd class="col-sm-9">{{ $company->Country ?? 'N/A' }}</dd>

                <dt class="col-sm-3">City</dt>
                <dd class="col-sm-9">{{ $company->City ?? 'N/A' }}</dd>

                <dt class="col-sm-3">Website</dt>
                <dd class="col-sm-9">
                    @if($company->{'Web site'})
                        <a href="{{ $company->{'Web site'} }}" target="_blank" class="link-primary">{{ $company->{'Web site'} }}</a>
                    @else
                        N/A
                    @endif
                </dd>

                <dt class="col-sm-3">Detailed Address</dt>
                <dd class="col-sm-9">{{ $company->{'Detailed Address'} ?? 'N/A' }}</dd>

                <dt class="col-sm-3">Description</dt>
                <dd class="col-sm-9 text-muted">{{ $company->Description ?? 'N/A' }}</dd> {{-- كلاس نص خافت --}}

                <dt class="col-sm-3">Requested At</dt>
                <dd class="col-sm-9">{{ $company->created_at ? $company->created_at->format('Y-m-d H:i') : 'N/A' }}</dd>

                 {{-- رابط لعرض سجل الشركة في الإدارة العامة --}}
                 <dt class="col-sm-3 mt-3"></dt>
                 <dd class="col-sm-9 mt-3">
                      @isset($company)
                        <a href="{{ route('admin.companies.show', $company) }}" class="btn btn-sm btn-outline-secondary"> {{-- زر Bootstrap --}}
                             <i class="fas fa-eye me-1"></i> View Company Record
                        </a>
                      @endisset
                </dd>
            </dl>
        </div>
    </div>

    {{-- قسم إجراءات المراجعة --}}
    <div class="card shadow-sm">
        <div class="card-header">
            <i class="fas fa-tasks me-2"></i> Review Actions
        </div>
         <div class="card-body">
             <form action="{{ route('admin.company-requests.update', $company) }}" method="POST">
                 @csrf
                 @method('PUT')

                <div class="mb-3">
                    <label for="rejection_reason" class="form-label">Rejection Reason <small class="text-muted">(Required if rejecting)</small></label>
                    <textarea name="rejection_reason" id="rejection_reason" rows="3" class="form-control @error('rejection_reason') is-invalid @enderror">{{ old('rejection_reason') }}</textarea>
                    @error('rejection_reason') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-start gap-2 mt-4"> {{-- أزرار الموافقة والرفض --}}
                    <button type="submit" name="action" value="approve" class="btn btn-success" onclick="return confirm('Approve this company request?');">
                        <i class="fas fa-check me-1"></i> Approve
                    </button>
                    <button type="submit" name="action" value="reject" class="btn btn-danger" onclick="return confirm('Reject this company request?');">
                         <i class="fas fa-times me-1"></i> Reject
                    </button>
                    <a href="{{ route('admin.company-requests.index') }}" class="btn btn-secondary ms-auto"> {{-- زر العودة للقائمة --}}
                         <i class="fas fa-arrow-left me-1"></i> Back to List
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection