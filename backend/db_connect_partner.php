<?php
/**
 * Created by PhpStorm.
 * User: andrea
 * Date: 13/02/19
 * Time: 15.43
 */

define("HOST","localhost");
define("PORT","5432");
define("USER","partner");
define("PASSWORD","sL3FBmAxjFYnjsgBBN4HV8UF");
define("DATABASE","soccerdb");

$db = pg_connect("host=" . HOST . " port=" . PORT . " user=" . USER . " password=" . PASSWORD . " dbname=" . DATABASE);

if(!$db){
    die("Connection error: ".USER);
}