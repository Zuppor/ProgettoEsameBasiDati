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

            $resource = pg_prepare($db,"insert_player","select func_insert_player(row($1,$2,$3,$4,$5,$6)) as result");
            $resource = pg_prepare($db,"insert_participation","select func_player_formation_assoc(row($1,$2)) as result");
            $resource = pg_prepare($db,"insert_team","select func_insert_team(row($1,$2,$3)) as result");
            $resource = pg_prepare($db,"insert_match","select func_insert_match(row($1,$2,$3,$4,$5,$6::timestamp,$7,$8,$9,$10,$11)) as result");
            $resource = pg_prepare($db,"insert_country","select func_insert_country($1) as result");
            $resource = pg_prepare($db,"insert_league","select func_insert_league($1) as result");

            if($resource === false)
                die("Errore nelle pg_prepare: ".pg_last_error($resource));

            while(($data = fgetcsv($handle,0,',')) !== false){

                if($row > 0){

                    //inserisci country
                    $resource = pg_execute($db,"insert_country",array($data[1]));
                    if($resource === false)
                        die("Errore inserimento country: ".pg_last_error($resource));

                    $arr = pg_fetch_row($resource,null,PGSQL_ASSOC);

                    if($arr['result'] >= 0){
                        $data[1] = $arr['result'];
                    }
                    else{
                        die("Error inserting country ".$data[1]." | error code: ".$arr['result']);
                    }






                    //inserisci league
                    $resource = pg_execute($db,"insert_league",array($data[2]));
                    if($resource === false)
                        die("Errore inserimento league: ".pg_last_error($resource));

                    $arr = pg_fetch_row($resource,null,PGSQL_ASSOC);

                    if($arr['result'] >= 0){
                        $data[2] = $arr['result'];
                    }
                    else{
                        die("Error inserting league ".$data[2]." | error code: ".$arr['result']);
                    }






                    //metti anno season in data[3]
                    $arr = explode("/",$data[3],2);
                    $data[3] = $arr[0];






                    //inserisci team home
                    $resource = pg_execute($db,"insert_team",array($data[6],$data[8],$data[7]));

                    $arr = pg_fetch_row($resource,null,PGSQL_ASSOC);


                    if($arr['result'] !== '0' && $arr['result'] !== '3'){
                        print_r($arr);
                        die("Non sono riuscito a mettere il team nel db: ".$data[7]." Error postgres: ".pg_last_error($resource));
                    }

                    //inserisci team away
                    $resource = pg_execute($db,"insert_team",array($data[9],$data[11],$data[10]));

                    $arr = pg_fetch_array($resource,null,PGSQL_ASSOC);

                    if($arr['result'] !== '0' && $arr['result'] !== '3'){
                        print_r($arr);
                        die("Non sono riuscito a mettere il team nel db: ".$data[10]." Error code: ".$arr['result']);
                    }



                    //inserisci match
                    //id, home_team_id, away_team_id, season, stage, date, a_team_goal, h_team_goal, league_id, country_id, operator_id

                    $values = array($data[0],$data[6],$data[9],$data[3],$data[4],$data[5],$data[13],$data[12],$data[2],$data[1],$_SESSION['user_id']);
                    $resource = pg_execute($db,"insert_match",$values);

                    $arr = pg_fetch_row($resource,null,PGSQL_ASSOC);



                    if($arr['result'] !== '0' && $arr['result'] !== '3'){
                        die( '<br>Error inserting row number '.$row.'<br> cause: '.pg_last_error($resource).'<br>code: '.$arr['result']);
                    }




                    //inserisci giocatori
                    $curr_player = 14;
                    for($i = 0;$i < 22;$i++){

                        if($data[$curr_player] != null){

                            $data[$curr_player+1] = preg_replace('/[0-9]+/', '',$data[$curr_player+1]);
                            $data[$curr_player+1] = preg_replace("#[[:punct:]]#", "", $data[$curr_player+1]);
                            echo "Inserting player: ".$data[$curr_player]." ".$data[$curr_player+1]." ".$data[$curr_player+2]." ".$data[$curr_player+3]." ".$data[$curr_player+4]."<br>";
                            $resource = pg_execute($db,"insert_player",array($data[$curr_player],$data[$curr_player+1],$data[$curr_player+2],$data[$curr_player+3],$data[$curr_player+4],$i<=10?$data[6]:$data[9]));
                            if($resource === false)
                                die("error inserting player: ".pg_last_error($resource));
                            $arr = pg_fetch_row($resource,null,PGSQL_ASSOC);
                            if($arr['result'] < 0 && $arr['result'] > -3){
                                die("errore inserimento giocatore: ".$arr['result']);
                            }

                            $resource = pg_execute($db,"insert_participation",array($data[0],$data[$curr_player]));

                            if($resource === false)
                                die("Error inserting participation: ".pg_last_error($resource));

                            $arr = pg_fetch_row($resource,null,PGSQL_ASSOC);

                            if($arr['result'] !== '0'){
                                echo "<p style=\"color:red\">ERROR inserting participation. Code: ".$arr['result']." Data: ".$data[0]." ".$data[$curr_player]." Association with formation will be skipped</p><br>";
                            }
                        }
                        /*else{
                            echo "Player id null. Skipping<br>";
                        }*/
                        $curr_player+=5;
                    }
                }
                $row++;
            }

            fclose($handle);

            header('Location: ../frontend/home.php?success=Database aggiornato correttamente');
        }
    /*}
    else{
        echo 'Incorrect file type: '.mime_content_type($_FILES['csv']);
    }*/