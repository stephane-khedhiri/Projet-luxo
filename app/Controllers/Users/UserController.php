<?php

namespace App\Controllers\Users;

use App\App;
use App\Entitys\UserEntity;
use Core\HTML\Form;

class UserController extends AppControllers
{
    public function __construct()
    {
        parent::__construct();
    }
    // edit user
    public function edit(){

        App::getInstance()->title = 'edit utilisateur';
        $this->loadModel('user');
        $userCurrent = $this->user->getUserById($_SESSION['Users']['id']);

        $form = new Form($_POST,[], $userCurrent);
        if(isset($_POST['edit'])){
            if(!$form->getErrors()){
                $user = $form->getData(UserEntity::class);
                $this->user->updateUser($_SESSION['Users']['id'], $user);

            }
        }
        $this->render('users.edit', ["form" => $form]);

    }
    // deleted user
    public function deleted()
    {
        $this->loadModel('user');
        $result = $this->delete($_SESSION['Users']['id']);
        if($result){
            $this->disconnect();
        }
    }
    // disconnect
    public function disconnect(){
        session_unset();
        session_destroy();
        header('Location:index.php');
    }
}