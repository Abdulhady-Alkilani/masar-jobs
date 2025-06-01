{{-- Partial form for creating and editing users --}}
@csrf

{{-- First Name --}}
<div class="mb-4">
    <label for="first_name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('First Name') }} <span class="text-red-500">*</span></label>
    <input id="first_name" type="text" name="first_name" value="{{ old('first_name', $user->first_name ?? '') }}" required autofocus
           class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm @error('first_name') border-red-500 @enderror">
    @error('first_name') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
</div>

{{-- Last Name --}}
<div class="mb-4">
    <label for="last_name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Last Name') }} <span class="text-red-500">*</span></label>
    <input id="last_name" type="text" name="last_name" value="{{ old('last_name', $user->last_name ?? '') }}" required
           class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm @error('last_name') border-red-500 @enderror">
    @error('last_name') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
</div>

{{-- Username --}}
<div class="mb-4">
    <label for="username" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Username') }} <span class="text-red-500">*</span></label>
    <input id="username" type="text" name="username" value="{{ old('username', $user->username ?? '') }}" required
           class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm @error('username') border-red-500 @enderror">
    @error('username') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
</div>

{{-- Email --}}
<div class="mb-4">
    <label for="email" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Email Address') }} <span class="text-red-500">*</span></label>
    <input id="email" type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required
           class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm @error('email') border-red-500 @enderror">
    @error('email') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
</div>

{{-- Phone --}}
<div class="mb-4">
    <label for="phone" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Phone') }}</label>
    <input id="phone" type="tel" name="phone" value="{{ old('phone', $user->phone ?? '') }}"
           class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm @error('phone') border-red-500 @enderror">
    @error('phone') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
</div>

{{-- User Type --}}
<div class="mb-4">
     <label for="type" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('User Type') }} <span class="text-red-500">*</span></label>
     <select name="type" id="type" required class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm @error('type') border-red-500 @enderror">
        <option value="" disabled {{ old('type', $user->type ?? '') == '' ? 'selected' : '' }}>{{ __('-- Select Type --') }}</option>
        {{-- يجب أن تكون القيم مطابقة لما هو معرف في النظام --}}
        <option value="خريج" {{ old('type', $user->type ?? '') == 'خريج' ? 'selected' : '' }}>{{ __('Graduate') }}</option>
        <option value="خبير استشاري" {{ old('type', $user->type ?? '') == 'خبير استشاري' ? 'selected' : '' }}>{{ __('Consultant') }}</option>
        <option value="مدير شركة" {{ old('type', $user->type ?? '') == 'مدير شركة' ? 'selected' : '' }}>{{ __('Company Manager') }}</option>
        <option value="Admin" {{ old('type', $user->type ?? '') == 'Admin' ? 'selected' : '' }}>{{ __('Admin') }}</option>
     </select>
     @error('type') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
</div>

{{-- Status --}}
<div class="mb-4">
     <label for="status" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Status') }} <span class="text-red-500">*</span></label>
     <select name="status" id="status" required class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm @error('status') border-red-500 @enderror">
        <option value="" disabled {{ old('status', $user->status ?? '') == '' ? 'selected' : '' }}>{{ __('-- Select Status --') }}</option>
        {{-- يجب أن تكون القيم مطابقة لما هو معرف في النظام --}}
        <option value="مفعل" {{ old('status', $user->status ?? '') == 'مفعل' ? 'selected' : '' }}>{{ __('Active') }}</option>
        <option value="معلق" {{ old('status', $user->status ?? '') == 'معلق' ? 'selected' : '' }}>{{ __('Pending / Inactive') }}</option>
        <option value="محذوف" {{ old('status', $user->status ?? '') == 'محذوف' ? 'selected' : '' }}>{{ __('Deleted') }}</option> {{-- أو Banned --}}
     </select>
     @error('status') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
</div>


{{-- Password (required on create, optional on edit) --}}
<div class="mb-4">
    <label for="password" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
        {{ __('Password') }}
        @isset($user) <span class="text-xs italic text-gray-500 dark:text-gray-400"> (Leave blank to keep current)</span> @else <span class="text-red-500">*</span> @endisset
    </label>
    <input id="password" type="password" name="password" {{ !isset($user) ? 'required' : '' }} autocomplete="new-password"
           class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm @error('password') border-red-500 @enderror">
    @error('password') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
</div>

{{-- Confirm Password --}}
<div class="mb-4">
    <label for="password_confirmation" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Confirm Password') }}</label>
    <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password"
           class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
</div>

{{-- Submit Button --}}
<div class="flex items-center justify-end mt-6">
     <a href="{{ route('admin.users.index') }}" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md mr-4">
        {{ __('Cancel') }}
    </a>

    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
        {{ isset($user) ? __('Update User') : __('Create User') }}
    </button>
</div>