@extends('layouts.app') {{-- افترض أن لديك layout أساسي --}}

@section('content')
<div class="container">
    <h1>السجلات الطبية</h1>
    <a href="{{ route('medical-records.create') }}" class="btn btn-primary mb-3">إضافة سجل طبي جديد</a>

    @if ($medicalRecords->isEmpty())
        <p>لا توجد سجلات طبية بعد.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>المريض</th>
                    <th>الطبيب</th>
                    <th>التشخيص</th>
                    <th>العلاج</th>
                    <th>تاريخ السجل</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($medicalRecords as $record)
                <tr>
                    <td>{{ $record->id }}</td>
                    <td>{{ $record->patient->name ?? 'غير معروف' }}</td> {{-- عرض اسم المريض --}}
                    <td>{{ $record->doctor->name ?? 'غير معروف' }}</td>   {{-- عرض اسم الطبيب --}}
                    <td>{{ Str::limit($record->diagnosis, 50) }}</td>
                    <td>{{ Str::limit($record->treatment, 50) }}</td>
                    <td>{{ $record->record_date->format('Y-m-d') }}</td> {{-- تنسيق التاريخ --}}
                    <td>
                        <a href="{{ route('medical-records.show', $record->id) }}" class="btn btn-info btn-sm">عرض</a>
                        <a href="{{ route('medical-records.edit', $record->id) }}" class="btn btn-warning btn-sm">تعديل</a>
                        <form action="{{ route('medical-records.destroy', $record->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد؟')">حذف</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection