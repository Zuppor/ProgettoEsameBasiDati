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
    ?>

<div class="container-fluid">
    <?php
        if(isset($_GET['error'])){
            echo '<div class="alert alert-danger" role="alert">'.$_GET['error'].'</div><br><br>';
        }
        elseif (isset($_GET['success'])){
            echo '<div class="alert alert-success" role="alert">'.$_GET['success'].'</div><br><br>';
        }
    ?>
    <div class="row flex-xl-nowrap">
<?php



    if(login_check($db) === true):
        switch ($_SESSION['user_level']){





            //ADMIN:
            case 0:
?>
            <div class="col-12 col-md-3 col-xl-2 border-right border-secondary">
                <ul class="nav nav-pills align-items-start flex-column">
                    <?php
                        $items = array("Upload from file","Manage matches","Manage leagues","Manage player attributes","Manage teams","Manage countries");

                        for($i = 0;$i<count($items);$i++){
                            ?>
                            <li class="nav-item">
                                <a class="nav-link <?php if(isset($_GET['sidenav_active'])) echo $_GET['sidenav_active']==$i?"active":"";else if($i == 0){ echo "active";$_GET['sidenav_active'] = 0;}?>" href="/frontend/home.php?sidenav_active=<?php echo $i; ?>">
                                    <?php echo $items[$i]; ?>
                                </a>
                            </li>
                    <?php
                        }
                    ?>
                </ul>
            </div>



            <main class="col-12 col-ms-9 col-xl-8 py-md-3 pl-md-5" role="main">
            <?php
            include_once 'admin_main_content/admin_content_'.$_GET['sidenav_active'].'.php';
            /*if($_GET['sidenav_active'] == 0)
                include_once 'admin_main_content/admin_content_0.php';
            else
                include_once "admin_main_content/admin_content.php?content=".$_GET['sidenav_active'];*/
        ?>
            </main>
    <?php
    break;





    //OPERATORE:
    case 1:
        ?>
        <div class="col-12 col-md-3 col-xl-2 border-right border-secondary">
            <ul class="nav nav-pills align-items-start flex-column">
                <?php
                $items = array("Insert match","Manage matches");

                for($i = 0;$i<count($items);$i++){
                    ?>
                    <li class="nav-item">
                        <a class="nav-link <?php if(isset($_GET['sidenav_active'])) echo $_GET['sidenav_active']==$i?"active":"";else if($i == 0){ echo "active";$_GET['sidenav_active'] = 0;}?>" href="/frontend/home.php?sidenav_active=<?php echo $i; ?>">
                            <?php echo $items[$i]; ?>
                        </a>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </div>
        <main class="col-12 col-ms-9 col-xl-8 py-md-3 pl-md-5" role="main">
        <?php
        switch ($_GET['sidenav_active']){
            case 0:
                include_once 'form_insert_match.php';
                break;
            case 1:
                include_once 'op_main_content/op_main_content_1.php';
                break;
            default:
                echo "Nothing to display.";
        }
        //include_once 'op_main_content/op_main_content_'.$_GET['sidenav_active'].'.php';
        ?>
        </main>
<?php break;






//PARTNER:
case 2:?>
    <div class="col-12 col-md-3 col-xl-2 border-right border-secondary">
        <ul class="nav nav-pills align-items-start flex-column">
            <?php
            $items = array("Insert bet","Delete bets","Modify bets");

            for($i = 0;$i<count($items);$i++){
                ?>
                <li class="nav-item">
                    <a class="nav-link <?php if(isset($_GET['sidenav_active'])) echo $_GET['sidenav_active']==$i?"active":"";else if($i == 0){ echo "active";$_GET['sidenav_active'] = 0;}?>" href="/frontend/home.php?sidenav_active=<?php echo $i; ?>">
                        <?php echo $items[$i]; ?>
                    </a>
                </li>
                <?php
            }
            ?>
        </ul>
    </div>
    <main class="col-12 col-ms-9 col-xl-8 py-md-3 pl-md-5" role="main">
        <?php

        include_once 'partner_main_content/partner_main_content_'.$_GET['sidenav_active'].'.php';

        /*
        switch ($_GET['sidenav_active']){
            case 0:
                include_once 'partner_main_content_0.php';
                break;
            case 1:
                include_once 'partner_main_content/partner_main_content_1.php';
                break;
            default:
                echo "Nothing to display.";
        }*/
        //include_once 'op_main_content/op_main_content_'.$_GET['sidenav_active'].'.php';
        ?>
    </main>
<?php break;






//SCONOSCIUTO
            default:
                die('Livello utente sconosciuto: '.$_SESSION['user_level']);
                break;
    }?>



<!--
        <a href="classification.php">Visualizza classifica</a><br>
        <a href="../backend/process_logout.php">Logout</a>
        -->
   <?php else:?>
Devi <a href="login.php">accedere ad un account</a> prima di entrare in questa pagina<br>
<?php endif;?>

    </div>
</div>
</body>

</html>