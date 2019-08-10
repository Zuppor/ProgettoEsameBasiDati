<?php
/**
 * Created by PhpStorm.
 * User: andrea
 * Date: 17/02/19
 * Time: 16.42
 */


include 'db_connect_admin.php';
include 'functions.php';

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

    $query = "select func_insert_player_attributes(row($1";
    for($i = 2;$i<=40;$i++){
        $query = $query.",$".$i."::percentage";
    }
    $query = $query.")) as result";
    //die($query);

    $resource = pg_prepare($db,"",$query);


    while (($data = fgetcsv($handle, 0, ',')) !== false) {

        if ($row > 0) {
            echo "inserting row ".$row."...<br>";
            //print_r($data);

            $resource = pg_execute($db,"",$data);
            $arr = pg_fetch_row($resource,null,PGSQL_ASSOC);

            if($arr['result'] !== '0'){
                echo "error inserting row ".$row. " result code: ".$arr['result']."<br>";
            }

            if(!$resource){
                echo "error inserting row ".$row;
            }
        }
        $row++;
    }
}
else{
    die("Caricamento csv fallito");
}

echo "Done";
