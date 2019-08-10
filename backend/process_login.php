<?php
/**
 * Created by PhpStorm.
 * User: andrea
 * Date: 10/02/19
 * Time: 17.57
 */

include 'db_connect_login.php';
include 'functions.php';

start_secure_session();

if(isset($_POST['username'],$_POST['password'])){
    $result = login($_POST['username'],$_POST['password'],$db);
    if($result === true){
        //login riuscito
        header('Location: ../frontend/home.php');
    }
    else{
        //login fallito
        header('Location: ../frontend/login.php?error='.$result);
    }
}
else{
    //le richieste corrette non sono state inviate a questa pagina dal metofo post
    echo 'Invalid request';
}