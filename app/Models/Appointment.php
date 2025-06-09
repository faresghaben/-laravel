<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    // الأعمدة التي يمكن تعبئتها جماعياً (mass assignable)
    protected $fillable = [
        'doctor_id',
        'patient_id',
        'start_time',
        'end_time',
        'status',
        'cancellation_reason',
    ];

    // تحويل الأعمدة إلى كائنات DateTime تلقائياً
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    // علاقة الموعد بالطبيب
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    // علاقة الموعد بالمريض (الذي هو مستخدم)
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
}