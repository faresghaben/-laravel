@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center">إضافة مستخدم جديد</h1>

    <div class="card p-4 mx-auto" style="max-width: 500px;">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">الاسم:</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
                @error('name') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">البريد الإلكتروني:</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
                @error('email') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">كلمة المرور:</label>
                <input type="password" id="password" name="password" class="form-control" required>
                @error('password') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">تأكيد كلمة المرور:</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
            </div>

            
            <div class="mb-3">
                <label for="role" class="form-label">الدور:</label>
                <select id="role" name="role" class="form-select">
                    <option value="patient">مريض</option>
                    <option value="doctor">طبيب</option>
                    <option value="admin">مسؤول</option>
                </select>
                @error('role') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
            

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-success me-md-2">إضافة المستخدم</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">العودة إلى القائمة</a>
            </div>
        </form>
    </div>
</div>
@endsection