<script type="text/javascript">
    /*    function submit(form,action){
            var act = document.createElement("input");

            form.appendChild(act);

            act.name = "action";
            act.type = "hidden";
            act.value = action;

            form.submit();
        }*/

    function setOption(selectElement, value) {
        var options = selectElement.options;
        for (var i = 0, optionsLength = options.length; i < optionsLength; i++) {
            if (options[i].value === value) {
                selectElement.selectedIndex = i;
                return true;
            }
        }
        return false;
    }

    function updateForm(){
        setOption(document.getElementById("match"),)
        document.getElementById("match")
    }

    function toggle_buttons(t){
        document.getElementById("modify").disabled = t;
        document.getElementById("delete").disabled = t;
    }
</script>
<form action="../../backend/manage_bets.php" method="post">
    <div class="form-group">

        <label for="bet">Bets placed by your society:</label><br>
        <select name="bet" id="bet" class="form-control" onchange="updateForm(this.value)" required>
            <?php

            include_once '../../backend/db_connect_login.php';

            $resource = pg_prepare($db,"","select b.match_id,b.bet,b.value,b.currency_id,m.date,th.long_name as thl,th.short_name as ths,ta.long_name as tal,ta.short_name as tas 
            from bets b         
            join public.match m on b.match_id = m.id
            join team th on public.match.home_team_id = th.id
            join team ta on public.match.away_team_id = ta.id
            where operator_id like $1
            order by date desc");

            $resource = pg_execute($db,"",array($_SESSION['user_id']));

            while($row = pg_fetch_array($resource,null,PGSQL_ASSOC)) {
                ?>
                <option value="<?php echo $row['match_id'].' '.$row['bet'].' '.$row['value'].' '.$row['currency_id']; ?>">
                    <?php echo "Date: ".$row['date']." | Home team: ".$row['thl']." (".$row['ths'].") | Away team: ".$row['tal']." (".$row['tas'].") "; ?>
                </option>
                <?php
            }
            ?>
        </select><br>





            <div class="form-group">
                <label for="match">Select one or more matches</label><br>
                <select name="match" id="match" class="form-control" onchange="toggle_buttons(false)" required>
                    <?php

                    //include_once '../../backend/db_connect_login.php';

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





    </div>
    <div class="form-group">
        <ul class="nav nav-justified">
            <li>
                <input type="reset" class="btn btn-outline-primary mr-3" name="reset" id="reset" value="Reset selection" onclick="toggle_buttons(true)"/>
            </li>
            <li>
                <input type="submit" class="btn btn-primary" name="submit" id="modify" value="Modify" disabled/>
            </li>
        </ul>
    </div>

</form>