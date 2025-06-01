@extends('layouts.consultant')
@section('title', 'Edit My Profile')

@section('header')
    <h2 class="h4 mb-0 text-primary"><i class="fas fa-user-edit me-2"></i> Edit Your Profile & Skills</h2>
@endsection

@section('content')
    {{-- !!! يجب أن يكون النموذج هو العنصر الخارجي !!! --}}
    <form action="{{ route('consultant.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-4">
            {{-- العمود الأول: المعلومات الأساسية والصورة --}}
            <div class="col-lg-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header"><i class="fas fa-user-circle me-2"></i> Basic Info & Photo</div>
                    <div class="card-body">
                         {{-- First Name --}}
                        <div class="mb-3">
                            <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name ?? '') }}" required>
                            @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                         {{-- Last Name --}}
                        <div class="mb-3">
                            <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name ?? '') }}" required>
                             @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                         {{-- Phone --}}
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone ?? '') }}">
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        {{-- Photo Upload --}}
                        <div class="mb-3">
                            <label for="photo" class="form-label">Profile Photo</label>
                            <input type="file" class="form-control @error('photo') is-invalid @enderror" id="photo" name="photo" accept="image/*">
                             @error('photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                             @if($user->photo)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/'.$user->photo) }}" class="img-thumbnail" style="max-height: 100px;" alt="Current Photo">
                                    <small class="text-muted d-block">Current photo. Upload new to replace.</small>
                                </div>
                             @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- العمود الثاني: تفاصيل البروفايل والمهارات --}}
            <div class="col-lg-8">
                {{-- تفاصيل البروفايل --}}
                <div class="card shadow-sm mb-4">
                     <div class="card-header"><i class="fas fa-id-card me-2"></i> Profile Details</div>
                     <div class="card-body">
                          {{-- University/Field --}}
                         <div class="mb-3">
                            <label for="University" class="form-label">Field / University</label>
                            <input type="text" class="form-control @error('University') is-invalid @enderror" id="University" name="University" value="{{ old('University', $user->profile->University ?? '') }}">
                             @error('University') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                         {{-- Experience/GPA --}}
                         <div class="mb-3">
                            <label for="GPA" class="form-label">Years of Experience / Degree</label>
                            <input type="text" class="form-control @error('GPA') is-invalid @enderror" id="GPA" name="GPA" value="{{ old('GPA', $user->profile->GPA ?? '') }}">
                            @error('GPA') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                         {{-- Personal Description --}}
                        <div class="mb-3">
                            <label for="Personal_Description" class="form-label">Personal Bio</label>
                            <textarea class="form-control @error('Personal Description') is-invalid @enderror" id="Personal_Description" name="Personal Description" rows="4">{{ old('Personal Description', $user->profile->{'Personal Description'} ?? '') }}</textarea>
                             @error('Personal Description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                         {{-- Technical Description --}}
                        <div class="mb-3">
                            <label for="Technical_Description" class="form-label">Technical Expertise</label>
                            <textarea class="form-control @error('Technical Description') is-invalid @enderror" id="Technical_Description" name="Technical Description" rows="4">{{ old('Technical Description', $user->profile->{'Technical Description'} ?? '') }}</textarea>
                            @error('Technical Description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                         {{-- Link --}}
                         <div class="mb-3">
                            <label for="Git_Hyper_Link" class="form-label">Website/Link (e.g., LinkedIn)</label>
                            <input type="url" class="form-control @error('Git Hyper Link') is-invalid @enderror" id="Git_Hyper_Link" name="Git Hyper Link" value="{{ old('Git Hyper Link', $user->profile->{'Git Hyper Link'} ?? '') }}" placeholder="https://...">
                            @error('Git Hyper Link') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                     </div>
                </div>

                 {{-- المهارات --}}
                <div class="card shadow-sm">
                     <div class="card-header"><i class="fas fa-star me-2"></i> Skills & Expertise</div>
                     <div class="card-body">
                         <p class="text-muted small mb-3">Select your skills and specify your proficiency level (Beginner, Intermediate, Advanced).</p>
                          @error('skills') <div class="alert alert-danger alert-sm py-1 px-2">{{ $message }}</div> @enderror
                          @error('skills.*') <div class="alert alert-danger alert-sm py-1 px-2">{{ $message }}</div> @enderror
                         <div class="row g-3">
                             @forelse($skills as $skill)
                                 <div class="col-md-6">
                                     <div class="input-group input-group-sm">
                                         <span class="input-group-text" style="min-width: 120px;">{{ $skill->Name }}</span>
                                         <select name="skills[{{ $skill->SkillID }}]" class="form-select form-select-sm">
                                             <option value="">- Not Set -</option>
                                             {{-- تم تعديل الوصول إلى userSkills --}}
                                             @php $currentLevel = $userSkills->firstWhere('SkillID', $skill->SkillID)?->pivot?->Stage ?? null; @endphp
                                             <option value="مبتدئ" {{ old('skills.'.$skill->SkillID, $currentLevel) == 'مبتدئ' ? 'selected' : '' }}>Beginner</option>
                                             <option value="متوسط" {{ old('skills.'.$skill->SkillID, $currentLevel) == 'متوسط' ? 'selected' : '' }}>Intermediate</option>
                                             <option value="متقدم" {{ old('skills.'.$skill->SkillID, $currentLevel) == 'متقدم' ? 'selected' : '' }}>Advanced</option>
                                         </select>
                                     </div>
                                 </div>
                             @empty
                                 <p class="text-warning col-12">No skills available in the system yet. Please contact admin.</p>
                             @endforelse
                         </div>
                    </div>
                </div>
            </div>

            {{-- !!! زر الحفظ والإلغاء الآن داخل وسم <form> !!! --}}
            <div class="col-12 text-end mt-4">
                 <a href="{{ route('consultant.profile.show') }}" class="btn btn-secondary me-2">Cancel</a>
                 <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-1"></i> Update Profile & Skills
                </button>
            </div>
        </div> {{-- نهاية row g-4 --}}
    </form> {{-- !!! إغلاق وسم <form> هنا !!! --}}
@endsection