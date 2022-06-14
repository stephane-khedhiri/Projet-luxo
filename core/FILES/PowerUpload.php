<?php

namespace Core\FILES;

use Exception;

class PowerUpload extends Upload
{
    public function setMaxSize($size){
        $this->maxsize = $size * (1024*1024);
    }
    public  function setNewFile($fileArray)
    {
        $this->fileArray    =   $fileArray;
        return $this;
    }
    public function getFileName(){
        return explode('.', $this->fileArray['name'])[0];
    }
    public  function setFileName($name)
    {
        $ext = explode('/', $this->fileArray['type']);
        $this->file_new_name_body   =   $name.'.'.end($ext);

        return $this;
    }
    public function getName(){
        return $this->file_new_name_body;
    }

    public  function checkHasUploaded()
    {
        $files  =   (!empty($this->fileArray));

        if(!$files)
            throw new Exception("une image est require");

        return $files;
    }

    public  function hasProcessed()
    {
        return $this->processed;
    }

    public  function processUpload($path)
    {
        $this->process($path);
    }

    public  function getError()
    {
        return $this->error;
    }
}