<?php

namespace App\Models;

use App\Traits\HasImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory, HasImage;

    protected $guarded = [];

    protected $appends = ['image_path'];

    protected $casts = [
        'is_barber' => 'boolean',
    ];
}
