<?php
session_start();
include("db.php");

// Giriş kontrolü: Kullanıcı ya da admin giriş yaptı mı?
if (!isset($_SESSION['admin']) && !isset($_SESSION['kullanici_id'])) {
    echo "Bu işlemi yapabilmek için giriş yapmalısınız.";
    exit;
}

// Gönderi ID'si alınıyor
$gonderi_id = $_GET['id'] ?? null;
if (!$gonderi_id) {
    echo "Geçersiz gönderi ID.";
    exit;
}

// Yönlendirme URL'sini kontrol edelim, var mı?
$redirect_url = $_GET['redirect'] ?? null; 

// Giriş yapan kişinin bilgileri
if (isset($_SESSION['admin'])) {
    // Admin giriş yaptıysa
    $aktif_kullanici = $_SESSION['admin'];
    $kullanici_turu = 'admin';
    $redirect_url = $redirect_url ?: 'admin_panel.php'; // Admin için admin paneline yönlendirme
} elseif (isset($_SESSION['kullanici_id'])) {
    // Kullanıcı giriş yaptıysa
    $aktif_kullanici = $_SESSION['kullanici_adi'];
    $kullanici_turu = $_SESSION['kullanici_turu'];
    // Kullanıcı için yönlendirme URL'si paylas.php olacak
    $redirect_url = $redirect_url ?: 'paylas.php'; 
} else {
    echo "Bu işlemi yapabilmek için giriş yapmalısınız.";
    exit;
}

// Gönderiyi veritabanından al
$sorgu = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$sorgu->execute([$gonderi_id]);
$post = $sorgu->fetch();

if (!$post) {
    echo "Gönderi bulunamadı.";
    exit;
}

// Yetki kontrolü: admin ya da gönderi sahibi
if ($kullanici_turu === 'admin' || $post['username'] === $aktif_kullanici) {
    $sil = $pdo->prepare("DELETE FROM posts WHERE id = ?");
    $sil->execute([$gonderi_id]);

    // Yönlendirme işlemine geç
    header("Location: $redirect_url");
    exit;
} else {
    echo "Bu gönderiyi silmeye yetkiniz yok.";
    exit;
}
?>
