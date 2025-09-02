<?php
    session_start();
    include("db.php");

    if (!isset($_SESSION["admin"])) {
        header("Location: admin_giris.php");
        exit();
    }

    // Adminin silineceği id'yi al
    if (isset($_GET['id'])) {
        $admin_id = $_GET['id'];

        try {
            // Admini veritabanından silme
            $query = "DELETE FROM adminler WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':id', $admin_id, PDO::PARAM_INT);
            $stmt->execute();

            // Başarıyla silindiği mesajı
            echo "✅ Admin başarıyla silindi.";

            // Admin paneline yönlendirme
            header("Location: admin_panel.php");
            exit();
        } catch (PDOException $e) {
            echo "❌ Hata: " . $e->getMessage();
        }
    } else {
        echo "❌ Hata: Admin ID bulunamadı.";
    }
?>