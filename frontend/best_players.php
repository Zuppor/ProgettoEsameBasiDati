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

$resource = pg_prepare($db,"","select id from public.match order by date desc limit $1");
$resource = pg_execute($db,"",array($_GET['lazy_load']));

while($row = pg_fetch_array($resource,null,PGSQL_ASSOC)){

    $r = pg_prepare($db,"","select get_best_players($1) as result");
    $r = pg_execute($db,"",array($row['id']));
    $curr_match_arr = array();

    while($row2 = pg_fetch_array($r,null,PGSQL_ASSOC)) {

        $row2 = $row2['result'];

        $row2 = str_replace("\"", "", $row2);

        $row2 = str_replace("(", "", $row2);

        $row2 = str_replace(")", "", $row2);

        $row2 = explode(",", $row2);

        array_push($curr_match_arr, $row2);
    }

    //extract teams
    $i = 1;
    $h_team = $curr_match_arr[0][0];
    $h_rating = $curr_match_arr[0][3];
    $a_team = null;
    $a_rating = null;
    while(($a_team = $curr_match_arr[$i][0]) == $h_team && $i < sizeof($curr_match_arr))
        $i++;
    $a_rating = $curr_match_arr[$i][3];
        ?>
<table class="table table-bordered" style="text-align: center;vertical-align: middle;table-layout: fixed">
                <tr class="table-primary">
                    <td colspan="2">
                        <?php
                        $r = pg_prepare($db,"","select date,season,stage,l.name from public.match m join league l on m.league_id = l.id where m.id = $1");
                        $r = pg_execute($db,"",array($row['id']));
                        $arr = pg_fetch_array($r,null,PGSQL_ASSOC);
                        echo "<b>Date: </b>".$arr['date']." <b>Season: </b>".$arr['season']."/".($arr['season']+1)." <b>Stage: </b>".$arr['stage']." <b>League: </b>".$arr['name']; ?>
                    </td>
                </tr>
                <tr>
                    <td  style="border-right: 1px solid grey">
                        <?php
                        $r = pg_prepare($db,"","select long_name,short_name from team where id = $1");
                        $r = pg_execute($db,"",array($h_team));
                        $arr = pg_fetch_array($r,null,PGSQL_ASSOC);
                        echo "<b>Team home: </b>".$arr['long_name']." (".$arr['short_name'].")"; ?>
                    </td>
                    <td >
                        <?php
                        //$r = pg_prepare($db,"","select long_name,short_name from team where id = $1");
                        $r = pg_execute($db,"",array($a_team));
                        $arr = pg_fetch_array($r,null,PGSQL_ASSOC);
                        echo "<b>Team away: </b>".$arr['long_name']." (".$arr['short_name'].")"; ?>
                    </td>
                </tr>
                <tr>
                    <td  style="border-right: 1px solid grey">
                        <?php
                        echo "<b>Best player(s): </b><br>";
                        for($j = 0;$j<sizeof($curr_match_arr);$j++) {
                            if($curr_match_arr[$j][1] == 't') {
                                //if(strcmp($row[2],"") != 0) echo $row[2]; else echo "No data available";
                                echo $curr_match_arr[$j][2]."<br>";
                            }

                        }

                        ?>
                    </td>
                    <td >
                        <?php echo "<b>Best player(s): </b><br>";
                        //if(strcmp($row[5],"") != 0) echo $row[5]; else echo "No data available";
                        for($j = 0;$j<sizeof($curr_match_arr);$j++) {
                            if($curr_match_arr[$j][1] == 'f') {
                                //if(strcmp($row[2],"") != 0) echo $row[2]; else echo "No data available";
                                echo $curr_match_arr[$j][2]."<br>";
                            }

                        }
                        ?>
                    </td>
                </tr>
    <tr>
        <td style="border-right: 1px solid grey">
            <?php echo "<b>Overall rating: </b>";
            //if(strcmp($row[3],"") != 0) echo $row[3]; else echo "N/A";
            echo $h_rating;
            ?>
        </td>

        <td>
            <?php echo "<b>Overall rating: </b>";
            //if(strcmp($row[6],"") != 0) echo $row[6]; else echo "N/A";
            echo $a_rating;
            ?>
        </td>
    </tr>
</table>
        <?php }?>

<input type="button" class="btn-primary" value="Load more" onclick="<?php echo "window.location.href='best_players.php?lazy_load=".($_GET['lazy_load']+$lazy_load_step)."'"; ?>"/>

</body>
</html>