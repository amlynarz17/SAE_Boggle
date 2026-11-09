<?php
session_start();
if (!empty($_SESSION['user_id'])) { header('Location: /parties.php'); exit; }

$lettres = str_split('BOGLEMOTSJEUXRAPD');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Boggle Online : trouvez des mots, battez vos amis</title>
  <style>
    :root { --bois:#c8893b; --bois-fonce:#7a4a14; --creme:#fff6e0; --accent:#d9381e; }
    * { box-sizing: border-box; }
    body { margin:0; font-family: system-ui, sans-serif; background:var(--creme); color:#2b1b08; }
    header { background:var(--bois-fonce); color:var(--creme); text-align:center; padding:2rem 1rem; }
    header h1 { margin:0; font-size:2.6rem; letter-spacing:.15em; }
    header p  { margin:.5rem 0 0; font-size:1.2rem; }
    main { max-width:900px; margin:0 auto; padding:2rem 1rem; display:flex; flex-wrap:wrap; gap:2rem; justify-content:center; align-items:center; }
    .grille { display:grid; grid-template-columns:repeat(4,64px); gap:8px; background:var(--bois-fonce); padding:12px; border-radius:12px; }
    .de { width:64px; height:64px; background:var(--creme); border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:2rem; font-weight:700; box-shadow:0 3px 0 var(--bois); }
    .actions { display:flex; flex-direction:column; gap:.8rem; min-width:240px; }
    .actions p { margin:0 0 .5rem; max-width:320px; }
    .btn { display:block; text-align:center; padding:.8rem 1rem; border-radius:8px; text-decoration:none; font-weight:600; background:var(--accent); color:#fff; }
    .btn.second { background:var(--bois); }
    .lien { text-align:center; color:var(--bois-fonce); }
    footer { text-align:center; padding:1rem; font-size:.85rem; color:#6b5430; }
  </style>
</head>
<body>
  <header>
    <h1>BOGGLE ONLINE</h1>
    <p>Trouvez un maximum de mots en 3 minutes, face à vos amis.</p>
  </header>

  <main>
    <div class="grille" aria-hidden="true">
      <?php foreach (array_slice($lettres, 0, 16) as $l): ?>
        <div class="de"><?= htmlspecialchars($l, ENT_QUOTES, 'UTF-8') ?></div>
      <?php endforeach; ?>
    </div>

    <div class="actions">
      <p>Une grille de 16 lettres, 3 minutes, des mots à relier. Créez une partie ou rejoignez-en une.</p>
      <a class="btn" href="/connexion.php">Se connecter</a>
      <a class="btn second" href="/inscription.php">Créer un compte</a>
      <a class="lien" href="/mot_de_passe_oublie.php">Mot de passe oublié ?</a>
    </div>
  </main>

  <footer>SAE Boggle : projet universitaire</footer>
</body>
</html>