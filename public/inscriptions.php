<?php
require __DIR__ . '/inc/init.php';
$erreurs = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    checkCsrf();
    $pseudo = trim($_POST['pseudo'] ?? '');
    $email  = strtolower(trim($_POST['email'] ?? ''));
    $mdp    = $_POST['mot_de_passe'] ?? '';

    if (!preg_match('/^[A-Za-z0-9_]{3,30}$/', $pseudo)) $erreurs[] = "Pseudo invalide (3-30 caractères, lettres/chiffres/_).";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))     $erreurs[] = "Email invalide.";
    if (strlen($mdp) < 12)                              $erreurs[] = "Mot de passe : 12 caractères minimum.";

    if (!$erreurs) {
        try {
            $st = getPdo()->prepare(
                'INSERT INTO utilisateur (pseudo, email, mot_de_passe_hash) VALUES (?, ?, ?)');
            $st->execute([$pseudo, $email, password_hash($mdp, PASSWORD_DEFAULT)]);
            header('Location: /connexion.php?ok=1'); exit;
        } catch (PDOException $e) {
            $erreurs[] = "Impossible de créer le compte avec ces informations.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr"><head><meta charset="utf-8"><title>Inscription</title></head>
<body>
<h1>Créer un compte</h1>
<?php foreach ($erreurs as $e): ?><p style="color:red"><?= h($e) ?></p><?php endforeach; ?>
<form method="post">
  <input type="hidden" name="csrf" value="<?= csrfToken() ?>">
  <input name="pseudo" placeholder="Pseudo" required>
  <input type="email" name="email" placeholder="Email" required>
  <input type="password" name="mot_de_passe" placeholder="Mot de passe" required minlength="12">
  <button>S'inscrire</button>
</form>
</body></html>