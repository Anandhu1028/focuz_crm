<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoursePayments extends Model
{
    use HasFactory;


    public function courses()
    {
        return $this->belongsTo(Courses::class, 'course_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branches::class, 'branch_id'); // Assuming 'branch_id' is the foreign key
    }

    public function students()
    {
        return $this->belongsTo(Students::class, 'student_id'); // Assuming 'branch_id' is the foreign key
    }
    
        public function payments()
{
    return $this->hasMany(\App\Models\Payments::class, 'course_id', 'course_id')
                ->where(function ($query) {
                    $query->where('student_id', $this->student_id);
                });
}


   


}
