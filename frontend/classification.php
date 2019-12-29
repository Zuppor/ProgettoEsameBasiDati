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

//print_r2($years);

$resource = pg_query($db,"select distinct(league) as league from classifica order by league");

$leagues = pg_fetch_all($resource);

//print_r2($leagues);

for($y = 0;$y<sizeof($years);$y++){
    for($l = 0;$l<sizeof($leagues);$l++) {
        $resource = pg_query($db, "select * 
                                        from classifica 
                                        where season = " . $years[$y]['season'] .
                                        "and league = '" . $leagues[$l]['league'] .
                                        "' order by score desc");

        $arr = pg_fetch_all($resource);

        //echo "Year: ".$years[$y]['season']."   League: ".$leagues[$l]['league'];
        //print_r2($arr);
        //echo $arr[0]['l_name'];
        ?>
        <table class="table table-bordered" style="text-align: center;vertical-align: middle;table-layout: fixed">
            <tr class="table-success">
                <td colspan="6"><?php echo "<b>Stagione: </b>".$years[$y]['season'] . "/" . ($years[$y]['season'] + 1); ?></td>
            </tr>
            <tr class="table-primary">
                <td colspan="6"><?php echo "<b>League: </b>".$leagues[$l]['league']; ?></td>
            </tr>
            <tr>
                <td>Team</td>
                <td>Punti</td>
                <td>Vittorie</td>
                <td>Pareggi</td>
                <td>Perse</td>
                <td>Giocate</td>
            </tr><!--
            <tr>
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
                <td>5</td>
                <td>6</td>
            </tr>-->
            <?php
                //foreach ($arr as list($lname,$sname,$score,$vict,$draws,$lost,$played)){
                for($x = 0;$x<count($arr,COUNT_NORMAL);$x++){//fixme: il ciclo fa crashare tutto
                    //print_r2($x);
                //$x = 0;
            ?>
            <tr>
                <td><?php echo $arr[$x]['l_name']." (".$arr[$x]['s_name'].")"; ?></td>
                <td><?php echo $arr[$x]['score']; ?></td>
                <td><?php echo $arr[$x]['victories']; ?></td>
                <td><?php echo $arr[$x]['draws']; ?></td>
                <td><?php echo $arr[$x]['lost']; ?></td>
                <td><?php echo $arr[$x]['played']; ?></td>
            </tr>
            <?php}?>
        </table>
        <?php
    }
}
?>
</body>
</html>