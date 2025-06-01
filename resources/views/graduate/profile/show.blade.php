@extends('layouts.graduate') {{-- أو layouts.app --}}
@section('title', 'My Profile')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0 text-primary"><i class="fas fa-user-circle me-2"></i> My Profile</h2>
        <a href="{{ route('graduate.profile.edit') }}" class="btn btn-warning btn-sm">
            <i class="fas fa-edit me-1"></i> Edit Profile & Skills
        </a>
    </div>
@endsection

@section('content')
    @include('partials._alerts')

    <div class="row g-4">
        {{-- العمود الأيسر: الصورة والمعلومات الأساسية --}}
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4 text-center">
                <div class="card-body">
                    {{-- الصورة الشخصية --}}
                    <img src="{{ $user->photo ? asset('storage/'.$user->photo) : 'https://via.placeholder.com/150/cccccc/808080?text=No+Photo' }}"
                         alt="{{ $user->username }}" class="rounded-circle img-thumbnail mb-3 mx-auto" style="width: 150px; height: 150px; object-fit: cover;">
                    <h5 class="card-title mb-1">{{ $user->first_name }} {{ $user->last_name }}</h5>
                    <p class="card-text text-muted mb-2">{{ '@' . $user->username }} ({{ $user->type }})</p>
                    <hr>
                    <p class="card-text text-start small mb-1"><i class="fas fa-envelope fa-fw me-2 text-muted"></i>{{ $user->email }}</p>
                    <p class="card-text text-start small mb-0"><i class="fas fa-phone fa-fw me-2 text-muted"></i>{{ $user->phone ?? 'N/A' }}</p>
                     {{-- رابط GitHub --}}
                     @if($user->profile?->{'Git Hyper Link'})
                        <a href="{{ $user->profile->{'Git Hyper Link'} }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-dark mt-3">
                            <i class="fab fa-github me-1"></i> GitHub/Link
                        </a>
                     @endif
                </div>
            </div>
        </div>

        {{-- العمود الأيمن: تفاصيل البروفايل والمهارات --}}
        <div class="col-lg-8">
             {{-- تفاصيل البروفايل --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <i class="fas fa-user-graduate me-2"></i> Academic & Professional Details
                </div>
                <div class="card-body">
                    @if($user->profile)
                        <dl class="row mb-0">
                            <dt class="col-sm-4">University:</dt>
                            <dd class="col-sm-8">{{ $user->profile->University ?? 'Not Specified' }}</dd>

                            <dt class="col-sm-4">GPA:</dt>
                            <dd class="col-sm-8">{{ $user->profile->GPA ?? 'Not Specified' }}</dd>

                            <dt class="col-sm-4">Personal Bio:</dt>
                            <dd class="col-sm-8 text-muted">{{ $user->profile->{'Personal Description'} ?? 'Not Specified' }}</dd>

                             <dt class="col-sm-4">Technical Summary:</dt>
                            <dd class="col-sm-8 text-muted">{{ $user->profile->{'Technical Description'} ?? 'Not Specified' }}</dd>
                        </dl>
                    @else
                        <p class="text-muted mb-0">Profile details have not been completed yet. <a href="{{ route('graduate.profile.edit') }}">Complete it now?</a></p>
                    @endif
                </div>
            </div>

             {{-- المهارات --}}
            <div class="card shadow-sm">
                <div class="card-header">
                    <i class="fas fa-star me-2"></i> My Skills
                </div>
                 <div class="card-body">
                     @if($user->skills && $user->skills->count() > 0)
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($user->skills as $skill)
                                <span class="badge rounded-pill bg-primary px-3 py-2"> {{-- Badge أكبر --}}
                                    {{ $skill->Name }}
                                    @if($skill->pivot->Stage)
                                    <span class="badge bg-light text-primary ms-1">{{ $skill->pivot->Stage }}</span> {{-- عرض المستوى --}}
                                    @endif
                                </span>
                            @endforeach
                        </div>
                     @else
                         <p class="text-muted mb-0">No skills have been added yet. <a href="{{ route('graduate.profile.edit') }}">Add them now?</a></p>
                     @endif
                </div>
            </div>
        </div>
    </div>
@endsection