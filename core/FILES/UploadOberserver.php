<?php

namespace Core\FILES;

class UploadOberserver
{
    private $Upload;

    // on crée une instance upload
    public function upload($file){
        $this->Upload = new PowerUpload($file);

        return $this->Upload;
    }
    // getFiles traitera le tableau de fichiers $_FILES
    public function getFiles($key = 'images'){
        $files = array();
        foreach ($_FILES[$key] as $k => $l){
            foreach ($l as $i => $v){
                if (!array_key_exists($i, $files)){
                    $files[$i] = array();
                }
                $files[$i][$k] = $v;
            }

        }
        #Renvoyer le tableau formaté
        return $files;
    }
}