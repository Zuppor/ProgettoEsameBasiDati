<?php

include_once 'db_connect_operator.php';
include_once 'functions.php';

start_secure_session();



if(isset($_POST['country'],$_POST['season'],$_POST['league'],$_POST['stage'],$_POST['date'],$_POST['team_h'],$_POST['team_a'],$_POST['h_goal'],$_POST['a_goal'])){
    $resource = pg_prepare($db,"","select func_insert_match(row((select max(id)+1 from public.match),$1,$2,$3,$4,$5::timestamp ,$6,$7,$8,$9,$10)) as result");
    $resource = pg_execute($db,"",array($_POST['team_h'],$_POST['team_a'],$_POST['season'],$_POST['stage'],$_POST['date'],$_POST['a_goal'],$_POST['h_goal'],$_POST['league'],$_POST['country'],$_SESSION['user_id']));

    if($resource === false){
        $error = "Errore durante il caricamento del match: '".pg_last_error($resource);
    }
    else{
        $arr = pg_fetch_array($resource,null,PGSQL_ASSOC);

        switch($arr['result']){
            case 0:
                $error = "";
                break;
            case 3:
                $error = 'Error: match already in the database';
                break;
            default:
                $error = 'Error. Code: '.$arr['result'];
        }
    }
}
else{
    $error = 'Alcuni campi non sono stati compilati';
}

if($error == ""){
    $hdr = 'Location: ../frontend/home.php?success=Database aggiornato correttamente';
}
else{
    $hdr = 'Location: ../frontend/home.php?error='.$error;
}

Header($hdr);