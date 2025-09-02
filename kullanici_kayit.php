<?php
    // Veritabanı bağlantısı (db.php)
    include('db.php');

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Kullanıcıdan gelen bilgiler
        $kullanici_adi = $_POST['kullanici_adi'];
        $sifre = $_POST['sifre'];

        // MD5 ile şifreyi hash'le
           $hashed_password = md5($sifre);


        // Veritabanına kullanıcıyı ekle
        try {
            $query = "INSERT INTO kullanicilar (kullanici_adi, sifre) VALUES (:kullanici_adi, :sifre)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':kullanici_adi', $kullanici_adi);
            $stmt->bindParam(':sifre', $hashed_password);
            $stmt->execute();
            echo "Kullanıcı başarıyla kaydedildi.";
        } catch (PDOException $e) {
            echo "Kayıt hatası: " . $e->getMessage();
        }
    }
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
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
            display: block;
            margin-bottom: 5px;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Kayıt Ol</h2>

    <form method="POST">
        <label for="kullanici_adi">Kullanıcı Adı:</label>
        <input type="text" name="kullanici_adi" placeholder="Kullanıcı Adı" required><br>

        <label for="sifre">Şifre:</label>
        <input type="password" name="sifre" placeholder="Şifre" required><br>

        <button type="submit">Kaydol</button>
    </form>

</div>

</body>
</html>