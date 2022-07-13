<?php

use App\App;


define('ROOT', dirname(__DIR__.DIRECTORY_SEPARATOR.'Luxo'));


require ROOT . '/app/App.php';

App::load();

App::getInstance()->addvisitor();
$route = App::getInstance()->getRouter($_GET['url']);
$route->run();


/*
if(isset($_GET['url'])){
    $action = $_GET['url'];
}else{
    $action = 'home';
}

$action = explode('.', $action);




if($action[0] == 'admins' || $action[0] == 'users'){
    if(empty($action[2])){
      App::getInstance()->getControllers()->notFound();
    }
    $controller = '\App\Controllers\\'.$action[0].'\\'. ucfirst($action[1]).'Controller';
if(method_exists($controller, $action[2])){
    $method = $action[2];
}else{
    App::getInstance()->getControllers()->notFound();
}

}else{
    $dir =scandir(ROOT.DIRECTORY_SEPARATOR.'app/Controllers');
    if(!in_array(ucfirst($action[0]).'Controller.php', $dir)){
        App::getInstance()->getControllers()->notFound();
    }
    $controller = '\App\Controllers\\'.ucfirst($action[0]).'Controller';
    if(method_exists($controller, $action[1])){
        $method = $action[1];
    }else{
        App::getInstance()->getControllers()->notFound();
    }

}

$controller = new $controller();

$controller->$method();
*/
