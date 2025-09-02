<?php
    include("db.php");

    if (isset($_GET['id'])) {
        $kitap_id = $_GET['id'];
        $query = "DELETE FROM kitaplar WHERE id = $kitap_id";
        $pdo->query($query);
        header("Location: admin_panel.php");
    }
?>