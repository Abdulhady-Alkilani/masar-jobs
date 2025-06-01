@extends('layouts.app') {{-- أو layouts.guest إذا أردت إتاحته للزوار --}}
@section('title', 'Community Groups')

@section('header')
    {{-- يمكنك إضافة عنوان رئيسي هنا إذا كان الـ layout يدعمه --}}
    {{-- <h2 class="h4 mb-0 text-primary"> ... </h2> --}}
@endsection

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <h1 class="text-center mb-4"><i class="fas fa-users me-2"></i> Community Groups</h1>
            <p class="lead text-center text-muted mb-5">
                Join our community groups on Telegram to connect with other graduates, consultants, and companies.
            </p>

             @include('partials._alerts') {{-- لعرض أي رسائل عامة --}}

            <div class="list-group shadow-sm"> {{-- استخدام List Group لعرض الروابط --}}
                @forelse ($groups as $group)
                    <a href="{{ $group->{'Telegram Hyper Link'} }}"
                       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                       target="_blank" rel="noopener noreferrer">
                        <span>
                             <i class="fab fa-telegram-plane fa-fw me-2 text-primary"></i> {{-- أيقونة تيليجرام --}}
                            {{-- عرض اسم المجموعة إذا كان موجودًا، وإلا عرض جزء من الرابط --}}
                            {{ $group->Name ?? 'Group Link ' . $loop->iteration }}
                             <small class="d-block text-muted ps-4">{{ Str::limit(str_replace(['https://t.me/joinchat/', 'https://t.me/'], '', $group->{'Telegram Hyper Link'}), 50) }}</small>
                        </span>
                        <i class="fas fa-external-link-alt fa-xs text-secondary"></i> {{-- أيقونة رابط خارجي --}}
                    </a>
                @empty
                    <div class="list-group-item text-muted text-center py-4">
                        No community groups have been added yet. Please check back later.
                    </div>
                @endforelse
            </div>

            {{-- Pagination Links --}}
            @if ($groups->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $groups->links('pagination::bootstrap-5') }}
                </div>
            @endif

             {{-- زر العودة للصفحة الرئيسية أو الداشبورد --}}
             <div class="text-center mt-5">
                 <a href="{{ url('/') }}" class="btn btn-outline-secondary">
                      <i class="fas fa-home me-1"></i> Back to Home
                 </a>
             </div>

        </div>
    </div>
</div>
@endsection

{{-- تأكد من تضمين Font Awesome إذا أردت استخدام الأيقونات --}}
@push('styles')
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" ... /> --}}
@endpush