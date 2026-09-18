<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'subject_id',
        'exam_name',
        'marks_obtained',
        'total_marks',
        'percentage',
        'grade',
        'term',
        'academic_year',
        'remarks'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function getGradeAttribute()
    {
        $percentage = $this->percentage;
        if ($percentage >= 75)
            return 'A';
        if ($percentage >= 65)
            return 'B';
        if ($percentage >= 55)
            return 'C';
        if ($percentage >= 35)
            return 'S';
        return 'F';
    }
}