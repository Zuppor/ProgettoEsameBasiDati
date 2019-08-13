<html lang="it">
<head>
    <script type="text/javascript" src="../backend/sha512.js"></script>
    <script type="text/javascript" src="../backend/forms.js"></script>
    <meta charset="UTF-8">
    <title>Main</title>
</head>
<body>
    <?php
        if(isset($_GET['error'])){
            echo $_GET['error'].'<br><br>';
        }
    ?>
    <form method="post" action="../backend/process_login.php" name="login_form">
        <label>Username</label><br>
        <input type="text" name="username" required/><br>
        <br>
        <label>Password</label><br>
        <input type="password" name="password" id="p" required/><br>
        <br>
        <input type="submit" value="Login" onclick="formhash(this.form,this.form.p);"/>
    </form>
    <br>
    <br>
    <a href="register.php">Registra utente</a><br>
    <a href="../index.php">Annulla</a>
</body>
</html>