<?php
require __DIR__ . '/inc/init.php';
$erreurs = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    checkCsrf();
    $pseudo = trim($_POST['pseudo'] ?? '');
    $email  = strtolower(trim($_POST['email'] ?? ''));
    $mdp    = $_POST['mot_de_passe'] ?? '';
    if (!preg_match('/^[A-Za-z0-9_]{3,30}$/', $pseudo)) $erreurs[] = "Pseudo invalide : 3 à 30 caractères, lettres, chiffres ou _.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))     $erreurs[] = "Adresse e-mail invalide.";
    if (strlen($mdp) < 12)                              $erreurs[] = "Le mot de passe doit contenir au moins 12 caractères.";
    if ($mdp !== ($_POST['confirmation'] ?? ''))        $erreurs[] = "Les deux mots de passe sont différents.";
    if (empty($_POST['cgu']))                           $erreurs[] = "Vous devez accepter les conditions d'utilisation.";
    if (!$erreurs) {
        try {
            getPdo()->prepare('INSERT INTO utilisateur (pseudo, email, mot_de_passe_hash) VALUES (?, ?, ?)')
                ->execute([$pseudo, $email, password_hash($mdp, PASSWORD_DEFAULT)]);
            header('Location: /connexion.php?ok=1'); exit;
        } catch (PDOException $e) {
    error_log('Inscription : ' . $e->getMessage());
    if ($e->getCode() === '23505') {
        $erreurs[] = "Impossible de créer le compte avec ces informations.";
    } else {
        $erreurs[] = "Erreur technique, réessayez plus tard.";
    }
}
    }
}
?>
<!DOCTYPE html>
<html lang="fr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Créer un compte · Boggle</title></head>
<body><div class="page">
<?php include __DIR__ . '/inc/gauche.php'; ?>
<main class="droite">
  <a class="retour" href="/index.php">← Retour à l'accueil</a>
  <div class="contenu">
    <h2>Créer</h2>
    <p class="intro">Créez votre compte et lancez-vous dans les lettres.</p>
    <?php foreach ($erreurs as $e): ?><p class="erreur"><?= h($e) ?></p><?php endforeach; ?>
    <form method="post">
      <input type="hidden" name="csrf" value="<?= csrfToken() ?>">
      <label for="pseudo">Pseudo</label>
      <input id="pseudo" type="text" name="pseudo" placeholder="Votre nom de joueur" required maxlength="30" value="<?= h($_POST['pseudo'] ?? '') ?>">
      <label for="email">Adresse e-mail</label>
      <input id="email" type="email" name="email" placeholder="vous@exemple.fr" required autocomplete="email" value="<?= h($_POST['email'] ?? '') ?>">
      <label for="mdp">Mot de passe</label>
      <input id="mdp" type="password" name="mot_de_passe" placeholder="Choisissez un mot de passe" required minlength="12" autocomplete="new-password">
      <p class="aide">12 caractères minimum.</p>
      <label for="conf">Confirmer le mot de passe</label>
      <input id="conf" type="password" name="confirmation" placeholder="Saisissez-le à nouveau" required minlength="12" autocomplete="new-password">
      <label class="cgu"><input type="checkbox" name="cgu" value="1" required> J'accepte les conditions d'utilisation et la politique de confidentialité.</label>
      <button class="btn" type="submit">Créer mon compte</button>
    </form>
    <p class="pied">Déjà un compte ? <a href="/connexion.php">Se connecter</a></p>
  </div>
</main>
</div></body></html>