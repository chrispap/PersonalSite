<?php

$lang = array ("cpp", "avr", "android", "java", );
if (in_array($this->p2, $lang)) $lang = array ($this->p2);

?>

<div id="content">
    <?php
        foreach ($lang as &$l)
            include("pages/portfolio/portfolio_$l.php");
    ?>

</div>
