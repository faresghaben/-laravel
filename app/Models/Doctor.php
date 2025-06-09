<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',   // تأكد أن هذا موجود في جدول الأطباء لربطه بالمستخدم 
        'name',      
        'specialization',
        'license_number',
        'phone',
        'address',
        // ... أي أعمدة أخرى لديك للطبيب
    ];

    // علاقة الطبيب بالمستخدم (User)
    // الطبيب ينتمي إلى مستخدم واحد (Many-to-One: Doctor belongs to one User)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // علاقة الطبيب بالمواعيد
    // الطبيب يمكن أن يكون لديه عدة مواعيد (One-to-Many: Doctor has many Appointments)
    public function appointments()
    {
        // 'doctor_id' هو اسم المفتاح الأجنبي في جدول 'appointments' الذي يشير إلى الطبيب
        return $this->hasMany(Appointment::class, 'doctor_id');
    }
}