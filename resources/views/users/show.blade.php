@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center">تفاصيل المستخدم</h1>

    <div class="card p-4 mx-auto" style="max-width: 600px;">
        <div class="card-body">
            <p class="card-text"><strong>الرقم التعريفي:</strong> {{ $user->id }}</p>
            <p class="card-text"><strong>الاسم:</strong> {{ $user->name }}</p>
            <p class="card-text"><strong>البريد الإلكتروني:</strong> {{ $user->email }}</p>
            <p class="card-text"><strong>تاريخ الإنشاء:</strong> {{ $user->created_at->format('Y-m-d H:i') }}</p>
            <p class="card-text"><strong>آخر تحديث:</strong> {{ $user->updated_at->format('Y-m-d H:i') }}</p>
        </div>
    </div>

    <div class="text-center mt-4">
        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning me-2">تعديل بيانات المستخدم</a>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">العودة إلى قائمة المستخدمين</a>
    </div>
</div>
@endsection