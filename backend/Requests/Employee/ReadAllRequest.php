<?php

namespace App\Http\Requests\Employee;

use App\Http\Requests\AbstractFormRequest;
use App\Models\Employee;
use App\Traits\OnlyAdminAccess;

class ReadAllRequest extends AbstractFormRequest
{
    use OnlyAdminAccess;
    protected bool $pageable = true;
    protected bool $filterable = true;
    protected string $resource = Employee::class;
}
