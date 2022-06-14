<?php

namespace App\Models;
use \Core\Database;
use App\Entity\AnnoncementEntity;
use App\Entity\Image;
use Core\Models;
use \PDO;

class ImageModel extends Models
{
    protected $table = 'images';
    // get les images




    //supprime une images avec son id
    public function deletedImage(Image $image):bool{
        $query = $this->db->prepare("DELETE FROM images WHERE images.id = ? LIMIT 1;");
        $result = $query->execute([$image->getId]);
        return $result;
    }

}