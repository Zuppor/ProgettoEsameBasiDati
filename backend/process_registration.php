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
            throw_error("Società di scommesse non specificata");
            //header('Location: ../frontend/register.php?error=Nessuna società di scommesse specificata');
        else
            $society = $_POST['society'];

    if($_POST['username'] == ''){
        throw_error("Nome utente non specificato");
    }
    if(strlen($_POST['username'] > 100)){
        throw_error("Nome utente troppo lungo");
    }

    if($_POST['password'] == '')
        throw_error("Campo password vuoto");

    if($_POST['password2'] == '')
        throw_error("Ripetere la password");

    if(strcmp($_POST['password'],$_POST['password2']) !== 0)
        throw_error("Le password non coincidono");

    $result = register_new_user($_POST['username'],$_POST['password'],$_POST['level'],$society,$db);

    if($result === true){
        //utente registrato con successo
        header('Location: ../frontend/login.php?success=Utente registrato correttamente. E\' possibile eseguire il login');
    }
    else{
        throw_error($result);
    }
}
else{
    throw_error("Parametri non corretti. Ricontrollare il form");
    //header('Location: ../frontend/register.php?error=Parametri non corretti '.$_POST['username']." P1".$_POST['password']." P2".$_POST['password2']." ".$_POST['level']);
}
unset($_POST);

function throw_error($msg){
    header('Location: ../frontend/register.php?error='.$msg);
}