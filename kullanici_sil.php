<?php
    session_start();
    include("db.php");

    if (!isset($_SESSION["admin"])) {
        header("Location: admin_giris.php");
        exit();
    }

    // Kullanıcının silineceği id'yi al
    if (isset($_GET['id'])) {
        $kullanici_id = $_GET['id'];

        try {
            // Kullanıcıyı veritabanından silme
            $query = "DELETE FROM kullanicilar WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':id', $kullanici_id, PDO::PARAM_INT);
            $stmt->execute();

            // Başarıyla silindiği mesajı
            echo "✅ Kullanıcı başarıyla silindi.";

            // Admin paneline yönlendirme
            header("Location: admin_panel.php");
            exit();
        } catch (PDOException $e) {
            echo "❌ Hata: " . $e->getMessage();
        }
    } else {
        echo "❌ Hata: Kullanıcı ID bulunamadı.";
    }
?>