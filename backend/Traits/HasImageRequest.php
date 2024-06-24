<?php

namespace App\Traits;


use App\Http\Requests\AbstractFormRequest;
use Exception;

trait HasImageRequest
{
    /**
     * @param AbstractFormRequest $request
     * @param array $data
     * @return void
     * @throws Exception
     */
    public function mutateStoreData(AbstractFormRequest $request, array &$data)
    {
        if($request->hasFile('image_name')){
            $modelName = $this->repository->getModelName();
            $data['image_name'] = $this->fileService->store($request->file('image_name'), $modelName);
        }
    }

    /**
     * @param AbstractFormRequest $request
     * @param int $id
     * @param array $data
     * @return void
     * @throws Exception
     */
    public function mutateUpdateData(AbstractFormRequest $request, int $id, array &$data)
    {
        if($request->hasFile('image_name')){
            $imageName = $this->repository->findById($id)->image_name;
            $modelName = $this->repository->getModelName();
            $data['image_name'] = $this->fileService->update($request->file('image_name'), $modelName, $imageName);
        }
    }

    /**
     * @param int $id
     * @return void
     * @throws Exception
     */
    public function deleteFile(int $id)
    {
        $imageName = $this->repository->findById($id)->image_name;
        $modelName = $this->repository->getModelName();
        $imageName && $this->fileService->delete($modelName, $imageName);
    }
}
