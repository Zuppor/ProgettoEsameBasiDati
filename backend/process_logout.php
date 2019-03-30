<?php
/**
 * Created by PhpStorm.
 * User: andrea
 * Date: 10/02/19
 * Time: 18.02
 */

include 'functions.php';

start_secure_session();

//elimina dati sessione
$_SESSION = array();

//recupera parametri sessione
$params = session_get_cookie_params();

//cancella cookie correnti
setcookie(session_name(),'',time() - 42000,$params["path"],$params["domain"],$params["secure"],$params["httponly"]);

//cancella sessione
session_destroy();

header("Location: ../index.php");