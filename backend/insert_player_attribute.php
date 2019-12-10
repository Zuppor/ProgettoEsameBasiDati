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

//if($handle = get_csv_handler($_FILES) !== false){
    //per i file csv di grandi dimensioni, imposta limite di tempo infinito
    set_time_limit(0);

    $row = 0;

    $resource = pg_prepare($db,"","select func_insert_player_attributes(row($1,$2::timestamp,$3)) as result");
    if($resource === false)
        die("e ".pg_last_error($resource));

    $field_name = array();

    while (($data = fgetcsv($handle, 0, ',')) !== false) {
        if ($row == 0){
            $field_name = $data;
        }
        else{

            for($i = 2;$i<sizeof($data);$i++){
                if(!is_numeric($data[$i])) {
                    $data[$i] = fetch_rate($data[$i]);
                }

                if($data[$i] != null){
                    $resource = pg_execute($db,"",array($data[0],$data[1],$field_name[$i],$data[$i]));

                    if($resource === false)
                        die(" ee ".pg_last_error($resource));
                    $arr = pg_fetch_row($resource,null,PGSQL_ASSOC);

                    if($arr['result'] === '5'){
                        echo "error inserting row ".$row.": duplicated entry (".$data[0].",".$data[1]."). Skipping<br>";
                    }
                    else if($arr['result'] !== '0' && $arr['result'] !== '5'){
                        echo "error inserting row ".$row. " result code: ".$arr['result']."<br>";
                    }
                }
            }

            //echo "inserting row ".$row."...<br>";
        }
        $row++;
    }

    fclose($handle);

    header('Location: ../frontend/home.php?attribute_upload_msg=Database aggiornato con successo');
}
else{
    header('Location: ../frontend/home.php?attribute_upload_msg=Caricamento csv fallito');
}

echo "Done";
