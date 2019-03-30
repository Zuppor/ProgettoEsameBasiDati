<?php
/**
 * Created by PhpStorm.
 * User: andrea
 * Date: 17/02/19
 * Time: 14.55
 */

include 'db_connect_admin.php';
include 'functions.php';

start_secure_session();

//controlla se ci sono errori
if($_FILES['csv']['error'] > 0){
    die( 'Error code: '.$_FILES['csv']['error'].'<br>');
}
    $name = $_FILES['csv']['name'];
    $ext = strtolower(end(explode('.',$_FILES['csv']['name'])));
    $type = $_FILES['csv']['type'];
    $tmpName = $_FILES['csv']['tmp_name'];

    //controlla se è un file csv
    //if(mime_content_type($_FILES['csv']) == 'csv'){
        if(($handle = fopen($tmpName,'r')) !== false){
            //per i file csv di grandi dimensioni, imposta limite di tempo infinito
            set_time_limit(0);

            $row = 0;

            while(($data = fgetcsv($handle,0,',')) !== false){

                if($row > 0){


                    $resource = pg_prepare($db,"cmd","select iso from country where name like $1 limit 1");
                    $resource = pg_execute($db,"cmd",array($data[1]));

                    if(pg_num_rows($resource) === 1){
                        $arr = pg_fetch_array($resource,null,PGSQL_ASSOC);
                        $data[1] = $arr['iso'];
                    }
                    else{
                        print_r($data);
                        die("ID di questo stato non presente: ".$data[1].' error message: '.pg_result_error($resource));
                    }

                    pg_free_result($resource);




                    $resource = pg_prepare($db,"cmd","select id from league where name like $1 limit 1");
                    echo "select id from league where name like ".$data[2]." limit 1";
                    $resource = pg_execute($db,"cmd",array($data[2]));

                    if(pg_num_rows($resource) === 1){
                        $arr = pg_fetch_array($resource,null,PGSQL_ASSOC);
                        $data[2] = $arr['id'];
                    }
                    else{
                        $resource = pg_prepare($db,"cmd","insert into league (name) values ($1) returning id");
                        $resource = pg_execute($db,"cmd",array($data[2]));

                        if(pg_affected_rows($resource) <= 0){
                            print_r($data);
                            die("Non sono riuscito ad inserire la league: ".$data[2]);
                        }

                        $arr = pg_fetch_array($resource,null,PGSQL_ASSOC);
                        $data[2] = $arr['id'];
                    }

                    pg_free_result($resource);




                    $arr = explode("/",$data[3],2);
                    $data[3] = $arr[0];


/*
                    $resource = pg_prepare($db,"cmd","select id from team where id = $1");
                    $resource = pg_execute($db,"cmd",array($data[6]));

                    if(pg_num_rows($resource) == 0){
                        $resource = pg_prepare($db,"cmd","insert into team (id,long_name,short_name) values ($1,$2,$3)");
                        $resource = pg_execute($db,"cmd",array($data[6],$data[7],$data[8]));

                        if(!$resource){
                            print_r($data);
                            die("Non sono riuscito a mettere il team nel db: ".$data[7]." Error message: ".pg_result_error($resource));
                        }
                    }*/

                    $resource = pg_prepare($db,"cmd","insert into team (id,long_name,short_name) values ($1,$2,$3)");
                    $resource = pg_execute($db,"cmd",array($data[6],$data[7],$data[8]));


                    pg_free_result($resource);


/*
                    $resource = pg_prepare($db,"cmd","select id from team where id = $1");
                    $resource = pg_execute($db,"cmd",array($data[9]));

                    if(pg_num_rows($resource) == 0){
                        $resource = pg_prepare($db,"cmd","insert into team (id,long_name,short_name) values ($1,$2,$3)");
                        $resource = pg_execute($db,"cmd",array($data[9],$data[10],$data[11]));

                        if(!$resource){
                            print_r($data);
                            die("Non sono riuscito a mettere il team nel db: ".$data[10]." Error message: ".pg_result_error($resource));
                        }
                    }*/
                    $resource = pg_prepare($db,"cmd","insert into team (id,long_name,short_name) values ($1,$2,$3)");
                    $resource = pg_execute($db,"cmd",array($data[9],$data[10],$data[11]));

                    pg_free_result($resource);


                    $sql = "select insert_match($1,$2,$3,$4,$5,$6,$7,$8,$9,$10)";
                    $resource = pg_prepare($db,"cmd",$sql);
                    $values = array($data[1],$data[2],$data[3],$data[4],$data[5],$data[6],$data[9],$data[12],$data[13],$_SESSION['user_id']);
                    $resource = pg_execute($db,"cmd",$values);

                    if(pg_affected_rows($resource) == 0){
                        echo '<br>Error inserting row number '.$row.'<br>';
                        print_r($data);
                    }
                    pg_free_result($resource);
                }
                $row++;
            }

            fclose($handle);
        }
    /*}
    else{
        echo 'Incorrect file type: '.mime_content_type($_FILES['csv']);
    }*/