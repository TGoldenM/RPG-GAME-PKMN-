<?php
require_once './db_connection.php';
require_once './data_access/user.php';
require_once './data_access/trainer.php';
require_once './data_access/system_role.php';

$curTr = getCurrentTrainer();
?>

<ul class="nav right">
    <li class="header top">Account</li>
    <li><a href="main.php">Main</a></li>
    <?php
    echo "<li><a href='userView.php?id=$curTr->id_user''>Profile</a></li>";
    echo "<li><a href='userEdit.php?id=$curTr->id_user'>Edit Account</a></li>";
    echo "<li><a href='trnPkmn.php?id_tr=$curTr->id'>My Team</a></li>";
    ?>
    <li><a href='trnItems.php'>My Items</a></li>
    <li><a href='pokebox.php'>Pokedex</a></li>
    <li><a href='trades.php'>Trades</a></li>
    <li><a href='pkmnStore.php'>Pokemon Store</a></li>
    <li><a href='pkmnSales.php'>View sales</a></li>
    
    <?php if(curUserIsAdmin()) : ?>
    
    <li class="header top">Administrate</li>
    <li><a href='sysPropAdmin.php'>System Properties</a></li>
    <li><a href='userAdmin.php'>Users</a></li>
    <li><a href='roleAdmin.php'>Roles</a></li>
    <li><a href='newsAdmin.php'>News</a></li>
    <li><a href='groupAdmin.php'>Egg Groups</a></li>
    <li><a href='typesAdmin.php'>Pokemon Types</a></li>
    <li><a href='moveAdmin.php'>Pokemon Moves</a></li>
    <li><a href='itemAdmin.php'>Pokemon Items</a></li>
    <li><a href='factionAdmin.php'>Factions</a></li>
    <li><a href='gymAdmin.php'>Gyms</a></li>
    <li><a href='badgeAdmin.php'>Badges</a></li>
    <li><a href='regionAdmin.php'>Regions</a></li>
    
    <?php endif;?>

    <li class="header">Rankings</li>
    <li><a href="rankingLvlPkmn.php">Leveled pokemon</a></li>
    <li><a href="rarityList.php">Rarity List</a></li>
    <li><a href="rankingCurrency.php">Economic value</a></li>
    <li><a href="rankingVD.php">Victory count</a></li>
    <li><a href="factions.php">Factions & Shops</a></li>
</ul>
