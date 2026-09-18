<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_name',
        'description',
        'event_date',
        'created_by',
    ];

    public function images()
    {
        return $this->hasMany(GalleryImage::class, 'gallery_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
