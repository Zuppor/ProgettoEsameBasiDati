<html  lang="it">
<head>
    <meta charset="UTF-8">
    <title>Classification</title>
    <style>
        table,th,td{
            text-align: center;
            border: 1px solid black;
        }
    </style>
</head>
<body>
<table style="width: 100%">
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
    <?php
        include '../backend/db_connect_login.php';

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
</table>


<button onclick="goBack()">Go Back</button>

<script>
    function goBack() {
        window.history.back();
    }
</script>


</body>
</html>