<?php
namespace App\Controllers\Users;

class AppControllers extends \App\Controllers\AppControllers

{
    public function __construct()
    {
        parent::__construct();
        if (!$_SESSION['Users']){
            $this->Forbidden();
        }
    }
}