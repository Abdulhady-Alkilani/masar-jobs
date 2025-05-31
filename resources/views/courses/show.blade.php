@extends('layouts.app')

@section('title', $trainingCourse->{'Course name'} ?? 'Course Details')

@section('header')
     {{-- <h2 class="h4 text-primary mb-0"><i class="fas fa-graduation-cap me-2"></i> {{ $trainingCourse->{'Course name'} }}</h2> --}}
@endsection

@section('content')
<div class="container mt-4">
     <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">Courses</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($trainingCourse->{'Course name'}, 50) }}</li>
        </ol>
    </nav>

     <div class="card shadow-sm">
         <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
             <h1 class="h3 mb-0">{{ $trainingCourse->{'Course name'} }}</h1>
              {{-- زر التسجيل للخريج (أكثر بروزًا هنا) --}}
             @auth
                @if(Auth::user()->type === 'خريج')
                <form action="{{ route('courses.enroll', $trainingCourse) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-light btn-sm fw-bold">
                        <i class="fas fa-user-plus me-1"></i> Enroll Now
                    </button>
                </form>
                @endif
             @endauth
             @guest
                 <a href="{{ route('register', ['type' => 'خريج']) }}" class="btn btn-light btn-sm fw-bold">Register to Enroll</a>
             @endguest
         </div>
         <div class="card-body">
             @include('partials._alerts') {{-- لعرض رسائل الخطأ عند محاولة التسجيل مرة أخرى مثلاً --}}

             <div class="row g-4">
                 <div class="col-md-7">
                     <h5 class="card-title">Course Description</h5>
                     <p class="text-muted" style="white-space: pre-wrap;">{{ $trainingCourse->{'Course Description'} }}</p>
                 </div>
                 <div class="col-md-5">
                     <h5 class="card-title">Course Details</h5>
                     <ul class="list-group list-group-flush">
                         <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                             <span><i class="fas fa-chalkboard-teacher fa-fw me-2 text-muted"></i> Trainer(s):</span>
                             <span class="text-end">
                                {{ $trainingCourse->{'Trainers name'} ?? 'N/A' }}
                                @if($trainingCourse->{'Trainers Site'})
                                    <a href="{{ $trainingCourse->{'Trainers Site'} }}" target="_blank" class="ms-1" title="Trainer Website"><i class="fas fa-link fa-xs"></i></a>
                                @endif
                            </span>
                         </li>
                          <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                             <span><i class="fas fa-map-marker-alt fa-fw me-2 text-muted"></i> Location/Site:</span>
                             <strong>{{ $trainingCourse->Site ?? 'N/A' }}</strong>
                         </li>
                          <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                             <span><i class="fas fa-layer-group fa-fw me-2 text-muted"></i> Stage:</span>
                             <span class="badge bg-secondary">{{ $trainingCourse->Stage ?? 'N/A' }}</span>
                         </li>
                         <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                             <span><i class="fas fa-calendar-alt fa-fw me-2 text-muted"></i> Start Date:</span>
                             <strong>{{ $trainingCourse->{'Start Date'} ? \Carbon\Carbon::parse($trainingCourse->{'Start Date'})->format('M d, Y') : 'Not Specified' }}</strong>
                         </li>
                         <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                             <span><i class="fas fa-calendar-check fa-fw me-2 text-muted"></i> End Date:</span>
                              <strong>{{ $trainingCourse->{'End Date'} ? \Carbon\Carbon::parse($trainingCourse->{'End Date'})->format('M d, Y') : 'Not Specified' }}</strong>
                         </li>
                         <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                             <span><i class="fas fa-certificate fa-fw me-2 {{ $trainingCourse->Certificate == 'يوجد' ? 'text-success' : 'text-muted' }}"></i> Certificate:</span>
                             <strong class="{{ $trainingCourse->Certificate == 'يوجد' ? 'text-success' : '' }}">{{ $trainingCourse->Certificate == 'يوجد' ? 'Available' : 'Not Available' }}</strong>
                         </li>
                          <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                             <span><i class="fas fa-user fa-fw me-2 text-muted"></i> Created By:</span>
                             <span>{{ $trainingCourse->creator->username ?? 'N/A' }}</span> {{-- افتراض وجود علاقة creator --}}
                         </li>
                         @if($trainingCourse->{'Enroll Hyper Link'})
                          <li class="list-group-item px-0 text-center mt-3">
                                <a href="{{ $trainingCourse->{'Enroll Hyper Link'} }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary">
                                     <i class="fas fa-external-link-alt me-1"></i> Enroll via External Link
                                 </a>
                          </li>
                         @endif
                     </ul>
                 </div>
             </div>

              {{-- أزرار الإدارة (إذا كان المستخدم المصرح له يزور هذه الصفحة) --}}
              @canany(['update', 'delete'], $trainingCourse)
                <hr class="my-4">
                <div class="text-end">
                     <a href="{{-- route('...') --}}" class="btn btn-sm btn-warning me-2"><i class="fas fa-edit me-1"></i> Edit Course</a>
                     <form action="{{-- route('...') --}}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                         @csrf
                         @method('DELETE')
                         <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash me-1"></i> Delete Course</button>
                     </form>
                 </div>
             @endcanany

         </div>
     </div>

     <div class="text-center mt-4">
         <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary btn-sm">
             <i class="fas fa-arrow-left me-1"></i> Back to Courses List
         </a>
     </div>
</div>
@endsection