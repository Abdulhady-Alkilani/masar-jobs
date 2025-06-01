{{-- resources/views/admin/users/index.blade.php (النسخة الصحيحة بـ Bootstrap) --}}
@extends('layouts.admin') {{-- تأكد أنه يرث من layout الأدمن الصحيح --}}

@section('title', 'Manage Users')

@section('header')
    {{-- استخدام H2 بسيط للعنوان داخل الهيدر الذي يوفره الـ layout --}}
    <h2 class="h4 mb-0">{{ __('Manage Users') }}</h2>
@endsection

@section('content')
{{-- لا نحتاج container هنا لأن الـ layout يفترض أن يوفر padding --}}
<div class="card shadow-sm"> {{-- استخدام Card كهيكل رئيسي --}}
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <span><i class="fas fa-users me-2"></i>Users List</span> {{-- أيقونة وعنوان --}}
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Add New User
            </a>
        </div>
    </div> {{-- نهاية card-header --}}

    <div class="card-body">
        {{-- تضمين التنبيهات --}}
        @include('partials._alerts')

        {{-- يمكنك إضافة نموذج بحث هنا إذا لزم الأمر --}}
        {{-- <div class="mb-3"> ... search form ... </div> --}}

        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle"> {{-- align-middle لمحاذاة المحتوى رأسياً --}}
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td><span class="badge bg-secondary">{{ $user->type }}</span></td> {{-- تنسيق النوع كـ Badge --}}
                            <td>
                                <span class="badge {{ $user->status === 'مفعل' ? 'bg-success' : ($user->status === 'معلق' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                    {{ $user->status }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group"> {{-- تجميع الأزرار --}}
                                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-info" title="View"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" title="Delete"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">
                                No users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div> {{-- نهاية card-body --}}

    @if ($users->hasPages())
        <div class="card-footer bg-light">
            <div class="d-flex justify-content-center">
                {{ $users->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif

</div> {{-- نهاية card --}}
@endsection