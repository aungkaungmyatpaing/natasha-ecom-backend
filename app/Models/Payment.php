<?php

namespace App\Models;

use App\Casts\Image;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;


    protected $fillable = [
      'payment_logo','payment_type','name','number', 'status','available_currency'
    ];

    protected $casts = [
        'payment_logo' => Image::class,
        'available_currency' => 'array',
    ];
}