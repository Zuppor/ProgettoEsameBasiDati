<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../bootstrap-4.3.1-dist/css/bootstrap.min.css">
    <title>Home</title>
</head>
<body>
<?php

include_once '../backend/functions.php';
include_once '../backend/db_connect_login.php';

start_secure_session();

$lazy_load_step = 10;

$_GET['navbar_active'] = 2;
if(!isset($_GET['lazy_load'])) $_GET['lazy_load'] = $lazy_load_step;

include_once 'navbar.php';

$resource = pg_prepare($db,"","select m.id,m.date,season,stage,l.name,home_team_id,away_team_id from public.match m join league l on m.league_id = l.id order by date desc limit $1");
$resource = pg_execute($db,"",array($_GET['lazy_load']));

while($row = pg_fetch_array($resource,null,PGSQL_ASSOC)){

    $r = pg_prepare($db,"","select * from get_best_players($1)");
    $r = pg_execute($db,"",array($row['id']));

    $curr_match_arr = pg_fetch_all($r);

    if($curr_match_arr === false){
        die('An error occurred.');
    }
        ?>
<table class="table table-bordered" style="text-align: center;vertical-align: middle;table-layout: fixed">
                <tr class="table-primary">
                    <td colspan="2">
                        <?php
                        echo "<b>Date: </b>".date("j/m/Y H:i",strtotime($row['d'])) ;
                        echo " <b>Season: </b>".$row['season']."/".($row['season']+1) ;
                        echo " <b>Stage: </b>".$row['stage'] ;
                        echo " <b>League: </b>".$row['name'] ;
                        ?>
                    </td>
                </tr>
                <tr>
                    <td  style="border-right: 1px solid grey">
                        <?php
                        $r = pg_prepare($db,"","select long_name,short_name from team where id = $1");
                        $r = pg_execute($db,"",array($row['home_team_id']));
                        $arr = pg_fetch_array($r,null,PGSQL_ASSOC);
                        echo "<b>Team home: </b>".$arr['long_name']." (".$arr['short_name'].")"; ?>
                    </td>
                    <td >
                        <?php
                        $r = pg_execute($db,"",array($row['away_team_id']));
                        $arr = pg_fetch_array($r,null,PGSQL_ASSOC);
                        echo "<b>Team away: </b>".$arr['long_name']." (".$arr['short_name'].")"; ?>
                    </td>
                </tr>
                <tr>
                    <td  style="border-right: 1px solid grey">
                        <?php
                        $present = false;
                        echo "<b>Best player(s): </b><br>";
                        for($j = 0;$j<sizeof($curr_match_arr);$j++) {
                            if($curr_match_arr[$j]['team_h'] == 't') {
                                echo $curr_match_arr[$j]['player_name']."<br>";
                                $ratingH = $curr_match_arr[$j]['rating'];
                                $present = true;
                            }
                        }
                        if($present === false){
                            echo "No data available";
                        }

                        ?>
                    </td>
                    <td >
                        <?php
                        echo "<b>Best player(s): </b><br>";
                        $present = false;

                        for($j = 0;$j<sizeof($curr_match_arr);$j++) {
                            if($curr_match_arr[$j]['team_h'] == 'f') {
                                echo $curr_match_arr[$j]['player_name']."<br>";
                                $ratingA = $curr_match_arr[$j]['rating'];
                                $present = true;
                            }
                        }
                        if($present === false){
                            echo "No data available";
                        }
                        ?>
                    </td>
                </tr>
    <tr>
        <td style="border-right: 1px solid grey">
            <?php
            echo "<b>Overall rating: </b>";
            if(isset($ratingH)) echo $ratingH; else echo "No data available";
            unset($ratingH);
            ?>
        </td>

        <td>
            <?php
            echo "<b>Overall rating: </b>";
            if(isset($ratingA)) echo $ratingA; else echo "No data available";
            unset($ratingA);
            ?>
        </td>
    </tr>
</table>
        <?php }?>

<input type="button" class="btn-primary" value="Load more..." onclick="<?php echo "window.location.href='best_players.php?lazy_load=".($_GET['lazy_load']+$lazy_load_step)."'"; ?>"/>

</body>
</html>