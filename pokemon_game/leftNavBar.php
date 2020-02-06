<?php
require_once './db_connection.php';
require_once './data_access/user.php';
require_once './data_access/region.php';
require_once './data_access/system_role.php';

$curTr = getCurrentTrainer();
?>

<ul class="nav left">

    <?php if (curUserIsAdmin()) : ?>
        <li class="header top">Trainers</li>
        <li><a href='trainers.php'>View NPCs</a></li>
    <?php endif; ?>

    <li class="header top">Profiles</li>
    <li><a href="userList.php">View</a></li>
    
    <li class="header top">Explore Maps</li>
    <?php
    $regions = getRegionsList('name');
    foreach ($regions as $r) {
        echo "<li><a href='mapAdmin.php?id_region=$r->id'>$r->name</a></li>";
    }
    ?>

    <li class="header">Battle Area</li>
    <li><a href="gyms.php">GYM</a></li>
    <li><a href="towers.php">Towers</a></li>
</ul>				
