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
                    'username' => 'required|text|lenght,4:15',
                    'email'=> 'required|email',
                    'password' => 'required|lenght,8:18|password',
                ]);

                $validation->validate();

            if(!$validation->getErrors()){
                try {
                    $newUser = $validation->getData(UserEntity::class);
                    $user = $this->user->getUserByMail($newUser->getEmail());
                    if ($user){
                        throw new Exception("l'adresse mail existe déjà");
                    }
                    $create = $this->user->CreateUser($newUser);
                    if($create){
                        if($this->isAjax()){
                            echo json_encode(['redirect' => 'users.connect']);
                            header('Content-Type: application/json');
                            http_response_code(200);
                            die();
                        }
                        App::getInstance()->sucess = "Compte crée";
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
                App::getInstance()->error = $validation->getErrors();
            }
        }
        $this->render('Users.create', ['form'=> $form]);
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
                        $user = $this->user->getUserByMail($userCurrent->getEmail());
                        if ($user) {
                            if (password_verify($userCurrent->getPassword(), $user->getPassword())) {
                                $_SESSION['Users']['id'] = $user->getId();
                                $_SESSION['Users']['mail'] = $user->getEmail();
                                header('Location:'. App::getInstance()->routes->url('Annoncement.home'));

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
    // disconnect
    public function disconnect(){

        session_unset();
        session_destroy();
        header('Location:'. App::getInstance()->routes->url('Annoncement.home'));
    }


}