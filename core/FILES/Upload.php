<?php

namespace Core\FILES;

use Exception;

class Upload
{
    public $uploaded;
    public $processed;
    public $error;

    protected $filename;
    protected $fileArray;
    protected $maxsize;

    public function __construct($fileArray)
    {
        $this->fileArray = $fileArray;
    }

    public function process($path)
    {
        if(!is_dir($path)){
            mkdir($path,0755, true);
        }
        if (empty($this->file_new_name_body)){
            $this->file_new_name_body   =   $this->fileArray['name'];
        }

        if ($this->maxsize< $this->fileArray['size']){
            throw  new Exception("Fichier trop volumineux !");
        }

        $this->error = $this->fileArray['error'];
        $this->processed = move_uploaded_file($this->fileArray['tmp_name'], str_replace('//','/',$path.'/'.$this->file_new_name_body));

        if (!$this->processed){
            throw new Exception("Le fichier n'a pas pu être téléchargé.");
        }
    }
}