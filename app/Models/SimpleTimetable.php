<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SimpleTimetable extends Model
{
    protected $fillable = [
        'day',
        'grade',
        't_8_00_8_30',
        't_8_30_9_00',
        't_9_00_9_30',
        't_9_30_10_00',
        't_10_00_10_30',
        't_10_30_11_00',
        't_11_00_11_30',
        't_11_30_12_00',
        't_12_00_12_30',
        't_12_30_1_00',
        't_1_00_1_30',
    ];
}
