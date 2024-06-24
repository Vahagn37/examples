<?php

namespace App\Http\Controllers;

use App\Http\Requests\Employee\ReadAllRequest;
use App\Http\Requests\Employee\StoreRequest;
use App\Http\Requests\Employee\UpdateRequest;
use App\Http\Resources\Employee\EmployeeCollection;
use App\Http\Resources\Employee\EmployeeResource;
use App\Repositories\EmployeeRepository;
use App\Services\FileService;
use App\Traits\HasImageRequest;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Inertia\Response as InertiaResponse;

class EmployeeController extends ModelController
{
    use HasImageRequest;

    protected FileService $fileService;

    public function __construct (
        string $resourceKey = 'employee',
        string $resourceClass = EmployeeRepository::class
    ) {
        parent::__construct($resourceKey, $resourceClass);
        $this->fileService = new FileService();
    }

    /**
     * @param Model $model
     * @return EmployeeResource
     */
    protected function getResource(Model $model): EmployeeResource
    {
        return new EmployeeResource($model);
    }

    /**
     * @param $models
     * @return EmployeeCollection
     */
    protected function getCollection($models): EmployeeCollection
    {
        return new EmployeeCollection($models);
    }

    /**
     * @param StoreRequest $request
     * @return RedirectResponse
     * @throws Exception
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $this->mutateStoreData($request, $data);
        return parent::storeData($data);
    }


    /**
     * @param UpdateRequest $request
     * @param int $id
     * @return RedirectResponse
     * @throws Exception
     */
    public function update(UpdateRequest $request, int $id): RedirectResponse
    {
        $data = $request->validated();
        $this->mutateUpdateData($request, $id, $data);
        return parent::updateData($id, $data);
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
        return parent::createData();
    }

    /**
     * @param int $id
     * @return InertiaResponse
     */
    public function edit(int $id): InertiaResponse
    {
        return parent::editData($id);
    }

    /**
     * @param int $id
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->deleteFile($id);
        return parent::destroy($id);
    }
}

