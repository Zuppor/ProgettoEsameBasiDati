<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../bootstrap-4.3.1-dist/css/bootstrap.min.css">
    <title>Home</title>
</head>

<body>
<?php

    include '../backend/functions.php';
    include '../backend/db_connect_login.php';

    start_secure_session();

    $_GET['navbar_active'] = 3;

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
                        $items = array("Upload from file","Manage leagues","Manage teams","Manage countries");

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
                $items = array("Insert match","Delete matches","Modify match");

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

        if($_GET['sidenav_active'] == 0){
            include 'form_insert_match.php';
        }
        else{
            include_once 'op_main_content/op_main_content_'.$_GET['sidenav_active'].'.php';
        }
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
        ?>
    </main>
<?php break;






//SCONOSCIUTO
            default:
                die('Livello utente sconosciuto: '.$_SESSION['user_level']);
                break;
    }?>



   <?php else:?>
Devi <a href="login.php">accedere ad un account</a> prima di entrare in questa pagina<br>
<?php endif;?>

    </div>
</div>
</body>

</html>