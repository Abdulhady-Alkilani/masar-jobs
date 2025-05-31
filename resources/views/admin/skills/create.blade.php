@extends('layouts.admin')
@section('title', 'Create New Skill')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0 text-primary">
            <i class="fas fa-plus-circle me-2"></i> {{ __('Create New Skill') }}
        </h2>
         <a href="{{ route('admin.skills.index') }}" class="btn btn-sm btn-outline-secondary">
             <i class="fas fa-arrow-left me-1"></i> Back to List
        </a>
    </div>
@endsection

@section('content')
    {{-- استخدام عمود أضيق لأن النموذج بسيط --}}
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header">
                    <i class="fas fa-star me-2"></i> New Skill Details
                </div>
                <div class="card-body">
                    @include('partials._alerts')

                    <form action="{{ route('admin.skills.store') }}" method="POST">
                        {{-- تضمين النموذج الجزئي للمهارات --}}
                        {{-- لا نحتاج لتمرير $skill هنا لأننا في وضع الإنشاء --}}
                        @include('admin.skills._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection