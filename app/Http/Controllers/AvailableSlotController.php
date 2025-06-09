<?php

namespace App\Http\Controllers;

use App\Models\AvailableSlot;
use App\Models\Doctor; // يجب استيراد موديل Doctor لاستخدامه في جلب الأطباء
use Illuminate\Http\Request;
use Carbon\Carbon; // لاستخدام Carbon لتنسيق الوقت إذا لزم الأمر

class AvailableSlotController extends Controller
{
    /**
     * عرض قائمة بجميع المواعيد المتاحة.
     */
    public function index()
    {
        // جلب جميع المواعيد المتاحة وتحميل علاقة الطبيب معها
        $availableSlots = AvailableSlot::with('doctor')->get();
        return view('available_slots.index', compact('availableSlots'));
    }

    /**
     * عرض نموذج لإنشاء موعد متاح جديد.
     */
    public function create()
    {
        // جلب جميع الأطباء لعرضهم في القائمة المنسدلة في النموذج
        $doctors = Doctor::all();
        return view('available_slots.create', compact('doctors'));
    }

    /**
     * تخزين موعد متاح جديد في قاعدة البيانات.
     */
    public function store(Request $request)
    {
        // التحقق من صحة البيانات المرسلة من النموذج
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id', // يجب أن يكون موجوداً في جدول الأطباء
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i', // تنسيق الوقت 24 ساعة (مثلاً 13:00)
            'end_time' => 'required|date_format:H:i|after:start_time', // يجب أن يكون بعد وقت البدء
            'is_available' => 'boolean', // يجب أن يكون قيمة منطقية (صحيح/خطأ)
        ]);

        // إنشاء سجل جديد في جدول available_slots
        AvailableSlot::create([
            'doctor_id'      => $request->doctor_id,
            'day_of_week'    => $request->day_of_week,
            'start_time'     => $request->start_time,
            'end_time'       => $request->end_time,
            'is_available'   => $request->has('is_available'), // التحقق إذا كان checkbox مُعلَّمًا
        ]);

        // إعادة التوجيه إلى قائمة المواعيد المتاحة مع رسالة نجاح
        return redirect()->route('available-slots.index')->with('success', 'تم إضافة الموعد المتاح بنجاح.');
    }

    /**
     * عرض تفاصيل موعد متاح محدد.
     */
    public function show(AvailableSlot $availableSlot)
    {
        // يتم حقن (inject) موديل AvailableSlot تلقائياً بواسطة Laravel
        return view('available_slots.show', compact('availableSlot'));
    }

    /**
     * عرض نموذج لتعديل موعد متاح موجود.
     */
    public function edit(AvailableSlot $availableSlot)
    {
        // يتم حقن (inject) موديل AvailableSlot تلقائياً بواسطة Laravel
        // جلب جميع الأطباء لعرضهم في القائمة المنسدلة في نموذج التعديل
        $doctors = Doctor::all();
        return view('available_slots.edit', compact('availableSlot', 'doctors'));
    }

    /**
     * تحديث موعد متاح موجود في قاعدة البيانات.
     */
    public function update(Request $request, AvailableSlot $availableSlot)
    {
        // التحقق من صحة البيانات المرسلة من النموذج
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'is_available' => 'boolean',
        ]);

        // تحديث سجل الموعد المتاح
        $availableSlot->update([
            'doctor_id'      => $request->doctor_id,
            'day_of_week'    => $request->day_of_week,
            'start_time'     => $request->start_time,
            'end_time'       => $request->end_time,
            'is_available'   => $request->has('is_available'),
        ]);

        // إعادة التوجيه إلى قائمة المواعيد المتاحة مع رسالة نجاح
        return redirect()->route('available-slots.index')->with('success', 'تم تحديث الموعد المتاح بنجاح.');
    }

    /**
     * حذف موعد متاح من قاعدة البيانات.
     */
    public function destroy(AvailableSlot $availableSlot)
    {
        // حذف سجل الموعد المتاح
        $availableSlot->delete();
        // إعادة التوجيه إلى قائمة المواعيد المتاحة مع رسالة نجاح
        return redirect()->route('available-slots.index')->with('success', 'تم حذف الموعد المتاح بنجاح.');
    }
}