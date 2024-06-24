<?php

namespace App\Http\Controllers;

use App\Http\Requests\Service\ReadAllRequest;
use App\Http\Requests\Service\StoreRequest;
use App\Http\Requests\Service\UpdateRequest;
use App\Http\Resources\Service\ServiceCollection;
use App\Http\Resources\Service\ServiceResource;
use App\Repositories\ServiceRepository;
use App\Services\FileService;
use App\Traits\HasImageRequest;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Inertia\Response as InertiaResponse;

class ServiceController extends ModelController
{
    use HasImageRequest;

    protected FileService $fileService;

    public function __construct (
        string $resourceKey = 'service',
        string $resourceClass = ServiceRepository::class,
    ) {
        parent::__construct($resourceKey, $resourceClass);
        $this->fileService = new FileService();
    }

    /**
     * @param Model $model
     * @return ServiceResource
     */
    protected function getResource(Model $model): ServiceResource
    {
        return new ServiceResource($model);
    }

    /**
     * @param $models
     * @return ServiceCollection
     */
    protected function getCollection($models): ServiceCollection
    {
        return new ServiceCollection($models);
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
