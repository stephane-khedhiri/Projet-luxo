<?php

namespace App\Controllers;


use App\App;
use Core\Controller;

use App\Entitys\AnnoncementEntity as EntityAnnoncement;



class AnnoncementController extends AppControllers {


    protected $errors;

    public function __construct(){
        parent::__construct();
        $this->loadModel('annoncement');
        $this->errors = [];
    } 
    // page home 
    public function home(){
        App::getInstance()->title = 'home';
        $locations = $this->annoncement->getAnnoncements(['category' => EntityAnnoncement::getCategoryByKey('location')], 5, "date_at",false);
        $achats = $this->annoncement->getAnnoncements(['category' => EntityAnnoncement::getCategoryByKey('achat')], 5, "date_at",false);
        $this->render('annoncements.home', ['locations'=>$locations, 'achats'=> $achats]);

    }
    // page pour déferente categorie location ou achat 
    public function category($category){
        $currentPage = (int) ($_GET['p'] ?? 1);
        $limit = 16;
        $count = $this->annoncement->count(['category' => EntityAnnoncement::getCategoryByKey($category)]);
        $pages = ceil($count / $limit);
        if($currentPage > $pages){
            throw new \Exception('cette page n\'existe pas');
        }
        $annoncements = $this->annoncement->getAnnoncements(['category' => EntityAnnoncement::getCategoryByKey($category)], $limit, "date_at",false,$currentPage);

        $this->render("annoncements.$category", ["annoncements" => $annoncements, "pages"=> $pages, "currentPage" => $currentPage]);
    }
    // detail de l'annonce
    public function detail($id){
        App::getInstance()->title = 'detail';
        $annoncement = $this->annoncement->getAnnoncementById($id);
        if (!$annoncement) {
            $this->redirect('Annoncement.home');
        }
        $this->render('annoncements.detail', ['annoncement' => $annoncement]);
    }
}
