<?php
    session_start();
    include("db.php");

    if (!isset($_SESSION["admin"])) {
        header("Location: admin_giris.php");
        exit();
    }

    // Kitapları id'ye göre sıralama (artık sıralı şekilde gelecek)
    $query = "SELECT id, kitap_adi FROM kitaplar ORDER BY id ASC"; // ASC: Artan sıralama
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $kitaplar = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Adminleri listelemek için sorgu
    $query_adminler = "SELECT id, kullanici_adi FROM adminler ORDER BY id ASC"; 
    $stmt_adminler = $pdo->prepare($query_adminler);
    $stmt_adminler->execute();
    $adminler = $stmt_adminler->fetchAll(PDO::FETCH_ASSOC);

    // Kullanıcıları listelemek için sorgu
    $query_kullanicilar = "SELECT id, kullanici_adi FROM kullanicilar ORDER BY id ASC"; 
    $stmt_kullanicilar = $pdo->prepare($query_kullanicilar);
    $stmt_kullanicilar->execute();
    $kullanicilar = $stmt_kullanicilar->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Paneli</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1000px;
            margin: 30px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2, h3 {
            color: #333;
            text-align: center;
        }
        a {
            text-decoration: none;
            color: #4CAF50;
            margin: 10px;
        }
        a:hover {
            color: #45a049;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        td {
            background-color: #f9f9f9;
        }
        button {
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .form-container {
            margin-top: 20px;
            text-align: center;
        }
        .form-container input {
            padding: 10px;
            margin: 10px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Admin Paneli</h2>
    <p>Hoş geldiniz, <?php echo $_SESSION["admin"]; ?>!</p>
    <div class="form-container">
        <a href="admin_ekle.php">Admin Ekle</a> |
        <a href="admin_cikis.php">Çıkış Yap</a>
    </div>

    <!-- Kitap Ekleme Formu -->
    <div class="form-container">
        <form action="kitap_ekle.php" method="POST">
            <input type="text" name="kitap_adi" placeholder="Kitap Adı" required>
            <button type="submit">Kitap Ekle</button>
        </form>
    </div>

    <!-- Kitap Listesi -->
    <h3>Mevcut Kitaplar</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Kitap Adı</th>
            <th>İşlemler</th>
        </tr>
        <?php foreach ($kitaplar as $kitap): ?>
        <tr>
            <td><?php echo htmlspecialchars($kitap['id']); ?></td>
            <td><?php echo htmlspecialchars($kitap['kitap_adi']); ?></td>
            <td>
                <a href="kitap_duzenle.php?id=<?php echo $kitap['id']; ?>">Düzenle</a> |
                <a href="kitap_sil.php?id=<?php echo $kitap['id']; ?>">Sil</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <!-- Admin Listesi -->
    <h3>Mevcut Adminler</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Kullanıcı Adı</th>
            <th>İşlemler</th>
        </tr>
        <?php foreach ($adminler as $admin): ?>
        <tr>
            <td><?php echo htmlspecialchars($admin['id']); ?></td>
            <td><?php echo htmlspecialchars($admin['kullanici_adi']); ?></td>
            <td>
                <a href="admin_sil.php?id=<?php echo $admin['id']; ?>">Sil</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <!-- Kullanıcılar Listesi -->
    <h3>Mevcut Kullanıcılar</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Kullanıcı Adı</th>
            <th>İşlemler</th>
        </tr>
        <?php foreach ($kullanicilar as $kullanici): ?>
        <tr>
            <td><?php echo htmlspecialchars($kullanici['id']); ?></td>
            <td><?php echo htmlspecialchars($kullanici['kullanici_adi']); ?></td>
            <td>
                <a href="kullanici_sil.php?id=<?php echo $kullanici['id']; ?>">Sil</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <!-- Gönderiler Listesi -->
<h3>Mevcut Gönderiler</h3>
<table>
    <tr>
        <th>ID</th>
        <th>Başlık</th>
        <th>İçerik</th>
        <th>Kullanıcı</th>
        <th>Tarih</th>
        <th>İşlemler</th>
    </tr>
    <?php
    $query_posts = "SELECT id, title, content, username, created_at FROM posts ORDER BY created_at DESC";
    $stmt_posts = $pdo->prepare($query_posts);
    $stmt_posts->execute();
    $gonderiler = $stmt_posts->fetchAll(PDO::FETCH_ASSOC);

    foreach ($gonderiler as $gonderi): ?>
        <tr>
            <td><?php echo htmlspecialchars($gonderi['id']); ?></td>
            <td><?php echo htmlspecialchars($gonderi['title']); ?></td>
            <td><?php echo htmlspecialchars(mb_strimwidth($gonderi['content'], 0, 60, "...")); ?></td>
            <td><?php echo htmlspecialchars($gonderi['username']); ?></td>
            <td><?php echo htmlspecialchars($gonderi['created_at']); ?></td>
            <td>
                <a href="post_sil.php?id=<?php echo $gonderi['id']; ?>" onclick="return confirm('Bu gönderiyi silmek istediğinize emin misiniz?');">Sil</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</div>

</body>
</html>