<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'category',
        'posted_by',
        'expiry_date',
        'target_audience',
        'recipient_id'
    ];

    protected $casts = [
        'expiry_date' => 'date',
    ];

    public function postedBy()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }
}