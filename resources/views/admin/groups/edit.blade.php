@extends('layouts.admin')
{{-- نفترض أن الرابط هو الشيء الرئيسي للتعريف --}}
@section('title', 'Edit Group: ' . Str::limit($group->{'Telegram Hyper Link'}, 30))

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0 text-primary"><i class="fas fa-edit me-2"></i> Edit Group</h2>
         <a href="{{ route('admin.groups.index') }}" class="btn btn-sm btn-outline-secondary">
             <i class="fas fa-arrow-left me-1"></i> Back to List
        </a>
    </div>
@endsection

@section('content')
     <div class="card shadow-sm">
         <div class="card-header">
            Update Group Details
        </div>
        <div class="card-body">
            @include('partials._alerts')
            <form action="{{ route('admin.groups.update', $group) }}" method="POST">
                 @csrf
                 @method('PUT')
                 {{-- تضمين النموذج الجزئي مع بيانات المجموعة الحالية --}}
                 @include('admin.groups._form', ['group' => $group])
             </form>
        </div>
    </div>
@endsection