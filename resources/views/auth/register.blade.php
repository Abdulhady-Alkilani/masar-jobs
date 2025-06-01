@extends('layouts.guest')
@section('title', 'Create New Account')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8"> {{-- يمكنك تعديل عرض العمود حسب رغبتك --}}
            <div class="card shadow-sm">
                <div class="card-header text-center bg-primary text-white">
                    <h3 class="fw-light my-3">{{ __('Register as a Graduate') }}</h3> {{-- تعديل العنوان قليلاً --}}
                </div>

                <div class="card-body p-4 p-md-5">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row gx-3 mb-3">
                            <div class="col-md-6">
                                <div class="form-floating mb-3 mb-md-0">
                                    <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" placeholder="First Name" required autocomplete="given-name" autofocus>
                                    <label for="first_name">{{ __('First Name') }} <span class="text-danger">*</span></label>
                                    @error('first_name')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" placeholder="Last Name" required autocomplete="family-name">
                                    <label for="last_name">{{ __('Last Name') }} <span class="text-danger">*</span></label>
                                    @error('last_name')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" placeholder="Username" required autocomplete="username">
                            <label for="username">{{ __('Username') }} <span class="text-danger">*</span></label>
                            @error('username')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="form-floating mb-3">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="name@example.com" required autocomplete="email">
                            <label for="email">{{ __('Email Address') }} <span class="text-danger">*</span></label>
                            @error('email')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="form-floating mb-3">
                            <input id="phone" type="tel" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" placeholder="Phone (Optional)" autocomplete="tel">
                            <label for="phone">{{ __('Phone (Optional)') }}</label>
                            @error('phone')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        {{-- !!! تم حذف قسم اختيار نوع المستخدم من هنا !!! --}}

                        <div class="row gx-3 mb-3">
                            <div class="col-md-6">
                                <div class="form-floating mb-3 mb-md-0">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" required autocomplete="new-password">
                                    <label for="password">{{ __('Password') }} <span class="text-danger">*</span></label>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" required autocomplete="new-password">
                                    <label for="password-confirm">{{ __('Confirm Password') }} <span class="text-danger">*</span></label>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid mt-4 mb-0">
                            <button type="submit" class="btn btn-primary btn-block btn-lg">
                                {{ __('Register') }}
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center py-3 border-0 rounded-bottom">
                    <div class="small"><a href="{{ route('login') }}">Already have an account? Go to login</a></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection