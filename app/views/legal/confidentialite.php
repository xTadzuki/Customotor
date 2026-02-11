<?php
$title  = 'Confidentialité';
$active = '';
?>

<section class="cm-legal-hero" style="--hero-img: url('<?= BASE_URL ?>/assets/img/hero-car.jpg');">
  <div class="cm-legal-hero__overlay"></div>

  <div class="cm-container cm-legal-hero__inner">
    <p class="cm-hero__kicker reveal reveal--1">RGPD</p>

    <h1 class="cm-legal-hero__title cm-neon-racing reveal reveal--2">
      Politique de confidentialité
    </h1>

    <p class="cm-legal-hero__subtitle reveal reveal--3">
      Gestion des données personnelles et informations sur vos droits.
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

      <h2 class="cm-legal__h2">Données collectées</h2>
      <p class="cm-legal__p">
        Lors d’une demande via le formulaire, nous collectons : nom, prénom, email, téléphone (optionnel),
        informations véhicule, message.
      </p>

      <h2 class="cm-legal__h2">Finalités</h2>
      <p class="cm-legal__p">
        Répondre aux demandes, gérer les rendez-vous, assurer le suivi client.
      </p>

      <h2 class="cm-legal__h2">Durée de conservation</h2>
      <p class="cm-legal__p">
        (À préciser : ex. 12/24 mois pour demandes sans suite, etc.)
      </p>

      <h2 class="cm-legal__h2">Partage</h2>
      <p class="cm-legal__p">
        Les données ne sont pas vendues. Elles peuvent être accessibles uniquement par Customotor et, si nécessaire, par l’hébergeur.
      </p>

      <h2 class="cm-legal__h2">Vos droits</h2>
      <p class="cm-legal__p">
        Vous pouvez demander l’accès, la rectification ou la suppression de vos données en nous contactant.
      </p>

      <div class="cm-legal__meta">
        <p class="cm-legal__small">Contact RGPD : (email à renseigner)</p>
        <p class="cm-legal__small">Dernière mise à jour : <?= date('d/m/Y') ?></p>
      </div>

    </article>
  </div>
</section>
