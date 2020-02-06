<?php
if (!login_check()) {
    header('Location: login.php');
}
?>