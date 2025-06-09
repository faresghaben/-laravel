@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center">تفاصيل المريض: {{ $patient->name }}</h1>

    <div class="card p-4 mx-auto" style="max-width: 600px;">
        <div class="mb-3">
            <strong>اسم المريض:</strong> {{ $patient->name }}
        </div>
        <div class="mb-3">
            <strong>تاريخ الميلاد:</strong> {{ $patient->date_of_birth ?? 'غير متوفر' }}
        </div>
        <div class="mb-3">
            <strong>الجنس:</strong> {{ $patient->gender ?? 'غير متوفر' }}
        </div>
        <div class="mb-3">
            <strong>فصيلة الدم:</strong> {{ $patient->blood_type ?? 'غير متوفر' }}
        </div>
        <div class="mb-3">
            <strong>الحساسيات:</strong> {{ $patient->allergies ?? 'لا يوجد' }}
        </div>
        <hr>
        <h5 class="mb-3">بيانات المستخدم المرتبط:</h5>
        <div class="mb-3">
            <strong>اسم المستخدم:</strong> {{ $patient->user->name ?? 'غير متوفر' }}
        </div>
        <div class="mb-3">
            <strong>البريد الإلكتروني:</strong> {{ $patient->user->email ?? 'غير متوفر' }}
        </div>
        <div class="mb-3">
            <strong>الدور:</strong> {{ $patient->user->role ?? 'غير متوفر' }}
        </div>
        <div class="mb-3">
            <strong>تاريخ الإنشاء:</strong> {{ $patient->created_at->format('Y-m-d H:i') }}
        </div>
        <div class="mb-3">
            <strong>آخر تحديث:</strong> {{ $patient->updated_at->format('Y-m-d H:i') }}
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
            <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-warning me-md-2">تعديل</a>
            <a href="{{ route('patients.index') }}" class="btn btn-secondary">العودة إلى القائمة</a>
        </div>
    </div>
</div>
@endsection