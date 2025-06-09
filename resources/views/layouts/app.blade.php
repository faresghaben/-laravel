<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Figtree', sans-serif;
        }
        .container {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                {{-- <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'My Clinic App') }}
                </a> --}}
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('patients.index') }}">المرضى</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('doctors.index') }}">الأطباء</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('appointments.index') }}">المواعيد</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('medical-records.index') }}">السجلات الطبية</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('available-slots.index') }}">المواعيد المتاحة</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('users.index') }}">اليوزر </a>
                        </li>
                        {{-- أضف المزيد من الروابط هنا --}}
                    </ul>

                    <ul class="navbar-nav ms-auto">
                        </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content') {{-- هذا هو المكان الذي سيتم فيه عرض محتوى كل صفحة فرعية --}}
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>