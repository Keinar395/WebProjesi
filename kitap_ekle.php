<?php
    include("db.php");

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $kitap_adi = $_POST['kitap_adi'];

        $query = "INSERT INTO kitaplar (kitap_adi) 
                VALUES ('$kitap_adi')";
        $pdo->query($query);
        header("Location: admin_panel.php");
    }
?>