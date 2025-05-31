{{-- resources/views/partials/_upgrade_request_buttons.blade.php --}}
<div class="d-flex flex-wrap gap-2">
    <form action="{{ route('upgrade.request.store') }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to request an upgrade to Consultant account?');">
        @csrf
        <input type="hidden" name="requested_role" value="خبير استشاري">
        {{-- يمكنك إضافة حقل لسبب الطلب هنا إذا أردت --}}
        {{-- <div class="mb-2">
            <textarea name="reason" class="form-control form-control-sm" rows="2" placeholder="Optional: Reason for your request..."></textarea>
        </div> --}}
        <button type="submit" class="btn btn-outline-success btn-sm">
            <i class="fas fa-user-tie me-1"></i> Request Consultant Account
        </button>
    </form>

    <form action="{{ route('upgrade.request.store') }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to request an upgrade to Company Manager account?');">
        @csrf
        <input type="hidden" name="requested_role" value="مدير شركة">
         {{-- يمكنك إضافة حقل لسبب الطلب هنا إذا أردت --}}
        {{-- <div class="mb-2">
            <textarea name="reason" class="form-control form-control-sm" rows="2" placeholder="Optional: Reason for your request..."></textarea>
        </div> --}}
        <button type="submit" class="btn btn-outline-info btn-sm">
            <i class="fas fa-building me-1"></i> Request Company Manager Account
        </button>
    </form>
</div>