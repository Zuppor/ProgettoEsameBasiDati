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

?>
        <?php
            while($row = pg_fetch_array($resource,null,PGSQL_ASSOC)){
                $row = $row['result'];
                $row['result'] = str_replace("\""," ",$row);
                $row = str_replace("("," ",$row);
                $row = str_replace(")"," ",$row);
                $row = split(",",$row);
                print_r2($row);
                die();
        ?>


        <?php }?>


</body>
</html>