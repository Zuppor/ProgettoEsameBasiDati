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

$_GET['navbar_active'] = 2;

include_once 'navbar.php';

$resource = pg_query($db,"select get_best_players() as result");

//$row = pg_fetch_array($resource,null,PGSQL_ASSOC);
//print_r2($row);


?>

        <?php
            while($row = pg_fetch_array($resource,null,PGSQL_ASSOC)){
                $row = $row['result'];

                echo $row."<br>";

                $row= str_replace("\"","",$row);

                $row = str_replace("(","",$row);

                $row = str_replace(")","",$row);

                echo $row."<br>";

                $row = explode(",",$row);

                print_r2($row);
        ?>
<table class="table table-bordered" style="text-align: center;vertical-align: middle">
                <tr class="table-primary">
                    <td colspan="8">
                        <?php
                        $r = pg_query($db,"select date,season,stage,l.name from public.match m join league l on m.league_id = l.id where m.id = ".$row[0]);
                        $arr = pg_fetch_array($r,null,PGSQL_ASSOC);
                        echo "<b>Date: ".$arr['date']." Season: ".$arr['season']." Stage: ".$arr['stage']." League: ".$arr['name']."</b>"; ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" style="border-right: 1px solid grey">
                        <?php
                        $r = pg_query($db,"select long_name,short_name from team where id = ".$row[1]);
                        $arr = pg_fetch_array($r,null,PGSQL_ASSOC);
                        echo "Team away: ".$arr['long_name']." (".$arr['short_name'].")"; ?>
                    </td>
                    <td colspan="4">
                        <?php
                        $r = pg_query($db,"select long_name,short_name from team where id = ".$row[7]);
                        $arr = pg_fetch_array($r,null,PGSQL_ASSOC);
                        echo "Team home: ".$arr['long_name']." (".$arr['short_name'].")"; ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" style="border-right: 1px solid grey">
                        <?php echo "Best player: ".$row[2]; ?>
                    </td>
                    <td colspan="4">
                        <?php echo "Best player: ".$row[8]; ?>
                    </td>
                </tr>
    <tr>
        <td>
            <?php echo "Birthday: ".$row[3]; ?>
        </td>
        <td>
            <?php echo "Height: ".$row[4]; ?>
        </td>
        <td>
            <?php echo "Weight: ".$row[5]; ?>
        </td>
        <td style="border-right: 1px solid grey">
            <?php echo "Overall rating: ".$row[6]; ?>
        </td>

        <td>
            <?php echo "Birthday: ".$row[9]; ?>
        </td>
        <td>
            <?php echo "Height: ".$row[10]; ?>
        </td>
        <td>
            <?php echo "Weight: ".$row[11]; ?>
        </td>
        <td>
            <?php echo "Overall rating: ".$row[12]; ?>
        </td>
    </tr>


</table>
        <?php }?>


</body>
</html>