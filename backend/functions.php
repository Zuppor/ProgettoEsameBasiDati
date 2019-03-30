<?php
/**
 * Created by PhpStorm.
 * User: andrea
 * Date: 10/02/19
 * Time: 16.51
 */

function start_secure_session(){
    $session_name = 'secure_session_id';
    $secure = false;// impostare a TRUE se si desidera utilizzare https
    $httponly = true;// impedisci a javascript di accedere all'id della sessione

    ini_set('session.use_only_cookies',1);//forza la sessione ad utilizzare solo cookie
    $cookieParams = session_get_cookie_params();// legge parametri correnti dei cookie
    session_set_cookie_params($cookieParams["lifetime"],$cookieParams["path"],$cookieParams["domain"],$secure,$httponly);
    session_name($session_name);// imposta nome sessione
    session_start();//avvia sessione
    session_regenerate_id();//rigenera la sessione per prevenire hijacking
}

function login($username,$password,$db){
    $resource = pg_prepare($db, "cmd", "select id,username,password,salt,level from users where username = $1 limit 1");
    $resource = pg_execute($db, "cmd", array($username));

    if (pg_num_rows($resource) == 1) {//se l'utente esiste
        $row = pg_fetch_row($resource, null, PGSQL_ASSOC);

        //verifica del bruteforce
        if (checkbrute($row['id'], $db) == true) {
            //l'account è disabilitato
            return 'Account temporanemanete disabilitato per tentato bruteforce';

        } else {
            //controlla se la password è giusta
            $password = hash('sha512', $password . $row['salt']);
            if ($row['password'] == $password) {
                //password corretta
                $user_browser = $_SERVER['HTTP_USER_AGENT'];//recupero parametro user-agent dell'utente corrente

                $row['id'] = preg_replace("/[^0-9]+/", "", $row['id']);//protezione da attacco xss
                $_SESSION['user_id'] = $row['id'];

                $row['username'] = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $row['username']);//protezione da attacco xss
                $_SESSION['username'] = $row['username'];

                $_SESSION['login_string'] = hash('sha512', $password.$user_browser);

                $_SESSION['user_level'] = $row['level'];

                //login eseguito
                return true;

            } else {
                //password errata

                //registrazione tentativo fallito
                $now = time();
                pg_query($db, "insert into login_attempt (user_id,time) values ('" . $row['id'] . "','" . $now . "')");
                return 'username o password errati';
            }
        }
    } else {
        //l'utente non esiste
        return 'Utente non presente nel database';
    }

}

function checkbrute($user_id,$db){
    $now = time();

    //analizzo tutti i tentativi di accesso delle ultime 2 ore
    $valid_attempts = $now - (7200);
    $resource = pg_prepare($db,"cmd","select time from login_attempt where user_id = $1 and time > $2");
    $value = array($user_id,$valid_attempts);
    $resource = pg_execute($db,"cmd",$value);

    //verifico se il login è fallito più di 5 volte
    if(pg_num_rows($resource) > 5){
        return true;
    }
    else{
        return false;
    }
}

function login_check($db){
    //verifca che tutte le variabili di sessione siano impostate correttamente
    if(isset($_SESSION['user_id'],$_SESSION['username'],$_SESSION['login_string'],$_SESSION['user_level'])){

        $resource = pg_prepare($db,null,"select password from users where id = $1 limit 1");
        $resource = pg_execute($db,null,array($_SESSION['user_id']));

        if(pg_num_rows($resource) == 1){//se l'utente esiste
            $row = pg_fetch_row($resource,null,PGSQL_ASSOC);

            $login_check = hash('sha512',$row['password'].$_SERVER['HTTP_USER_AGENT']);

            if($login_check == $_SESSION['login_string']){
                //login eseguito correttamente
                return true;
            }
            else{
                return 'login_string non corrispondente: '.$_SESSION['login_string'].' , '.$login_check;
            }
        }
        else{
            return 'utente inesistente';
        }
    }
    else{
        return 'variabili non impostate correttamente:'.$_SESSION['user_id'].' '.$_SESSION['username']. ' '.$_SESSION['login_string'].' '.$_SESSION['user_level'];
    }
}

function register_new_user($username,$password,$level,$society,$db){
    //verifica che l'utente non sia già registrato
    $resource = pg_prepare($db,"cmd","select username from users where username = ?");
    $resource = pg_execute($db,"cmd",array($_POST['username']));

    if(pg_num_rows($resource)>=1){
        //l'utente esiste già
        return 'username già presente nel database';
    }
    else{
        //l'utente non esiste

        pg_free_result($resource);

        //se è di livello 2, controlla che la società sia settata
        if($level == 2 && !isset($society)){
            return 'società inesistente';
        }

        //genera chiave casuale
        $random_salt = hash('sha512',uniqid(mt_rand(1,mt_getrandmax()),true));

        //crea password usando il salt
        $password = hash('sha512',$password.$random_salt);

        $resource = pg_prepare($db,"cmd","insert into users (username,password,salt,level,society_id) values ($1,$2,$3,$4,$5)");
        $resource = pg_execute($db,"cmd",array($username,$password,$random_salt,$level,$society));


        if(pg_affected_rows($resource) == 0){
            return 'errore sconosciuto';
        }

        return true;
    }
}