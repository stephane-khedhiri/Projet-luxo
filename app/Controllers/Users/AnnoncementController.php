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
use Exception;
use PDOException;

class AnnoncementController extends AppControllers
{
    protected $errors = false;
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
            $this->errors = $e->getMessage();
        }
        $this->render('annoncements.list', ['annoncements'=>$annoncements,"pages"=> $pages, "currentPage" => $currentPage, 'error' => $this->errors]);
    }
    // permet que l'utilisateur de poste une annonce
    public function create(){

            $form = new Form($_POST);
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                try {
                    $annoncement= $form->getData(AnnoncementEntity::class);
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
                        if ($this->isAjax()){
                            echo json_encode(['success'=>'votre annoncement a était crée']);
                            header('Content-Type:application/json');
                            http_response_code(200);
                            die();
                        }else{
                            header('Location:index.php?action=users.annoncement.list');
                        }
                    }else{
                        if ($this->isAjax()) {
                            echo json_encode(['name' => 'title', 'message' => 'une error est survenu !!']);
                            header('Content-Type:application/json');
                            http_response_code(400);
                            die();
                        }
                    }
                }catch (Exception $e){
                    $this->errors = $e->getMessage();
                    if ($this->isAjax()){
                        echo json_encode(['name'=>$this->errors]);
                        header('Content-Type:application/json');
                        http_response_code(400);
                        die();
                    }

                }
            }
        $this->render('annoncements.create', ['form'=>$form, 'error'=> $this->errors]);

    }
    // permet que l'utilisateur éditer sa propos  annonce
    public function edit()
    {
        App::getInstance()->title = 'edit';
        $this->loadModel('annoncement');
        $annoncements = $this->annoncement->getAnnoncement($_GET['id'], $_SESSION['Users']['id']);

        $form = new Form($_POST, [], $annoncements);

        if (isset($_POST['modifier'])) {
                // insert dans la base de données
                $annoncement = $form->getData($annoncement);
                $dir_upload = 'www/image/' . $annoncement->getTypeToString();
                $upload = new Upload($dir_upload, $_FILES, $annoncement->getId(), $this->errors);
                $upload->upload();
                if (empty($upload->getErrors())) {

                    $annoncement = $this->annoncements->AddAnnoncement($annoncement);
                    if ($annoncement) {
                        // ajouter envoie de mail de confirmation d'ajout d'annoncements
                        $alert = "votre annonce a bien été créé";
                    }

                } else {
                    $alert = implode('<br>', $upload->getErrors());
                }
        }
        $this->render('annoncements.edit', ['form' => $form, 'annoncement' => $annoncements]);

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