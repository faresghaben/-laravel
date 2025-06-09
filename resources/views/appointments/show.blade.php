@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center">تفاصيل الموعد</h1>

    <div class="card p-4 mx-auto" style="max-width: 600px;">
        <div class="card-body">
            <p class="card-text"><strong>الطبيب:</strong> {{ $appointment->doctor->user->name ?? 'غير معروف' }} ({{ $appointment->doctor->specialization ?? 'غير محدد' }})</p>
            <p class="card-text"><strong>المريض:</strong> {{ $appointment->patient->name ?? 'غير معروف' }}</p>
            <p class="card-text"><strong>وقت البداية:</strong> {{ $appointment->start_time->format('Y-m-d H:i') }}</p>
            <p class="card-text"><strong>وقت النهاية:</strong> {{ $appointment->end_time->format('Y-m-d H:i') }}</p>
            <p class="card-text"><strong>الحالة:</strong> {{ $appointment->status }}</p>
            <p class="card-text"><strong>سبب الإلغاء:</strong> {{ $appointment->cancellation_reason ?? 'لا يوجد' }}</p>
            <p class="card-text"><strong>تاريخ الإنشاء:</strong> {{ $appointment->created_at->format('Y-m-d H:i') }}</p>
            <p class="card-text"><strong>آخر تحديث:</strong> {{ $appointment->updated_at->format('Y-m-d H:i') }}</p>
        </div>
    </div>

    <div class="text-center mt-4">
        <a href="{{ route('appointments.edit', $appointment->id) }}" class="btn btn-warning me-2">تعديل</a>
        <form action="{{ route('appointments.destroy', $appointment->id) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger me-2" onclick="return confirm('هل أنت متأكد من حذف هذا الموعد؟')">حذف</button>
        </form>
        <a href="{{ route('appointments.index') }}" class="btn btn-secondary">العودة إلى قائمة المواعيد</a>
    </div>
</div>
@endsection