<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\User; // يجب استيراد موديل User للتعامل مع المستخدمين
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // لتشفير كلمة المرور في Laravel 8
use Illuminate\Validation\Rule; // لاستخدام Rule في التحقق من فرادة البريد الإلكتروني

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // استعراض جميع المرضى مع تحميل بيانات المستخدم المرتبط بهم
        $patients = Patient::with('user')->get();
        return view('patients.index', compact('patients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // عرض نموذج إضافة مريض جديد
        return view('patients.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 1. التحقق من صحة بيانات المستخدم (User) قبل إنشاء المريض
        $request->validate([
            'user_name' => ['required', 'string', 'max:255'],
            'user_email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'user_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // 2. التحقق من صحة بيانات المريض (Patient)
        $request->validate([
            'name' => ['required', 'string', 'max:255'], // حقل اسم المريض
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', Rule::in(['male', 'female', 'other'])],
            'blood_type' => ['nullable', 'string', Rule::in(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])],
            'allergies' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = null; // تهيئة متغير user
        try {
            // 3. إنشاء المستخدم (User) أولاً وربطه بدور "patient"
            $user = User::create([
                'name' => $request->user_name,
                'email' => $request->user_email,
                'password' => Hash::make($request->user_password), // تشفير كلمة المرور في Laravel 8
                'role' => 'patient', // تعيين الدور "patient"
            ]);

            // 4. إنشاء المريض (Patient) وربطه بالمستخدم الذي تم إنشاؤه
            Patient::create([
                'user_id' => $user->id, // ربط المريض بمعرف المستخدم
                'name' => $request->name, // حفظ اسم المريض من النموذج
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'blood_type' => $request->blood_type,
                'allergies' => $request->allergies,
            ]);

            return redirect()->route('patients.index')->with('success', 'تمت إضافة المريض بنجاح!');
        } catch (\Exception $e) {
            // في حالة حدوث خطأ بعد إنشاء المستخدم، قم بحذف المستخدم الذي تم إنشاؤه
            if ($user && $user->exists) { // تأكد من أن المستخدم قد تم إنشاؤه بالفعل
                $user->delete();
            }
            return redirect()->back()->withInput()->with('error', 'حدث خطأ أثناء إضافة المريض: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function show(Patient $patient)
    {
        // عرض تفاصيل مريض محدد (مع تحميل بيانات المستخدم المرتبط)
        $patient->load('user'); // تحميل بيانات المستخدم المرتبط
        return view('patients.show', compact('patient'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function edit(Patient $patient)
    {
        // عرض نموذج تعديل مريض موجود
        // تحتاج إلى جلب بيانات المستخدم المرتبط بالمريض لعرضها أيضًا
        $user = $patient->user; // الحصول على كائن المستخدم المرتبط
        return view('patients.edit', compact('patient', 'user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Patient $patient)
    {
        // 1. التحقق من صحة بيانات المستخدم (User) المرتبط
        $user = $patient->user; // جلب المستخدم المرتبط لتجاهل بريده الإلكتروني الحالي
        $request->validate([
            'user_name' => ['required', 'string', 'max:255'],
            'user_email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'user_password' => ['nullable', 'string', 'min:8', 'confirmed'], // كلمة المرور اختيارية عند التعديل
        ]);

        // 2. التحقق من صحة بيانات المريض (Patient)
        $request->validate([
            'name' => ['required', 'string', 'max:255'], // حقل اسم المريض
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', Rule::in(['male', 'female', 'other'])],
            'blood_type' => ['nullable', 'string', Rule::in(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])],
            'allergies' => ['nullable', 'string', 'max:1000'],
        ]);

        try {
            // 3. تحديث بيانات المستخدم المرتبط
            $user->name = $request->user_name;
            $user->email = $request->user_email;
            if ($request->filled('user_password')) { // تحديث كلمة المرور فقط إذا تم إدخالها
                $user->password = Hash::make($request->user_password);
            }
            $user->save(); // حفظ التغييرات على المستخدم

            // 4. تحديث بيانات المريض
            $patient->name = $request->name; // تحديث اسم المريض
            $patient->date_of_birth = $request->date_of_birth;
            $patient->gender = $request->gender;
            $patient->blood_type = $request->blood_type;
            $patient->allergies = $request->allergies;
            $patient->save(); // حفظ التغييرات على المريض

            return redirect()->route('patients.index')->with('success', 'تم تحديث بيانات المريض بنجاح!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'حدث خطأ أثناء تحديث بيانات المريض: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function destroy(Patient $patient)
    {
        try {
            // عند حذف المريض، يفضل حذف المستخدم المرتبط به أيضًا
            // (أو يمكنك تغيير دور المستخدم إذا كان يستخدم لأغراض أخرى)
            $user = $patient->user; // جلب المستخدم المرتبط
            $patient->delete(); // حذف سجل المريض
            if ($user) { // تأكد من وجود المستخدم قبل حذفه
                $user->delete(); // حذف المستخدم المرتبط
            }

            return redirect()->route('patients.index')->with('success', 'تم حذف المريض بنجاح!');
        } catch (\Exception | \Illuminate\Database\QueryException $e) {
            // معالجة خطأ قيود المفتاح الأجنبي (مثلاً إذا كان المريض لديه مواعيد مرتبطة)
            if ($e instanceof \Illuminate\Database\QueryException && $e->getCode() == '23000') {
                return redirect()->back()->with('error', 'لا يمكن حذف هذا المريض لارتباطه ببيانات أخرى (مثل المواعيد).');
            }
            return redirect()->back()->with('error', 'حدث خطأ غير متوقع أثناء حذف المريض: ' . $e->getMessage());
        }
    }
}