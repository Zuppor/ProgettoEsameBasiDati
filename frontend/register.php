<html lang="it">
<head>
    <script type="text/javascript" src="../backend/sha512.js"></script>
    <script type="text/javascript" src="../backend/forms.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../bootstrap-4.3.1-dist/css/bootstrap.min.css">
    <title>Register</title>
</head>
<body>
<?php
    if(isset($_GET['error'])){
        echo $_GET['error'].'<br><br>';
    }
?>

    <form method="post" action="../backend/process_registration.php">
        Username: <input type="text" name="username" required maxlength="100" minlength="1"/><br>
        Password: <input type="password" name="password" id="p" required><br>
        Level: <select name="level">
            <option value="0">Amministratore</option>
            <option value="1">Operatore</option>
            <option value="2">Partner</option>
        </select><br>
        Bet society: <select name="society">
            <option value="null">Nessuna</option>
            <?php
                include '../backend/db_connect_login.php';
                $resource = pg_query($db,"select id,long_name from bet_society");

                while($row = pg_fetch_array($resource,null,PGSQL_ASSOC)){
                    ?>
                    <option value="<?php echo $row['id'];?>"><?php echo $row['long_name'];?></option>
               <?php }?>
        </select><br>
        <input type="button" value="Register" onclick="formhash(this.form,this.form.p);"/>
    </form>
<button onclick="goBack()" class="btn btn-outline-secondary">< Back</button>

<script>
    function goBack() {
        window.history.back();
    }
</script>
</body>
</html>