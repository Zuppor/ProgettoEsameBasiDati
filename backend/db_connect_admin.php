<?php
/**
 * Created by PhpStorm.
 * User: andrea
 * Date: 13/02/19
 * Time: 15.43
 */

define("HOST","localhost");
define("PORT","5432");
define("USER","amministratore");
define("PASSWORD","V4Nb3Vy4QJHmgGL9bj7Npds9");
define("DATABASE","soccerdb");

$db = pg_pconnect("host=" . HOST . " port=" . PORT . " user=" . USER . " password=" . PASSWORD . " dbname=" . DATABASE);

if(!$db){
    die("Connection error: amministratore");
}