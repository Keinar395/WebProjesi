<?php
    session_start();
    include("db.php");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $kullanici_adi = $_POST["kullanici_adi"];
        $sifre = md5($_POST["sifre"]);

        // PDO ile sorgu hazırlama
        $stmt = $pdo->prepare("SELECT * FROM adminler WHERE kullanici_adi = :kullanici_adi AND sifre = :sifre");
        $stmt->bindParam(':kullanici_adi', $kullanici_adi);
        $stmt->bindParam(':sifre', $sifre);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $_SESSION["admin"] = $kullanici_adi;
            header("Location: admin_panel.php");
            exit();
        } else {
            echo "Hatalı kullanıcı adı veya şifre!";
        }
    }
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Admin Giriş</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 400px;
            margin: 100px auto;
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
            display: block;
            margin: 10px 0 5px;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
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
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Admin Giriş</h2>

    <form method="post">
        <label for="kullanici_adi">Kullanıcı Adı:</label>
        <input type="text" id="kullanici_adi" name="kullanici_adi" required><br>

        <label for="sifre">Şifre:</label>
        <input type="password" id="sifre" name="sifre" required><br>

        <input type="submit" value="Giriş">
    </form>

    <?php
    // Hatalı giriş olduğunda mesaj
    if ($_SERVER["REQUEST_METHOD"] == "POST" && $stmt->rowCount() == 0) {
        echo "<p class='alert'>Hatalı kullanıcı adı veya şifre!</p>";
    }
    ?>
</div>

</body>
</html>