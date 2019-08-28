<?php
include_once 'backend/functions.php';
include_once 'backend/db_connect_login.php';

start_secure_session();
?>
<header class="navbar navbar-expand-md navbar-light bg-white">
    <a class="navbar-brand" href="../index.php">LottoCalcio</a>

    <?php
    if(!isset($_GET['navbar_active'])){
        $_GET['navbar_active'] = 0;
    }
    ?>

    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link <?php if($_GET['navbar_active'] == 1) echo 'active'; ?>" href="/frontend/classification.php">Classifica</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php if($_GET['navbar_active'] == 2) echo 'active'; ?>" href="/frontend/best_players.php">Best players</a>
            </li>
            <?php
            if(login_check($db) === true) {
            ?>
            <li class="nav-item">
                <a class="nav-link <?php if($_GET['navbar_active'] == 3) echo 'active'; ?>" href="/frontend/home.php">Dashboard</a>
            </li>
                <?php
            } ?>
        </ul>
    </div>
    <span>
        <?php
        if(login_check($db) === true) {
            ?>
            Hello, <?php echo $_SESSION['username']."  "; ?><a href="/backend/process_logout.php" class="btn btn-outline-primary">Logout</a>
            <?php
        }
        else{
            ?>
            <a href="/frontend/login.php" class="btn btn-outline-primary">Login</a>
            <a href="/frontend/register.php" class="btn btn-outline-primary">Sign up</a>
        <?php }?>
    </span>
</header>
