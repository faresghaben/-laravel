@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center">إدارة المستخدمين</h1>

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

    <div class="mb-3 text-end">
        <a href="{{ route('users.create') }}" class="btn btn-success">إضافة مستخدم جديد</a>
    </div>

    @if($users->isEmpty())
        <p class="text-center">لا يوجد مستخدمون حاليًا.</p>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>الرقم التعريفي</th>
                        <th>الاسم</th>
                        <th>البريد الإلكتروني</th>
                        <th>تاريخ الإنشاء</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->created_at->format('Y-m-d H:i') }}</td>
                            <td class="d-flex flex-wrap gap-1">
                                <a href="{{ route('users.show', $user->id) }}" class="btn btn-info btn-sm">عرض</a>
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">تعديل</a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد أنك تريد حذف هذا المستخدم؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">حذف</button>
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