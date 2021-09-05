<?php
spl_autoload_register(function($class)
{
    $path = APP_DIR.DS.'classes'.DS;
    
    if(file_exists($path. $class . '.php')){
        require_once($path . $class . '.php');
    }
});