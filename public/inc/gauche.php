<?php
$lettres = ['A','C','L','E','R','C','U','S','P','E','M','T','O','R','I','L'];
$actives = [0,1,5,6,9,10,14,15];
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Nunito:wght@400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/style.css">
<section class="gauche">
  <h1 class="logo">BOGGLE</h1>
  <div class="plateau" aria-hidden="true">
    <?php foreach ($lettres as $i => $l): ?><div class="de<?= in_array($i, $actives) ? ' on' : '' ?>"><?= $l ?></div><?php endforeach; ?>
  </div>
  <p class="slogan">À vos lettres, prêts, jouez !</p>
  <p class="sous-slogan">Des lettres, des mots et de beaux défis à partager.</p>
</section>
