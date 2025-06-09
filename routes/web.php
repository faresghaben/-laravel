<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\AvailableSlotController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PatientController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// المسارات العامة التي يمكن الوصول إليها من قبل الجميع
Route::get('/', function () {
    return view('welcome');
});

// لوحة التحكم (dashboard) - تتطلب المصادقة والتحقق من البريد
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// مسارات ملف تعريف المستخدم (البروفايل) - تتطلب المصادقة
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
// في routes/web.php (مسار مؤقت للتحقق)
use Illuminate\Support\Facades\Auth; // تأكد من وجود هذا السطر في أعلى الملف

Route::get('/check-my-role', function() {
    if (Auth::check()) {
        return "الدور الحالي للمستخدم الذي سجلت دخوله هو: " . Auth::user()->role;
    }
    return "أنت غير مسجل للدخول.";
})->middleware('auth');
// استيراد مسارات المصادقة (التسجيل، تسجيل الدخول، إلخ)
require __DIR__.'/auth.php';


// =====================================================================
// مسارات محمية بواسطة 'can' Middleware (يجب تعريف كل مورد مرة واحدة فقط هنا)
// =====================================================================


// =====================================================================
// مسارات للمدراء فقط
Route::middleware(['auth', 'can:is-admin'])->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('doctors', DoctorController::class);
    Route::resource('patients', PatientController::class);
    Route::resource('medical-records', MedicalRecordController::class);
});

// مسارات للأطباء فقط
Route::middleware(['auth', 'can:is-doctor'])->group(function () {
    Route::get('/doctor/appointments', [AppointmentController::class, 'doctorIndex'])->name('doctor.appointments');
    Route::resource('available-slots', AvailableSlotController::class);
});

// مسارات للمرضى فقط
Route::middleware(['auth', 'can:is-patient'])->group(function () {
    Route::get('/patient/profile', [ProfileController::class, 'patientProfile'])->name('patient.profile');
    Route::resource('appointments', AppointmentController::class)->except(['edit', 'update']);
});