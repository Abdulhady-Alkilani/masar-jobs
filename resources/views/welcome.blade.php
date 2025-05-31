@extends('layouts.guest') {{-- أو layouts.app إذا كان للكل --}}
@section('title', 'Welcome to ' . config('app.name', 'Masar App'))

@section('content')
    {{-- Hero Section --}}
    <div class="container-fluid bg-dark text-light text-center py-5 mb-5 shadow-lg position-relative">
        {{-- مثال لخلفية متدرجة أو صورة يمكن إضافتها هنا --}}
        {{-- <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('path/to/your/hero-image.jpg') no-repeat center center; background-size: cover; z-index: 0;"></div> --}}
        <div class="container position-relative" style="z-index: 1;"> {{-- لجعل المحتوى فوق الخلفية --}}
            {{-- يمكنك إضافة شعار هنا إذا أردت --}}
            <div class="mb-4">
                {{-- <img src="{{ asset('images/logo-white.png') }}" alt="{{ config('app.name') }}" class="mx-auto" style="max-height: 70px;"> --}}
                <i class="fas fa-road fa-3x mb-3 text-primary"></i> {{-- مثال لأيقونة كشعار --}}
            </div>
            <h1 class="display-3 fw-bolder">{{ config('app.name', 'Masar App') }}</h1>
            <p class="lead my-4 col-md-8 mx-auto">
                بوابتك نحو مستقبل مهني واعد! اكتشف أحدث فرص العمل والتدريب والدورات لتطوير مهاراتك والانطلاق في مسيرتك.
            </p>
            <p>
                @guest
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg me-2 shadow">
                        <i class="fas fa-user-plus me-2"></i> إنشاء حساب جديد
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg shadow">
                        <i class="fas fa-sign-in-alt me-2"></i> تسجيل الدخول
                    </a>
                @else
                     <a href="{{ route('home') }}" class="btn btn-light btn-lg shadow">
                        <i class="fas fa-tachometer-alt me-2"></i> اذهب إلى لوحة التحكم
                    </a>
                @endguest
            </p>
        </div>
    </div>

@endsection

@push('styles')
<style>
    .lift {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    .hero-section-overlay { /* مثال بسيط لخلفية متدرجة مع صورة */
        background: linear-gradient(rgba(25, 30, 35, 0.7), rgba(25, 30, 35, 0.7)), url('https://via.placeholder.com/1920x600.png?text=Career+Opportunities') no-repeat center center;
        background-size: cover;
    }
</style>
@endpush