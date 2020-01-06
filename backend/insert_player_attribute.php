<?php
/**
 * Created by PhpStorm.
 * User: andrea
 * Date: 17/02/19
 * Time: 16.42
 */


include 'db_connect_admin.php';
include 'functions.php';

function fetch_rate($rate){
    $rate = strtolower(substr($rate,0,1));

    if($rate === false)
        return null;
    else {
        switch ($rate){
            case 'l':
                return 0;
            case 'n':
            case 'r':
                return 1;
            case 'm':
                return 2;
            case 'h':
                return 3;
            default:
                return null;
        }
    }
}

start_secure_session();

//ini_set("auto_detect_line_endings",true);
if($_FILES['csv']['error'] > 0){
    die( 'Error code: '.$_FILES['csv']['error'].'<br>');
}
$tmpName = $_FILES['csv']['tmp_name'];

if(($handle = fopen($tmpName,"r")) !== false) {

    //per i file csv di grandi dimensioni, imposta limite di tempo infinito
    set_time_limit(0);

    $row = 0;

    $resource = pg_prepare($db,"","select func_insert_player_attribute(row($1,$2::timestamp,$3,$4)) as result");
    if($resource === false)
        die("Error pg_prepare: ".pg_last_error($resource));

    $field_name = array();

    while (($data = fgetcsv($handle, 0, ',')) !== false) {
        if ($row == 0){
            $field_name = $data;//extract fields names
        }
        else{

            for($i = 2;$i<sizeof($data);$i++){
                if(!is_numeric($data[$i])) {
                    $data[$i] = fetch_rate($data[$i]);
                }

                if($data[$i] !== null){
                    $resource = pg_execute($db,"",array($data[0],$data[1],$field_name[$i],$data[$i]));

                    if($resource === false)
                        die("Error pg_execute: ".pg_last_error($resource));
                    $arr = pg_fetch_row($resource,null,PGSQL_ASSOC);

                    switch($arr['result']){
                        case 0:
                            //echo "All nominal<br>";
                            break;
                        case '1':
                            echo "Error executing query<br>";
                            break;
                        case '2':
                            echo "Not null violation<br>";
                            break;
                        case '3':
                            echo "Foreign key violation<br>";
                            break;
                        case '4':
                            echo "Unique violation<br>";
                            break;
                        default:
                            echo "Unknown code: ".$arr['result']."<br>";
                    }
                }
            }
        }
        $row++;
    }

    fclose($handle);

    header('Location: ../frontend/home.php?success=Database aggiornato con successo');
}
else{
    header('Location: ../frontend/home.php?error=Caricamento csv fallito');
}

echo "Done";
