<?php
require __DIR__ . '/inc/init.php';
$erreur = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    checkCsrf();
    $email = strtolower(trim($_POST['email'] ?? ''));
    $ip = $_SERVER['REMOTE_ADDR'];
    $pdo = getPdo();
    $st = $pdo->prepare("SELECT COUNT(*) FROM tentative_connexion WHERE email = ? AND succes = FALSE AND date_tentative > NOW() - INTERVAL '15 minutes'");
    $st->execute([$email]);
    if ($st->fetchColumn() >= 5) {
        $erreur = "Trop de tentatives. Réessayez dans 15 minutes.";
    } else {
        $st = $pdo->prepare('SELECT id, mot_de_passe_hash FROM utilisateur WHERE email = ?');
        $st->execute([$email]);
        $u = $st->fetch();
        $ok = $u && password_verify($_POST['mot_de_passe'] ?? '', $u['mot_de_passe_hash']);
        $pdo->prepare('INSERT INTO tentative_connexion (email, ip, succes) VALUES (?, ?, ?)')->execute([$email, $ip, $ok ? 'true' : 'false']);
        if ($ok) { session_regenerate_id(true); $_SESSION['user_id'] = $u['id']; header('Location: /parties.php'); exit; }
        $erreur = "Adresse e-mail ou mot de passe incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Connexion · Boggle</title></head>
<body><div class="page">
<?php include __DIR__ . '/inc/gauche.php'; ?>
<main class="droite">
  <a class="retour" href="/index.php">← Retour à l'accueil</a>
  <div class="contenu">
    <h2>Connexion</h2>
    <p class="intro">Connectez-vous pour retrouver vos défis.</p>
    <?php if (isset($_GET['ok'])): ?><p class="succes">Compte créé. Vous pouvez vous connecter.</p><?php endif; ?>
    <?php if (isset($_GET['reinit'])): ?><p class="succes">Mot de passe modifié. Connectez-vous.</p><?php endif; ?>
    <?php if ($erreur): ?><p class="erreur"><?= h($erreur) ?></p><?php endif; ?>
    <form method="post">
      <input type="hidden" name="csrf" value="<?= csrfToken() ?>">
      <label for="email">Adresse e-mail</label>
      <input id="email" type="email" name="email" placeholder="vous@exemple.fr" required autocomplete="email">
      <label for="mdp">Mot de passe</label>
      <input id="mdp" type="password" name="mot_de_passe" placeholder="Votre mot de passe" required autocomplete="current-password">
      <div class="ligne"><a class="lien" href="/mot_de_passe_oublie.php">Mot de passe oublié ?</a></div>
      <button class="btn" type="submit">Se connecter</button>
    </form>
    <p class="pied">Pas encore de compte ? <a href="/inscription.php">Créer un compte</a></p>
  </div>
</main>
</div></body></html>