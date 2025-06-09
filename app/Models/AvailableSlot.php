<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvailableSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'day_of_week',
        'start_time',
        'end_time',
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        // يمكنك إزالة هذه الأسطر إذا كانت الأعمدة في قاعدة البيانات من نوع TIME
        // 'start_time' => 'string',
        // 'end_time' => 'string',
    ];

    /**
     * Get the doctor that owns the available slot.
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    // لا تضف أي شيء آخر هنا حالياً، فقط لضمان النظافة
}