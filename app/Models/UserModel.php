<?php
namespace App\Models;


use App\Entitys\AnnoncementEntity;
use App\Entitys\UserEntity;
use App\Entitys\AdminEntity;

use Core\Models;
use \Exception;
use \PDO;
use \PDOException;


class UserModel extends Models
{


    protected $table = 'users';
    public $entity = UserEntity::class;


    // ajouté un utilisateur

    /**
     * @param UserEntity $user
     * @return bool
     */
    public function CreateUser($user)
    {
        return $this->query("INSERT INTO users (username, email,date_at, password) VALUES(?, ?, ?, ?)",
            [
                $user->getUsername(),
                $user->getEmail(),
                $user->getDateAt(),
                $user->getPasswordHash(),
            ]);

    }
    // récupére un utilisateur avec son mail

    /**
     * @param string $mail
     * @return UserEntity|bool|mixed
     */
    public function getUserByMail(string $mail)
    {
        return $this->query('SELECT id,email, username, password FROM users WHERE email=?', [$mail], true, UserEntity::class);
    }

    //récupére un utilisateur avec son id
    public function getUserById(int $id): UserEntity
    {
        $user = $this->query('SELECT email,username  FROM users WHERE id=?', [$id], true, $this->entity);
        return $user;
    }
    // modifier un utilisateur avec son id
    // passe en paramétre l entity Users
    public function updateUser(int $id, UserEntity $user)
    {

        if ($user->getPassword()) {

            return $this->query('UPDATE users SET  username=?,email=?, password=? WHERE id=?',
                [
                    $user->getUsername(),
                    $user->getEmail(),
                    $user->getPasswordHash(),
                    $id
                ], true);
        }
        $this->query('UPDATE users SET  username=?,email=? WHERE id=?',
            [
                $user->getUsername(),
                $user->getEmail(),
                $id
            ], true);

    }

    public function getCountUsers()
    {
        $count = $this->query("SELECT COUNT(id) as count FROM Users")[0];
        return (int)$count->count;
    }

    // récupére tout utilisateurs
    public function getUsers(array $wheres = null, int $limit = null, string $order = null, bool $all = true, int $currentPage = null)
    {
        $where = "";
        if (is_array($wheres)) {
            $where = 'Where ';
            $param = [];
            foreach ($wheres as $sql => $attribute) {
                $where .= $sql . ' = ? ';
                $param [] = $attribute;

            }
        } else {
            $wheres = '';
            $param = null;
        }

        if (!is_null($order)) {
            $order = " ORDER BY  $order";
        } else {
            $order = '';
        }
        if (!is_null($limit)) {
            $limits = " LIMIT $limit";
            if (!is_null($currentPage)) {
                $offset = $limit * ($currentPage - 1);
                $offset = " OFFSET $offset";
            } else {
                $offset = '';
            }
        } else {
            $limit = '';
        }

        $users = $this->query(
            "SELECT id, name, first_name, username,date_at FROM Users $where $order $limits $offset",
            $param, $all, UserEntity::class
        );

        return $users;


    }
}

