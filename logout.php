<?php
    session_start();
    session_destroy();
    header("Location: MenuPrincipal.php");
    exit;
?>