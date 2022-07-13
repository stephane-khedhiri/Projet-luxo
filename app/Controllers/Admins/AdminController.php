<?php

namespace App\Controllers\Admins;


use App\App;


use App\Entitys\AdminEntity as EntityAdmin;
use Core\HTML\Form\Form;


class AdminController extends AppControllers {

    public function __construct(){
        parent::__construct();
        $this->loadModel('admin');
        $this->loadModel('visitor');
        $this->loadModel('annoncement');
        $this->loadModel('User');

        $this->errors = array();
    }
    // rendu page connection + verification
    public function connect(){
        if($this->isConnected()){
            $this->redirect('Admins.Admin.statis');
        }
        App::getInstance()->title = 'connection';
        $form = new Form($_POST);
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
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
                        $_SESSION['admin']['mail'] = $user->getUsername();
                        header('Location:index.php?action=admins.admin.statis');
                    }
                }
                
            }
        }
        $this->render('admins.connect', ['form' => $form]);
    }

    // rendu page statistique
    public function statis($id = null){
            if(!$this->isConnected()){
                $this->redirect('Admins.Admin.connect');
            }
            App::getInstance()->title = 'statis';
            $year = (isset($id['year'])?$id:date('Y'));
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


    }

    // rendu page Utilisateur
    public function Users(){
        if(!$this->isConnected()){
            $this->redirect('Admins.Admin.connect');
        }
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
    }

    // rendu page info Users
    public function User($id){
        if(!$this->isConnected()){
            $this->redirect('Admins.admin.connect');
        }
        App::getInstance()->title = 'utilisateur';

        $currentPage = (int)($_GET['p'] ?? 1);
        $limit = 16;
        $count = $this->annoncement->count(['user_id' => $id]);
        $pages = ceil($count / $limit);

        if($currentPage > $pages){
            throw new \Exception('cette page n\'existe pas');
        }

        $annoncements = $this->annoncement->getAnnoncements(['user_id'=> $id],$limit,"date_at",false,$currentPage);
        $this->render('admins.Users', [
        'annoncements' => $annoncements,
        'id', $id,
        'pages' => $pages,
        'currentPage' => $currentPage
        ]);


    }


    public function isConnected(){
        if(isset($_SESSION['admin'])){
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