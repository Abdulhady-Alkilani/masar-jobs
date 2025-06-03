@extends('layouts.graduate') {{-- تأكد من أنه يرث من الـ Layout الصحيح للخريج --}}
@section('title', ($user->username ?? 'User') . ' - My Profile')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0 text-primary"><i class="fas fa-user-circle me-2"></i> My Profile</h2>
        <a href="{{ route('graduate.profile.edit') }}" class="btn btn-warning btn-sm">
            <i class="fas fa-edit me-1"></i> Edit Profile & Skills
        </a>
    </div>
@endsection

@section('content')
    @include('partials._alerts') {{-- لتضمين رسائل النجاح/الخطأ --}}

    <div class="row g-4">
        {{-- العمود الأيسر: الصورة والمعلومات الأساسية --}}
        <div class="col-lg-4">
            <div class="card shadow-sm text-center h-100"> {{-- h-100 لجعل ارتفاع الكارد متناسق --}}
                <div class="card-body p-4 d-flex flex-column align-items-center"> {{-- توسيط عمودي وأفقي --}}
                    {{-- الجزء الخاص بعرض الصورة --}}
                    @if ($user->photo && Storage::disk('public')->exists($user->photo))
                        {{-- إذا كان لدى المستخدم صورة وموجودة فعليًا في الـ storage --}}
                        <img src="{{ Storage::disk('public')->url($user->photo) }}"
                             alt="{{ $user->username ?? 'User' }}'s Profile Photo"
                             class="rounded-circle img-thumbnail mb-3"
                             style="width: 150px; height: 150px; object-fit: cover; border-width: 3px;">
                    @else
                        {{-- صورة افتراضية إذا لم تكن هناك صورة أو المسار غير صحيح --}}
                        {{-- تأكد من وجود هذه الصورة في public/images/default_avatar.png --}}
                        <img src="{{ asset('images/default_avatar.png') }}"
                             alt="Default Avatar"
                             class="rounded-circle img-thumbnail mb-3"
                             style="width: 150px; height: 150px; object-fit: cover; border-width: 3px;">
                    @endif
                    {{-- نهاية الجزء الخاص بعرض الصورة --}}

                    <h5 class="card-title mb-1 fw-bold">{{ $user->first_name }} {{ $user->last_name }}</h5>
                    <p class="card-text text-muted mb-0"><small>{{ '@' . ($user->username ?? 'N/A') }}</small></p>
                    <p class="card-text text-muted mb-2"><span class="badge bg-info text-dark">{{ $user->type ?? 'N/A' }}</span></p>
                    <hr class="my-3">
                    <div class="text-start w-100">
                        <p class="card-text small mb-1">
                            <i class="fas fa-envelope fa-fw me-2 text-muted"></i>
                            <a href="mailto:{{ $user->email }}" class="text-decoration-none text-dark">{{ $user->email }}</a>
                        </p>
                        <p class="card-text small mb-0">
                            <i class="fas fa-phone fa-fw me-2 text-muted"></i>
                            {{ $user->phone ?? 'Not Provided' }}
                        </p>
                    </div>

                     @if($user->profile?->{'Git Hyper Link'})
                        <a href="{{ $user->profile->{'Git Hyper Link'} }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-dark mt-3 w-100">
                            <i class="fab fa-github me-1"></i> View GitHub/Link
                        </a>
                     @endif
                </div>
            </div>
        </div>

        {{-- العمود الأيمن: تفاصيل البروفايل والمهارات --}}
        <div class="col-lg-8">
            {{-- تفاصيل البروفايل --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light py-3">
                    <h6 class="mb-0"><i class="fas fa-user-graduate me-2 text-primary"></i> Academic & Professional Details</h6>
                </div>
                <div class="card-body p-4">
                    @if($user->profile)
                        <dl class="row mb-0">
                            <dt class="col-sm-3 text-muted">University:</dt>
                            <dd class="col-sm-9">{{ $user->profile->University ?? 'Not Specified' }}</dd>

                            <dt class="col-sm-3 text-muted">GPA:</dt>
                            <dd class="col-sm-9">{{ $user->profile->GPA ?? 'Not Specified' }}</dd>

                            <dt class="col-sm-3 text-muted">Personal Bio:</dt>
                            <dd class="col-sm-9">{{ $user->profile->{'Personal Description'} ?? 'Not Specified' }}</dd>

                             <dt class="col-sm-3 text-muted">Technical Summary:</dt>
                            <dd class="col-sm-9">{{ $user->profile->{'Technical Description'} ?? 'Not Specified' }}</dd>
                        </dl>
                    @else
                        <div class="text-center p-3">
                            <p class="text-muted mb-2">Profile details have not been completed yet.</p>
                            <a href="{{ route('graduate.profile.edit') }}" class="btn btn-success btn-sm">
                                <i class="fas fa-plus-circle me-1"></i> Complete Your Profile
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- المهارات --}}
            <div class="card shadow-sm">
                <div class="card-header bg-light py-3">
                    <h6 class="mb-0"><i class="fas fa-star me-2 text-primary"></i> My Skills</h6>
                </div>
                <div class="card-body p-4">
                     @if($user->skills && $user->skills->count() > 0)
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($user->skills as $skill)
                                <span class="badge rounded-pill bg-primary px-3 py-2 fs-6">
                                    {{ $skill->Name }}
                                    @if($skill->pivot->Stage)
                                    <span class="badge bg-light text-primary ms-1 border border-primary">{{ $skill->pivot->Stage }}</span>
                                    @endif
                                </span>
                            @endforeach
                        </div>
                     @else
                         <div class="text-center p-3">
                             <p class="text-muted mb-2">No skills have been added yet.</p>
                              <a href="{{ route('graduate.profile.edit') }}" class="btn btn-success btn-sm">
                                 <i class="fas fa-plus-circle me-1"></i> Add Your Skills
                             </a>
                         </div>
                     @endif
                </div>
            </div>
        </div>
    </div>
@endsection