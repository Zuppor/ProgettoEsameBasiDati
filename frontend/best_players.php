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
//todo: sistemare larghezza colonne in modo che siano tutte uguali
include_once '../backend/functions.php';
include_once '../backend/db_connect_login.php';

start_secure_session();

$_GET['navbar_active'] = 2;

include_once 'navbar.php';

$resource = pg_query($db,"select get_best_players() as result");

            while($row = pg_fetch_array($resource,null,PGSQL_ASSOC)){
                $row = $row['result'];

                $row= str_replace("\"","",$row);

                $row = str_replace("(","",$row);

                $row = str_replace(")","",$row);

                $row = explode(",",$row);
        ?>
<table class="table table-bordered" style="text-align: center;vertical-align: middle">
                <tr class="table-primary">
                    <td colspan="2">
                        <?php
                        $r = pg_query($db,"select date,season,stage,l.name from public.match m join league l on m.league_id = l.id where m.id = ".$row[0]);
                        $arr = pg_fetch_array($r,null,PGSQL_ASSOC);
                        echo "<b>Date: </b>".$arr['date']." <b>Season: </b>".$arr['season']."/".($arr['season']+1)." <b>Stage: </b>".$arr['stage']." <b>League: </b>".$arr['name']; ?>
                    </td>
                </tr>
                <tr>
                    <td  style="border-right: 1px solid grey">
                        <?php
                        $r = pg_query($db,"select long_name,short_name from team where id = ".$row[1]);
                        $arr = pg_fetch_array($r,null,PGSQL_ASSOC);
                        echo "<b>Team away: </b>".$arr['long_name']." (".$arr['short_name'].")"; ?>
                    </td>
                    <td >
                        <?php
                        $r = pg_query($db,"select long_name,short_name from team where id = ".$row[4]);
                        $arr = pg_fetch_array($r,null,PGSQL_ASSOC);
                        echo "<b>Team home: </b>".$arr['long_name']." (".$arr['short_name'].")"; ?>
                    </td>
                </tr>
                <tr>
                    <td  style="border-right: 1px solid grey">
                        <?php echo "<b>Best player: </b>";
                        if(strcmp($row[2],"") != 0) echo $row[2]; else echo "No data available";
                        ?>
                    </td>
                    <td >
                        <?php echo "<b>Best player: </b>";
                        if(strcmp($row[5],"") != 0) echo $row[5]; else echo "No data available";
                        ?>
                    </td>
                </tr>
    <tr>
        <td style="border-right: 1px solid grey">
            <?php echo "<b>Overall rating: </b>";
            if(strcmp($row[3],"") != 0) echo $row[3]; else echo "N/A";
            ?>
        </td>

        <td>
            <?php echo "<b>Overall rating: </b>";
            if(strcmp($row[6],"") != 0) echo $row[6]; else echo "N/A";
            ?>
        </td>
    </tr>
</table>
        <?php }?>


</body>
</html>