@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center">تعديل بيانات المستخدم</h1>

    <div class="card p-4 mx-auto" style="max-width: 500px;">
        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">الاسم:</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                @error('name') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">البريد الإلكتروني:</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                @error('email') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">كلمة المرور الجديدة (اتركها فارغة للإبقاء على الحالية):</label>
                <input type="password" id="password" name="password" class="form-control">
                @error('password') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">تأكيد كلمة المرور الجديدة:</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
            </div>

            
            <div class="mb-3">
                <label for="role" class="form-label">الدور:</label>
                <select id="role" name="role" class="form-select">
                    <option value="patient" {{ old('role', $user->role) == 'patient' ? 'selected' : '' }}>مريض</option>
                    <option value="doctor" {{ old('role', $user->role) == 'doctor' ? 'selected' : '' }}>طبيب</option>
                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>مسؤول</option>
                </select>
                @error('role') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
            

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-primary me-md-2">تحديث بيانات المستخدم</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">العودة إلى القائمة</a>
            </div>
        </form>
    </div>
</div>
@endsection