<?php

namespace App\Http\Controllers;

use App\Http\Requests\AbstractFormRequest;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

abstract class ModelController extends Controller
{
    protected AbstractRepository $repository;
    protected string $modelKey = '';

    public function __construct(string $resourceKey, string $resourceClass)
    {
        $this->modelKey = $resourceKey;
        $this->repository = app($resourceClass);
    }

    abstract protected function getResource(Model $model): JsonResource;
    abstract protected function getCollection($models): ResourceCollection;

    /**
     * @return string
     */
    protected function getModelsKey(): string
    {
        return Str::plural($this->modelKey);
    }

    /**
     * @return string
     */
    protected function getFolderName(): string
    {
        return Str::ucfirst($this->getModelsKey());
    }

    /**
     * @return string
     */
    protected function getMainRoute(): string
    {
        return "{$this->getModelsKey()}.index";
    }

    /**
     * @param AbstractFormRequest $request
     * @return InertiaResponse
     */
    protected function getData(AbstractFormRequest $request): InertiaResponse
    {
        $pagination = $request->input('pagination');
        $page = $request->input('page') ?? 1;
        $models = $this->repository->getAll($pagination, $page);
        return Inertia::render("Admin/{$this->getFolderName()}/Index", [
            $this->getModelsKey() => $this->getCollection($models),
        ]);
    }

    /**
     * @param array $data
     * @return InertiaResponse
     */
    protected function createData(array $data = []): InertiaResponse
    {
        return Inertia::render("Admin/{$this->getFolderName()}/Create", $data);
    }

    /**
     * @param array $data
     * @return RedirectResponse
     */
    protected function storeData(array $data): RedirectResponse
    {
        $this->repository->create($data);
        return Redirect::route($this->getMainRoute())->with('success', 'Data was created.');
    }

    /**
     * @param int $id
     * @param array $data
     * @return InertiaResponse
     */
    protected function editData(int $id, array $data = []): InertiaResponse
    {
        $model = $this->repository->findById($id);
        return Inertia::render("Admin/{$this->getFolderName()}/Edit",
            array_merge($data, [$this->modelKey => $this->getResource($model)])
        );
    }

    /**
     * @param int $id
     * @param array $data
     * @return RedirectResponse
     */
    protected function updateData(int $id, array $data): RedirectResponse
    {
        $this->repository->update($id, $data);
        return Redirect::route($this->getMainRoute())->with('success', 'Data was updated.');
    }

    /**
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->repository->delete($id);
        return Redirect::route($this->getMainRoute())->with('success', 'Data was deleted.');
    }
}
