<?php
namespace App\Controllers;


use App\App;
use Core\Controller;
use Core\HTML\Form\Form;
use \App\Entitys\UserEntity;
use Core\Validator\validator;
use  Core\Validator\validation;
use Core\Validator\Type\Email;
use \Exception;

class UserController extends AppControllers {
    protected $success;
    public function __construct(){
        parent::__construct();
        $this->loadModel('user');
        if($this->isConnected()){
            header('Location:index.php');
        }
    }
    // create user
    public function create(){

        App::getInstance()->title = 'create';
        $form = new Form($_POST);

        if(!empty($_POST)){

                $validator = new validator();
                $validation = $validator->make($_POST, [
                    'name' => 'required|text|lenght,4:15',
                    'firstName' => 'required|text|lenght,4:15',
                    'username' => 'required|text|lenght,4:15',
                    'email'=> 'required|email',
                ]);

                $validation->validate();
            if(!$validation->getErrors()){
                try {
                    $newUser = $validation->getData(UserEntity::class);

                    $user = $this->user->getUserByMail($newUser->getMail());
                    if ($user){
                        throw new Exception("l'adresse mail existe déjà");
                    }
                    $create = $this->user->CreateUser($newUser);
                    if($create){
                        App::getInstance()->sucess = "Compte crée";
                        header('Location:index.php?action=user.connect');
                    }
                }catch (Exception $e){
                    if($this->isAjax()){
                        echo json_encode([$e->getMessage()]);
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
                App::getInstance()->error = $validation->getErrors()[0];
            }
        }
        $this->render('Users.create', ['form'=> $form, 'error'=>App::getInstance()->error]);
    }
    // make connect
    public function connect(){
        App::getInstance()->title = 'connect';
        $form = new Form($_POST);
        if($_SERVER['REQUEST_METHOD'] == 'POST') {

                $validator = new validator();
                $validation = $validator->make($_POST, [
                    'email'=> 'required|email',
                ]);
                $validation->validate();
                if(!$validation->getErrors()){
                    try {


                        $userCurrent = $validation->getData(UserEntity::class);
                        $user = $this->user->getUserByMail($userCurrent->getMail());
                        if ($user) {
                            if (password_verify($userCurrent->getPassword(), $user->getPassword())) {
                                $_SESSION['Users']['id'] = $user->getId();
                                $_SESSION['Users']['name'] = $user->getName();
                                $_SESSION['Users']['firstName'] = $user->getFirstName();
                                $_SESSION['Users']['mail'] = $user->getMail();
                                header('Location:index.php?action=users.annoncement.list');
                            } else {
                                throw new Exception('le mot de passe incorrect !');
                            }
                        } else {

                            throw new Exception('l\'adresse email inconnu !');
                        }
                    }catch (Exception $e){
                        if($this->isAjax()){
                            echo json_encode([$e->getMessage()]);
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
                    foreach($validation->getErrors() as $k => $errors){

                    App::getInstance()->error=$errors;
                    }
                }
        }
        $this->render('users.connect', ['form' => $form]);
    }
    // check connect
    public function isConnected() {
        if(isset($_SESSION['Users'])) {
            return true;
        } else {
            return false;
        }
    }


}