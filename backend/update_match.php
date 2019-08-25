<?php

include_once 'db_connect_operator.php';
include_once 'functions.php';

start_secure_session();


if(isset($_POST['match'],$_POST['country'],$_POST['league'],$_POST['season'],$_POST['stage'],$_POST['date'],$_POST['team_h'],$_POST['team_a'],$_POST['h_goal'],$_POST['a_goal'])) {
    //deve aggiornare
    $_POST['match'] = substr($_POST['match'],0,strpos($_POST['match']," "));
    $resource = pg_prepare($db, "", "select func_update_match(row($1,$2,$3,$4,$5,$6::timestamp,$7,$8,$9,$10,$11)) as result");
    $resource = pg_execute($db,"",array($_POST['match'],$_POST['team_h'],$_POST['team_a'],$_POST['season'],$_POST['stage'],$_POST['date'],$_POST['a_goal'],$_POST['h_goal'],$_POST['league'],$_POST['country'],$_SESSION['user_id']));

    if ($resource === false) {
        $error = "Error: " . pg_last_error($resource);
    }
    else {
        $arr = pg_fetch_array($resource, null, PGSQL_ASSOC);

        switch($arr['result']){
            case 0:
                $error = "";
                break;
            case 3:
                $error = "Match già presente";
                break;
            default:
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