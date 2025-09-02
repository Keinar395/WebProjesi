<?php
    session_start();
    include("db.php");

    if (isset($_GET['id'])) {
        $kitap_id = $_GET['id'];
        
        // PDO ile veri çekme
        $query = "SELECT * FROM kitaplar WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':id' => $kitap_id]);
        
        $kitap = $stmt->fetch(PDO::FETCH_ASSOC);  // Sonucu al
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $kitap_adi = $_POST['kitap_adi'];

        // PDO ile güncelleme
        $query = "UPDATE kitaplar SET kitap_adi = :kitap_adi WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':kitap_adi' => $kitap_adi,
            ':id' => $kitap_id
        ]);

        header("Location: admin_panel.php");
    }
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitap Düzenle</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333;
            text-align: center;
        }
        .form-container {
            text-align: center;
        }
        .form-container input {
            padding: 10px;
            margin: 10px 0;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-container button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-container button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Kitap Düzenle</h2>
    
    <div class="form-container">
        <form action="kitap_duzenle.php?id=<?php echo $kitap['id']; ?>" method="POST">
            <label for="kitap_adi">Kitap Adı:</label><br>
            <input type="text" name="kitap_adi" value="<?php echo htmlspecialchars($kitap['kitap_adi']); ?>" required><br>
            <button type="submit">Kitap Düzenle</button>
        </form>
    </div>
</div>

</body>
</html>