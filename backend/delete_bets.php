<?php

include_once 'db_connect_partner.php';
include_once 'functions.php';

start_secure_session();

$errors = "";
$errorNumber = count($_POST['bets']);

if(isset($_POST['bets'])){
    //deve eliminare
        $resource = pg_prepare($db, "", "select func_delete_bet($1,$2,$3,$4) as result");
        foreach ($_POST['bets'] as $bet) {
            $data = explode(" ", $bet);
            $resource = pg_execute($db, "", array($data[0],$_SESSION['user_id'],$data[1],$data[2]));

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
    $hdr = "Location: ../frontend/home.php?error=".$errors."<br>".$errorNumber." bet(s) deleted correctly";
}
else{
    $hdr = 'Location: ../frontend/home.php?success=Scommesse eliminate';
}

header($hdr);