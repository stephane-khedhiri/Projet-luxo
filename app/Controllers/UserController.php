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
    protected $errors = false;

    public function __construct(){
        parent::__construct();
        $this->loadModel('user');
        if($this->isConnected()){
            header('Location:index.php');
        }
    }
    // create user
    public function create(){
        /*
         * $validator = new Validator();
         * $validator->make([
         *      'email' => required
         *      'name' => required|text
         * ])
         * $validator->validate()
         * if($validator->fails){
         *  ici il y a des errors
         * }
         *
         */
        App::getInstance()->title = 'creation';
        $form = new Form($_POST);
        $error= false;
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            try{
            $validator = new validator();
            $validation = $validator->make($_POST, [
                'email'=> 'required|email'
            ]);
                $validation->validate();
            }catch (Exception $e){
                var_dump($e->getMessage());
            }
            die();
            try{

                $newUser = $form->getData(UserEntity::class);
                $userCurrent = $this->user->getUserByMail($newUser->getMail());
                if($userCurrent){
                    if ($this->isAjax()){
                        echo json_encode(['name' =>'mail', 'message' => "l'utilisateur existe déjà"]);
                        header('Content-Type: application/json');
                        http_response_code(400);
                        die();
                    }else{
                        throw new Exception("l'email existe déjà");
                    }
                }
                $result = $this->user->CreateUser($newUser);
                if($result){
                if ($this->isAjax()){
                    echo json_encode(['success'=>'votre compte vient d\'être crée']);
                    header('Content-Type: application/json');
                    http_response_code(200);
                    die();
                }
                $this->render('users.connect', ['form'=> $form, 'success'=>'votre compte vient d\'être crée']);
                die();
                }
            }catch (Exception $e){
                $error = $e->getMessage();
            }
        }
        $this->render('Users.create', ['form'=> $form, 'error'=>$error]);
    }
    // make connect
    public function connect(){

        $form = new Form($_POST);

        if(!empty($_POST)) {
            try {

                $userCurrent = $form->getData(UserEntity::class);
                $user = $this->user->getUserByMail($userCurrent->getMail());

                if ($user) {
                    if (password_verify($userCurrent->getPassword(), $user->getPassword())) {
                        $_SESSION['Users']['id'] = $user->getId();
                        $_SESSION['Users']['name'] = $user->getName();
                        $_SESSION['Users']['firstName'] = $user->getFirstName();
                        $_SESSION['Users']['mail'] = $user->getMail();
                        header('Location:index.php?action=users.annoncement.list');
                    }else{
                        if($this->isAjax()){
                            echo json_encode(['name' =>'password', 'message' => "le mot de passe incorrect !"]);
                            header('Content-Type: application/json');
                            http_response_code(200);
                            die();
                        }
                        throw new Exception('le mot de passe incorrect !');
                    }
                } else {
                    if($this->isAjax()){
                        echo json_encode(['name' =>'mail', 'message' => "E-mail inconnu !"]);
                        header('Content-Type: application/json');
                        http_response_code(200);
                        die();
                    }
                    throw new Exception('E-mail inconnu !');
                }
            }catch (Exception $e){
                $this->errors = $e->getMessage();
            }
        }
        $this->render('users.connect', ['form' => $form, 'error' => $this->errors]);
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