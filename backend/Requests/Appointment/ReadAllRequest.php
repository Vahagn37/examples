<?php

namespace App\Http\Requests\Appointment;

use App\Http\Requests\AbstractFormRequest;
use App\Models\Appointment;
use App\Traits\OnlyAdminAccess;

class ReadAllRequest extends AbstractFormRequest
{
    use OnlyAdminAccess;
    protected bool $pageable = true;
    protected bool $filterable = true;
    protected string $resource = Appointment::class;
}
