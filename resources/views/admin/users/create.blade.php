@extends('layouts.admin')
@section('title', 'Create New User')

@section('header')
    {{-- استخدام H2 بسيط للعنوان داخل الهيدر الذي يوفره الـ layout --}}
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0 text-primary">
            <i class="fas fa-user-plus me-2"></i> {{ __('Create New User') }}
        </h2>
         <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary">
             <i class="fas fa-arrow-left me-1"></i> Back to List
        </a>
    </div>
@endsection

@section('content')
    {{-- لا نحتاج container هنا --}}
    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf

        <div class="row g-4"> {{-- استخدام صف مع مسافات (gutters) --}}

            {{-- العمود الأول: المعلومات الأساسية --}}
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <i class="fas fa-info-circle me-2"></i> Basic Information
                    </div>
                    <div class="card-body">
                        @include('partials._alerts') {{-- عرض التنبيهات هنا --}}

                        <div class="row gx-3 gy-3"> {{-- صف داخلي لحقول النموذج --}}
                            <div class="col-md-6">
                                <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name') }}" required autofocus>
                                @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" required>
                                @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}">
                                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- العمود الثاني: الصلاحيات وكلمة المرور --}}
            <div class="col-lg-4">
                 {{-- قسم النوع والحالة --}}
                 <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <i class="fas fa-user-tag me-2"></i> Role & Status
                    </div>
                    <div class="card-body">
                         <div class="mb-3">
                            <label for="type" class="form-label">User Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="" disabled {{ old('type') ? '' : 'selected' }}>-- Select Type --</option>
                                <option value="خريج" {{ old('type') == 'خريج' ? 'selected' : '' }}>Graduate</option>
                                <option value="خبير استشاري" {{ old('type') == 'خبير استشاري' ? 'selected' : '' }}>Consultant</option>
                                <option value="مدير شركة" {{ old('type') == 'مدير شركة' ? 'selected' : '' }}>Company Manager</option>
                                <option value="Admin" {{ old('type') == 'Admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                             <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                             <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="" disabled {{ old('status') ? '' : 'selected' }}>-- Select Status --</option>
                                <option value="مفعل" {{ old('status', 'مفعل') == 'مفعل' ? 'selected' : '' }}>Active</option> {{-- افتراضي: مفعل --}}
                                <option value="معلق" {{ old('status') == 'معلق' ? 'selected' : '' }}>Pending / Inactive</option>
                                <option value="محذوف" {{ old('status') == 'محذوف' ? 'selected' : '' }}>Deleted / Banned</option>
                             </select>
                              @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                 {{-- قسم كلمة المرور --}}
                <div class="card shadow-sm">
                    <div class="card-header">
                       <i class="fas fa-key me-2"></i> Password
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required autocomplete="new-password">
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
                             {{-- لا نحتاج لعرض خطأ التأكيد هنا عادةً، خطأ كلمة المرور يكفي --}}
                        </div>
                    </div>
                </div>
            </div>

            {{-- زر الحفظ في الأسفل يمتد على عرض الصفحة --}}
            <div class="col-12">
                 <div class="text-end mt-3">
                     <a href="{{ route('admin.users.index') }}" class="btn btn-secondary me-2">
                         <i class="fas fa-times me-1"></i> Cancel
                     </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-plus me-1"></i> Create User
                    </button>
                 </div>
            </div>

        </div> {{-- نهاية row g-4 --}}
    </form>
@endsection