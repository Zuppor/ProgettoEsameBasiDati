<?php

include_once 'db_connect_operator.php';
include_once 'functions.php';

start_secure_session();

$errors = "";
$errorNumber = count($_POST['matches']);

if(isset($_POST['matches'])){
    //deve eliminare
    $resource = pg_prepare($db, "", "select func_delete_match($1,$2) as result");
    foreach ($_POST['matches'] as $match) {
        $resource = pg_execute($db, "", array($match,$_SESSION['user_id']));

        if ($resource === false) {
            $errors = $errors . "Error: " . pg_last_error($resource) . "<br>";
        } else {
            $arr = pg_fetch_array($resource, null, PGSQL_ASSOC);

            switch ($arr['result']) {
                case 0:
                    $errorNumber--;
                    break;
                default:
                    $errors = $errors . "Error. Code: " . $arr['result'] . "<br>";
            }
        }
    }
}
else{
    $errors = $errors."Error: parametri richiesti<br>";
}

$errorNumber = count($_POST['bets']) - $errorNumber;

if($errors != ""){
    $hdr = "Location: ../frontend/home.php?error=".$errors."<br>".$errorNumber." match(es) deleted correctly";
}
else{
    $hdr = 'Location: ../frontend/home.php?success=Matches eliminati';
}

header($hdr);