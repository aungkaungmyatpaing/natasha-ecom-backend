<?php

namespace App\Models;

use App\Casts\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ads extends Model
{
    use HasFactory;

    protected $casts = [
        'image' => Image::class,
    ];

    protected $fillable = [
        'image','slider_duration','status'
   ];
}
