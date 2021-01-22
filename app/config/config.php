<?php
    //DB Params
    define('DB_HOST',$_SERVER['SERVER_NAME']);
    define('DB_USER','root');
    define('DB_PASS','tiger');
    define('DB_NAME','testSQL');

    //APP ROOT
    define('APPROOT',dirname(dirname(__FILE__)));
    //URL ROOT
    define('URLROOT','http://'.$_SERVER['SERVER_NAME'].':8088/camagru');
    //SITE NAME
    define('SITENAME','Camagru');