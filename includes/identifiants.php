<?php

try {

    $db_config = array();
    $db_config['SGBD']  = 'mysql';
    $db_config['HOST']  = 'localhost';
    $db_config['DB_NAME']   = '';
    $db_config['USER']  = 'root';
    $db_config['PASSWORD']  = '';
    $db_config['OPTIONS']   = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
    );

    $db = new PDO($db_config['SGBD'] .':host='. $db_config['HOST'] .';dbname='. $db_config['DB_NAME'],
    $db_config['USER'],
    $db_config['PASSWORD'],
    $db_config['OPTIONS']);
    unset($db_config);

}

catch(Exception $e) {

    trigger_error($e->getMessage(), E_USER_ERROR);

}