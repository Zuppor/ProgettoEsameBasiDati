<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="bootstrap-4.3.1-dist/css/bootstrap.min.css">
    <title>Main</title>
</head>
<?php

include_once 'frontend/navbar.php'
?>
<body>
<div id="carouselContent" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        <li data-target="#carouselContent" data-slide-to="0" class="active"></li>
        <li data-target="#carouselContent" data-slide-to="1"></li>
        <li data-target="#carouselContent" data-slide-to="2"></li>
    </ol>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="images/c1.jpeg" class="d-block w-100" alt="...">
        </div>
        <div class="carousel-item">
            <img src="images/c2.jpeg" class="d-block w-100" alt="...">
        </div>
        <div class="carousel-item">
            <img src="images/c3.jpeg" class="d-block w-100" alt="...">
        </div>
    </div>
    <a class="carousel-control-prev" href="#carouselContent" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselContent" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>
</body>

<script>
    function goPrev(){
        $('.carouselContent').carousel('prev');
    }

    function goNext(){
        $('.carouselContent').carousel('next');
    }

</script>
</html>