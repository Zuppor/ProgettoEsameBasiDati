<html lang="it">
<head>
    <script type="text/javascript" src="../backend/sha512.js"></script>
    <script type="text/javascript" src="../backend/forms.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../bootstrap-4.3.1-dist/css/bootstrap.min.css">
    <title>Main</title>
</head>
<body>
    <?php
        if(isset($_GET['error'])){
            echo $_GET['error'].'<br><br>';
        }
    ?>
    <div class="container justify-content-center">
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
    Non hai un account? <a href="register.php">Registrati</a><br>
    <button onclick="goBack()" class="btn btn-outline-secondary">< Back</button>
    </div>
    <script>
        function goBack() {
            window.history.back();
        }
    </script>
</body>
</html>