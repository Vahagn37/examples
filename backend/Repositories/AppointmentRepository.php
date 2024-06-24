<?php

namespace App\Repositories;

use App\Models\Appointment;

class AppointmentRepository extends AbstractRepository
{
    public function __construct()
    {
        parent::__construct(Appointment::class);
    }
}
