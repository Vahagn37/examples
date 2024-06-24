<?php

namespace App\Http\Requests\Service;

use App\Http\Requests\AbstractFormRequest;
use App\Models\Service;
use App\Traits\OnlyAdminAccess;

class ReadAllRequest extends AbstractFormRequest
{
    use OnlyAdminAccess;
    protected bool $pageable = true;
    protected bool $filterable = true;
    protected string $resource = Service::class;
}
