<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmergencyAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'preset_type',
        'posted_by',
    ];

    public function postedBy()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }
}
