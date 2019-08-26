<?php

include_once 'db_connect_admin.php';
include_once 'functions.php';

start_secure_session();

$errors = "";

if(isset($_POST['submit'])){
    switch ($_POST['submit']){
        case "Insert":
            if(isset($_POST['country'])){
                $resource = pg_prepare($db,"","select func_insert_country($1) as result");
                $resource = pg_execute($db,"",array($_POST['country']));

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
            if(isset($_POST['countries'])){
                $resource = pg_prepare($db,"","select func_delete_country($1) as result");
                foreach ($_POST['countries'] as $country){
                    $resource = pg_execute($db,"",array($country));

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
            if(isset($_POST['country_new_name'],$_POST['country'])){
                $_POST['country'] = substr($_POST['country'],0,strpos($_POST['country']," "));

                $resource = pg_prepare($db,"","select func_update_country(row($1,$2)) as result");
                $resource = pg_execute($db,"",array($_POST['country'],$_POST['country_new_name']));

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