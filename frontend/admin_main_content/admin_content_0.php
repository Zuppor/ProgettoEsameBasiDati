<!-- UPLOAD MATCHES AND PLAYER ATTRIBUTES FROM CSV-->
<form action="../../backend/insert_match_from_csv.php" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="csv">Upload match.csv</label>
        <input type="file" class="form-control-file" name="csv" value="" required/><br>
        <input type="submit" class="btn btn-primary" name="submit" value="Submit"/>
    </div>
</form>

<form action="../../backend/insert_player_attribute.php" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="csv">Upload player_attribute.csv</label>
        <input type="file" class="form-control-file" name="csv" value="" required/><br>
        <input type="submit" class="btn btn-primary" name="submit" value="Submit"/>
    </div>
</form>