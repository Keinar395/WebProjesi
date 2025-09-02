<?php
    session_start(); // Oturumu başlat

    require 'db.php';  // Veritabanı bağlantısı

    // Oturumda kullanıcı adı mevcutsa, kullanıcı adını al
    if (isset($_SESSION['kullanici'])) {
        $username = $_SESSION['kullanici'];
        $isLoggedIn = true;
    } else {
        $isLoggedIn = false;
        // Eğer oturum açmamışsa, yönlendirme yapabilirsiniz
        header("Location: kullanici_giris.php");
        exit;
    }

    // Kullanıcıya ait kitapları çekiyoruz
    $books = $pdo->query("SELECT * FROM kitaplar")->fetchAll(PDO::FETCH_ASSOC);

    // Paylaşım ekleme işlemi
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Formdan gelen veriler
        $title = $_POST["title"];
        $content = $_POST["content"];

        // Veritabanına veri ekleme
        $stmt = $pdo->prepare("INSERT INTO posts (title, username, content) VALUES (?, ?, ?)");
        $stmt->execute([$title, $username, $content]);

        // Yönlendirme (Paylaşım sonrası aynı sayfada kal)
        header("Location: paylas.php");
        exit;
    }

    // Kullanıcıya ait gönderileri veritabanından çekiyoruz
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE username = ? ORDER BY created_at DESC");
    $stmt->execute([$username]);
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <title>Paylaşım</title>
</head>
<body>
    <div class="site-title">
        <h1 class="site-title-h1">KİTAPLAN 📚</h1>
    </div>

    <div class="top-right-buttons">
        <button onclick="window.location.href='index.php'">
            <i class="fas fa-home"></i>
        </button>

        <?php if ($isLoggedIn): ?>
            <!-- Paylaşım butonu -->
            <button onclick="window.location.href='paylas.php'">
                <i class="fas fa-pencil-alt"></i>
            </button>

            <!-- Çıkış Yap butonu -->
            <button onclick="window.location.href='kullanici_cikis.php'">
                <i class="fas fa-sign-out-alt"></i>
            </button>
        <?php else: ?>
            <!-- Giriş Yap butonu -->
            <button onclick="window.location.href='kullanici_giris.php'">
                <i class="fas fa-sign-in-alt"></i> 
            </button>
        <?php endif; ?>

        <!-- Karanlık Mod butonu -->
        <button id="darkModeToggle" onclick="toggleDarkMode()">
            <i class="far fa-moon"></i>
        </button>
    </div>

    <form method="POST" class="post-form">
        <label for="username">Kullanıcı Adı</label>
        <input type="text" name="username" autocomplete="off" class="form-input" placeholder="Kullanıcı Adı" value="<?= htmlspecialchars($username) ?>" readonly>

        <label for="bookSelect">📚 Kitap Seçin</label>
        <select id="bookSelect" name="title" class="form-select">
            <option disabled selected>Kitap Seçin</option>
            <?php foreach ($books as $book): ?>
                <option value="<?= htmlspecialchars($book['kitap_adi']) ?>"><?= htmlspecialchars($book['kitap_adi']) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="content">📝 İçerik</label>
        <textarea id="content" class="post-textarea" name="content" placeholder="Metin Girin..." autocomplete="off"></textarea>

        <button type="submit" class="submit-button">Gönder</button>
    </form>

    <div class="post-list">
        <h2>Paylaşımlarınız</h2>
        <?php if (count($posts) > 0): ?>
            <?php foreach ($posts as $post): ?>
                <div class="post-item">
                    <h3><?= htmlspecialchars($post['title']) ?></h3>
                    <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                    <small>Paylaşan: <?= htmlspecialchars($post['username']) ?> | <?= $post['created_at'] ?></small><br>

                    <?php if ($post['username'] === $username): ?>
                        <a href="post_sil.php?id=<?= $post['id'] ?>&redirect=paylas.php" onclick="return confirm('Bu gönderiyi silmek istediğinizden emin misiniz?')">
                            <button style="margin-top: 5px;">Sil</button>
                        </a>
                    <?php endif; ?>
                </div>
                <hr>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Henüz bir paylaşım yapmadınız.</p>
        <?php endif; ?>
    </div>

    <script>
        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');

            // Karanlık modu kaydet
            if (document.body.classList.contains('dark-mode')) {
                localStorage.setItem('darkMode', 'enabled');
            } else {
                localStorage.setItem('darkMode', 'disabled');
            }
        }

        // Sayfa yüklendiğinde karanlık mod tercihini kontrol et
        document.addEventListener("DOMContentLoaded", function () {
            const darkModeStatus = localStorage.getItem('darkMode');
            if (darkModeStatus === 'enabled') {
                document.body.classList.add('dark-mode');
            }
        });
    </script>
</body>
</html>

<style>
    .post-list {
        margin: 30px auto;
        max-width: 800px;
        font-family: 'Montserrat', sans-serif;
    }
    .post-item {
        background-color: #f5f5f5;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
    }
    .dark-mode .post-item {
        background-color: #333;
        color: #fff;
    }
</style>
