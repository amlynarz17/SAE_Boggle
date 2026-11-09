<?php
require __DIR__ . '/inc/init.php';
if (!empty($_SESSION['user_id'])) { header('Location: /parties.php'); exit; }
?>
<!DOCTYPE html>
<html lang="fr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Boggle : trouvez des mots, relevez des défis</title></head>
<body><div class="page">
<?php include __DIR__ . '/inc/gauche.php'; ?>
<main class="droite"><div class="contenu">
  <a class="btn" href="/connexion.php">Connexion</a>
  <a class="btn contour" href="/inscription.php">Créer un compte</a>
  <p class="pied"><a href="/mot_de_passe_oublie.php">Mot de passe oublié ?</a></p>
</div></main>
</div></body></html>