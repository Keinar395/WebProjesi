<?php
    session_start(); // Oturumu başlat

    // Admin oturum kontrolü
    if (!isset($_SESSION['admin'])) {
        // Eğer oturumda admin yoksa, giriş sayfasına yönlendir
        header("Location: admin_giris.php");
        exit();
    }

    include("db.php");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $kullanici_adi = $_POST['kullanici_adi'];
        $sifre_plain = $_POST['sifre'];

        try {
            // Kullanıcı adı daha önce eklenmiş mi?
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM adminler WHERE kullanici_adi = :kullanici_adi");
            $stmt->execute([':kullanici_adi' => $kullanici_adi]);
            $kullanici_var_mi = $stmt->fetchColumn();

            if ($kullanici_var_mi > 0) {
                echo "⚠️ Bu kullanıcı adı zaten mevcut.";
            } else {
                // Şifreyi MD5 ile hash'le
                $sifre_hashli = md5($sifre_plain);

                // Admini ekle
                $stmt = $pdo->prepare("INSERT INTO adminler (kullanici_adi, sifre) VALUES (:kullanici_adi, :sifre)");
                $stmt->execute([
                    ':kullanici_adi' => $kullanici_adi,
                    ':sifre' => $sifre_hashli
                ]);
                header("Location: admin_panel.php");
            }
        } catch (PDOException $e) {
            header("Location: admin_panel.php");
        }
    }
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Admin Ekle</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 500px;
            margin: 50px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            font-size: 14px;
            color: #333;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0 20px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .alert {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Admin Ekleme Formu</h2>

    <?php
    // Eğer hata mesajı varsa göster
    if (isset($kullanici_var_mi) && $kullanici_var_mi > 0) {
        echo "<p class='alert'>⚠️ Bu kullanıcı adı zaten mevcut.</p>";
    }
    ?>

    <form method="POST">
        <label for="kullanici_adi">Kullanıcı Adı:</label>
        <input type="text" id="kullanici_adi" name="kullanici_adi" required><br>

        <label for="sifre">Şifre:</label>
        <input type="password" id="sifre" name="sifre" required><br>

        <input type="submit" value="Admin Ekle">
    </form>
</div>

</body>
</html>