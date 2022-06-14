<?php
namespace App\Models;


use App\Entitys\AnnoncementEntity;
use App\Entitys\UserEntity;
use App\Entitys\AdminEntity;

use Core\Models;
use \Exception;
use \PDO;
use \PDOException;


class UserModel extends Models {


    protected $table = 'users';
    public $entity = UserEntity::class;


    // ajouté un utilisateur

    /**
     * @param UserEntity $user
     * @return bool
     */
    public function CreateUser($user){
         return $this->query("INSERT INTO users (name, first_name, mail, username, date_at, password) VALUES(?, ?, ?, ?, ?, ?)",
            [
                $user->getName(),
                $user->getFirstName(),
                $user->getMail(),
                $user->getUsername(),
                $user->getDateAt(),
                $user->getPasswordHash(),
            ]);

    }
    // récupére un utilisateur avec son mail

    /**
     * @param string $mail
     * @return UserEntity|bool|mixed
     */
    public function getUserByMail(string $mail) {
        return $this->query('SELECT id,name, first_name,mail, username, password FROM users WHERE mail=?',[$mail],true, UserEntity::class);
    }
    //récupére un utilisateur avec son id
    public function getUserById(int $id): UserEntity {
        $user = $this->query('SELECT name, first_name,mail,username  FROM users WHERE id=?', [$id], true, $this->entity);
        return $user;
    }
    // modifier un utilisateur avec son id
    // passe en paramétre l entity Users
    public function updateUser(int $id, UserEntity $user){

        if ($user->getPassword()){

         return $this->query('UPDATE users SET name=?, first_name=?, username=?,mail=?, password=? WHERE id=?',
                [
                    $user->getName(),
                    $user->getFirstName(),
                    $user->getUsername(),
                    $user->getMail(),
                    $user->getPasswordHash(),
                    $id
                ], true);
        }
        $this->query('UPDATE users SET name=?, first_name=?, username=?,mail=? WHERE id=?',
            [
                $user->getName(),
                $user->getFirstName(),
                $user->getUsername(),
                $user->getMail(),
                $id
            ], true);

    }
    public function getCountUsers(){
        $count = $this->query("SELECT COUNT(id) as count FROM Users")[0];
        return (int)$count->count;
    }
    // récupére tout utilisateurs
    public function getUsers(array $wheres = null,int $limit = null, string $order = null, bool $all = true ,int $currentPage = null){
        $where= "";
        if (is_array($wheres)){
            $where = 'Where ';
            $param = [];
            foreach ($wheres as $sql => $attribute){
                $where .= $sql.' = ? ';
                $param []= $attribute;

            }
        }else{
            $wheres ='';
            $param = null;
        }

        if(!is_null($order)){
            $order = " ORDER BY  $order";
        }else{
            $order='';
        }
        if(!is_null($limit)){
            $limits= " LIMIT $limit";
            if(!is_null($currentPage)){
                $offset = $limit * ($currentPage-1);
                $offset = " OFFSET $offset";
            }else{
                $offset ='';
            }
        }else{
            $limit ='';
        }

        $users = $this->query(
            "SELECT id, name, first_name, username,date_at FROM Users $where $order $limits $offset",
            $param , $all, UserEntity::class
        );

        return $users;


    }
    //récupére un utilisateur avec son annonce
    public function getUsersByIdFormAnnoncements(int $id):EntityUser
    {
        $query = $this->db->prepare("SELECT id, name, first_name, username,date_at, mail FROM Users WHERE Users.id = ?");
        $query->setFetchMode(PDO::FETCH_CLASS, EntityUser::class);
        $query->execute([$id]);
        $user = $query->fetch();
        return $user;
    }
    // supprimé un utilisateur, ses annonce et ses images avec son id
    private function delectUser(int $id): bool{
        $query = $this->db->prepare("DELETE a, i ,u   FROM Users u 
                            LEFT JOIN annoncements a ON u.id = a.Users
                            LEFT JOIN images i ON i.annoncementId = a.id
                            WHERE a.Users = ?;");
        $result = $query->execute([$id]);

        return $result;
    }
}

