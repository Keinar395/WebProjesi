<?php
    session_start();
    session_destroy();
    header("Location: kullanici_giris.php");
?>