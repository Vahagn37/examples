<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\File;

class FileService
{
    /**
     * @param object $file
     * @param string $modelName
     * @return string
     * @throws Exception
     */
    public function store(object $file, string $modelName): string
    {
        $fileNameWithExt = $file->getClientOriginalName();
        $fileNameToStore = time().'_'.$fileNameWithExt;
        $location = $this->getFilePath($modelName);
        $file->move($location, $fileNameToStore);
        return $fileNameToStore;
    }

    /**
     * @param array $files
     * @param string $modelName
     * @return array
     * @throws Exception
     */
    public function storeMany(array $files, string $modelName): array
    {
        $names = [];
        foreach($files as $file) {
            $names[] = $this->store($file, $modelName);
        }
        return $names;
    }

    /**
     * @param object $file
     * @param string $modelName
     * @param string|null $imageName
     * @return string
     * @throws Exception
     */
    public function update(object $file, string $modelName, string $imageName = null): string
    {
        $imageName && $this->delete($modelName, $imageName);
        return $this->store($file, $modelName);
    }

    /**
     * @param string $modelName
     * @param string $imageName
     * @return void
     * @throws Exception
     */
    public function delete(string $modelName, string $imageName): void
    {
        $location = $this->getFilePath($modelName);
        File::delete("{$location}/{$imageName}");
    }

    /**
     * @param string $modelName
     * @return string
     * @throws Exception
     */
    public function getFilePath(string $modelName): string
    {
        return match ($modelName) {
            'Employee' => 'media/images/employees',
            'Service' => 'media/images/services',
            'Product' => 'media/images/products',
            'Article' => 'media/images/articles',
            'Image' => 'media/images',
            default => throw new Exception('No image path!'),
        };
    }
}
