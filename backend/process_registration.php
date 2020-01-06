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

if(isset($_POST['username'],$_POST['password'],$_POST['password2'],$_POST['level'],$_POST['society'])){
    if($_POST['level'] != '2')
        $society = null;
    else
        if($_POST['society'] == 'null')
            header('Location: ../frontend/register.php?error=Nessuna società di scommesse specificata');
        else
            $society = $_POST['society'];

    if($_POST['username'] == ''){
        header('Location: ../frontend/register.php?error=Nome utente non specificato');
    }

    die( "PASSWORDS:: ".$_POST['password']."<br>".$_POST['password2']."<br>USERNAME ".$_POST['username']."<br>LEVEL ".$_POST['level']);

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