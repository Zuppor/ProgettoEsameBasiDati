<script type="text/javascript">

    function setOption(selectElement, value) {
        var data = value.split(" ");
        //value = value.substr(0,value.indexOf(" "));
        //alert(value);
        var options = selectElement.options;
        for (var i = 0, optionsLength = options.length; i < optionsLength; i++) {
            if (options[i].value === data[0]) {
                selectElement.selectedIndex = i;

                var bet = document.getElementById("new_bet");
                var opLength = bet.options.length;
                var j;
                for(j = 0;j<opLength;j++){
                    if(bet.options[j].value === data[1]){
                        bet.selectedIndex = j;
                    }
                }

                document.getElementById("sum").value = data[2];

                var cur = document.getElementById("currency");
                opLength = cur.options.length;
                for(j=0;j<opLength;j++){
                    if(cur.options[j].value === data[3]){
                        cur.selectedIndex = j;
                    }
                }

                return true;
            }
        }


        selectElement.selectedIndex = 0;
        return false;
    }

    function updateForm(val){
        if(setOption(document.getElementById("match"),val)){
            toggle_buttons(false);
        }
        else{
            toggle_buttons(true);
        }
    }

    function toggle_buttons(t){
        document.getElementById("modify").disabled = t;
        document.getElementById("match").disabled = t;
        document.getElementById("new_bet").disabled = t;
        document.getElementById("sum").disabled = t;
        document.getElementById("currency").disabled = t;
    }
</script>
<form action="../../backend/update_bet.php" method="post">
    <div class="form-group">

        <label for="bet">Bets placed by you:</label><br>
        <select name="bet" id="bet" class="form-control" onchange="updateForm(this.value)" required>
            <option>Nothing selected</option>
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
                <option value="<?php echo $row['match_id'].' '.$row['bet'].' '.$row['value'].' '.$row['currency_id']; ?>">
                    <?php echo "Date: ".$row['date']." | Home team: ".$row['thl']." (".$row['ths'].") | Away team: ".$row['tal']." (".$row['tas'].") | Bet: ".$row['bet']." | Value: ".$row['currency_id']." ".$row['value']; ?>
                </option>
                <?php
            }
            ?>
        </select><br>





            <div class="form-group">
                <label for="match">Select one match to replace</label><br>
                <select name="match" id="match" class="form-control" onchange="toggle_buttons(false)" required disabled>
                    <?php

                    $resource = pg_query($db,"select m.id,date,stage,th.long_name as thl,th.short_name as ths,ta.long_name as tal,ta.short_name as tas 
                        from public.match m
                        join team th on m.home_team_id = th.id
                        join team ta on m.away_team_id = ta.id
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
                <label for="new_bet">Bet on:</label>
                <select name="new_bet" id="new_bet" class="form-control-sm" required disabled>
                    <option value="h">Home Team</option>
                    <option value="a">Away Team</option>
                    <option value="d">Draw</option>
                </select>
            </div>
            <div class="form-group">
                <label for="sum">Sum to bet: </label>
                <input name="sum" id="sum" type="number" value="0.01" min="0.01" step="any" required disabled/>
                <label for="currency">Currency:</label>
                <select name="currency" id="currency" class="form-control-sm" required disabled>
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