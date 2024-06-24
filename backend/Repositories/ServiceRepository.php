<?php

namespace App\Repositories;

use App\Models\Service;

class ServiceRepository extends AbstractRepository
{
    public function __construct()
    {
        parent::__construct(Service::class);
    }
}
