<?php

spl_autoload_register(function ($class) {
    if(file_exists('../classes/' . $class . '.php')){
        require_once '../classes/' . $class . '.php';
    }elseif(file_exists('../models/' . $class . '.php')){
        require_once '../models/' . $class . '.php';
    }
});