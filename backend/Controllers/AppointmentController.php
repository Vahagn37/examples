<?php

namespace App\Http\Controllers;

use App\Http\Requests\Appointment\ReadAllRequest;
use App\Http\Requests\Appointment\StoreRequest;
use App\Http\Requests\Appointment\UpdateRequest;
use App\Http\Resources\Appointment\AppointmentCollection;
use App\Http\Resources\Appointment\AppointmentResource;
use App\Http\Resources\Employee\EmployeeCollection;
use App\Http\Resources\Service\ServiceCollection;
use App\Repositories\AppointmentRepository;
use App\Repositories\EmployeeRepository;
use App\Repositories\ServiceRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Inertia\Response as InertiaResponse;

class AppointmentController extends ModelController
{
    protected EmployeeRepository $employeeRepository;
    protected ServiceRepository $serviceRepository;

    public function __construct (
        string $resourceKey = 'appointment',
        string $resourceClass = AppointmentRepository::class,
    ) {
        parent::__construct($resourceKey, $resourceClass);
        $this->employeeRepository = new EmployeeRepository();
        $this->serviceRepository = new ServiceRepository();
    }

    /**
     * @param Model $model
     * @return AppointmentResource
     */
    protected function getResource(Model $model): AppointmentResource
    {
        return new AppointmentResource($model);
    }

    /**
     * @param $models
     * @return AppointmentCollection
     */
    protected function getCollection($models): AppointmentCollection
    {
        return new AppointmentCollection($models);
    }

    /**
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        return parent::storeData($request->validated());
    }

    /**
     * @param UpdateRequest $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(UpdateRequest $request, int $id): RedirectResponse
    {
        return parent::updateData($id, $request->validated());
    }

    /**
     * @param ReadAllRequest $request
     * @return InertiaResponse
     */
    public function index(ReadAllRequest $request): InertiaResponse
    {
        return parent::getData($request);
    }

    /**
     * @return InertiaResponse
     */
    public function create(): InertiaResponse
    {
        return parent::createData($this->getAdditionalData());
    }

    /**
     * @param int $id
     * @return InertiaResponse
     */
    public function edit(int $id): InertiaResponse
    {
        return parent::editData($id, $this->getAdditionalData());
    }

    private function getAdditionalData(): array
    {
        return [
            'barbers' => new EmployeeCollection($this->employeeRepository->getBarbers()),
            'services' => new ServiceCollection($this->serviceRepository->getAll()),
        ];
    }
}

