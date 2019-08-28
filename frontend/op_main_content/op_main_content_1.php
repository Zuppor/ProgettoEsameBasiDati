<script type="text/javascript">
    function toggle_buttons(t){
        document.getElementById("delete").disabled = t;
    }
</script>
<form action="../../backend/delete_matches.php" method="post">
    <div class="form-group">

        <label for="matches">Matches inserted by you:</label><br>
        <select multiple name="matches[]" id="matches" class="form-control" onchange="toggle_buttons(false)" required>
            <?php

            include_once '../../backend/db_connect_login.php';


            $resource = pg_prepare($db,"","select m.id,m.date,m.stage,th.long_name as thl,th.short_name as ths,ta.long_name as tal,ta.short_name as tas 
                from public.match m
                join team th on m.home_team_id = th.id
                join team ta on m.away_team_id = ta.id
                where m.operator_id = $1
                order by date desc");

            $resource = pg_execute($db,"",array($_SESSION['user_id']));


            while($row = pg_fetch_array($resource,null,PGSQL_ASSOC)) {
                ?>
                <option value="<?php echo $row['id']; ?>">
                    <?php echo "Date: ".$row['date']." | Stage: ".$row['stage']." | Home team: ".$row['thl']." (".$row['ths'].") | Away team: ".$row['tal']." (".$row['tas'].") "; ?>
                </option>
                <?php
            }
            ?>


        </select><br>
    </div>
    <div class="form-group">
        <ul class="nav nav-justified">
            <li>
                <input type="reset" class="btn btn-outline-primary mr-3" name="reset" id="reset" value="Reset selection" onclick="toggle_buttons(true)"/>
            </li>
            <li>
                <input type="submit" class="btn btn-danger" name="submit" id="delete" value="Delete" disabled/>
            </li>
        </ul>
    </div>
</form>