<!DOCTYPE html>
<html  lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../bootstrap-4.3.1-dist/css/bootstrap.min.css">
    <title>Classification</title>
</head>
<body>
<?php
include '../backend/functions.php';
include '../backend/db_connect_login.php';

start_secure_session();

$_GET['navbar_active'] = 1;

include_once 'navbar.php';


$resource = pg_query($db,"select distinct(season) as season from classifica order by season desc");
$years = pg_fetch_all($resource);

$resource = pg_query($db,"select distinct(league) as league from classifica order by league");
$leagues = pg_fetch_all($resource);

for($y = 0;$y<sizeof($years);$y++){
    for($l = 0;$l<sizeof($leagues);$l++) {
        ?>
        <table class="table table-borderless" style="text-align: center;vertical-align: middle">
            <tr class="table-primary">
                <td colspan="7"><?php echo "<b>Stagione: </b>".$years[$y]['season'] . "/" . ($years[$y]['season'] + 1); ?></td>
            </tr>
            <tr class="table-primary">
                <td colspan="7"><?php echo "<b>League: </b>".$leagues[$l]['league']; ?></td>
            </tr>
            <tr>
                <td style="width: 100px"></td>
                <td><b>Team</b></td>
                <td><b>Punti</b></td>
                <td><b>Vittorie</b></td>
                <td><b>Pareggi</b></td>
                <td><b>Perse</b></td>
                <td><b>Giocate</b></td>
            </tr>
            <?php
            $x=1;
            $resource = pg_query($db, "select * from classifica where season = ".$years[$y]['season']."and league = '" . $leagues[$l]['league']."' order by score desc");
            while($row = pg_fetch_array($resource,null,PGSQL_ASSOC)) {
                //print_r2($row);
                if($x%2==0) echo "<tr class='table-light'>"; else echo "<tr class='table-secondary'>";
                echo "<td>".$x."</td>";
                echo "<td>".$row['l_name']." (".$row['s_name'].")</td>";
                echo "<td>".$row['score']."</td>";
                echo "<td>".$row['victories']."</td>";
                echo "<td>".$row['draws']."</td>";
                echo "<td>".$row['lost']."</td>";
                echo "<td>".$row['played']."</td>";
                echo "</tr>";
                $x++;
            }
            ?>
        </table>
        <?php
        echo str_repeat("<br>",5);
    }
}
?>
</body>
</html>