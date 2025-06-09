<?php

namespace App\Http\Controllers;

use App\Models\User; // تأكد من استيراد موديل User
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // <--- هذا السطر تم تفعيله (إلغاء التعليق)
use Illuminate\Validation\Rule; // لاستخدام Rule في التحقق من فرادة البريد الإلكتروني عند التعديل

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // استعراض جميع المستخدمين
        $users = User::all();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // عرض نموذج إنشاء مستخدم جديد
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // التحقق من صحة البيانات المدخلة
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'], // 'confirmed' يتطلب حقل password_confirmation
            'role' => ['nullable', 'string', 'in:patient,doctor,admin'], // تم تفعيل هذا الحقل إذا كنت تستخدم الأدوار
        ]);

        try {
            // إنشاء مستخدم جديد. يجب استخدام Hash::make() في Laravel 8
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password), // <--- استخدام Hash::make()
                'role' => $request->role, // حفظ الدور إذا تم إدخاله
            ]);

            return redirect()->route('users.index')->with('success', 'تمت إضافة المستخدم بنجاح!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'حدث خطأ أثناء إضافة المستخدم: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        // عرض تفاصيل مستخدم محدد
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        // عرض نموذج تعديل مستخدم موجود
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        // التحقق من صحة البيانات المدخلة
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)], // تجاهل البريد الإلكتروني الحالي للمستخدم عند التحقق من الفرادة
            'password' => ['nullable', 'string', 'min:8', 'confirmed'], // 'nullable' تسمح بتركه فارغًا، 'confirmed' يتطلب password_confirmation
            'role' => ['nullable', 'string', 'in:patient,doctor,admin'], // تم تفعيل هذا الحقل إذا كنت تستخدم الأدوار
        ]);

        try {
            // تحديث بيانات المستخدم
            $user->name = $request->name;
            $user->email = $request->email;

            // تحديث كلمة المرور فقط إذا تم إدخال قيمة جديدة.
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password); // <--- استخدام Hash::make()
            }

            $user->role = $request->role; // حفظ الدور إذا تم إدخاله

            $user->save();

            return redirect()->route('users.index')->with('success', 'تم تحديث بيانات المستخدم بنجاح!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'حدث خطأ أثناء تحديث بيانات المستخدم: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        try {
            // حذف المستخدم
            $user->delete();
            return redirect()->route('users.index')->with('success', 'تم حذف المستخدم بنجاح!');
        } catch (\Exception | \Illuminate\Database\QueryException $e) {
            // إضافة QueryException للتعامل مع أخطاء القيود (مثل المفاتيح الأجنبية)
            if ($e instanceof \Illuminate\Database\QueryException && $e->getCode() == '23000') {
                return redirect()->back()->with('error', 'لا يمكن حذف هذا المستخدم لارتباطه ببيانات أخرى (مثل المواعيد أو السجلات).');
            }
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف المستخدم: ' . $e->getMessage());
        }
    }
}