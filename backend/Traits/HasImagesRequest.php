<?php

namespace App\Traits;


use App\Http\Requests\AbstractFormRequest;
use Exception;

trait HasImagesRequest
{
    /**
     * @param AbstractFormRequest $request
     * @param array $data
     * @return void
     * @throws Exception
     */
    public function mutateImagesData(AbstractFormRequest $request, array &$data)
    {
        if($request->hasFile('image_names')){
            $modelName = $this->repository->getImagesModelName();
            $data['image_names'] = $this->fileService->storeMany($request->file('image_names'), $modelName);
        }
    }

    /**
     * @param int $imageId
     * @return void
     * @throws Exception
     */
    public function deleteImageFile(int $imageId)
    {
        $imageRepo = $this->repository->getImageRepository();
        $imageName = $imageRepo->findById($imageId)->image_name;
        $modelName = $imageRepo->getModelName();
        $imageName && $this->fileService->delete($modelName, $imageName);
        $imageRepo->delete($imageId);
    }

    /**
     * @param int $id
     * @return void
     * @throws Exception
     */
    public function deleteAllFiles(int $id)
    {
        $modelName = $this->repository->getImagesModelName();

        $imageNames = [];
        foreach ($this->repository->getImages($id) as $images) {
            $imageNames[] = $images->image_name;
        }

        foreach ($imageNames as $imageName) {
            $this->fileService->delete($modelName, $imageName);
        }
    }
}
