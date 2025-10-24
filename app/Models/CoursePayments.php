<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoursePayments extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_id',
        'course_schedule_id',
        'branch_id',
        'amount',
        'discount',
        'payment_status',
        'customer_relation_executive', 
        'created_by',
        'admission_date',
        'student_track_id',
    ];

    public function courses()
    {
        return $this->belongsTo(Courses::class, 'course_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branches::class, 'branch_id');
    }

    public function students()
    {
        return $this->belongsTo(Students::class, 'student_id');
    }

    public function payments()
    {
        return $this->hasMany(\App\Models\Payments::class, 'course_id', 'course_id')
            ->where('student_id', $this->student_id);
    }

    public function customer_relation_executive_user()
    {
        return $this->belongsTo(\App\Models\User::class, 'customer_relation_executive');
    }

    public function customer_relation_executive_employee()
    {
        return $this->belongsTo(\App\Models\Employees::class, 'customer_relation_executive');
    }

    // Helper to always return CRE name
    public function cre_name()
    {
        if ($this->customer_relation_executive_user) {
            return $this->customer_relation_executive_user->name;
        }
        if ($this->customer_relation_executive_employee) {
            return $this->customer_relation_executive_employee->first_name . ' ' . $this->customer_relation_executive_employee->last_name;
        }
        return 'N/A';
    }
}
