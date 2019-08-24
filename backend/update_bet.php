<?php

include_once 'db_connect_partner.php';
include_once 'functions.php';

start_secure_session();

$error = "";

if(isset($_POST['bet'],$_POST['match'],$_POST['new_bet'],$_POST['sum'],$_POST['currency'],$_POST['sum'])) {
    //deve aggiornare
    //m_id int,p_id int, b char, curr char(3), new_m_id int, new_b char, new_curr char(3), new_val numeric
    $resource = pg_prepare($db, "", "select func_update_bet($1,$2,$3,$4,$5,$6,$7,$8) as result");
        $oldbet = explode(" ", $_POST['bet']);
        $resource = pg_execute($db,"",array($oldbet[0],$_SESSION['user_id'],$oldbet[1],$oldbet[3],$_POST['match'],$_POST['new_bet'],$_POST['currency'],$_POST['sum']));

        if ($resource === false) {
            $errors = $errors . "Error: " . pg_last_error($resource) . "<br>";
        }
        else {
            $arr = pg_fetch_array($resource, null, PGSQL_ASSOC);

            if($arr['result'] !== '0'){
                $error = "Error. Code: " . $arr['result'];
            }
        }
}
else{
    $error = "Error: parametri richiesti";
}

if($error != ""){
    $hdr = "Location: ../frontend/home.php?error=".$error;
}
else{
    $hdr = 'Location: ../frontend/home.php?success=Scommessa aggiornata';
}

header($hdr);