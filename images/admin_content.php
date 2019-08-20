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


            $message = $_GET['content'];
            echo "<script type='text/javascript'>alert('$message');</script>";

            include_once '../../backend/db_connect_login.php';

            $sql = "";

            switch ($_GET['content']){
                case 1:
                    $sql = "select public.match.id,date,stage,th.long_name as thl,th.short_name as ths,ta.long_name as tal,ta.short_name as tas 
            from public.match
            join team th on public.match.home_team_id = th.id
            join team ta on public.match.away_team_id = ta.id
            order by date desc";
                    break;
                case 2:
                    $sql = "select id,name from league order by name ";
                    break;
                case 3:
                    $sql = "select pa.player_id,p.name as pname,pa.date,pa.overall_rating from player_attribute pa join player p on pa.player_id = p.id order by date desc ";
                    break;
                case 4:
                    $sql = "select id,long_name,short_name from team order by long_name";
                    break;
                case 5:
                    $sql = "select id,name from country order by name";
                    break;
            }

            $resource = pg_query($db,$sql);

            while($row = pg_fetch_array($resource,null,PGSQL_ASSOC)) {
                ?>
                <option value="<?php if($_GET['content'] == 3){ $value = $row['player_id']." ".$row['date']; echo $value;} else echo $row['id'];?>">
                    <?php
                    switch ($_GET['content']){
                        case 1:
                            echo "Date: ".$row['date']." | Stage: ".$row['stage']." | Home team: ".$row['thl']." (".$row['ths'].") | Away team: ".$row['tal']." (".$row['tas'].") ";
                            break;
                        case 5:
                        case 2:
                            echo $row['name'];
                            break;
                        case 3:
                            echo $row['pname']." REGISTERED ON DATE ".$row['date']." OVERALL RATING: ".$row['overall_rating'];
                            break;
                        case 4:
                            echo $row['long_name']." (".$row['short_name'].")";
                            break;
                    }

                    ?>
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