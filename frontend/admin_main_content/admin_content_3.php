<!-- MANAGE COUNTRIES -->
<script type="text/javascript">
    function updateField(val){
        var field = document.getElementById("country_new_name");

        if(val.localeCompare("null") === 0){
            field.value = "";
            field.disabled = true;
            toggle_button('modify',true);
        }
        else{
            val = val.substr(val.indexOf(" ")+1);
            field.disabled = false;
            field.value = val;
            toggle_button('modify',false);
        }
    }

    function toggle_button(button,state){
        document.getElementById(button).disabled = state;
    }
</script>



<!-- Insert form -->
<form action="../../backend/manage_country.php" method="post" class="border-bottom border-secondary" >
    <h1>Insert country</h1>
    <div class="form-group">

        <label for="country">Country name: </label>
        <input type="text" name="country" id="country" oninput="if(this.value.localeCompare('') === 0) toggle_button('insert',true); else toggle_button('insert',false);" required/>

        <ul class="nav nav-justified">
            <li>
                <input type="submit" class="btn btn-primary mr-3" name="submit" id="insert" value="Insert" disabled/>
            </li>
        </ul>
    </div>
</form>




<!-- Delete form -->
<form action="../../backend/manage_country.php" method="post" class="border-bottom border-secondary">
    <h1>Delete countries</h1>
    <div class="form-group">

        <label for="countries">Select one or more countries</label><br>
        <select multiple name="countries[]" id="countries" class="form-control" onchange="toggle_button('delete',false)" required>
            <?php

            include_once '../../backend/db_connect_login.php';

            $resource = pg_query($db,"select id,name from country order by name ");

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
                <input type="reset" class="btn btn-outline-primary mr-3" name="reset" id="reset" value="Reset selection" onclick="toggle_button('delete',true)"/>
            </li>
            <li>
                <input type="submit" class="btn btn-danger" name="submit" id="delete" value="Delete" disabled/>
            </li>
        </ul>
    </div>
</form>







<!-- Update form -->
<form action="../../backend/manage_country.php" method="post">
    <h1>Update country</h1>
    <div class="form-group">

        <label for="country">Select one country</label><br>
        <select name="country" id="country" class="form-control" onchange="updateField(this.value);" required>
            <option value="null">Nothing selected</option>
            <?php

            include_once '../../backend/db_connect_login.php';

            $resource = pg_query($db,"select id,name from country order by name ");

            while($row = pg_fetch_array($resource,null,PGSQL_ASSOC)) {
                ?>
                <option value="<?php echo $row['id'].' '.$row['name']; ?>">
                    <?php echo $row['name']; ?>
                </option>
                <?php
            }
            ?>
        </select><br>

        <label for="country_new_name">Country new name: </label>
        <input type="text" name="country_new_name" id="country_new_name" required disabled oninput="if(this.value.localeCompare('') === 0) toggle_button('modify',true); else toggle_button('modify',false);"/>

        <ul class="nav nav-justified">
            <li>
                <input type="reset" class="btn btn-outline-primary mr-3" name="reset" id="reset" value="Reset selection"/>
            </li>
            <li>
                <input type="submit" class="btn btn-primary mr-3" name="submit" id="modify" value="Modify" disabled/>
            </li>
        </ul>
    </div>
</form>