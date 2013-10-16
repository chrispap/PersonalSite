<?php
$this->mysqlConnect();
mysql_select_db("chris_movies", $this->link);
$sort = $this->p3;

switch ( $sort )
{
    case "actor":
                    $sql = "SELECT title ,year, genre.genre AS genre, CONCAT_WS(' ',actor.fname, actor.lname) AS actor, GROUP_CONCAT(CONCAT_WS(' ',_actor.fname, _actor.lname) ORDER BY CONCAT_WS(' ',_actor.fname, _actor.lname) SEPARATOR ',  ') AS actors\n"
                        . "FROM role JOIN actor ON role.actor = actor.actor_id JOIN movies ON role.movie = movies.title_id , actor AS _actor, genre \n"
                        . "WHERE _actor.actor_id IN (SELECT _role.actor actor_id FROM role AS _role WHERE _role.movie = role.movie) \n"
                        . "AND genre.genre_id = movies.genre \n"
                        . "GROUP BY role.movie,role.actor\n"
                        . "ORDER BY actor, title";
                    break;
    case "genre":   break;
    case "year" :   break;
    case "title":   break;
    default     :   $sort = 'title';
}

$sql = "SELECT title_id, title, year, genre.genre AS genre, \n"
    . "GROUP_CONCAT(\n"
    . " CONCAT_WS(' ',actor.fname, actor.lname) \n"
    . " ORDER BY CONCAT_WS(' ',actor.fname, actor.lname)\n"
    . " SEPARATOR ',  '\n"
    . ") AS actors\n"
    . "FROM genre, movies LEFT JOIN role ON title_id=role.movie LEFT JOIN actor ON actor_id=role.actor\n"
    . "WHERE genre_id = movies.genre\n"
    . "GROUP BY title\n"
    . "ORDER BY ".$sort." ASC, title_id ASC";

?>

<!-- Present the links -->
<div class="movies">
    <?php
    if ($res = mysql_query($sql, $this->link)){
        echo "<ul>";
        $begin = true;
        while ($row = mysql_fetch_array($res)){
            switch ($sort){
                case "title": $curr_Category = mb_substr($row['title'],0,1,'utf8'); break;
                case "genre": $curr_Category = $row['genre']; break;
                case "actor": $curr_Category = $row['actor']; break;
                case "year" : $curr_Category = 10 * ((int)($row['year']/10)); break;
                default:
            }

            if ($curr_Category != $category) {
                $category = $curr_Category;
                echo "<div class='sortKey'> ".$category."<a name='".$category."'> &nbsp </a> </div>";
            }
    ?>
        <li>
            <a class="mov_title" href='./?case=edit&movie=<?php echo $row['title_id']; ?>' > <?php echo $row['title']; ?> </a>
            <!-- <br> -->
            <span class="mov_year"> <?php echo $row['genre']." &nbsp - &nbsp  ".$row['year'] ?> </span>

    <?php   if ($row['actors']) echo " <div class='mov_actorList' >".$row['actors']."</div>";
            else echo "&nbsp";
    ?>
        </li>
    <?php
        }
        echo "</ul>";
    }
    ?>
        <br>
</div>
