<?php
namespace App;

use App\Models\AdminModel;
use App\Models\AnnoncementModel;
use App\Models\UserModel;
use App\Models\VisitorModel;
use Core\Config;
use Core\Database;
use Core\Router\Router;
use Core\Routes;


class App
{
    /** page title
     * @var string
     */
    public $title = 'luxo';

    /**
     * @var string
     */
    public $error;

    /**
     * @var string
     */
    public $success;

    /**
     * @var Database
     */
    private $db_instance;

    /**
     * @var App
     */
    private static $_instance;

    public $routes;

    /**
     *
     * @return App
     */
    public static function getInstance()
    {
        if (is_null(self::$_instance)){
            self::$_instance = new App();
        }
        return self::$_instance;
    }
    public function getRouter($url){
        if(is_null($this->routes)){
            $routes = new Routes(ROOT . '/config/routes.php');
            $router = new Router($url);

            foreach ($routes->get('GET') as $path => $controller){
                $router->get($path, $controller)
                    ->with('id', '[0-9]+')
                    ->with('slug', '[a-z\-0-9]+');
            }
            foreach ($routes->get('POST') as $path => $controller){

                $router->post($path, $controller);
            }
            $this->routes = $router;

        }
        return $this->routes;

    }
    /**
     * add visitors in database
     * @return void
     */
    public static function load()
    {
        session_start();

        require ROOT . '/app/Autoloader.php';
        \App\Autoloader::register();
        require ROOT . '/core/Autoloader.php';
        \Core\Autoloader::register();
    }

    public function addvisitor(){
        $visitor = $this->getTable('visitor');
        $visitor->init();
    }


    public function getTable($name)
    {
        $class_name = '\\App\\Models\\'. ucfirst($name).'Model';
        return new $class_name($this->getDb());
    }

    /**
     * @return Database
     */
    public function getDb(){
        $config = Config::getInstance(ROOT . '/config/config.php');
        if(is_null($this->db_instance)){
            $this->db_instance = new Database($config->get('db_name'), $config->get('db_user'),$config->get('db_pass'), $config->get('db_host'));
        }
        return $this->db_instance;
    }

    /**
     * @return Controllers\AppControllers
     */
    public function getControllers(){
        $class_name = '\\App\\Controllers\\AppControllers';
        return new $class_name();
    }

    /**
     * @return bool
     */
    public function is_user(){
        if(isset($_SESSION['Users'])){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @return bool
     */
    public function is_admin(){
        if(isset($_SESSION['admins'])){
            return true;
        }else{
            return false;
        }
    }

}