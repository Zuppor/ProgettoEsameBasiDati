<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="bootstrap-4.3.1-dist/css/bootstrap.min.css">
    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="bootstrap-4.3.1-dist/js/bootstrap.min.js"></script>
    <title>Main</title>
</head>
<?php

include_once 'frontend/navbar.php'
?>
<body>
<div id="carouselElement" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        <li data-target="#carouselElement" data-slide-to="0" class="active"></li>
        <li data-target="#carouselElement" data-slide-to="1"></li>
        <li data-target="#carouselElement" data-slide-to="2"></li>
    </ol>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="images/c1.jpeg" class="d-block w-100" alt="Calcio">
            <div class="carousel-caption d-none d-md-block" style="background-color: rgba(0, 0, 0, 0.5);">
                <h5><b>Competizione</b></h5>
                <p>Controlla la classifica aggiornata</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="images/c2.jpeg" class="d-block w-100" alt="Sport">
            <div class="carousel-caption d-none d-md-block" style="background-color: rgba(0, 0, 0, 0.5);">
                <h5><b>Campionati</b></h5>
                <p>Segui le tue squadre preferite</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="images/c3.jpeg" class="d-block w-100" alt="Scommesse">
            <div class="carousel-caption d-none d-md-block" style="background-color: rgba(0, 0, 0, 0.5);">
                <h5><b>Scommesse</b></h5>
                <p>Unisciti ai partner e piazza scommesse</p>
            </div>
        </div>
    </div>
    <a class="carousel-control-prev" href="#carouselElement" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselElement" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>
</body>
</html>