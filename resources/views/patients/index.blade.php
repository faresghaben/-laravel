@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center">قائمة المرضى</h1>

    {{-- رسائل النجاح أو الخطأ --}}
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

    <div class="mb-3 d-flex justify-content-end">
        <a href="{{ route('patients.create') }}" class="btn btn-primary">إضافة مريض جديد</a>
    </div>

    @if ($patients->isEmpty())
        <div class="alert alert-info text-center">
            لا يوجد مرضى مسجلون حاليًا.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>اسم المريض</th>
                        <th>تاريخ الميلاد</th>
                        <th>الجنس</th>
                        <th>فصيلة الدم</th>
                        <th>البريد الإلكتروني للمستخدم</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($patients as $patient)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $patient->name }}</td> {{-- اسم المريض من جدول patients --}}
                            <td>{{ $patient->date_of_birth ?? 'N/A' }}</td>
                            <td>{{ $patient->gender ?? 'N/A' }}</td>
                            <td>{{ $patient->blood_type ?? 'N/A' }}</td>
                            <td>{{ $patient->user->email ?? 'N/A' }}</td> {{-- البريد الإلكتروني للمستخدم المرتبط --}}
                            <td>
                                <a href="{{ route('patients.show', $patient->id) }}" class="btn btn-info btn-sm">عرض</a>
                                <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-warning btn-sm">تعديل</a>
                                <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد من حذف هذا المريض؟ سيتم حذف المستخدم المرتبط به أيضًا.')">حذف</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection