@extends('layouts.admin')
@section('title', 'Add New Group')

@section('header')
     <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0 text-primary"><i class="fas fa-plus-circle me-2"></i> Add New Group</h2>
         <a href="{{ route('admin.groups.index') }}" class="btn btn-sm btn-outline-secondary">
             <i class="fas fa-arrow-left me-1"></i> Back to List
        </a>
    </div>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            Enter Group Details
        </div>
        <div class="card-body">
             @include('partials._alerts')
            <form action="{{ route('admin.groups.store') }}" method="POST">
                {{-- تضمين النموذج الجزئي (بدون تمرير $group) --}}
                @include('admin.groups._form')
            </form>
        </div>
    </div>
@endsection