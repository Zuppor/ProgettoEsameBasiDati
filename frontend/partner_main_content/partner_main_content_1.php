<script type="text/javascript">
    /*    function submit(form,action){
            var act = document.createElement("input");

            form.appendChild(act);

            act.name = "action";
            act.type = "hidden";
            act.value = action;

            form.submit();
        }*/

    function toggle_buttons(t){
        document.getElementById("modify").disabled = t;
        document.getElementById("delete").disabled = t;
    }
</script>
<form action="../../backend/manage_bets.php" method="post">
    <div class="form-group">
        <label for="bets">Bets placed by your society:</label><br>


        <select multiple="multiple" name="bets[]" id="bets" class="form-control" onchange="toggle_buttons(false)" required>
            <?php

            include_once '../../backend/db_connect_login.php';

            $resource = pg_query($db,"select b.match_id,b.bet,b.value,b.currency_id,m.date,th.long_name as thl,th.short_name as ths,ta.long_name as tal,ta.short_name as tas 
            from bets b         
            join public.match m on b.match_id = m.id
            join team th on public.match.home_team_id = th.id
            join team ta on public.match.away_team_id = ta.id
            order by date desc");

            while($row = pg_fetch_array($resource,null,PGSQL_ASSOC)) {
                ?>
                <option value="<?php echo $row['match_id'].' '.$row['bet'].' '.$row['value'].' '.$row['currency_id']; ?>">
                    <?php echo "Date: ".$row['date']." | Home team: ".$row['thl']." (".$row['ths'].") | Away team: ".$row['tal']." (".$row['tas'].") "; ?>
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