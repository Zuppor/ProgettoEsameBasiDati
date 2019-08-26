<?php

include_once 'db_connect_admin.php';
include_once 'functions.php';

start_secure_session();

$errors = "";

if(isset($_POST['submit'])){
    switch ($_POST['submit']){
        case "Insert":
            if(isset($_POST['team_l'],$_POST['team_s'])){
                $resource = pg_prepare($db,"","select func_insert_team(row((select max(id)+1 from team),$1,$2)) as result");
                $resource = pg_execute($db,"",array($_POST['team_s'],$_POST['team_l']));

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
            if(isset($_POST['teams'])){
                $resource = pg_prepare($db,"","select func_delete_team($1) as result");
                foreach ($_POST['teams'] as $team){
                    $resource = pg_execute($db,"",array($team));

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
            if(isset($_POST['team_l'],$_POST['team_s'],$_POST['team'])){

                $team_id = substr($_POST['team'],0,strpos($_POST['team'],";"));

                $resource = pg_prepare($db,"","select func_update_team(row($1,$2,$3)) as result");
                $resource = pg_execute($db,"",array($team_id,$_POST['team_s'],$_POST['team_l']));

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
                $errors = "Parametri richiesti";
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