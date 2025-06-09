@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>المواعيد المتاحة</h1>
        <a href="{{ route('available-slots.create') }}" class="btn btn-primary mb-3">إضافة موعد متاح جديد</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>الطبيب</th>
                    <th>اليوم</th>
                    <th>وقت البدء</th>
                    <th>وقت الانتهاء</th>
                    <th>متاح؟</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($availableSlots as $slot)
                    <tr>
                        <td>{{ $slot->doctor->name }}</td>
                        <td>{{ $slot->day_of_week }}</td>
                        <td>{{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }}</td>
                        <td>{{ \Carbon\Carbon::parse($slot->end_time)->format('h:i A') }}</td>
                        <td>{{ $slot->is_available ? 'نعم' : 'لا' }}</td>
                        <td>
                            <a href="{{ route('available-slots.show', $slot->id) }}" class="btn btn-info btn-sm">عرض</a>
                            <a href="{{ route('available-slots.edit', $slot->id) }}" class="btn btn-warning btn-sm">تعديل</a>
                            <form action="{{ route('available-slots.destroy', $slot->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد؟')">حذف</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection