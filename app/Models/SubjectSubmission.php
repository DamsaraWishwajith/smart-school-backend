<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subject_id',
        'assignment_pdf',
        'grade',
        'feedback',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
