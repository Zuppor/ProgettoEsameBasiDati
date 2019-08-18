<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../bootstrap-4.3.1-dist/css/bootstrap.min.css">
    <title>Home</title>
</head>
<script>
    var seasonComp;
    var teamComp;

    function onload(){
        seasonComp = document.getElementById("seasonComp");
        teamComp = document.getElementById("teamComp");

        //document.getElementById("date").value = new Date().getDate().toString();
        var y = new Date().getFullYear();

        document.getElementById("season").setAttribute("value",y.toString());
        y++;
        seasonComp.innerHTML = "/" + y;
    }

    function adjustSeason(year){
        year = Number(year);
        year++;
        seasonComp.innerHTML = "/" + year;
    }

    function checkTeams(team1,team2){
        if(team1.value === team2.value){
            teamComp.innerHTML = "Le squadre devono essere diverse";
        }
        else{
            teamComp.innerHTML = "";
        }
    }
</script>

<body onload="onload()">
<?php

    include '../backend/functions.php';
    include '../backend/db_connect_login.php';

    start_secure_session();

    include_once 'navbar.php';

    if(isset($_GET['error'])){
        echo '<div class="alert alert-danger" role="alert">'.$_GET['error'].'</div><br><br>';
    }
    elseif (isset($_GET['success'])){
        echo '<div class="alert alert-success" role="alert">'.$_GET['success'].'</div><br><br>';
    }

    if(login_check($db) === true):
        switch ($_SESSION['user_level']){





            //ADMIN:
            case 0:
?>

            <form action="../backend/insert_match_from_csv.php" method="post" enctype="multipart/form-data">
                Carica match<br>
                <div class="form-control-file">
                <label class="custom-file-label" for="csv">Choose file</label>
                <input type="file" class="custom-file-input" name="csv" id="csv" value="" required/><br>
                <input type="submit" class="btn btn-primary" name="submit" value="Submit"/>
                </div>
            </form>

            <form action="../backend/insert_player_attribute.php" method="post" enctype="multipart/form-data">
                Carica player attribute<br>
                <div class="form-control-file">
                <label class="custom-file-label" for="csv">Choose file</label>
                <input type="file" class="custom-file-input" name="csv" id="csv" value="" required/><br>
                <input type="submit" class="btn btn-primary" name="submit" value="Submit"/>
                </div>
            </form>
    <?php
    break;





    //OPERATORE:
    case 1:?>
    <form action="../backend/insert_match_from_form.php" method="post">
        <label for="country">Country</label>
        <select name="country" id="country" class="custom-select-sm" required>
            <?php
            $resource = pg_query($db,"select id,name from country order by name");

            while($row = pg_fetch_array($resource,null,PGSQL_ASSOC)) {
                ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                <?php
            }
            ?>

        </select><br>

        <label for="league">League</label>
        <select name="league" id="league" class="custom-select-sm" required>
            <?php
            $resource = pg_query($db,"select id,name from league order by name");

            while($row = pg_fetch_array($resource,null,PGSQL_ASSOC)) {
                ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                <?php
            }
            ?>

        </select><br>

        <label for="season">Season</label>
        <input type="number" name="season" id="season" value="" min="0" oninput="adjustSeason(this.value)" required/> <p id="seasonComp"></p><br>

        <label for="stage">Stage</label>
        <input type="number" name="stage" id="stage" value="1" min="1" required/><br>

        <label for="date">Date</label>
        <input type="date" name="date" id="date" value="" required/><br>

        <?php
            $resource = pg_query($db,"select id,long_name,short_name from team order by long_name");

            $rows = pg_fetch_all($resource);
        ?>

        <label for="team_h">Team home</label>
        <select name="team_h" id="team_h" class="custom-select-sm" onchange="checkTeams(this,this.form.team_a)" required>
            <?php
            for($i = 0;$i<sizeof($rows);$i++) {
                ?>
                <option value="<?php echo $rows[$i]['id']; ?>"><?php echo $rows[$i]['long_name'] . ' (' . $rows[$i]['short_name'] . ')'; ?></option>
                <?php
            }
            ?>

        </select><br>

        <label for="team_a">Team away</label>
        <select name="team_a" id="team_a" class="custom-select-sm" onchange="checkTeams(this,this.form.team_h)" required>

            <?php
            for($i = 0;$i<sizeof($rows);$i++) {
                ?>
                <option value="<?php echo $rows[$i]['id']; ?>"><?php echo $rows[$i]['long_name'] . ' (' . $rows[$i]['short_name'] . ')'; ?></option>
                <?php
            }
            ?>

        </select><p class="text-danger" id="teamComp"></p> <br>


        <label for="h_goal">Home goals</label>
        <input type="number" name="h_goal" id="h_goal" value="0" min="0" required/><br>

        <label for="a_goal">Away goals</label>
        <input type="number" name="a_goal" id="a_goal" value="0" min="0" required/><br>

        <input type="submit" class="btn btn-primary" name="submit" value="Submit"/>
    </form>
<?php break;






//PARTNER:
case 2:?>
    <a href="">Inserisci quota</a><br>
<?php break;






//SCONOSCIUTO
            default:
                die('Livello utente sconosciuto: '.$_SESSION['user_level']);
                break;
    }?>




        <a href="classification.php">Visualizza classifica</a><br>
        <a href="../backend/process_logout.php">Logout</a>
   <?php else:?>
Devi <a href="login.php">accedere ad un account</a> prima di entrare in questa pagina<br>
<?php endif;?>
</body>

</html>