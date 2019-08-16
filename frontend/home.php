<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../bootstrap-4.3.1-dist/css/bootstrap.min.css">
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

    /*include '../backend/functions.php';
    include '../backend/db_connect_login.php';

    start_secure_session();*/

    include_once 'navbar.php';

    if(login_check($db) === true):
        switch ($_SESSION['user_level']){





            //ADMIN:
            case 0:
?>
            <div class="container-fluid">
    Carica match<br>
            <form action="../backend/insert_match_from_csv.php" method="post" enctype="multipart/form-data">
                <div class="form-control-file">
                <label class="custom-file-label" for="csv">Choose file</label>
                <input type="file" class="custom-file-input" name="csv" id="csv" value="" required><br>
                <input type="submit" class="btn btn-primary" name="submit" value="Submit">
                </div>
            </form>
    Carica player attribute<br>
            <form action="../backend/insert_player_attribute.php" method="post" enctype="multipart/form-data">
                <div class="form-control-file">
                <label class="custom-file-label" for="csv">Choose file</label>
                <input type="file" class="custom-file-input" name="csv" id="csv" value="" required><br>
                <input type="submit" class="btn btn-primary" name="submit" value="Submit">
                </div>
            </form>
            </div>
    <?php
    break;





    //OPERATORE:
    case 1:?>
    <form action="../backend/insert_match_from_csv.php" method="post">
        <label for="country">Country</label>
        <input type="text" name="country" id="country" value="" required><br>

        <label for="league">League</label>
        <input type="text" name="league" id="league" value="" required><br>

        <label for="season">Season</label>
        <input type="date" name="season" id="season" value="" required><br>

        <label for="stage">Stage</label>
        <input type="number" name="stage" id="stage" value="1" min="1" required><br>

        <label for="date">Date</label>
        <input type="date" name="date" id="date" value="" required><br>

        <?php
            $resource = pg_query($db,"select id,long_name,short_name from team order by long_name");
            if($resource === false){
                echo "Errore: ".pg_last_error($resource);
            }

            $rows = pg_fetch_all($resource);
        ?>

        <label for="team_h">Team home</label>
        <select name="team_h" id="team_h" class="custom-select-sm" required>
            <?php
            for($i = 0;$i<sizeof($rows);$i++) {
                ?>
                <option value="<?php echo $rows[$i]['id']; ?>"><?php echo $rows[$i]['long_name'] . ' (' . $rows[$i]['short_name'] . ')'; ?></option>
                <?php
            }
            ?>

        </select><br>

        <label for="team_a">Team away</label>
        <select name="team_a" id="team_a" class="custom-select-sm" required>

            <?php
            for($i = 0;$i<sizeof($rows);$i++) {
                ?>
                <option value="<?php echo $rows[$i]['id']; ?>"><?php echo $rows[$i]['long_name'] . ' (' . $rows[$i]['short_name'] . ')'; ?></option>
                <?php
            }
            ?>

        </select><br>


        <label for="h_goal">Home goals</label>
        <input type="number" name="h_goal" id="h_goal" value="0" min="0" required><br>

        <label for="a_goal">Away goals</label>
        <input type="number" name="a_goal" id="a_goal" value="0" min="0" required><br>

        <input type="submit" class="btn btn-primary" name="submit" value="Submit">
    </form>
<?php break;






//PARTNER:
case 2:?>
    <a href="">Inserisci quota</a><br>
<?php break;

            default:
                die('Livello utente sconosciuto: '.$_SESSION['user_level']);
                break;
    }?>




        <a href="classification.php">Visualizza classifica</a><br>
        <a href="../backend/process_logout.php">Logout</a>
   <?php else:?>
devi <a href="login.php">accedere ad un account</a> prima di entrare in questa pagina<br>
<?php endif;?>
</body>

</html>