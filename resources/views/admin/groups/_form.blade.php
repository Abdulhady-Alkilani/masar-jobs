{{-- resources/views/admin/groups/_form.blade.php --}}
@csrf

{{-- Telegram Link --}}
<div class="mb-3">
    <label for="Telegram_Hyper_Link" class="form-label">Telegram Group Link <span class="text-danger">*</span></label>
    <input type="url" class="form-control @error('Telegram Hyper Link') is-invalid @enderror" id="Telegram_Hyper_Link" name="Telegram Hyper Link" value="{{ old('Telegram Hyper Link', $group->{'Telegram Hyper Link'} ?? '') }}" required placeholder="https://t.me/joinchat/...">
    @error('Telegram Hyper Link') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- يمكنك إضافة حقول أخرى هنا إذا كان المودل يحتويها، مثل اسم المجموعة أو وصفها --}}
{{-- <div class="mb-3">
    <label for="GroupName" class="form-label">Group Name</label>
    <input type="text" class="form-control @error('GroupName') is-invalid @enderror" id="GroupName" name="GroupName" value="{{ old('GroupName', $group->Name ?? '') }}">
    @error('GroupName') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div> --}}


{{-- Submit Buttons --}}
<div class="d-flex justify-content-end mt-4">
     <a href="{{ route('admin.groups.index') }}" class="btn btn-secondary me-2">Cancel</a>
    <button type="submit" class="btn btn-success">
        <i class="fas fa-save me-1"></i>
        {{ isset($group) ? 'Update Group' : 'Create Group' }}
    </button>
</div>