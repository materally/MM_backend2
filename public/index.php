<?php

require_once '../app/init.php';

// DEBUG MODE 
if(DEBUG_MODE == "true"){
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

$app = new KM_App;