<?php
$title  = 'Mentions légales';
$active = '';
?>

<section class="cm-legal-hero" style="--hero-img: url('<?= BASE_URL ?>/assets/img/hero-car.jpg');">
  <div class="cm-legal-hero__overlay"></div>

  <div class="cm-container cm-legal-hero__inner">
    <p class="cm-hero__kicker reveal reveal--1">Informations</p>

    <h1 class="cm-legal-hero__title cm-neon-racing reveal reveal--2">
      Mentions légales
    </h1>

    <p class="cm-legal-hero__subtitle reveal reveal--3">
      Informations légales relatives au site.
    </p>

    <div class="cm-legal-hero__actions reveal reveal--3">
      <a class="cm-btn cm-btn--outline" href="<?= BASE_URL ?>/contact">Contact</a>
      <a class="cm-btn cm-btn--outline" href="<?= BASE_URL ?>/">Retour accueil</a>
    </div>
  </div>
</section>

<section class="cm-legal">
  <div class="cm-container">
    <article class="cm-legal__card">

      <h2 class="cm-legal__h2">Éditeur du site</h2>
      <p class="cm-legal__p">
        <strong>Customotor</strong><br>
        (Renseigner la forme juridique / SIRET / adresse)<br>
        Email : (renseigner)<br>
        Téléphone : (renseigner)
      </p>

      <h2 class="cm-legal__h2">Hébergement</h2>
      <p class="cm-legal__p">
        Hébergeur : (nom + adresse + téléphone)<br>
        (ex : alwaysdata, etc.)
      </p>

      <h2 class="cm-legal__h2">Propriété intellectuelle</h2>
      <p class="cm-legal__p">
        L’ensemble des contenus (textes, images, logos) est protégé. Toute reproduction non autorisée est interdite.
      </p>

      <h2 class="cm-legal__h2">Responsabilité</h2>
      <p class="cm-legal__p">
        Les informations présentées sont fournies à titre indicatif. Customotor ne saurait être tenu responsable d’un usage inadapté.
      </p>

      <div class="cm-legal__meta">
        <p class="cm-legal__small">Dernière mise à jour : <?= date('d/m/Y') ?></p>
      </div>
    </article>
  </div>
</section>
