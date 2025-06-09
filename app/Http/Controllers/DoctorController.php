<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\User; // يجب استيراد موديل User للتعامل مع المستخدمين
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // لتشفير كلمة المرور في Laravel 8
use Illuminate\Validation\Rule; // لاستخدام Rule في التحقق من فرادة البريد الإلكتروني

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // استعراض جميع الأطباء مع تحميل بيانات المستخدم المرتبط بهم
        $doctors = Doctor::with('user')->get();
        return view('doctors.index', compact('doctors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // عرض نموذج إضافة طبيب جديد
        return view('doctors.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 1. التحقق من صحة بيانات المستخدم (User) قبل إنشاء الطبيب
        $request->validate([
            'user_name' => ['required', 'string', 'max:255'],
            'user_email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'user_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // 2. التحقق من صحة بيانات الطبيب (Doctor)
        $request->validate([
            'name' => ['required', 'string', 'max:255'], // حقل اسم الطبيب
            'specialization' => ['required', 'string', 'max:255'],
            'license_number' => ['required', 'string', 'max:255', 'unique:doctors,license_number'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        $user = null; // تهيئة متغير user
        try {
            // 3. إنشاء المستخدم (User) أولاً وربطه بدور "doctor"
            $user = User::create([
                'name' => $request->user_name,
                'email' => $request->user_email,
                'password' => Hash::make($request->user_password), // تشفير كلمة المرور في Laravel 8
                'role' => 'doctor', // تعيين الدور "doctor"
            ]);

            // 4. إنشاء الطبيب (Doctor) وربطه بالمستخدم الذي تم إنشاؤه
            Doctor::create([
                'user_id' => $user->id, // ربط الطبيب بمعرف المستخدم
                'name' => $request->name, // حفظ اسم الطبيب من النموذج
                'specialization' => $request->specialization,
                'license_number' => $request->license_number,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

            return redirect()->route('doctors.index')->with('success', 'تمت إضافة الطبيب بنجاح!');
        } catch (\Exception $e) {
            // في حالة حدوث خطأ بعد إنشاء المستخدم، قم بحذف المستخدم الذي تم إنشاؤه
            if ($user && $user->exists) { // تأكد من أن المستخدم قد تم إنشاؤه بالفعل
                $user->delete();
            }
            return redirect()->back()->withInput()->with('error', 'حدث خطأ أثناء إضافة الطبيب: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function show(Doctor $doctor)
    {
        // عرض تفاصيل طبيب محدد (مع تحميل بيانات المستخدم المرتبط)
        $doctor->load('user'); // تحميل بيانات المستخدم المرتبط
        return view('doctors.show', compact('doctor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function edit(Doctor $doctor)
    {
        // عرض نموذج تعديل طبيب موجود
        // تحتاج إلى جلب بيانات المستخدم المرتبط بالطبيب لعرضها أيضًا
        $user = $doctor->user; // الحصول على كائن المستخدم المرتبط
        return view('doctors.edit', compact('doctor', 'user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Doctor $doctor)
    {
        // 1. التحقق من صحة بيانات المستخدم (User) المرتبط
        $user = $doctor->user; // جلب المستخدم المرتبط لتجاهل بريده الإلكتروني الحالي
        $request->validate([
            'user_name' => ['required', 'string', 'max:255'],
            'user_email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'user_password' => ['nullable', 'string', 'min:8', 'confirmed'], // كلمة المرور اختيارية عند التعديل
        ]);

        // 2. التحقق من صحة بيانات الطبيب (Doctor)
        $request->validate([
            'name' => ['required', 'string', 'max:255'], // حقل اسم الطبيب
            'specialization' => ['required', 'string', 'max:255'],
            'license_number' => ['required', 'string', 'max:255', Rule::unique('doctors', 'license_number')->ignore($doctor->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            // 3. تحديث بيانات المستخدم المرتبط
            $user->name = $request->user_name;
            $user->email = $request->user_email;
            if ($request->filled('user_password')) { // تحديث كلمة المرور فقط إذا تم إدخالها
                $user->password = Hash::make($request->user_password);
            }
            $user->save(); // حفظ التغييرات على المستخدم

            // 4. تحديث بيانات الطبيب
            $doctor->name = $request->name; // تحديث اسم الطبيب
            $doctor->specialization = $request->specialization;
            $doctor->license_number = $request->license_number;
            $doctor->phone = $request->phone;
            $doctor->address = $request->address;
            $doctor->save(); // حفظ التغييرات على الطبيب

            return redirect()->route('doctors.index')->with('success', 'تم تحديث بيانات الطبيب بنجاح!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'حدث خطأ أثناء تحديث بيانات الطبيب: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Doctor $doctor)
    {
        try {
            // عند حذف الطبيب، يفضل حذف المستخدم المرتبط به أيضًا
            // (أو يمكنك تغيير دور المستخدم إذا كان يستخدم لأغراض أخرى)
            $user = $doctor->user; // جلب المستخدم المرتبط
            $doctor->delete(); // حذف سجل الطبيب
            if ($user) { // تأكد من وجود المستخدم قبل حذفه
                $user->delete(); // حذف المستخدم المرتبط
            }

            return redirect()->route('doctors.index')->with('success', 'تم حذف الطبيب بنجاح!');
        } catch (\Exception | \Illuminate\Database\QueryException $e) {
            // معالجة خطأ قيود المفتاح الأجنبي (مثلاً إذا كان الطبيب لديه مواعيد مرتبطة)
            if ($e instanceof \Illuminate\Database\QueryException && $e->getCode() == '23000') {
                return redirect()->back()->with('error', 'لا يمكن حذف هذا الطبيب لارتباطه ببيانات أخرى (مثل المواعيد أو الفتحات المتاحة).');
            }
            return redirect()->back()->with('error', 'حدث خطأ غير متوقع أثناء حذف الطبيب: ' . $e->getMessage());
        }
    }
}