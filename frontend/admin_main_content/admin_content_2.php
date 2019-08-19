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
<form action="../../backend/manage_league.php" method="post">
    <div class="form-group">
        <label for="matches">Select one or more leagues</label><br>


        <select multiple name="matches" id="matches" class="form-control" onchange="toggle_buttons(false)" required>
            <?php

            include_once '../../backend/db_connect_login.php';

            $resource = pg_query($db,"select id,name from league order by name ");

            while($row = pg_fetch_array($resource,null,PGSQL_ASSOC)) {
                ?>
                <option value="<?php echo $row['id']; ?>">
                    <?php echo $row['name']; ?>
                </option>
                <?php
            }
            ?>


        </select><br>
    </div>
    <div class="form-group">
        <ul class="nav nav-justified">
            <li>
                <input type="button" class="btn btn-success mr-3" name="submit" id="insert" value="Insert" onclick="submit(this.form,this.value)"/>
            </li>
            <li>
                <input type="reset" class="btn btn-outline-primary mr-3" name="reset" id="reset" value="Reset selection" onclick="toggle_buttons(true)"/>
            </li>
            <li>
                <input type="button" class="btn btn-primary mr-3" name="submit" id="modify" value="Modify" onclick="submit(this.form,this.value)" disabled/>
            </li>
            <li>
                <input type="button" class="btn btn-danger" name="submit" id="delete" value="Delete" onclick="submit(this.form,this.value)" disabled/>
            </li>
        </ul>
    </div>

</form>