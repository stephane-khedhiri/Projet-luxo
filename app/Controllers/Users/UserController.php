<?php

namespace App\Controllers\Users;

use App\App;
use App\Entitys\UserEntity;
use Core\HTML\Form\Form;
use Core\Validator\validator;
use Exception;

class UserController extends AppControllers
{
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('user');
    }
    // edit user
    public function edit(){
        App::getInstance()->title = 'edit';
        $userCurrent = $this->user->getUserById($_SESSION['Users']['id']);
        $form = new Form($userCurrent);
        if(!empty($_POST)){

                $validator = new validator();
                $validation = $validator->make($_POST, [
                    'username' => 'required|text|lenght,4:15',
                    'email'=> 'required|email',
                ]);
                $validation->validate();
                if(!$validation->getErrors()){
                    try {
                        $user = $validation->getData(UserEntity::class);
                        $result = $this->user->updateUser($user);
                        if($result){
                            App::getInstance()->success = 'le compte a bien était modifier';
                        }else{
                            throw new Exception('une error est survenu');
                        }
                    }catch (Exception $e){
                        if($this->isAjax()){
                            echo json_encode(['name' => $e->getMessage()]);
                            header('Content-Type: application/json');
                            http_response_code(400);
                            die();
                        }
                        App::getInstance()->error = $e->getMessage();
                    }

                }else{
                    if ($this->isAjax()) {
                        echo json_encode($validation->getErrors());
                        header('Content-Type: application/json');
                        http_response_code(400);
                        die();
                    }
                    App::getInstance()->error = $validation->getErrors();
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