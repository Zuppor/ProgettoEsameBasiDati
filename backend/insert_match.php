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

//ini_set("auto_detect_line_endings", true);

//controlla se ci sono errori
if($_FILES['csv']['error'] > 0){
    die( 'Error code: '.$_FILES['csv']['error'].'<br>');
}

    /*$name = $_FILES['csv']['name'];
    $ext = strtolower(end(explode('.',$_FILES['csv']['name'])));
    $type = $_FILES['csv']['type'];*/
    $tmpName = $_FILES['csv']['tmp_name'];

    //controlla se è un file csv
    //if(mime_content_type($_FILES['csv']) == 'csv'){
        if(($handle = fopen($tmpName,'r')) !== false){
            //per i file csv di grandi dimensioni, imposta limite di tempo infinito
            set_time_limit(0);

            $row = 0;

            $resource = pg_prepare($db,"insert_player","select func_insert_player(row($1,$2,$3,$4,$5)) as result");
            $resource = pg_prepare($db,"insert_participation","insert into participation (team_id, player_id) value ($1,$2)");

            while(($data = fgetcsv($handle,0,',')) !== false){

                if($row > 0){


                    $resource = pg_prepare($db,"","select id from country where name like $1 limit 1");
                    $resource = pg_execute($db,"",array($data[1]));

                    if(pg_num_rows($resource) === 1){
                        $arr = pg_fetch_row($resource,null,PGSQL_ASSOC);
                        $data[1] = $arr['id'];
                    }
                    else{
                        $resource = pg_prepare($db,"","select func_insert_country($1) as result");
                        if($resource === false)
                            echo "e ".pg_last_error($resource);
                        $resource = pg_execute($db,"",array($data[1]));

                        $arr = pg_fetch_row($resource,null,PGSQL_ASSOC);

                        if($arr['result'] >= 0){
                            $data[1] = $arr['result'];
                        }
                        else{
                            die("Error inserting country ".$data[1]." | error code: ".$arr['result']);
                        }
                    }






                    $resource = pg_prepare($db,"","select id from league where name like $1 limit 1");
                    $resource = pg_execute($db,"",array($data[2]));

                    if(pg_num_rows($resource) === 1){
                        $arr = pg_fetch_row($resource,null,PGSQL_ASSOC);
                        $data[2] = $arr['id'];
                    }
                    else{
                        //die("<br>non ho trovato la league: ".$data[2]);
                        $resource = pg_prepare($db,"","select func_insert_league($1) as result");
                        $resource = pg_execute($db,"",array($data[2]));

                        $arr = pg_fetch_row($resource,null,PGSQL_ASSOC);
                        if($arr['result'] >= 0){
                            $data[2] = $arr['result'];
                        }
                        else{
                            die("Error inserting league ".$data[2]." | error code: ".$arr['result']);
                        }
                    }






                    //metti anno season in data[3]
                    $arr = explode("/",$data[3],2);
                    $data[3] = $arr[0];






                    //ottieni informazioni sulla squadra home

                    $resource = pg_prepare($db,"","select func_insert_team($1,$2,$3) as result");

                    if($resource === false){
                        die("falsea pg_prepare: ".pg_last_error());
                    }

                    $resource = pg_execute($db,"",array($data[6],$data[8],$data[7]));

                    $arr = pg_fetch_row($resource,null,PGSQL_ASSOC);


                    if($arr['result'] !== '0' && $arr['result'] !== '3'){
                        print_r($arr);
                        die("Non sono riuscito a mettere il team nel db: ".$data[7]." Error postgres: ".pg_last_error($resource));
                    }

                    $resource = pg_execute($db,"",array($data[9],$data[11],$data[10]));

                    $arr = pg_fetch_array($resource,null,PGSQL_ASSOC);

                    if($arr['result'] !== '0' && $arr['result'] !== '3'){
                        print_r($arr);
                        die("Non sono riuscito a mettere il team nel db: ".$data[10]." Error code: ".$arr['result']);
                    }




                    //inserisci giocatori
                    $players = array();
                    $curr_player = 14;
                    for($i = 0;$i < 22;$i++){
                        //echo "Number of columns: ".count($data);
                        echo "Inserting player: ".$data[$curr_player]." ".$data[$curr_player+1]." ".$data[$curr_player+2]." ".$data[$curr_player+3]." ".$data[$curr_player+4]."<br>";
                        if($data[$i] != ""){

                            pg_execute($db,"insert_player",array($data[$curr_player],$data[$curr_player+1],$data[$curr_player+2],$data[$curr_player+3],$data[$curr_player+4]));
                            if($resource === false)
                                die("error inserting player: ".pg_last_error($resource));
                            $arr = pg_fetch_row($resource,null,PGSQL_ASSOC);
                            if($arr['result'] < 0 && $arr['result']>-3){
                                die("errore inserimento giocatore: ".$arr['result']);
                            }

                            if($i<=10){//todo: aggiornare tabella participation
                                pg_execute($db,"insert_participation",)
                            }
                            else{
                                pg_execute($db,"insert_participation",)
                            }
                        }
                        else{
                            echo "Player id null. Skipping<br>";
                        }
                        $curr_player+=5;
                    }
                    //die();




                    //id, home_team_id, away_team_id, season, stage, date, a_team_goal, h_team_goal, league_id, country_id, operator_id

                    //echo "inserimento match...<br>";

                    $sql = "select func_insert_match(row($1,$2,$3,$4,$5,$6::timestamp,$7,$8,$9,$10,$11)) as result";
                    $resource = pg_prepare($db,"",$sql);
                    if($resource === false)
                        die("eee".pg_last_error($resource));
                    $values = array($data[0],$data[6],$data[9],$data[3],$data[4],$data[5],$data[13],$data[12],$data[2],$data[1],$_SESSION['user_id']);
                    //print_r($values);
                    $resource = pg_execute($db,"",$values);

                    $arr = pg_fetch_row($resource,null,PGSQL_ASSOC);



                    if($arr['result'] !== '0' && $arr['result'] !== '3'){
                        die( '<br>Error inserting row number '.$row.'<br> cause: '.pg_last_error($resource).'<br>code: '.$arr['result']);
                    }

                    //pg_close($db);
                    //die("Done");
                }
                $row++;
            }

            fclose($handle);

            //ini_set("auto_detect_line_endings", false);

            header('../frontend/home.php?match_upload_msg=Database aggiornato con successo');
        }
    /*}
    else{
        echo 'Incorrect file type: '.mime_content_type($_FILES['csv']);
    }*/