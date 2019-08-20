<?php

include_once '../backend/db_connect_login.php';

?>

<form action="../backend/insert_match_from_form.php" method="post">
    <div class="form-group">
        <label for="country">Country</label>
        <select name="country" id="country" class="custom-select-sm" required>
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
        <select name="league" id="league" class="custom-select-sm" required>
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
        <input type="number" name="season" id="season" value="" min="0" oninput="adjustSeason(this.value)" required/> <p id="seasonComp"></p><br>

        <label for="stage">Stage</label>
        <input type="number" name="stage" id="stage" value="1" min="1" required/><br>

        <label for="date">Date</label>
        <input type="date" name="date" id="date" value="" required/><br>

        <?php
        $resource = pg_query($db,"select id,long_name,short_name from team order by long_name");

        $rows = pg_fetch_all($resource);
        ?>

        <label for="team_h">Team home</label>
        <select name="team_h" id="team_h" class="custom-select-sm" onchange="checkTeams(this,this.form.team_a)" required>
            <?php
            for($i = 0;$i<sizeof($rows);$i++) {
                ?>
                <option value="<?php echo $rows[$i]['id']; ?>"><?php echo $rows[$i]['long_name'] . ' (' . $rows[$i]['short_name'] . ')'; ?></option>
                <?php
            }
            ?>

        </select><br>

        <label for="team_a">Team away</label>
        <select name="team_a" id="team_a" class="custom-select-sm" onchange="checkTeams(this,this.form.team_h)" required>

            <?php
            for($i = 0;$i<sizeof($rows);$i++) {
                ?>
                <option value="<?php echo $rows[$i]['id']; ?>"><?php echo $rows[$i]['long_name'] . ' (' . $rows[$i]['short_name'] . ')'; ?></option>
                <?php
            }
            ?>

        </select><p class="text-danger" id="teamComp"></p> <br>


        <label for="h_goal">Home goals</label>
        <input type="number" name="h_goal" id="h_goal" value="0" min="0" required/><br>

        <label for="a_goal">Away goals</label>
        <input type="number" name="a_goal" id="a_goal" value="0" min="0" required/><br>

        <input type="submit" class="btn btn-primary" name="submit" value="Submit"/>
    </div>
</form>
