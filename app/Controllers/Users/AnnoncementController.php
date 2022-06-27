<?php

namespace App\Controllers\Users;

use App\App;
use App\Entitys\AnnoncementEntity;
use App\Entitys\ImageEntity;
use Core\FILES\Upload;
use Core\FILES\Files;
use Core\FILES\Uploader;
use Core\FILES\UploadOberserver;
use Core\HTML\Form\Form;
use Core\Validator\validator;
use Exception;


class AnnoncementController extends AppControllers
{
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('annoncement');

    }
    // permet de listé toutes les annonce de l'utilisateur avec un system de padding
    public function list(){
        try{
        $currentPage = (int) ($_GET['p'] ?? 1);
        $limit = 16;
        $count = $this->annoncement->count(['user_id' => $_SESSION['Users']['id']]);
        $pages = ceil($count / $limit);
        $annoncements = $this->annoncement->getAnnoncements(['user_id' => $_SESSION['Users']['id']], $limit, 'date', false,$currentPage);
        if (!$annoncements){
            throw new Exception('Aucune annonce a était enregister !');
        }
        }catch (Exception $e){
            App::getInstance()->error = $e->getMessage();
        }

        $this->render('annoncements.list', ['annoncements'=>$annoncements,"pages"=> $pages, "currentPage" => $currentPage, 'success'=> App::getInstance()->getControllers()->sucess]);
    }
    // permet que l'utilisateur de poste une annonce
    public function create(){
            App::getInstance()->title = "create annonce";
            $form = new Form($_POST);
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                App::getInstance()->error = null;

                    $valitator = new validator();
                    /*il faut ajouter le file */
                    $valitation = $valitator->make($_POST, [
                        'title'=> 'required|text|lenght,3:20',
                        'description' => 'required|text|lenght,20:100',
                        'floor' => 'required|interger',
                        'room' => 'required|interger',
                        'surface' => 'required|interger',
                        'price' => 'required|interger',
                        'city' => 'required',
                        'category' => 'required',
                        'type' => 'required'
                    ]);
                    $valitation->validate();
                    if (!$valitation->getErrors()){
                        try {
                            $annoncement= $valitation->getData(AnnoncementEntity::class);
                            var_dump($annoncement);
                            $annoncement->setUser($_SESSION['Users']['id']);
                            $path = 'image/'.$annoncement->getCategoryToString().'/'.$annoncement->getUser().'/'.$annoncement->gettitle();
                            $image = new ImageEntity();
                            $uploader = new UploadOberserver();
                            foreach ($uploader->getFiles('images') as $file){
                                $fileName = $file;
                                $handle = $uploader->upload($file);
                                $fileName = $handle->getFileName().rand(0,999999);
                                $handle->checkHasUploaded();
                                $handle->setFileName($fileName);
                                $handle->setMaxSize(.5);
                                $handle->processUpload($this->uploadPath.$path);
                                $image->setName($handle->getName());
                                $image->setPath($path);
                                $annoncement->AddImages($image);
                            }
                            $this->loadModel('annoncement');
                            $result =$this->annoncement->Add($annoncement);
                            if ($result){
                                header('Location:index.php?action=users.annoncement.list');

                            }else{
                                throw new Exception("une error est survenu !!");

                            }
                        }catch (Exception $e){
                            if ($this->isAjax()){
                                echo json_encode(['name'=>$e->getTrace(), 'message' => $e->getMessage()]);
                                header('Content-Type:application/json');
                                http_response_code(400);
                                die();
                            }
                            App::getInstance()->error = $e->getMessage();
                        }
                    }else{
                        if ($this->isAjax()){
                            echo json_encode($valitation->getErrors());
                            header('Content-Type:application/json');
                            http_response_code(400);
                            die();
                        }
                        App::getInstance()->error = $valitation->getMessage();
                    }

            }
        $this->render('annoncements.create', ['form'=>$form, 'error'=> App::getInstance()->error]);

    }
    // permet que l'utilisateur éditer sa propos  annonce
    public function edit()
    {
        App::getInstance()->title = 'edit';
        $this->loadModel('annoncement');
        $annoncement = $this->annoncement->getAnnoncement($_GET['id'], $_SESSION['Users']['id']);

        $form = new Form($_POST, [], $annoncement);

        if (isset($_POST['modifier'])) {

        }
        $this->render('annoncements.edit', ['form' => $form, 'annoncement' => $annoncement]);

    }


    // permet que l'utilisateur suprrime sa propos annonce
    public function deleted(){
        App::getInstance()->title = 'detail';
            if(isset($_GET['id'])){
                try{
                $this->loadModel('annoncement');
                $this->annoncement->deleted($_GET['id'], $_SESSION['Users']['id']);
                header('Location: index.php?action=users.annoncement.list');
                }catch (Exception $e){
                    $error = $e->getMessage();
                    $this->list();
                }
            }

    }

}