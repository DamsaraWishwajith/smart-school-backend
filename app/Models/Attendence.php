<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendence extends Model
{
    use HasFactory;

    protected $table = 'attendence';

    protected $fillable = [
        'user_id',
        'date',
        'in_time',
        'out_time',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
