<?php
namespace App\Entitys;

class ImageEntity
{
    /**
     * @var int
     */
    public $id;
    /**
     * @var string
     */
    public $path;
    /**
     * @var string
     */
    public $name;
    
    public function getVarsToArray(){
        return get_object_vars($this);
    }
    public function getId() :int
    {
        return $this->id;
    }
    public function setId(int $id) :self
    {
        $this->id = $id;
        return $this;
    }
    public function getPath() :string
    {
        return $this->path;
    }
    
    public function setPath(string $path) :self
    {
        $this->path = $path;
        return $this;
    }


    /**
     * @return string
     */
    public function getName() :string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return self
     */
    public function setName(string $name) :self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getPathAbsolute():string
    {
        return './www/'.$this->path.'/'.$this->name;
    }
}