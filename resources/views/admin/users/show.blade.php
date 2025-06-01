@extends('layouts.admin') {{-- تأكد أنه يرث من layout الأدمن الصحيح --}}
@section('title', 'User Details: ' . $user->username)

@section('header')
    {{-- استخدام H2 بسيط للعنوان داخل الهيدر الذي يوفره الـ layout --}}
    <h2 class="h4 mb-0">User Details: <span class="text-primary">{{ $user->username }}</span></h2>
@endsection

@section('content')
    {{-- لا نحتاج container هنا لأن الـ layout يوفر padding --}}
    <div class="card shadow-sm mb-4"> {{-- الكارد الأساسي لمعلومات المستخدم --}}
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-user fa-fw me-2"></i>User Information</span>
            <span class="badge {{ $user->status === 'مفعل' ? 'bg-success' : ($user->status === 'معلق' ? 'bg-warning text-dark' : 'bg-danger') }}">
                {{ $user->status }}
            </span>
        </div>
        <div class="card-body">
            <dl class="row"> {{-- استخدام Definition List لعرض البيانات --}}
                <dt class="col-sm-3">ID</dt>
                <dd class="col-sm-9">{{ $user->UserID }}</dd>

                <dt class="col-sm-3">Username</dt>
                <dd class="col-sm-9">{{ $user->username }}</dd>

                <dt class="col-sm-3">First Name</dt>
                <dd class="col-sm-9">{{ $user->first_name }}</dd>

                <dt class="col-sm-3">Last Name</dt>
                <dd class="col-sm-9">{{ $user->last_name }}</dd>

                <dt class="col-sm-3">Email</dt>
                <dd class="col-sm-9">{{ $user->email }}</dd>

                <dt class="col-sm-3">Phone</dt>
                <dd class="col-sm-9">{{ $user->phone ?? 'N/A' }}</dd>

                <dt class="col-sm-3">User Type</dt>
                <dd class="col-sm-9"><span class="badge bg-info">{{ $user->type }}</span></dd>

                <dt class="col-sm-3">Registered At</dt>
                <dd class="col-sm-9">{{ $user->created_at->format('Y-m-d H:i:s') }} ({{ $user->created_at->diffForHumans() }})</dd>

                <dt class="col-sm-3">Last Updated</dt>
                <dd class="col-sm-9">{{ $user->updated_at->format('Y-m-d H:i:s') }} ({{ $user->updated_at->diffForHumans() }})</dd>
            </dl>
        </div>
    </div>

    {{-- قسم بيانات البروفايل (إذا كان المستخدم خريجًا أو استشاريًا ولديه بروفايل) --}}
    @if($user->profile)
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <i class="fas fa-id-card fa-fw me-2"></i>Profile Information
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">University/Field</dt>
                    <dd class="col-sm-9">{{ $user->profile->University ?? 'N/A' }}</dd>

                    <dt class="col-sm-3">GPA/Experience</dt>
                    <dd class="col-sm-9">{{ $user->profile->GPA ?? 'N/A' }}</dd>

                    <dt class="col-sm-3">Personal Description</dt>
                    <dd class="col-sm-9">{{ $user->profile->{'Personal Description'} ?? 'N/A' }}</dd>

                    <dt class="col-sm-3">Technical Description</dt>
                    <dd class="col-sm-9">{{ $user->profile->{'Technical Description'} ?? 'N/A' }}</dd>

                    <dt class="col-sm-3">Website/Link</dt>
                    <dd class="col-sm-9">
                        @if($user->profile->{'Git Hyper Link'})
                            <a href="{{ $user->profile->{'Git Hyper Link'} }}" target="_blank">{{ $user->profile->{'Git Hyper Link'} }}</a>
                        @else
                            N/A
                        @endif
                    </dd>
                     {{-- يمكنك إضافة عرض للمهارات هنا أيضًا --}}
                </dl>
            </div>
        </div>
    @endif

    {{-- قسم بيانات الشركة (إذا كان المستخدم مدير شركة ولديه شركة) --}}
    @if($user->type === 'مدير شركة' && $user->company)
        <div class="card shadow-sm mb-4">
             <div class="card-header">
                <i class="fas fa-building fa-fw me-2"></i>Company Information
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">Company Name</dt>
                    <dd class="col-sm-9">{{ $user->company->Name ?? 'N/A' }}</dd>

                    <dt class="col-sm-3">Company Email</dt>
                    <dd class="col-sm-9">{{ $user->company->Email ?? 'N/A' }}</dd>

                    {{-- أضف حقول الشركة الأخرى هنا --}}

                     <dt class="col-sm-3"></dt>
                    <dd class="col-sm-9 mt-2">
                         <a href="{{ route('admin.companies.show', $user->company) }}" class="btn btn-sm btn-outline-secondary">
                            View Full Company Details
                        </a>
                    </dd>
                </dl>
            </div>
        </div>
    @endif


    {{-- أزرار الإجراءات --}}
    <div class="mt-4 d-flex justify-content-end gap-2"> {{-- استخدام d-flex و gap --}}
        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
            <i class="fas fa-edit me-1"></i> Edit User
        </a>
        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash me-1"></i> Delete User
            </button>
        </form>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
             <i class="fas fa-arrow-left me-1"></i> Back to List
        </a>
    </div>

@endsection