<?php
namespace App\Models; // لا يوجد أي سطر فارغ أو مسافة بيضاء قبل هذا السطر

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'diagnosis',
        'treatment',
        'record_date',
    ];

    protected $casts = [
        'record_date' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}