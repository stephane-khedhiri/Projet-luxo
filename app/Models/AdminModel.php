<?php

namespace App\Models;


use App\Entitys\AnnoncementEntity;
use Core\Models;
use \PDO;

class AdminModel extends Models {

    protected $table = 'admins';

    public function getAdminByUser(string $username, $entity) {
        $query = $this->query("SELECT username, password FROM admins WHERE username = ?", [$username], true, $entity);
        return $query;
    }
    //ajouter un nouveau admins
    public function Add(string $username, string $password): bool {
        $query = $this->Bd->prepare("INSERT INTO admins(username, password) VALUES (?, ?);");
        $result = $query->execute([$username, password_hash($password, PASSWORD_DEFAULT)]);
        return $result;
    }


}