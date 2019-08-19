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
<form action="../../backend/manage_attribute.php" method="post">
    <div class="form-group">
        <label for="matches">Select one or more attributes</label><br>


        <select multiple name="matches" id="matches" class="form-control" onchange="toggle_buttons(false)" required>
            <?php

            include_once '../../backend/db_connect_login.php';

            $resource = pg_query($db,"select pa.player_id,p.name as pname,pa.date,pa.overall_rating from player_attribute pa join player p on pa.player_id = p.id order by date desc ");

            while($row = pg_fetch_array($resource,null,PGSQL_ASSOC)) {
                ?>
                <option value="<?php $value = $row['player_id']." ".$row['date']; echo $value; ?>">
                    <?php echo $row['pname']." REGISTERED ON DATE ".$row['date']." OVERALL RATING: ".$row['overall_rating'];?>
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