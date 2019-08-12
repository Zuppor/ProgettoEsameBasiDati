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
    include '../backend/db_connect_login.php';

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