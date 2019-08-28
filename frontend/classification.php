<html  lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../bootstrap-4.3.1-dist/css/bootstrap.min.css">
    <title>Classification</title>
</head>
<body>
<?php
include '../backend/functions.php';
include '../backend/db_connect_login.php';

start_secure_session();

$_GET['navbar_active'] = 1;

include_once 'navbar.php';
?>
<table class="table">
    <thead class="thead-light">
        <tr>
            <th>Date</th>
            <th>League</th>
            <th>Season</th>
            <th>Stage</th>
            <th>Home team</th>
            <th>Away team</th>
            <th>Home team goals</th>
            <th>Away team goals</th>
        </tr>
    </thead>
    <tbody>
    <?php
        //include '../backend/db_connect_login.php';


        $resource = pg_query($db,"select date::date as d,to_char(date,'HH24:MM') as t,name,stage,season,team_a,team_h,h_team_goal,a_team_goal from public.classifica");


        while($row = pg_fetch_array($resource,null,PGSQL_ASSOC)){
            ?>
            <tr>
                <td><?php echo $row['d'].' '.$row['t']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['season']; ?></td>
                <td><?php echo $row['stage']; ?></td>
                <td><?php echo $row['team_h']; ?></td>
                <td><?php echo $row['team_a']; ?></td>
                <td><?php echo $row['h_team_goal']; ?></td>
                <td><?php echo $row['a_team_goal']; ?></td>
            </tr>
    <?php } ?>
    </tbody>
</table>

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