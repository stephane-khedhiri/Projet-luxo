<?php

namespace Core;

class Routes
{
    private $routes = [];


    public function __construct($file){

        $this->routes = require ($file);
    }

    public function get($method){
        return $this->routes[$method];
    }


}