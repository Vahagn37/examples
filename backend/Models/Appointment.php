<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
    ];

    public function barber(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'barber_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
