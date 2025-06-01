{{-- resources/views/admin/upgrade_requests/show.blade.php --}}
@extends('layouts.admin')
@section('title', 'Review Upgrade Request: ' . ($upgradeRequest->user->username ?? 'Unknown User'))

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0 text-primary">
            <i class="fas fa-user-shield me-2"></i> Review Upgrade Request
        </h2>
        <a href="{{ route('admin.upgrade-requests.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Requests List
        </a>
    </div>
@endsection

@section('content')
    @include('partials._alerts')

    <div class="row g-4">
        {{-- معلومات الطلب والمستخدم --}}
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-header">
                    <i class="fas fa-info-circle me-2"></i> Request Details
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">Request ID:</dt>
                        <dd class="col-sm-8">{{ $upgradeRequest->id }}</dd>

                        <dt class="col-sm-4">User:</dt>
                        <dd class="col-sm-8">
                            @if($upgradeRequest->user)
                                <a href="{{ route('admin.users.show', $upgradeRequest->user) }}">{{ $upgradeRequest->user->username }}</a>
                                ({{ $upgradeRequest->user->first_name }} {{ $upgradeRequest->user->last_name }})
                            @else
                                <span class="text-danger">User not found</span>
                            @endif
                        </dd>

                        <dt class="col-sm-4">User's Current Role:</dt>
                        <dd class="col-sm-8"><span class="badge bg-secondary">{{ $upgradeRequest->user->type ?? 'N/A' }}</span></dd>

                        <dt class="col-sm-4">Requested Role:</dt>
                        <dd class="col-sm-8"><span class="badge bg-info text-dark">{{ $upgradeRequest->requested_role }}</span></dd>

                        <dt class="col-sm-4">Request Date:</dt>
                        <dd class="col-sm-8">{{ $upgradeRequest->created_at->format('Y-m-d H:i:s') }}</dd>

                        <dt class="col-sm-4">Current Status:</dt>
                        <dd class="col-sm-8">
                            <span class="badge
                                {{ match ($upgradeRequest->status) {
                                    'pending' => 'bg-warning text-dark',
                                    'approved' => 'bg-success',
                                    'rejected' => 'bg-danger',
                                    default => 'bg-secondary',
                                } }}">
                                {{ ucfirst($upgradeRequest->status) }}
                            </span>
                        </dd>

                        @if($upgradeRequest->reason)
                        <dt class="col-sm-4">Reason for Request:</dt>
                        <dd class="col-sm-8"><p class="text-muted fst-italic">"{{ $upgradeRequest->reason }}"</p></dd>
                        @endif

                        @if($upgradeRequest->status !== 'pending' && $upgradeRequest->admin_notes)
                            <dt class="col-sm-4">Admin Notes:</dt>
                            <dd class="col-sm-8">{{ $upgradeRequest->admin_notes }}</dd>
                        @endif
                        @if($upgradeRequest->status !== 'pending')
                            <dt class="col-sm-4">Processed At:</dt>
                            <dd class="col-sm-8">{{ $upgradeRequest->updated_at->format('Y-m-d H:i:s') }}</dd>
                        @endif
                    </dl>
                </div>
            </div>
        </div>

        {{-- قسم الإجراءات للأدمن (إذا كان الطلب لا يزال معلقًا) --}}
        <div class="col-lg-5">
            @if($upgradeRequest->status === 'pending')
                <div class="card shadow-sm">
                    <div class="card-header">
                        <i class="fas fa-tasks me-2"></i> Process Request
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.upgrade-requests.update', $upgradeRequest) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="admin_notes" class="form-label">Admin Notes <small class="text-muted">(Optional for approval, recommended for rejection)</small></label>
                                <textarea name="admin_notes" id="admin_notes" rows="3" class="form-control @error('admin_notes') is-invalid @enderror">{{ old('admin_notes', $upgradeRequest->admin_notes) }}</textarea>
                                @error('admin_notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="d-flex justify-content-start gap-2 mt-4">
                                <button type="submit" name="action" value="approve" class="btn btn-success" onclick="return confirm('Approve this upgrade request? User role will be changed.');">
                                    <i class="fas fa-check me-1"></i> Approve Request
                                </button>
                                <button type="submit" name="action" value="reject" class="btn btn-danger" onclick="return confirm('Reject this upgrade request?');">
                                    <i class="fas fa-times me-1"></i> Reject Request
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                <div class="alert alert-light border shadow-sm" role="alert">
                   <h4 class="alert-heading"><i class="fas fa-info-circle me-1"></i> Request Processed</h4>
                    <p>This upgrade request has already been processed. The status is <strong>{{ ucfirst($upgradeRequest->status) }}</strong>.</p>
                    @if($upgradeRequest->user)
                        <hr>
                        <p class="mb-0">
                            <a href="{{ route('admin.users.show', $upgradeRequest->user_id) }}" class="alert-link">View user's current profile</a>.
                        </p>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection