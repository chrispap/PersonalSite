<?php
$this->mysqlConnect();
mysql_select_db("chris_movies", $this->con);

if (isset($_POST['submitted'])){
	$sql = "INSERT INTO movies (title, title_id, year, genre) VALUES ('".mysql_real_escape_string($_POST['title'])."', NULL, ".$_POST['year'].", ".$_POST['genre'].");";
   
	if (mysql_query($sql, $this->con)){
		$this->title_id = mysql_insert_id(); // Το id της ταινίας που μόλις εισήχθη στην βάση.
		//echo "Title_ID: ".$this->title_id."<br> <br>";
		for ($i=0 ; $i<$_POST['actorCount'] ; $i++){
			$sql = "SELECT actor_id FROM actor WHERE fname LIKE '".mysql_real_escape_string($_POST['actor0'])."' OR lname LIKE '".mysql_real_escape_string($_POST['actor0'])."' OR CONCAT_WS(' ',fname,lname) LIKE '".mysql_real_escape_string($_POST['actor0'])."' ;";
			//echo $sql."<br> <br>";
			mysql_query($sql, $this->con);
			$res = mysql_query($sql, $this->con);
			$resCount = 0;
			while ($row = mysql_fetch_array($res)) // Εξακριβώνω οτι μόνο ένας ηθοποιός έχει αυτό το όνομα./ Αν δεν υπάρχει ο ηθοποιός τότε τον καταχωρώ.{
				$resCount++;
			$actor_id = $row['actor_id'];
		}

		//echo "Res_count: ".$resCount."<br> <br>";
		if ($resCount == 1){
			$sql = "INSERT INTO role (actor, movie) VALUES (".$actor_id.",".$this->title_id." );";
			mysql_query($sql, $this->con);
			//echo $sql;
		}
		elseif ($resCount == 0){
			$sql = "INSERT INTO actor (fname, lname, actor_id) VALUES ('".mysql_real_escape_string($_POST['actor0'])."', '', NULL);";
			mysql_query($sql, $this->con);
			$actor_id = mysql_insert_id();
			//echo $sql."<br> <br>";

			$sql = "INSERT INTO role (actor, movie) VALUES (".$actor_id.",".$this->title_id." );";
			mysql_query($sql, $this->con);
			//echo $sql."<br> <br>";
		}
		else {
			echo "Did not insert actors.<br> <br>";
		}
	}
}
else {
	echo "Error inserting the movie.";
}

$sql = "SELECT * from genre ORDER BY genre.genre ASC;";
?>

<div class="title"> Εισαγωγή νέας ταινίας </div>

<div class="entry">

	<form method="POST" action="<?php echo $this->FULL_PATH; ?>?case=insert" encoding="utf-8" >
		<fieldset>
		<legend> Νέα ταινία: </legend>

		<input type="hidden" name ="submitted" value="1" />
					<input type="hidden" name="actorCount" value="1" >

		<table class="plaisio" >
			<tr> <td> Τίτλος: 	</td> <td> <input type="text"   name="title" /> 	</td> </tr>
			<tr> <td> Ηθοποιοί:	</td> <td> <input type="text"   name="actor0" /> 	</td> </tr>
			<tr> <td> Έτος: 	</td> <td> <input type="text"   name="year" /> 	</td> </tr>
			<tr> <td> Είδος:	</td> <td> 
				<select name="genre" >
					<?php
					if ($res = mysql_query($sql, $this->con)){
						while ($row = mysql_fetch_array($res)){
							echo "<option value='".$row[genre_id]."'> ".$row[genre]." </option>";
						}
					} 
					?> 
				</select> </td> </tr>		
		</table>
		<input type="submit" value="Καταχώρηση" id="submitButton">
		</fieldset>
	</form>
</div>
