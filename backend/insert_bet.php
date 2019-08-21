<?php

include_once 'db_connect_partner.php';
include_once 'functions.php';

start_secure_session();

if(isset($_POST['matches'],$_POST['bet'],$_POST['sum'],$_POST['currency'])){
    //print_r2($_POST);//todo: scoprire come prendere valori da un select multiplo
    //die();
    //fixme: valore della scommessa è sempre 0
    $resource = pg_prepare($db,"","select func_insert_bet(row($1,$2,$3,$4,$5)) as result");
    $resource = pg_execute($db,"",array($_POST['matches'],$_SESSION['user_id'],$_POST['bet'],$_POST['sum'],$_POST['currency']));

    if($resource === false){
        $hdr = 'Location: ../frontend/home.php?error=Error: '.pg_last_error($resource);
        //header('Location: ../frontend/home.php?error=Error: '.pg_last_error($resource));
    }
    else{
        $arr = pg_fetch_array($resource,null,PGSQL_ASSOC);
        $hdr = "";

        switch ($arr['result']){
            case 0:
                $hdr = 'Location: ../frontend/home.php?success=Scommessa inserita nel database';
                break;
            case 5:
                //todo:  scoprire come prendere messaggio raise info
                $hdr = 'Location: ../frontend/home.php?error=Scommessa già presente';
                break;
            default:
                $hdr = 'Location: ../frontend/home.php?error='.$arr['result'];
        }
        header($hdr);
    }
}
else{
    $hdr = 'Location: ../frontend/home.php?error=Parametri richiesti';
    //header('Location: ../frontend/home.php?error=Parametri richiesti');
}
header($hdr);