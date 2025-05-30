@extends('layouts.admin')
{{-- استخدام $skill->Name في العنوان --}}
@section('title', 'Edit Skill: ' . $skill->Name)

@section('header')
     <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0 text-primary">
             <i class="fas fa-edit me-2"></i> Edit Skill: {{ $skill->Name }}
        </h2>
         <a href="{{ route('admin.skills.index') }}" class="btn btn-sm btn-outline-secondary">
             <i class="fas fa-arrow-left me-1"></i> Back to List
        </a>
    </div>
@endsection

@section('content')
     {{-- استخدام عمود أضيق --}}
     <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm">
                 <div class="card-header">
                    <i class="fas fa-star me-2"></i> Edit Skill Details
                </div>
                <div class="card-body">
                     @include('partials._alerts')

                     <form action="{{ route('admin.skills.update', $skill) }}" method="POST">
                        @method('PUT')
                        {{-- تضمين النموذج الجزئي وتمرير بيانات المهارة الحالية $skill --}}
                        @include('admin.skills._form', ['skill' => $skill])
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection