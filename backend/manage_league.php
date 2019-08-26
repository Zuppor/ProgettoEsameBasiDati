<?php

include_once 'db_connect_admin.php';
include_once 'functions.php';

start_secure_session();

$errors = "";

if(isset($_POST['submit'])){
    switch ($_POST['submit']){
        case "Insert":
            if(isset($_POST['league'])){
                $resource = pg_prepare($db,"","select func_insert_league($1) as result");
                $resource = pg_execute($db,"",array($_POST['league']));

                if(!$resource){
                    $errors = "Error: ".pg_last_error($resource);
                }
                else{
                    $arr = pg_fetch_array($resource,null,PGSQL_ASSOC);

                    if($arr['result'] < 0){
                        $errors = "Error. Code: ".$arr['result'];
                    }
                }
            }
            else{
                $error = "Parametri richiesti";
            }
            break;
        case "Delete":
            if(isset($_POST['leagues'])){
                $resource = pg_prepare($db,"","select func_delete_league($1) as result");
                foreach ($_POST['leagues'] as $league){
                    $resource = pg_execute($db,"",array($league));

                    if(!$resource){
                        $errors = $errors."Error: ".pg_last_error($resource)."<br>";
                    }
                    else{
                        $arr = pg_fetch_array($resource,null,PGSQL_ASSOC);

                        if($arr['result'] !== '0'){
                            $errors = $errors."Error. Code: ".$arr['result']."<br>";
                        }
                    }
                }
            }
            else{
                $error = "Parametri richiesti";
            }
            break;
        case "Modify":
            if(isset($_POST['league_new_name'],$_POST['league'])){
                $_POST['league'] = substr($_POST['league'],0,strpos($_POST['league']," "));

                $resource = pg_prepare($db,"","select func_update_league($1,$2) as result");
                $resource = pg_execute($db,"",array($_POST['league'],$_POST['league_new_name']));

                if(!$resource){
                    $errors = "Error: ".pg_last_error($resource);
                }
                else{
                    $arr = pg_fetch_array($resource,null,PGSQL_ASSOC);

                    if($arr['result'] !== '0'){
                        $errors = "Error. Code: ".$arr['result'];
                    }
                }
            }
            else{
                $error = "Parametri richiesti";
            }
            break;
        default:
            $errors = "Invalid submit";
    }
}
else{
    $errors = "How did you get here?";
}

if($errors === ""){
    $hdr = 'Location: ../frontend/home.php?success=Operazione completata';
}
else{
    $hdr = 'Location: ../frontend/home.php?error='.$errors;
}

header($hdr);