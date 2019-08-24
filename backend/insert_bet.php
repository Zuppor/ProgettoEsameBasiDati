<?php

include_once 'db_connect_partner.php';
include_once 'functions.php';

function addError($e,$err){
    $e = $e.$err."<br>";
    return $e;
}

start_secure_session();

$errors = "";
$errNumber = count($_POST['matches']);

if(isset($_POST['matches'],$_POST['bet'],$_POST['sum'],$_POST['currency'])){

    $resource = pg_prepare($db,"","select func_insert_bet(row($1,$2,$3,$4,$5)) as result");

    foreach ($_POST['matches'] as $match){
        $resource = pg_execute($db,"",array($match,$_SESSION['user_id'],$_POST['bet'],$_POST['sum'],$_POST['currency']));

        if($resource === false){
            $errors = addError($errors,"Error: ".pg_last_error($resource));
        }
        else{
            $arr = pg_fetch_array($resource,null,PGSQL_ASSOC);

            switch ($arr['result']){
                case 0:
                    $errNumber--;
                    break;
                case 5:
                    $errors = addError($errors,"Error: Scommessa già presente");
                    break;
                default:
                    $errors = addError($errors,"Error: code ".$arr['result']);
            }
        }
    }
}
else{
    $errors = addError($errors,"Error: Scommessa già presente");
}

$errNumber = count($_POST['matches'])-$errNumber;

if($errors != ""){
    $hdr = "Location: ../frontend/home.php?error=".$errors."<br>".$errNumber." bet(s) inserted correctly";
}
else{
    $hdr = 'Location: ../frontend/home.php?success=Scommessa/e inserita nel database';
}

header($hdr);