<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'amount',
        'type',
        'due_date'
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}