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
            echo '<div class="alert alert-danger" role="alert">'.$_GET['error'].'</div><br><br>';
        }
        elseif (isset($_GET['success'])){
            echo '<div class="alert alert-success" role="alert">'.$_GET['success'].'</div><br><br>';
        }
    ?>
    <div class="container justify-content-center">
    <form method="post" action="../backend/process_login.php" name="login_form">
        <label class="col-form-label" for="u">Username</label><br>
        <input type="text" name="username" id="u" required/><br>
        <br>
        <label class="col-form-label" for="p">Password</label><br>
        <input type="password" name="password" id="p" required/><br>
        <br>
        <input type="submit" class="btn btn-primary" value="Login" onclick="formhash(this.form,this.form.p);"/>
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