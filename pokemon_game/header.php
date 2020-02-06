<?php
require_once './db_connection.php';
require_once './data_access/user.php';
require_once './data_access/trade.php';
require_once './data_access/trainer.php';

$usr = getCurrentUser();
$curTrn = getCurrentTrainer();
$tradeCnt = getTradeCountByState($curTrn->id, 'Created');
$tradeMsg = $tradeCnt == 0 ? "No trades" : "$tradeCnt trade(s)";
?>

<div id="header">
    <a class="logo" href="index.html"></a>

    <table class="info-table">
        <tr>
        <td>
            <li><span class="img"></span><?php echo $curTrn->silver ?> Silver</li>
            <li><span class="img"></span><?php echo $curTrn->gold ?> Gold</li>
            <br>
            <li><span class="img"></span><a class="lnkTrades" href="trades.php"><?php echo $tradeMsg ?></a></li>
            <li><?php echo number_format($curTrn->faction_pts, 0, ",", ".")." points" ?></li>
        </td>
        </tr>
    </table>

    <form name="loginFrm" action="proc_data/proc_login.php" method="post">
        <input name="action" value="logout" type="hidden"/>
        
        <div class="user-info">
            <div class="avatar">
                <img src="proc_data/proc_user.php?action=img&w=60&h=60&id=<?php echo $usr->id ?>" />
            </div>
            <div class="nick"><?php echo $usr->username ?></div>
            <div class="id">[ID: <?php echo $usr->id ?>]</div>
            <div class="line"></div>
            
            <a class="settings" href="#">Settings</a>
            <a class="logout" href="#" onclick="loginFrm.submit()">Logout</a>
        </div>
    </form>
</div>
