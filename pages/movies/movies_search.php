<div class="title"> Αναζήτηση </div>

<div class="entry">
	<form method="POST" action="<?php echo $this->FULL_PATH; ?>" encoding="utf-8" >
		<fieldset>
		<legend> Αναζήτηση για: </legend>
		<input type="hidden" name ="submitted" value="1" />
		<table>
		<?php
		$key = $this->p3;
		switch ($key) {
			case 'genre': echo '<tr><td> Είδος </td> <td> <input type="text" name="genre" /> </td></tr>'; break;
			case 'actor': echo '<tr><td> Ηθοποιός </td> <td> <input type="text" name="actor" /> </td></tr>'; break;
			case 'year':  echo '<tr><td> Έτος από </td> <td> <input type="text" name="year_min" /> έως <input type="text" key="year_max" /> </td> </tr>'; break;
			case 'title':
			default:
					  echo '<tr><td> Τίτλος </td>  <td> <input type="text" name="title" /> </td> </tr>';
		} ?>
		<tr colspan=2 >	<td align="center">  <input type="submit" value="Αναζήτηση" id="submitButton" /> </td> </tr>
		</table>
		</fieldset>
	</form>
</div>
