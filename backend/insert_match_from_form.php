<?php

include_once 'functions.php';
include_once 'db_connect_operator.php';

$msg = "";

if(isset($_POST['country'],$_POST['season'],$_POST['league'],$_POST['stage'],$_POST['date'],$_POST['team_h'],$_POST['team_a'],$_POST['h_goal'],$_POST['a_goal'])){
    $resource = pg_prepare($db,"","insert into public.match(country_id,season,league_id,stage,date,home_team_id,away_team_id,h_team_goal,a_team_goal)
    values($1,$2,$3,$4,$5,$6,$7,$8,$9)");
    pg_execute($db,"",array($_POST['country'],$_POST['season'],$_POST['league'],$_POST['stage'],$_POST['date'],$_POST['team_h'],$_POST['team_a'],$_POST['h_goal'],$_POST['a_goal']));

    if($resource === false){
        $msg = "Errore durante il caricamento del match: '".pg_last_error($resource);
        Header('Location: ../frontend/home.php?error=Errore durante il caricamento del match: '.pg_last_error($resource));
    }
    else{
        Header('Location: ../frontend/home.php?success=Database aggiornato correttamente');
    }
}
Header('Location: ../frontend/home.php?error=Errore: alcuni campi non sono stati compilati');
