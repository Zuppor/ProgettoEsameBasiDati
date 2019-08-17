<?php
include_once 'backend/functions.php';
include_once 'backend/db_connect_login.php';

start_secure_session();
?>
<nav class="navbar navbar-expand-md navbar-light bg-light" role="navigation">
    <a class="navbar-brand" href="../index.php">LottoCalcio</a>

    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="/frontend/classification.php">Classifica</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/frontend/best_players.php">Best players</a>
            </li>
            <?php
            if(login_check($db) === true) {
            ?>
            <li class="nav-item">
                <a class="nav-link" href="/frontend/home.php">Dashboard</a>
            </li>
                <?php
            } ?>
        </ul>
    </div>
    <span>
        <?php
        if(login_check($db) === true) {
            ?>
            Hello, <?php echo $_SESSION['username']." " ?> <a href="/backend/process_logout.php" class="btn btn-outline-primary">Logout</a>
            <?php
        }
        else{
            ?>
            <a href="/frontend/login.php" class="btn btn-outline-primary">Login</a>
            <a href="/frontend/register.php" class="btn btn-outline-primary">Sign up</a>
        <?php }?>
    </span>
</nav>
