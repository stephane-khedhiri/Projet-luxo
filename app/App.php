<?php
namespace App;

use App\Models\AdminModel;
use App\Models\AnnoncementModel;
use App\Models\UserModel;
use App\Models\VisitorModel;
use Core\Config;
use Core\Database;



class App
{
    /** page title
     * @var string
     */
    public $title = 'luxo';


    /**
     * @var Database
     */
    private $db_instance;

    /**
     * @var App
     */
    private static $_instance;

    /**
     *
     * @return App
     */
    public static function getInstance()
    {
        if (is_null(self::$_instance)){
            self::$_instance =new App();
        }
        return self::$_instance;
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