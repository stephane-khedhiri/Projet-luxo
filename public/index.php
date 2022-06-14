<?php

use App\App;

define('ROOT', dirname(__DIR__));

require ROOT.'/app/App.php';

App::load();

App::getInstance()->addvisitor();
if(isset($_GET['action'])){
    $action = $_GET['action'];
}else{
    $action = 'Annoncement.home';
}

$action = explode('.', $action);


if($action[0] == 'admins' || $action[0] == 'users'){
    if(empty($action[2])){
      App::getInstance()->getControllers()->notFound();
    }
    $controller = '\App\Controllers\\'.$action[0].'\\'. ucfirst($action[1]).'Controller';

    $method = $action[2];

}else{
    if(empty($action[1])){
        App::getInstance()->getControllers()->notFound();
    }
        $controller = '\App\Controllers\\'.ucfirst($action[0]).'Controller';
        $method = $action[1];

}

$controller = new $controller();

$controller->$method();

