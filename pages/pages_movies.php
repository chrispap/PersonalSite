<link href="<?php echo $this->PATH; ?>public/movies.css" rel="stylesheet" type="text/css" media="screen" />

<div id="content" >
	<a href="<?php echo $this->PATH; ?>movies" > <img src="<?php echo $this->PATH; ?>public/images/my_movies.png" /> </a>
	<?php
	$action = $this->p2==""? "home" : $this->p2;
	$action = "pages/movies/movies_" .$action. ".php";
	include($action);
	?>
</div>
