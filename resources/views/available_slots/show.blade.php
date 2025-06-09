@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>تفاصيل الموعد المتاح</h1>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">الطبيب: {{ $availableSlot->doctor->name }}</h5>
                <p class="card-text"><strong>اليوم:</strong> {{ $availableSlot->day_of_week }}</p>
                <p class="card-text"><strong>وقت البدء:</strong> {{ \Carbon\Carbon::parse($availableSlot->start_time)->format('h:i A') }}</p>
                <p class="card-text"><strong>وقت الانتهاء:</strong> {{ \Carbon\Carbon::parse($availableSlot->end_time)->format('h:i A') }}</p>
                <p class="card-text"><strong>متاح:</strong> {{ $availableSlot->is_available ? 'نعم' : 'لا' }}</p>
                <p class="card-text"><strong>تاريخ الإنشاء:</strong> {{ $availableSlot->created_at->format('Y-m-d H:i') }}</p>
                <p class="card-text"><strong>آخر تحديث:</strong> {{ $availableSlot->updated_at->format('Y-m-d H:i') }}</p>
                <a href="{{ route('available-slots.edit', $availableSlot->id) }}" class="btn btn-warning">تعديل</a>
                <a href="{{ route('available-slots.index') }}" class="btn btn-secondary">العودة إلى القائمة</a>
            </div>
        </div>
    </div>
@endsection