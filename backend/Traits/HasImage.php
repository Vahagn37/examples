<?php

namespace App\Traits;


use App\Services\FileService;
use Exception;

trait HasImage
{
    /**
     * @return string|null
     * @throws Exception
     */
    public function getImagePathAttribute(): ?string
    {
        $modelName = class_basename($this);
        $location = (new FileService())->getFilePath($modelName);
        return $this->image_name ? asset("/$location/$this->image_name") : null;
    }
}
