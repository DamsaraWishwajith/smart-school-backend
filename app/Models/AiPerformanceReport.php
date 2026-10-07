<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiPerformanceReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'user_id',
        'generated_by',
        'academic_year',
        'term',
        'overall_performance',
        'strengths',
        'weaknesses',
        'improvement_suggestions',
        'growth_progress',
        'summary',
        'metrics',
        'raw_response'
    ];

    protected $casts = [
        'strengths' => 'array',
        'weaknesses' => 'array',
        'improvement_suggestions' => 'array',
        'metrics' => 'array'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function generator()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
