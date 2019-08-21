<script type="text/javascript">
    function toggle_buttons(t){
        document.getElementById("submit").disabled = t;
    }
</script>
<form action="../backend/insert_bet.php" method="post">
    <div class="form-group">
        <label for="matches">Select one or more matches</label><br>
        <select multiple name="matches" id="matches" class="form-control" onchange="toggle_buttons(false)" required>
            <?php

            include_once '../backend/db_connect_login.php';

            $resource = pg_query($db,"select public.match.id,date,stage,th.long_name as thl,th.short_name as ths,ta.long_name as tal,ta.short_name as tas 
            from public.match
            join team th on public.match.home_team_id = th.id
            join team ta on public.match.away_team_id = ta.id
            order by date desc");

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
        <label for="bet">Bet on:</label>
        <select name="bet" id="bet" class="form-control-sm" required>
            <option value="h">Home Team</option>
            <option value="a">Away Team</option>
            <option value="d">Draw</option>
        </select>
    </div>
    <div class="form-group">
        <label for="sum">Sum to bet: </label>
        <input name="sum" id="sum" type="number" value="0.01" min="0.01" required/>
        <label for="currency">Currency:</label>
        <select name="currency" id="currency" class="form-control-sm" required>
            <?php
                $resource = pg_query($db,"select code from currency order by code");

                while($row = pg_fetch_array($resource,null,PGSQL_ASSOC)){
                    ?>
                    <option value="<?php echo $row['code'] ?>"><?php echo $row['code'] ?></option>
            <?php
                }
            ?>
        </select>
    </div>
    <div class="form-group">
        <ul class="nav nav-justified">
            <li>
                <input type="reset" class="btn btn-outline-primary mr-3" name="reset" id="reset" value="Reset" onclick="toggle_buttons(true)"/>
            </li>
            <li>
                <input type="submit" class="btn btn-primary mr-3" name="submit" id="submit" value="Insert bet" disabled/>
            </li>
        </ul>
    </div>

</form>