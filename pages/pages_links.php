<?php
/**
 * Connect to the local DB
 * stored the new Link if there was one
 */

$this->mySqlConnect();

$this->authorized = isset($_SESSION['username']);
$categoryActive = $this->p2;

if ($this->authorized && $_POST['newLinkRequest'] == 'true' ) {
    $sql = 'INSERT INTO links (id, text, link, category) VALUES (NULL, "'.$_POST['newLinkText'].'", "'.$_POST['newLinkHref'].'", "'.$_POST['newLinkCategory'].'")';
    mysql_query($sql, $this->link);
}

else if ($this->authorized && $this->admin && isset($_GET['deleteLink']) ) {
    $sql = "DELETE FROM links WHERE id='" .$_GET['deleteLink']. "'";
    mysql_query($sql, $this->link);
}

else if ($this->authorized && $_POST['newCategory'] == "true" ) {
    $categoryActive = $_POST['newCategoryName'];
    $sql = 'INSERT INTO categories (name) VALUES ("'.$_POST['newCategoryName'].'")';
    mysql_query($sql, $this->link);
}

$sql = 'SELECT * FROM categories LEFT JOIN links ON name = category ORDER BY links.category DESC, links.id DESC';
$links = array();
$category = "";

if ($this->authorized && $res = mysql_query($sql, $this->link)) {
    $this->categoryActiveIndex = -1;
    $i = 0;
    while ($row = mysql_fetch_array($res)) {
        if ($category != $row['name']) {
            $category = $row['name'];
            if ($category == $categoryActive)
                $this->categoryActiveIndex = $i;
            $links[$category] = array();
            $i++;
        }
        if ($row['id'])
            $links[$category][$row['id']] = array ('href' => $row['link'], 'text' => $row['text'] );
    }
}
else {
    $links["Log in..."] = array (1 => array('href' => $this->PATH. "links#", text => "...to see the links"));
}

?>

<script>
$(function() {
    <?php echo "var categoryActiveIndex = " .$this->categoryActiveIndex. "; \n"; ?>

    if (categoryActiveIndex < 0)
        $( "#accordion" ).accordion({ activate: panelActivated, collapsible: true, active: false  });
    else
        $( "#accordion" ).accordion({ activate: panelActivated, collapsible: true, active: categoryActiveIndex });

});
</script>

<div id="content">
    <div class="notepad">
        <div id="accordion">
            <?php
            foreach ($links as $category => &$linkArr) {
                echo "<h3 class='title'> $category <a name='$category'> &nbsp </a> </h3> <div> <ul>";
                foreach ($linkArr as $id => &$link) {
                    $linkHref = $link['href'];
                    $linkText = $link['text'];
                    echo "<li> <a href='" .$this->PATH. "links/$category&deleteLink=$id' class='deleteLink'> x </a> <a target='_blank' href='$linkHref'> &nbsp $linkText </a> </li>\n";
                }
                echo "</ul>";
                echo "
                    <form method='post' action='" .$this->PATH. "links/$category' >
                    <input type='hidden' name='newLinkRequest' value='true' />
                    <input type='hidden' name='newLinkCategory' value='$category' />
                    <input type='text' name='newLinkText' value='Κείμενο συνδέσμου' class='inputTextfield' onfocus='clearText(this, \"Κείμενο συνδέσμου\")' onblur='restoreText(this, \"Κείμενο συνδέσμου\")'  />
                    <input type='text' name='newLinkHref'  value='http://' class='inputTextfield' onfocus='clearText(this, \"http://\")' onblur='restoreText(this, \"http://\")' />
                    <input type='submit' value='Καταχώρηση' id='button' />
                    </form> </div>";
            }
            ?>
        </div>
        <br> <hr>
        <form method='post' action='<?php echo $this->PATH; ?>links'>
        <input type='hidden' name='newCategory' value='true' />
        <input type='text' name='newCategoryName' value='Νέα κατηγορία' class='inputTextfield' onfocus='clearText(this, "Νέα κατηγορία")' onblur='restoreText(this, "Νέα κατηγορία")' />
        <input type='submit' value='Δημιουργία' id='button' />
        </form>
    </div>
</div>
