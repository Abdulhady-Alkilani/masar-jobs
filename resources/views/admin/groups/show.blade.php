@extends('layouts.admin')
@section('title', 'Group Details')

@section('header')
    <h2 class="h4 mb-0 text-primary"><i class="fas fa-info-circle me-2"></i> Group Details</h2>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">Group Information</div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">ID</dt>
                <dd class="col-sm-9">{{ $group->GroupID }}</dd>

                <dt class="col-sm-3">Telegram Link</dt>
                <dd class="col-sm-9"><a href="{{ $group->{'Telegram Hyper Link'} }}" target="_blank">{{ $group->{'Telegram Hyper Link'} }}</a></dd>

                {{-- أي بيانات أخرى للمجموعة --}}
                {{-- <dt class="col-sm-3">Name</dt>
                <dd class="col-sm-9">{{ $group->Name ?? 'N/A' }}</dd> --}}

                <dt class="col-sm-3">Created At</dt>
                <dd class="col-sm-9">{{ $group->created_at->format('Y-m-d H:i') }}</dd>
            </dl>
            <div class="text-end mt-3">
                <a href="{{ route('admin.groups.edit', $group) }}" class="btn btn-warning btn-sm me-1">Edit</a>
                <form action="{{ route('admin.groups.destroy', $group) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
                <a href="{{ route('admin.groups.index') }}" class="btn btn-secondary btn-sm ms-1">Back to List</a>
            </div>
        </div>
    </div>
@endsection