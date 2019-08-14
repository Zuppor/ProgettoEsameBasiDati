<html>
<head>
    <meta charset="UTF-8">
    <title>Home</title>
</head>

<body>
<?php

    if(isset($_GET['match_upload_msg'])){
        echo $_GET['match_upload_msg']."<br>";
    }
    if(isset($_GET['attribute_upload_msg'])){
        echo $_GET['attribute_upload_msg']."<br>";
    }

    include '../backend/functions.php';
    //include '../backend/db_connect_login.php';

    start_secure_session();

    if(login_check($db) === true):
        switch ($_SESSION['user_level']){
            case 0:
?>
    Carica match<br>
            <form action="../backend/insert_match.php" method="post" enctype="multipart/form-data">
                <input type="file" name="csv" id="csv" value="" required><br>
                <input type="submit" name="submit" value="Submit">
            </form>
    Carica player attribute<br>
            <form action="../backend/insert_player_attribute.php" method="post" enctype="multipart/form-data">
                <input type="file" name="csv" id="csv" value="" required><br>
                <input type="submit" name="submit" value="Submit">
            </form>

    <?php
    break;
    case 1:?>
    <form action=" ../backend/insert_match.php" method="post">
        <label for="country">Country</label>
        <input type="text" name="country" id="country" value="" required><br>

        <label for="league">League</label>
        <input type="text" name="league" id="league" value="" required><br>

        <label for="season">Season</label>
        <input type="date" name="season" id="season" value="" required><br>

        <label for="stage">Stage</label>
        <input type="number" name="stage" id="stage" value="" required><br>

        <label for="date">Date</label>
        <input type="date" name="date" id="date" value="" required><br>

        <label for="team_h">Team home</label>
        <select name="team_h" id="team_h" required>
            <?php
                include_once ("../backend/db_connect_operator.php");

                $resource = pg_query($db,"select id,long_name,short_name from team order by long_name");
                if($resource === false){
                    die("Errore: ".pg_last_error($resource));
                }

                while($row = pg_fetch_array($resource,null,PGSQL_ASSOC)){
                    ?>
                    <option value="<?php echo $row['id'];?>"><?php echo $row['long_name'].' ('.$row['short_name'].')';?></option>
            <?php
                }

            ?>

        </select><br>

        <label for="team_a">Team away</label>
        <select name="team_a" id="team_a" required>
            <?php
            //include_once ("../backend/db_connect_operator.php");

            $resource = pg_query($db,"select id,long_name,short_name from team order by long_name");
            if($resource === false){
                die("Errore: ".pg_last_error($resource));
            }

            while($row = pg_fetch_array($resource,null,PGSQL_ASSOC)){
                ?>
                <option value="<?php echo $row['id'];?>"><?php echo $row['long_name'].' ('.$row['short_name'].')';?></option>
                <?php
            }

            ?>

        </select><br>


        <label for="h_goal">Home goals</label>
        <input type="number" name="h_goal" id="h_goal" value="0" required><br>

        <label for="a_goal">Away goals</label>
        <input type="number" name="a_goal" id="a_goal" value="0" required><br>

    </form>
        <a href="">Inserisci match</a><br>
<?php break;
case 2:?>
    <a href="">Inserisci quota</a><br>
<?php break;}?>
        <a href="classification.php">Visualizza classifica</a><br>
        <a href="../backend/process_logout.php">Logout</a>
   <?php else:?>
devi <a href="login.php">accedere ad un account</a> prima di entrare in questa pagina<br>
<?php endif;?>
</body>

</html>