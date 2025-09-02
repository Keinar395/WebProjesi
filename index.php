<?php
    session_start(); // Oturumu başlat

    // Oturumda kullanıcı adı mevcutsa, kullanıcı adını al
    if (isset($_SESSION['kullanici'])) {
        $isLoggedIn = true;
    } else {
        $isLoggedIn = false;
    }

    require 'db.php';  // Veritabanı bağlantısı

    // Gönderileri veritabanından çekme
    $posts = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
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

    <?php foreach ($posts as $post): ?>
        <div class="post-container">
    <?php
        // created_at tarihini formatla: gün/ay/yıl
        $formatted_date = date("d/m/Y", strtotime($post['created_at']));

        // İçeriğin ilk 20 karakterini al
        $short_content = mb_substr($post['content'], 0, 20, 'UTF-8') . '...';
    ?>
    <div class="post-box">
    <h2><?= htmlspecialchars($post['title']) ?></h2>
    <p class="post-date">📅 <?= $formatted_date ?></p>  
    <p class="post-author">👤 <?= htmlspecialchars($post['username']) ?></p>  

    <p class="post-content" data-full="<?= htmlspecialchars($post['content']) ?>">
        <?= nl2br(htmlspecialchars($short_content)) ?>
    </p>

    <button class="toggle-btn">
        Devamını Gör <i class="fas fa-chevron-down"></i>
    </button>
    </div>

</div>

<?php endforeach; ?>

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
        // Yerel depolama kontrolü
        const darkModeStatus = localStorage.getItem('darkMode');

        if (darkModeStatus === 'enabled') {
            document.body.classList.add('dark-mode');
        }

        const buttons = document.querySelectorAll(".toggle-btn");

        buttons.forEach(button => {
            // Her buton için ilk durumu ayarla
            const contentEl = button.previousElementSibling;
            const fullContent = contentEl.getAttribute("data-full");
            const shortContent = fullContent.substring(0, 20) + "...";
            
            // İlk durumu ayarla (kısa içerik gösterilsin)
            contentEl.textContent = shortContent;
            button.textContent = "Devamını Gör";

            button.addEventListener("click", function () {
                const currentContent = contentEl.textContent;
                
                if (currentContent === shortContent || currentContent.length <= 23) {
                    // Kısa içerik gösteriliyorsa, tam içeriği göster
                    contentEl.textContent = fullContent;
                    this.textContent = "Daha Azını Gör";
                } else {
                    // Tam içerik gösteriliyorsa, kısa içeriği göster
                    contentEl.textContent = shortContent;
                    this.textContent = "Devamını Gör";
                }
            });
        });
    });
</script>
</body>
</html>
