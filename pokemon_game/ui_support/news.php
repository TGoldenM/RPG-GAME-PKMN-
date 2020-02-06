<?php
defined('ROOTPATH') || define('ROOTPATH', (dirname(__FILE__) . '/'));
require_once ROOTPATH . 'data_access/news.php';

function outputNews($showAvatar = false){
    $newsList = getNewsList("created desc", 3);
    $trainer_ids = array();
    $trainers = array();
    
    echo '<table class="news">';
    foreach ($newsList as $news) {
        $dt = strtotime($news->created);
        
        echo "<tr>";

        if ($showAvatar) {
            //Search first if we haven't processed a trainer/user earlier
            //If we did, do not load it again from DB.
            if(!in_array($news->id_trainer, $trainer_ids)) {
                $trainer_ids[] = $news->id_trainer;
                $tr = getTrainer($news->id_trainer, PKMN_SIMPLE_LOAD);
                $trainers[$news->id_trainer] = $tr;
            } else {
                $tr = $trainers[$news->id_trainer];
            }
            $usr = $tr->user;
            
            echo "<td class='td1'>";

            echo "<table>";
            echo "<tr><td>";
            echo "<div class='avatar-big'>";
            echo "<img src='proc_data/proc_user.php?action=img&id=$usr->id' />";
            echo "</div>";
            echo "</td>";
            echo "</tr>";

            echo "<tr>";
            echo "<td>";
            echo "<div class='info'>";
            echo "<p class='nick'>$usr->username</p>";
            $fc = $tr->faction;
            
            if(!is_null($fc)){
                echo "<p class='nick'>".$fc->name."</p>";
            }
            
            echo "<p class='rank'>[". getUserHighestRole($usr->username)->name."]</p>";
            echo "</div>";
            echo "</td>";
            echo "</tr>";
            echo "</table>";

            echo "</td>";
        }

        echo "<td class='td2'>";
        echo "<table style='width: 100%;'>";
        
        echo "<tr><td>";
        echo "<div class='post'>".stripslashes($news->content)."</div></td>";
        echo "<tr><td><div class='post-date'>Posted: ".date('d/m/Y', $dt)."</div>";
        echo "</td></tr>";
        
        echo "</table>";
        echo "</td>";

        echo "</tr>";
    }
    echo '</table>';
}
?>