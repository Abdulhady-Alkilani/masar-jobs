@extends('layouts.admin')
{{-- استخدام اسم الدورة في العنوان مع التحقق من وجوده --}}
@section('title', 'Edit Course: ' . ($trainingCourse->{'Course name'} ?? 'N/A'))

@section('header')
     <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0 text-primary">
             <i class="fas fa-edit me-2"></i>
             {{-- استخدام Str::limit لعرض جزء من العنوان الطويل --}}
             Edit Course: {{ Str::limit($trainingCourse->{'Course name'} ?? 'N/A', 40) }}
        </h2>
         <a href="{{ route('admin.training-courses.index') }}" class="btn btn-sm btn-outline-secondary">
             <i class="fas fa-arrow-left me-1"></i> Back to List
        </a>
    </div>
@endsection

@section('content')
    <div class="card shadow-sm">
         <div class="card-header">
            <i class="fas fa-info-circle me-2"></i> {{ __('Course Details') }}
        </div>
        <div class="card-body">
             {{-- عرض التنبيهات أولاً --}}
             @include('partials._alerts')

             {{-- التأكد التام من بناء جملة الـ Form والـ Route --}}
             <form action="{{ route('admin.training-courses.update', $trainingCourse) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- تضمين النموذج الجزئي وتمرير المتغيرات المطلوبة --}}
                {{-- $trainingCourse يجب أن يكون موجودًا هنا لأنه للتعديل --}}
                {{-- $creators يجب أن يتم تمريرها من الكنترولر --}}
                @include('admin.training_courses._form', [
                    'trainingCourse' => $trainingCourse,
                    'creators' => $creators ?? [] 
                ])

            </form>
        </div> {{-- نهاية card-body --}}
    </div> {{-- نهاية card --}}
@endsection