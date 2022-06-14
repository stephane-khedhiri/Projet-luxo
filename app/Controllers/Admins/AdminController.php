<?php

namespace App\Controllers\Admins;


use App\App;
use App\Controllers\AppControllers;
use Core\Controller;
use App\Entitys\AdminEntity as EntityAdmin;
use Core\HTML\Form;


class AdminController extends AppControllers {

    public function __construct(){
        parent::__construct();
        $this->loadModel('admin');
        $this->loadModel('visitor');
        $this->loadModel('annoncement');
        $this->loadModel('Users');

        $this->errors = array();
    }
    // rendu page connection + verification
    public function connect(){
        App::getInstance()->title = 'connection';
        $form = new Form($_POST, $this->errors, $this->EntityAdmin);
        if(isset($_POST['connection'])){
            $form->validateur(['text', 'required'],'Users');
            $form->validateur(['text', 'required', 'length'], 'password', 4, 50);
            if(empty($form->getErrors())){
                $userCurrent = $form->getData(new EntityAdmin());
                $user = $this->admin->getAdminByUser($userCurrent->getUsername(), EntityAdmin::class);
                if(!$user){
                    $form->AddError('mail', 'introuvable !');
                }else{
                    if(!password_verify($userCurrent->getPassword(), $user->getPassword())){
                        $form->AddError('password', 'mot de passe incorrecte !');
                    }else{
                        $_SESSION['admins']['mail'] = $user->getUsername();
                        header('Location:index.php?action=admins.admin.statis');
                    }
                }
                
            }
        }
        $this->render('admins.connect', ['form' => $form]);
    }

    // rendu page statistique
    public function statis(){
        if($this->isConnected()){
            App::getInstance()->title = 'statis';
            $year = (isset($_GET['year'])?$_GET['year']:date('Y'));
            $visiteurGrafig = $this->visitor->getVuesByDate($year);
            $usersGrafig = $this->visitor->getVueUser($year);
            if(!$visiteurGrafig || !$usersGrafig){
                $alert = 'manque de donnée pour general le grafique';
            }
            $this->render('admins.statis',
                [
                    'visiteurGrafig'=> $visiteurGrafig,
                    'usersGrafig' => $usersGrafig
                ]);

        }else{
            header('Location: index.php?action=admins.admin.connect');
        }
    }

    // rendu page Utilisateur
    public function Users(){
        if($this->isConnected()) {
            App::getInstance()->title = 'Utilisateurs';
            $currentPage = (int)($_GET['p'] ?? 1);
            $limit = 10;
            $count = $this->user->count();
            $pages = ceil($count / $limit);
            $users = $this->user->getUsers(null, $limit, "date_at", false,$currentPage);
            if (!$users) {
                $alert = 'aucun Users incrit pour le moment';
            }
            $this->render('admins.Users', ['Users' => $users, 'pages' => $pages, 'currentPage' => $currentPage]);
        }else{
            header('location:index.php&action=admins.admin.connect');
        }

    }

    // rendu page info Users
    public function User(){
        App::getInstance()->title = 'utilisateur';
        if($this->isConnected()){
            // virifier que get id et superieur a 0

                $id = (int)$_GET['id'];
                $currentPage = (int) ($_GET['p'] ?? 1);
                $limit = 16;
                $count = $this->annoncement->count(['user_id' => $id]);
                $pages = ceil($count / $limit);

                /*if($currentPage > $pages){
                    throw new \Exception('cette page n\'existe pas');
                }*/

                $annoncements = $this->annoncement->getAnnoncements(['user_id'=> $id],$limit,"date_at",false,$currentPage);
            $this->render('admins.Users', [
                'annoncements' => $annoncements,
                'pages' => $pages,
                'currentPage' => $currentPage
                ]);
        }else{
            header('Location:index.php&action=admins.admin.connect');
        }

    }


    public function isConnected(){
        if(isset($_SESSION['admins'])){
            return true;
        }else{
            return false;
        }
    }

    public function disconnect(){
        session_destroy();
        header('Location:index.php');
    }
}