$token = bin2hex(random_bytes(32));
$hash  = hash('sha256', $token);
$st = $pdo->prepare('SELECT id FROM utilisateur WHERE email = ?');
$st->execute([$email]);
if ($u = $st->fetch()) {
    $pdo->prepare("INSERT INTO reinit_mot_de_passe (utilisateur_id, token_hash, date_expiration)
                   VALUES (?, ?, NOW() + INTERVAL '30 minutes')")
        ->execute([$u['id'], $hash]);
    $lien = "https://VOTRE-SITE.onrender.com/reinit.php?token=$token";
    // envoyer $lien par mail (voir ci-dessous)
}
echo "Si ce compte existe, un email vient d'être envoyé."; // même message dans tous les cas