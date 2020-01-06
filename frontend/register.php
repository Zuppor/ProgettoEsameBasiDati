<!DOCTYPE HTML>
<html lang="it">
<head>
    <script type="text/javascript" src="../js/sha512.js"></script>
    <script type="text/javascript" src="../js/forms.js"></script>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="stylesheet" href="../bootstrap-4.3.1-dist/css/bootstrap.min.css"/>
    <title>Register</title>
</head>
<script type="text/javascript">
    var p1;
    var p2;
    var msg;
    var soc;
    var socLabel;

    function onload(){
        p1 = document.getElementById("p1");
        p2 = document.getElementById("p2");
        msg = document.getElementById("pmsg");
        soc = document.getElementById("society");
        socLabel = document.getElementById("socLabel");
        toggle_society_select('0');
    }

    function check_password() {
        if(p1.value.localeCompare("") !== 0 && p2.value.localeCompare("") !== 0){
            if(p2.value.localeCompare(p1.value) !== 0){
                msg.className = "text-danger";
                msg.innerHTML = "Le password non coincidono";
                p2.setCustomValidity("Le password non coincidono");
            }
            else{
                msg.className = "text-success";
                msg.innerHTML = "Ok";
                p2.setCustomValidity("");
            }
        }
        else{
            msg.innerHTML = "";
        }
    }

    function toggle_society_select(x){
        if(x === '2'){
            soc.style.visibility = "visible";
            socLabel.style.visibility = "visible";
        }
        else{
            soc.style.visibility = "hidden";
            socLabel.style.visibility = "hidden";
        }
    }
</script>
<body onload="onload()">
<?php
    if(isset($_GET['error'])){
        echo '<div class="alert alert-danger" role="alert">'.$_GET['error'].'</div><br><br>';
    }
    elseif (isset($_GET['success'])){
        echo '<div class="alert alert-success" role="alert">'.$_GET['success'].'</div><br><br>';
    }
?>

    <form method="post" action="../backend/process_registration.php">
        <label class="col-form-label" for="u">Username</label><br>
        <input type="text" name="username" value="" id="u" maxlength="100" minlength="1" oninvalid="alert('Il nome utente è obbligatorio');" required/><br>

        <label class="col-form-label" for="p1">Password (4 - 10 characters)</label><br>
        <input type="password" name="pass" value="" id="p1" oninput="check_password()" minlength="4" maxlength="10" required/><br>

        <label class="col-form-label" for="p2">Repeat password</label><br>
        <input type="password" name="pass2" value="" onchange="" oninvalid="alert('Le password devono essere uguali e non vuote');" id="p2" oninput="check_password()" minlength="4" maxlength="10" required/>
        <p id="pmsg"></p> <br>

        <label class="col-form-label" for="level">Level</label>
        <select name="level" class="custom-select-sm" id="level" onchange="toggle_society_select(this.value)" required>
            <option value="0">Amministratore</option>
            <option value="1">Operatore</option>
            <option value="2">Partner</option>
        </select><br>

        <label class="col-form-label" for="society" id="socLabel">Bet society</label>
        <select name="society" class="custom-select-sm" id="society" required>
            <option value="null">Nessuna</option>
            <?php
                include '../backend/db_connect_login.php';
                $resource = pg_query($db,"select id,long_name from bet_society");

                while($row = pg_fetch_array($resource,null,PGSQL_ASSOC)){
                    ?>
                    <option value="<?php echo $row['id'];?>"><?php echo $row['long_name'];?></option>
               <?php }?>
        </select><br><br>
        <input type="button" class="btn btn-primary" value="Register" onclick="formhash(this.form,this.form.p1,this.form.p2);"/>
        <!--<input type="submit" class="btn btn-primary" value="Register" onclick="formhash(this.form,this.form.p1,this.form.p2);"/>-->
    </form>
<button onclick="goBack()" class="btn btn-outline-secondary">< Back</button>

<script type="text/javascript">
    function goBack() {
        window.history.back();
    }
</script>
</body>
</html>