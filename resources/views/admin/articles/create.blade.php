@extends('layouts.admin')
@section('title', 'Create New User')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Create New User') }}
    </h2>
@endsection

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    {{-- Include the common form partial --}}
                    @include('admin.users._form')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection