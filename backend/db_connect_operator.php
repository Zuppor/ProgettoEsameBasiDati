<?php
define("HOST","localhost");
define("PORT","5432");
define("USER","operatore");
define("PASSWORD","P4pj92v5Gk7sDk8NaWaNTK2h");
define("DATABASE","soccerdb");

$db = pg_connect("host=" . HOST . " port=" . PORT . " user=" . USER . " password=" . PASSWORD . " dbname=" . DATABASE);

if(!$db){
    die("Connection error: ".USER);
}