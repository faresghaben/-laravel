@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center">تعديل بيانات المريض</h1>

    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="card p-4 mx-auto" style="max-width: 600px;">
        <form action="{{ route('patients.update', $patient->id) }}" method="POST">
            @csrf
            @method('PUT') {{-- ضروري لـ Laravel ليتعرف على طلب التحديث --}}

            {{-- 1. حقول بيانات المستخدم المرتبط (User) --}}
            <h5 class="mb-3">بيانات تسجيل الدخول (المستخدم المرتبط)</h5>
            <div class="mb-3">
                <label for="user_name" class="form-label">اسم المستخدم:</label>
                <input type="text" id="user_name" name="user_name" class="form-control"
                       value="{{ old('user_name', $user->name) }}" required>
                @error('user_name') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="user_email" class="form-label">البريد الإلكتروني للمستخدم:</label>
                <input type="email" id="user_email" name="user_email" class="form-control"
                       value="{{ old('user_email', $user->email) }}" required>
                @error('user_email') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="user_password" class="form-label">كلمة المرور (اتركها فارغة لعدم التغيير):</label>
                <input type="password" id="user_password" name="user_password" class="form-control">
                @error('user_password') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="user_password_confirmation" class="form-label">تأكيد كلمة المرور:</label>
                <input type="password" id="user_password_confirmation" name="user_password_confirmation" class="form-control">
            </div>

            <hr class="my-4">

            {{-- 2. حقول بيانات المريض (Patient) --}}
            <h5 class="mb-3">بيانات المريض</h5>
            <div class="mb-3">
                <label for="name" class="form-label">اسم المريض:</label>
                <input type="text" id="name" name="name" class="form-control"
                       value="{{ old('name', $patient->name) }}" required>
                @error('name') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="date_of_birth" class="form-label">تاريخ الميلاد:</label>
                <input type="date" id="date_of_birth" name="date_of_birth" class="form-control"
                       value="{{ old('date_of_birth', $patient->date_of_birth) }}">
                @error('date_of_birth') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="gender" class="form-label">الجنس:</label>
                <select id="gender" name="gender" class="form-select">
                    <option value="">اختر الجنس</option>
                    <option value="male" {{ old('gender', $patient->gender) == 'male' ? 'selected' : '' }}>ذكر</option>
                    <option value="female" {{ old('gender', $patient->gender) == 'female' ? 'selected' : '' }}>أنثى</option>
                    <option value="other" {{ old('gender', $patient->gender) == 'other' ? 'selected' : '' }}>أخرى</option>
                </select>
                @error('gender') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="blood_type" class="form-label">فصيلة الدم:</label>
                <select id="blood_type" name="blood_type" class="form-select">
                    <option value="">اختر فصيلة الدم</option>
                    <option value="A+" {{ old('blood_type', $patient->blood_type) == 'A+' ? 'selected' : '' }}>A+</option>
                    <option value="A-" {{ old('blood_type', $patient->blood_type) == 'A-' ? 'selected' : '' }}>A-</option>
                    <option value="B+" {{ old('blood_type', $patient->blood_type) == 'B+' ? 'selected' : '' }}>B+</option>
                    <option value="B-" {{ old('blood_type', $patient->blood_type) == 'B-' ? 'selected' : '' }}>B-</option>
                    <option value="AB+" {{ old('blood_type', $patient->blood_type) == 'AB+' ? 'selected' : '' }}>AB+</option>
                    <option value="AB-" {{ old('blood_type', $patient->blood_type) == 'AB-' ? 'selected' : '' }}>AB-</option>
                    <option value="O+" {{ old('blood_type', $patient->blood_type) == 'O+' ? 'selected' : '' }}>O+</option>
                    <option value="O-" {{ old('blood_type', $patient->blood_type) == 'O-' ? 'selected' : '' }}>O-</option>
                </select>
                @error('blood_type') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="allergies" class="form-label">الحساسيات (اختياري):</label>
                <textarea id="allergies" name="allergies" class="form-control" rows="3">{{ old('allergies', $patient->allergies) }}</textarea>
                @error('allergies') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                <button type="submit" class="btn btn-primary me-md-2">تحديث بيانات المريض</button>
                <a href="{{ route('patients.index') }}" class="btn btn-secondary">العودة إلى القائمة</a>
            </div>
        </form>
    </div>
</div>
@endsection