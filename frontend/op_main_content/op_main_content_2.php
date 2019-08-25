<script type="text/javascript">
    function updateForm(value){

        if(value.localeCompare("null") === 0){
            toggle_buttons(true);
            return;
        }

        var data = value.split(" ");

        selectIndex(document.getElementById("country"),data[1]);

        selectIndex(document.getElementById("league"),data[2]);

        document.getElementById("season").value = data[3];

        document.getElementById("stage").value = data[4];

        document.getElementById("date").value = data[5];

        selectIndex(document.getElementById("team_h"),data[6]);

        selectIndex(document.getElementById("team_a"),data[7]);

        document.getElementById("h_goal").value = data[8];

        document.getElementById("a_goal").value = data[9];

        toggle_buttons(false);
    }

    function selectIndex(selectElement,val){
        var options = selectElement.options;

        for(var i = 0, optionsLength = options.length;i<optionsLength;i++){
            if(options[i].value === val){
                selectElement.selectedIndex = i;
                return;
            }
        }
    }

    function adjustSeason(year){
        year = Number(year);
        year++;
        document.getElementById("seasonComp").innerHTML = "/" + year;
    }

    function checkTeams(team1,team2){
        if(team1.value === team2.value){
            document.getElementById("teamComp").innerHTML = "Le squadre devono essere diverse";
        }
        else{
            document.getElementById("teamComp").innerHTML = "";
        }
    }

    function toggle_buttons(t){

        document.getElementById("country").disabled = t;

        document.getElementById("league").disabled = t;

        document.getElementById("season").disabled = t;

        document.getElementById("stage").disabled = t;

        document.getElementById("date").disabled = t;

        document.getElementById("team_h").disabled = t;

        document.getElementById("team_a").disabled = t;

        document.getElementById("h_goal").disabled = t;

        document.getElementById("a_goal").disabled = t;

        document.getElementById("modify").disabled = t;
    }
</script>
<form action="../../backend/update_match.php" method="post">
    <div class="form-group">

        <label for="match">Select one or more matches</label><br>
        <select name="match" id="match" class="form-control" onchange="updateForm(this.value)" required>
            <option value="null">Nothing selected</option>
            <?php

            include_once '../../backend/db_connect_login.php';

            $resource = pg_prepare($db,"","select m.id,m.date::timestamp::date,m.season,m.stage,m.country_id,m.league_id,th.id as thid,ta.id as taid,m.h_team_goal,m.a_team_goal,th.long_name as thl,th.short_name as ths,ta.long_name as tal,ta.short_name as tas 
            from public.match m
            join team th on m.home_team_id = th.id
            join team ta on m.away_team_id = ta.id
            where m.operator_id = $1
            order by date desc");

            $resource = pg_execute($db,"",array($_SESSION['user_id']));

            while($row = pg_fetch_array($resource,null,PGSQL_ASSOC)) {
                ?>
                <option value="<?php echo $row['id'].' '.$row['country_id'].' '.$row['league_id'].' '.$row['season'].' '.$row['stage'].' '.$row['date'].' '.$row['thid'].' '.$row['taid'].' '.$row['h_team_goal'].' '.$row['a_team_goal']; ?>">
                    <?php echo "Date: ".$row['date']." | Stage: ".$row['stage']." | Home team: ".$row['thl']." (".$row['ths'].") | Away team: ".$row['tal']." (".$row['tas'].") "; ?>
                </option>
                <?php
            }
            ?>
        </select><br>
    </div>


    <div class="form-group">
        <label for="country">Country</label>
        <select name="country" id="country" class="custom-select-sm" required disabled>
            <?php
            $resource = pg_query($db,"select id,name from country order by name");

            while($row = pg_fetch_array($resource,null,PGSQL_ASSOC)) {
                ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                <?php
            }
            ?>

        </select><br>

        <label for="league">League</label>
        <select name="league" id="league" class="custom-select-sm" required disabled>
            <?php
            $resource = pg_query($db,"select id,name from league order by name");

            while($row = pg_fetch_array($resource,null,PGSQL_ASSOC)) {
                ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                <?php
            }
            ?>

        </select><br>

        <label for="season">Season</label>
        <input type="number" name="season" id="season" value="" min="0" oninput="adjustSeason(this.value)" required disabled/> <p id="seasonComp"></p><br>

        <label for="stage">Stage</label>
        <input type="number" name="stage" id="stage" value="1" min="1" required disabled/><br>

        <label for="date">Date</label>
        <input type="date" name="date" id="date" value="" required disabled/><br>

        <?php
        $resource = pg_query($db,"select id,long_name,short_name from team order by long_name");

        $rows = pg_fetch_all($resource);
        ?>

        <label for="team_h">Team home</label>
        <select name="team_h" id="team_h" class="custom-select-sm" onchange="checkTeams(this,this.form.team_a)" required disabled>
            <?php
            for($i = 0;$i<sizeof($rows);$i++) {
                ?>
                <option value="<?php echo $rows[$i]['id']; ?>"><?php echo $rows[$i]['long_name'] . ' (' . $rows[$i]['short_name'] . ')'; ?></option>
                <?php
            }
            ?>

        </select><br>

        <label for="team_a">Team away</label>
        <select name="team_a" id="team_a" class="custom-select-sm" onchange="checkTeams(this,this.form.team_h)" required disabled>

            <?php
            for($i = 0;$i<sizeof($rows);$i++) {
                ?>
                <option value="<?php echo $rows[$i]['id']; ?>"><?php echo $rows[$i]['long_name'] . ' (' . $rows[$i]['short_name'] . ')'; ?></option>
                <?php
            }
            ?>

        </select disabled><p class="text-danger" id="teamComp"></p> <br>


        <label for="h_goal">Home goals</label>
        <input type="number" name="h_goal" id="h_goal" value="0" min="0" required disabled/><br>

        <label for="a_goal">Away goals</label>
        <input type="number" name="a_goal" id="a_goal" value="0" min="0" required disabled/><br>
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
    <script type="text/javascript">
        (function () {
            var y = new Date().getFullYear();

            document.getElementById("season").setAttribute("value",y.toString());
            y++;
            document.getElementById("seasonComp").innerHTML = "/" + y
        })();
    </script>
</form>