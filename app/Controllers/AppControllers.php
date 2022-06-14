<?php

namespace App\Controllers;


use App\App;

use Core\Controller;

class AppControllers extends Controller
{
    /**
     * @var string
     */
    protected $template = 'layout';

    public function __construct()
    {
        $this->viewPath = ROOT . '/app/Views/';
        $this->uploadPath = ROOT . '/public/';
    }

    /**
     * get model and parameter argument new priority example loadModels(test) create $test->fint()
     * @param string $model_name
     */
    protected function loadModel(string $model_name)
    {
         $this->$model_name = App::getInstance()->getTable($model_name);
    }
}