<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource (عرض قائمة المواعيد).
     */
    public function index()
    {
        // جلب جميع المواعيد مع تحميل مسبق للعلاقات (الطبيب والمريض) لتحسين الأداء
        // وترتيبها تنازلياً حسب وقت البداية، مع ترقيم الصفحات
        $appointments = Appointment::with(['doctor.user', 'patient'])->orderBy('start_time', 'desc')->paginate(10);
        return view('appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new resource (عرض نموذج إضافة موعد جديد).
     */
    public function create()
    {
        // جلب جميع الأطباء مع بيانات المستخدم الخاصة بهم
        $doctors = Doctor::with('user')->get();
        // جلب جميع المستخدمين الذين لديهم دور 'patient'
        $patients = User::where('role', 'patient')->get();

        return view('appointments.create', compact('doctors', 'patients'));
    }

    /**
     * Store a newly created resource in storage (حفظ موعد جديد).
     */
    public function store(Request $request)
    {
        // قواعد التحقق (Validation) من البيانات المدخلة
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'patient_id' => 'required|exists:users,id',
            'start_time' => 'required|date|after_or_equal:now', // وقت البداية يجب أن يكون في المستقبل أو الآن
            'end_time' => 'required|date|after:start_time',     // وقت النهاية يجب أن يكون بعد وقت البداية
            'status' => 'required|string|in:scheduled,completed,canceled', // الحالات المسموحة
            'cancellation_reason' => 'nullable|string|max:1000', // اختياري
        ]);

        // إنشاء موعد جديد
        Appointment::create($request->all());

        return redirect()->route('appointments.index')->with('success', 'تم إضافة الموعد بنجاح.');
    }

    /**
     * Display the specified resource (عرض تفاصيل موعد معين).
     */
    public function show(Appointment $appointment)
    {
        $appointment->load(['doctor.user', 'patient']);
        return view('appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified resource (عرض نموذج تعديل موعد).
     */
    public function edit(Appointment $appointment)
    {
        $doctors = Doctor::with('user')->get();
        $patients = User::where('role', 'patient')->get();
        $appointment->load(['doctor.user', 'patient']);

        return view('appointments.edit', compact('appointment', 'doctors', 'patients'));
    }

    /**
     * Update the specified resource in storage (تحديث موعد).
     */
    public function update(Request $request, Appointment $appointment)
    {
        // قواعد التحقق (Validation) من البيانات المدخلة للتعديل
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'patient_id' => 'required|exists:users,id',
            'start_time' => 'required|date|after_or_equal:now',
            'end_time' => 'required|date|after:start_time',
            'status' => 'required|string|in:scheduled,completed,canceled',
            'cancellation_reason' => 'nullable|string|max:1000',
        ]);

        // تحديث بيانات الموعد
        $appointment->update($request->all());

        return redirect()->route('appointments.index')->with('success', 'تم تحديث الموعد بنجاح.');
    }

    /**
     * Remove the specified resource from storage (حذف موعد).
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()->route('appointments.index')->with('success', 'تم حذف الموعد بنجاح.');
    }
}