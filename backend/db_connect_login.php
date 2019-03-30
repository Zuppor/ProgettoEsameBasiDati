<?php
/**
 * Created by PhpStorm.
 * User: andrea
 * Date: 10/02/19
 * Time: 16.43
 */

define("HOST","localhost");
define("PORT","5432");
define("USER","login_user");
define("PASSWORD","rH4KJz5Es2ex7QUqvVntMjSM");
define("DATABASE","soccerdb");

$db = pg_connect("host=" . HOST . " port=" . PORT . " user=" . USER . " password=" . PASSWORD . " dbname=" . DATABASE);

if(!$db){
    die("Connection error: login_user");
}
