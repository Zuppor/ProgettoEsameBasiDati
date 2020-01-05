<?php
/**
 * Created by PhpStorm.
 * User: andrea
 * Date: 10/02/19
 * Time: 18.08
 */

include 'db_connect_login.php';
include 'functions.php';

start_secure_session();

if(isset($_POST['username'],$_POST['password'],$_POST['password2'],$_POST['level'])){
    if($_POST['level'] != '2') $society = null;
    else $society = $_POST['society'];

    echo "PASSWORDS:: ".$_POST['password']."<br>".$_POST['password2'];
    die();

    $result = register_new_user($_POST['username'],$_POST['password'],$_POST['password2'],$_POST['level'],$society,$db);

    if($result === true){
        //utente registrato con successo
        header('Location: ../frontend/login.php?success=Utente registrato correttamente. E\' possibile eseguire il login');
    }
    else{
        header('Location: ../frontend/register.php?error='.$result);
    }
}
else{
    header('Location: ../frontend/register.php?error=Parametri non corretti '.$_POST['username']." P1".$_POST['password']." P2".$_POST['password2']." ".$_POST['level']);
}
unset($_POST);