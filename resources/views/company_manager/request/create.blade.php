@extends('layouts.app') {{-- أو layouts.guest إذا لم يكن مسجلاً بعد --}}
@section('title', 'Request Company Creation')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Request Company Profile Creation') }}
    </h2>
@endsection

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
             <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">
                <p class="mb-4 text-gray-600 dark:text-gray-400">Please fill out the form below to request the creation of your company profile. It will be reviewed by an administrator.</p>
                 <form action="{{ route('company-manager.request.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    {{-- يمكنك هنا تضمين نفس حقول النموذج الموجودة في profile.edit ولكن بدون قيم افتراضية --}}
                     {{-- حقول Name, Email, Phone, Website, Country, City, Address, Description --}}
                     {{-- مثال لحقل الاسم --}}
                      <div class="mb-4">
                        <label for="Name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Company Name <span class="text-red-500">*</span></label>
                        <input id="Name" type="text" name="Name" value="{{ old('Name') }}" required class="block mt-1 w-full border-gray-300 ... rounded-md shadow-sm">
                        @error('Name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                     {{-- ... باقي الحقول ... --}}

                     {{-- يمكن إضافة حقل لرفع مستندات التسجيل هنا --}}

                    <div class="flex items-center justify-end mt-6">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 ... text-white ...">
                            Submit Request
                        </button>
                    </div>
                </form>
             </div>
        </div>
    </div>
</div>
@endsection