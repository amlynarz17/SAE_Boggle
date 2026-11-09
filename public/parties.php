<?php
require __DIR__ . '/inc/init.php';
requireLogin();
$parties = [['Partie #1', 'En cours'], ['Partie #2', 'Terminée'], ['Partie #3', 'En attente']];
?>
<h1>Mes parties</h1>
<ul><?php foreach ($parties as [$nom, $statut]): ?>
  <li><?= h($nom) ?> : <?= h($statut) ?></li>
<?php endforeach; ?></ul>
<button type="button">Nouvelle partie</button>
<a href="/deconnexion.php">Déconnexion</a>