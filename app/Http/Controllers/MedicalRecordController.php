<?php
namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\Patient; // استيراد مودل Patient
use App\Models\Doctor;  // استيراد مودل Doctor
use Illuminate\Http\Request;
use Illuminate\Support\Str; // لاستخدام Str::limit في الـ View

class MedicalRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $medicalRecords = MedicalRecord::with(['patient', 'doctor'])->get(); // جلب السجلات مع تحميل العلاقات
        return view('medical_records.index', compact('medicalRecords'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $patients =Patient::all(); // جلب جميع المرضى
        $doctors = Doctor::all();   // جلب جميع الأطباء
        return view('medical_records.create', compact('patients', 'doctors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'diagnosis' => 'required|string',
            'treatment' => 'nullable|string', // nullable لأننا جعلناه يقبل الفراغ
            'record_date' => 'required|date',
        ]);

        MedicalRecord::create($request->all());

        return redirect()->route('medical-records.index')
                        ->with('success', 'تم إضافة السجل الطبي بنجاح.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MedicalRecord $medicalRecord)
    {
        return view('medical_records.show', compact('medicalRecord'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MedicalRecord $medicalRecord)
    {
        $patients = Patient::all();
        $doctors = Doctor::all();
        return view('medical_records.edit', compact('medicalRecord', 'patients', 'doctors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MedicalRecord $medicalRecord)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'diagnosis' => 'required|string',
            'treatment' => 'nullable|string',
            'record_date' => 'required|date',
        ]);

        $medicalRecord->update($request->all());

        return redirect()->route('medical-records.index')
                        ->with('success', 'تم تحديث السجل الطبي بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MedicalRecord $medicalRecord)
    {
        $medicalRecord->delete();

        return redirect()->route('medical-records.index')
                        ->with('success', 'تم حذف السجل الطبي بنجاح.');
    }
}