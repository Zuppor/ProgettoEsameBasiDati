<!-- DELETE BETS -->
<script type="text/javascript">
    function toggle_buttons(t){
        document.getElementById("delete").disabled = t;
    }
</script>
<form action="../../backend/delete_bets.php" method="post">
    <div class="form-group">


        <label for="bets">Bets placed by you:</label><br>
        <select multiple="multiple"  name="bets[]" id="bets" class="form-control" onchange="toggle_buttons(false)" required>
            <?php

            include_once '../../backend/db_connect_login.php';

            $resource = pg_prepare($db,"","select b.match_id,b.bet,b.value,b.currency_id,m.date,th.long_name as thl,th.short_name as ths,ta.long_name as tal,ta.short_name as tas 
            from bets b         
            join public.match m on b.match_id = m.id
            join team th on m.home_team_id = th.id
            join team ta on m.away_team_id = ta.id
            where b.partner_id = $1
            order by date desc");

            $resource = pg_execute($db,"",array($_SESSION['user_id']));

            if(!$resource){
                die("There was an error quering the bets");
            }

            while($row = pg_fetch_array($resource,null,PGSQL_ASSOC)) {
                ?>
                <option value="<?php echo $row['match_id'].' '.$row['bet'].' '.$row['currency_id']; ?>">
                    <?php echo "Date: ".$row['date']." | Home team: ".$row['thl']." (".$row['ths'].") | Away team: ".$row['tal']." (".$row['tas'].") | Bet: ".$row['bet']." | Value: ".$row['currency_id']." ".$row['value']; ?>
                </option>
                <?php
            }
            ?>


        </select><br>
    </div>
    <div class="form-group">
        <ul class="nav nav-justified">
            <li>
                <input type="reset" class="btn btn-outline-primary" name="reset" id="reset" value="Reset selection" onclick="toggle_buttons(true)"/>
            </li>
            <li>
                <input type="submit" class="btn btn-danger" name="submit" id="delete" value="Delete"  disabled/>
            </li>
        </ul>
    </div>

</form>