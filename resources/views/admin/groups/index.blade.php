@extends('layouts.admin')
@section('title', 'Manage Groups')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0 text-primary"><i class="fas fa-layer-group me-2"></i> {{ __('Manage Groups') }}</h2>
        <a href="{{ route('admin.groups.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Add New Group
        </a>
    </div>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <span class="card-title mb-0">Groups List</span>
            {{-- Add search if needed --}}
        </div>

        <div class="card-body p-0">
            <div class="m-3"> @include('partials._alerts') </div>
             <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Telegram Link</th>
                            {{-- أضف أعمدة أخرى إذا كان المودل يحتويها --}}
                            {{-- <th>Name</th> --}}
                            {{-- !!! تم حذف عمود Created At !!! --}}
                            {{-- <th>Created At</th> --}}
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($groups as $group)
                            <tr>
                                <td class="ps-3">
                                    <a href="{{ $group->{'Telegram Hyper Link'} }}" target="_blank" rel="noopener noreferrer">
                                        {{ $group->{'Telegram Hyper Link'} }} <i class="fas fa-external-link-alt fa-xs ms-1"></i>
                                    </a>
                                </td>
                                {{-- <td>{{ $group->Name ?? 'N/A' }}</td> --}}
                                {{-- !!! تم حذف عرض التاريخ من هنا !!! --}}
                                {{-- <td>{{ $group->created_at->format('Y-m-d') }}</td> --}}
                                <td class="text-end pe-3">
                                    <div class="btn-group btn-group-sm">
                                        {{-- قد لا تحتاج لـ show view منفصل --}}
                                        {{-- <a href="{{ route('admin.groups.show', $group) }}" class="btn btn-info" title="View"><i class="fas fa-eye"></i></a> --}}
                                        <a href="{{ route('admin.groups.edit', $group) }}" class="btn btn-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('admin.groups.destroy', $group) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" title="Delete"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                {{-- !!! تعديل colspan ليناسب عدد الأعمدة الجديد !!! --}}
                                <td colspan="2" class="text-center text-muted py-4"> {{-- أو 3 إذا أضفت عمود Name --}}
                                    No groups found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($groups->hasPages())
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-center">
                    {{ $groups->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
@endsection