<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Passport extends Model
{




     /**
     * @var array<int, string>
     */
    use HasFactory;
    protected $fillable = [
        'name',
        'father_name',
        'passport',
        'passport_no',
        'passport_expire',
        'visa',
        'sl',
        'number',
        'status',
    ];

    protected $casts = [
        'status' => OrderStatus::class,
    ];

}
