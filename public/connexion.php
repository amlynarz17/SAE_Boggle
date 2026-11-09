<?php
require __DIR__ . '/inc/init.php';
$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    checkCsrf();
    $email = strtolower(trim($_POST['email'] ?? ''));
    $ip = $_SERVER['REMOTE_ADDR'];
    $pdo = getPdo();

    // Anti-bruteforce : 5 échecs en 15 min
    $st = $pdo->prepare("SELECT COUNT(*) FROM tentative_connexion
        WHERE email = ? AND succes = FALSE AND date_tentative > NOW() - INTERVAL '15 minutes'");
    $st->execute([$email]);

    if ($st->fetchColumn() >= 5) {
        $erreur = "Trop de tentatives, réessayez plus tard.";
    } else {
        $st = $pdo->prepare('SELECT id, mot_de_passe_hash FROM utilisateur WHERE email = ?');
        $st->execute([$email]);
        $u = $st->fetch();
        $ok = $u && password_verify($_POST['mot_de_passe'] ?? '', $u['mot_de_passe_hash']);

        $pdo->prepare('INSERT INTO tentative_connexion (email, ip, succes) VALUES (?, ?, ?)')
            ->execute([$email, $ip, $ok ? 'true' : 'false']);

        if ($ok) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $u['id'];
            header('Location: /parties.php'); exit;
        }
        $erreur = "Identifiants incorrects.";
    }
}