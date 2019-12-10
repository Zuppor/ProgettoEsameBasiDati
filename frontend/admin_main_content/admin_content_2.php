<!-- MANAGE TEAMS -->
<script type="text/javascript">
    function updateField(val){
        var team_l = document.getElementById("new_team_name_l");
        var team_s = document.getElementById("new_team_name_s");

        if(val.localeCompare("null") === 0){
            team_l.value = "";
            team_l.disabled = true;
            team_s.value = "";
            team_s.disabled = true;
            toggle_button('modify',true);
        }
        else{
            var data = val.split(";");
            team_l.disabled = false;
            team_l.value = data[1];
            team_s.disabled = false;
            team_s.value = data[2];
            toggle_button('modify',false);
        }
    }

    function toggle_button(button,state){
        document.getElementById(button).disabled = state;
    }
</script>




<!-- Insert form -->
<form action="../../backend/manage_team.php" method="post" class="border-bottom border-secondary" >
    <h1>Insert team</h1>
    <div class="form-group">

        <label for="team_name_l">Team long name: </label>
        <input type="text" name="team_l" id="team_name_l" oninput="if(this.value.localeCompare('') === 0) toggle_button('insert',true); else toggle_button('insert',false);" required/>

        <label for="team_name_s">Team short name: </label>
        <input type="text" name="team_s" id="team_name_s" minlength="3" maxlength="3" oninput="if(this.value.localeCompare('') === 0) toggle_button('insert',true); else toggle_button('insert',false);" required/>

        <ul class="nav nav-justified">
            <li>
                <input type="submit" class="btn btn-primary mr-3" name="submit" id="insert" value="Insert" disabled/>
            </li>
        </ul>
    </div>
</form>




<!-- Delete form -->
<form action="../../backend/manage_team.php" method="post" class="border-bottom border-secondary">
    <h1>Delete teams</h1>
    <div class="form-group">

        <label for="teams">Select one or more team</label><br>
        <select multiple name="teams[]" id="teams" class="form-control" onchange="toggle_button('delete',false)" required>
            <?php

            include_once '../../backend/db_connect_login.php';

            $resource = pg_query($db,"select id,long_name,short_name from team order by long_name");

            while($row = pg_fetch_array($resource,null,PGSQL_ASSOC)) {
                ?>
                <option value="<?php echo $row['id']; ?>">
                    <?php echo $row['long_name']." (".$row['short_name'].")"; ?>
                </option>
                <?php
            }
            ?>
        </select><br>
    </div>

    <div class="form-group">
        <ul class="nav nav-justified">
            <li>
                <input type="reset" class="btn btn-outline-primary mr-3" name="reset" id="reset" value="Reset selection" onclick="toggle_button('delete',true)"/>
            </li>
            <li>
                <input type="submit" class="btn btn-danger" name="submit" id="delete" value="Delete" disabled/>
            </li>
        </ul>
    </div>
</form>







<!-- Update form -->
<form action="../../backend/manage_team.php" method="post">
    <h1>Update team</h1>
    <div class="form-group">

        <label for="team">Select one team</label><br>
        <select name="team" id="team" class="form-control" onchange="updateField(this.value);" required>
            <option value="null">Nothing selected</option>
            <?php

            include_once '../../backend/db_connect_login.php';

            $resource = pg_query($db,"select id,long_name,short_name from team order by long_name");

            while($row = pg_fetch_array($resource,null,PGSQL_ASSOC)) {
                ?>
                <option value="<?php echo $row['id'].';'.$row['long_name'].';'.$row['short_name']; ?>">
                    <?php echo $row['long_name']." (".$row['short_name'].")"; ?>
                </option>
                <?php
            }
            ?>
        </select><br>

        <label for="new_team_name_l">Team long name: </label>
        <input type="text" name="team_l" id="new_team_name_l" oninput="if(this.value.localeCompare('') === 0) toggle_button('insert',true); else toggle_button('insert',false);" required disabled/>

        <label for="new_team_name_s">Team short name: </label>
        <input type="text" name="team_s" id="new_team_name_s" minlength="3" maxlength="3" oninput="if(this.value.localeCompare('') === 0) toggle_button('insert',true); else toggle_button('insert',false);" required disabled/>

        <ul class="nav nav-justified">
            <li>
                <input type="reset" class="btn btn-outline-primary mr-3" name="reset" id="reset" value="Reset selection" onclick="updateField('null')"/>
            </li>
            <li>
                <input type="submit" class="btn btn-primary mr-3" name="submit" id="modify" value="Modify" disabled/>
            </li>
        </ul>
    </div>
</form>