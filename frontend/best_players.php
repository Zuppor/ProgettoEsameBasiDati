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

include_once 'navbar.php';
?>
<!--
<button onclick="goBack()" class="btn btn-outline-secondary">< Back</button>

<script>
    function goBack() {
        window.history.back();
    }
</script>
-->
</body>
</html>