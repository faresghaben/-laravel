@extends('layouts.app')

@section('content')
<div class="container">
    <h1>تفاصيل السجل الطبي</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">السجل رقم: {{ $medicalRecord->id }}</h5>
            <p class="card-text"><strong>المريض:</strong> {{ $medicalRecord->patient->name ?? 'غير معروف' }}</p>
            <p class="card-text"><strong>الطبيب:</strong> {{ $medicalRecord->doctor->name ?? 'غير معروف' }}</p>
            <p class="card-text"><strong>التشخيص:</strong> {{ $medicalRecord->diagnosis }}</p>
            <p class="card-text"><strong>العلاج:</strong> {{ $medicalRecord->treatment }}</p>
            <p class="card-text"><strong>تاريخ السجل:</strong> {{ $medicalRecord->record_date->format('Y-m-d') }}</p>
            <p class="card-text"><strong>تاريخ الإنشاء:</strong> {{ $medicalRecord->created_at->format('Y-m-d H:i:s') }}</p>
            <p class="card-text"><strong>آخر تحديث:</strong> {{ $medicalRecord->updated_at->format('Y-m-d H:i:s') }}</p>
            <a href="{{ route('medical-records.edit', $medicalRecord->id) }}" class="btn btn-warning">تعديل</a>
            <a href="{{ route('medical-records.index') }}" class="btn btn-secondary">العودة للقائمة</a>
        </div>
    </div>
</div>
@endsection