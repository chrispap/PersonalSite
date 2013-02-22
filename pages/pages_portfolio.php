<?php
if ($this->p2  == "") {
	$lang = array ("avr", "cpp", "android", "java", );
}
else {
	$lang = array ($this->p2);
}

?>

<div id="content">
    <?php
        foreach ($lang as &$l)
			include("pages/portfolio/portfolio_$l.php");
    ?>

</div>
