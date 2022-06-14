<?php

namespace Core\FILES;

use App\Controllers\AppControllers;
use App\Entitys\ImageEntity;

class Files
{
    /**
     * @var array
     */
    protected $name = [];
    /**
     * @var array
     */
    protected $files;


    public function __construct(array $files){
        $this->files = $files;
    }


    /**
     * @param ImageEntity $entity
     * @param string $path
     * @return array
     */
    public function getImages(ImageEntity $entity, string $path){
        foreach ($this->files['name'] as $name){
            $entity->setName($name);
            $entity->setPath($path);
            $this->name[] = $entity;
        }
        return $this->name;
    }

}