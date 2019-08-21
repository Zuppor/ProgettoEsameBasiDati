<script type="text/javascript">
    function submit(form,action){
        var act = document.createElement("input");

        form.appendChild(act);

        act.name = "action";
        act.type = "hidden";
        act.value = action;

        form.submit();
    }

    function toggle_buttons(t){
        document.getElementById("modify").disabled = t;
        document.getElementById("delete").disabled = t;
    }
</script>
<form action="../../backend/manage_match.php" method="post">
    <div class="form-group">
        <label for="matches">Select one or more matches</label><br>


        <select multiple name="matches" id="matches" class="form-control" onchange="toggle_buttons(false)" required>
            <?php

            include_once '../../backend/db_connect_login.php';

            $resource = pg_query($db,"select public.match.id,date,stage,th.long_name as thl,th.short_name as ths,ta.long_name as tal,ta.short_name as tas 
            from public.match
            join team th on public.match.home_team_id = th.id
            join team ta on public.match.away_team_id = ta.id
            where public.match.operator_id = ".$_SESSION['user_id']."
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
        <ul class="nav nav-justified">
            <li>
                <input type="reset" class="btn btn-outline-primary" name="reset" id="reset" value="Reset selection" onclick="toggle_buttons(true)"/>
            </li>
            <li>
                <input type="button" class="btn btn-primary" name="submit" id="modify" value="Modify" onclick="submit(this.form,this.value)" disabled/>
            </li>
            <li>
                <input type="button" class="btn btn-danger" name="submit" id="delete" value="Delete" onclick="submit(this.form,this.value)" disabled/>
            </li>
        </ul>
    </div>

</form>

<?php
include_once '../form_insert_match.php';
?>