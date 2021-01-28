<?php
    // Load Libraries
    require_once 'config/config.php';

    require_once 'Helpers/session_helper.php';

    // Autoload core libraries
    spl_autoload_register(function($className){
        require_once 'libraries/' .$className .'.php';
    });