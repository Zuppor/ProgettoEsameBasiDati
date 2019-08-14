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
        if (!in_array($rate, array('l', 'n', 'm', 'h')))
            return null;
        else
            return $rate;
    }
}

function fetch_percentage($percentage){
    return is_numeric($percentage) ? $percentage : null;
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

    $query = "select func_insert_player_attributes(row($1,$2::timestamp,$3::percentage,$4::percentage,$5,$6,$7";
    for($i = 8;$i<=40;$i++){
        $query = $query.",$".$i."::percentage";
    }
    $query = $query.")) as result";
    //die($query);

    $resource = pg_prepare($db,"",$query);
    if($resource === false)
        die("e ".pg_last_error($resource));


    while (($data = fgetcsv($handle, 0, ',')) !== false) {

        if ($row > 0) {

            //print_r($data);



            $data[2] = fetch_percentage($data[2]);
            $data[3] = fetch_percentage($data[3]);

            $data[4] = strtolower(substr($data[4],0,1));
            $data[5] = fetch_rate($data[5]);
            $data[6] = fetch_rate($data[6]);

            for($i = 7;$i<40;$i++){
                $data[$i] = fetch_percentage($data[$i]);
            }


            echo "inserting row ".$row."...<br>";
            //print_r2($data);
            //die();

            $resource = pg_execute($db,"",$data);

            if($resource === false)
                die(" ee ".pg_last_error($resource));
            $arr = pg_fetch_row($resource,null,PGSQL_ASSOC);

            if($arr['result'] === '5'){
                echo "error inserting row ".$row.": duplicated entry (".$data[0].",".$data[1]."). Skipping<br>";
            }
            else if($arr['result'] !== '0' && $arr['result'] !== '5'){
                echo "error inserting row ".$row. " result code: ".$arr['result']."<br>";
            }
            //die("Inserted 1 row");
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
