<?php
require_once APP_ROOT . '/app/helpers/csrf.php';
$title  = 'Contact';
$active = 'contact';
?>

<section class="cm-legal-hero" style="--hero-img: url('<?= BASE_URL ?>/assets/img/hero-car.jpg');">
  <div class="cm-legal-hero__overlay"></div>

  <div class="cm-container cm-legal-hero__inner">
    <p class="cm-hero__kicker reveal reveal--1">CONTACT</p>

    <h1 class="cm-legal-hero__title cm-neon-racing reveal reveal--2">
      Contact &amp; devis
    </h1>

    <p class="cm-legal-hero__subtitle reveal reveal--3">
      Décrivez votre véhicule et votre besoin. Plus votre demande est précise,
      plus la réponse sera adaptée.
    </p>
  </div>
</section>

<section class="cm-contact">
  <div class="cm-container">

    <?php if (!empty($success)): ?>
      <div class="cm-alert cm-alert--success reveal reveal--1">
        <strong>Demande envoyée</strong>
        <span>Nous vous répondrons sous 24 à 48h ouvrées.</span>
      </div>
    <?php endif; ?>

    <div class="cm-contact__grid">
      <!-- FORM -->
      <div class="cm-contact__card reveal reveal--2">
        <h2 class="cm-contact__h2">Votre demande</h2>
        <p class="cm-contact__p">Champs obligatoires <span style="color:var(--accent)">*</span></p>

        <form method="post" action="<?= BASE_URL ?>/contact" novalidate>
          <?= csrf_field() ?>

          <div class="cm-form__row">
            <div class="cm-field">
              <label class="cm-label" for="firstname">Prénom *</label>
              <input class="cm-input" id="firstname" name="firstname" required autocomplete="given-name"
                value="<?= htmlspecialchars((string)($old['firstname'] ?? '')) ?>">
              <?php if (!empty($errors['firstname'])): ?>
                <small class="cm-error"><?= htmlspecialchars((string)$errors['firstname']) ?></small>
              <?php endif; ?>
            </div>

            <div class="cm-field">
              <label class="cm-label" for="lastname">Nom *</label>
              <input class="cm-input" id="lastname" name="lastname" required autocomplete="family-name"
                value="<?= htmlspecialchars((string)($old['lastname'] ?? '')) ?>">
              <?php if (!empty($errors['lastname'])): ?>
                <small class="cm-error"><?= htmlspecialchars((string)$errors['lastname']) ?></small>
              <?php endif; ?>
            </div>
          </div>

          <div class="cm-form__row">
            <div class="cm-field">
              <label class="cm-label" for="email">Email *</label>
              <input class="cm-input" id="email" name="email" type="email" required autocomplete="email"
                value="<?= htmlspecialchars((string)($old['email'] ?? '')) ?>">
              <?php if (!empty($errors['email'])): ?>
                <small class="cm-error"><?= htmlspecialchars((string)$errors['email']) ?></small>
              <?php endif; ?>
            </div>

            <div class="cm-field">
              <label class="cm-label" for="phone">Téléphone</label>
              <input class="cm-input" id="phone" name="phone" type="tel" autocomplete="tel"
                value="<?= htmlspecialchars((string)($old['phone'] ?? '')) ?>">
            </div>
          </div>

          <div class="cm-form__row">
            <div class="cm-field">
              <label class="cm-label" for="brand">Marque</label>
              <input class="cm-input" id="brand" name="brand"
                value="<?= htmlspecialchars((string)($old['brand'] ?? '')) ?>">
            </div>

            <div class="cm-field">
              <label class="cm-label" for="model">Modèle</label>
              <input class="cm-input" id="model" name="model"
                value="<?= htmlspecialchars((string)($old['model'] ?? '')) ?>">
            </div>
          </div>

          <div class="cm-field">
            <label class="cm-label" for="year">Année</label>
            <input class="cm-input" id="year" name="year" type="number" inputmode="numeric"
              min="1950" max="<?= (int)date('Y') + 1 ?>"
              value="<?= htmlspecialchars((string)($old['year'] ?? '')) ?>">
            <?php if (!empty($errors['year'])): ?>
              <small class="cm-error"><?= htmlspecialchars((string)$errors['year']) ?></small>
            <?php endif; ?>
          </div>

          <div class="cm-field">
            <label class="cm-label" for="message">Message *</label>
            <textarea class="cm-textarea" id="message" name="message" required rows="7"><?= htmlspecialchars((string)($old['message'] ?? '')) ?></textarea>
            <?php if (!empty($errors['message'])): ?>
              <small class="cm-error"><?= htmlspecialchars((string)$errors['message']) ?></small>
            <?php endif; ?>
          </div>

          <div class="cm-form__actions">
            <button class="cm-btn cm-btn--outline" type="submit">Envoyer la demande</button>
            <a class="cm-link-more" href="<?= BASE_URL ?>/">Retour accueil <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
          </div>

          <p class="cm-contact__fineprint">
            En envoyant ce formulaire, vous acceptez que vos données soient utilisées pour traiter votre demande.
            <a href="<?= BASE_URL ?>/confidentialite">En savoir plus</a>.
          </p>
        </form>
      </div>

      <!-- INFOS -->
      <aside class="cm-contact__side reveal reveal--3">
        <div class="cm-contact__card">
          <h2 class="cm-contact__h2">Infos atelier</h2>
          <ul class="cm-contact__list">
            <li><span class="cm-contact__label">Téléphone</span> <span class="cm-contact__value">06 75 91 27 86</span></li>
            <li><span class="cm-contact__label">Email</span> <span class="cm-contact__value">contact@customotor.fr</span></li>
            <li><span class="cm-contact__label">Localisation</span> <span class="cm-contact__value">Customotor - Venelles</span></li>
            <li><span class="cm-contact__label">Délais</span> <span class="cm-contact__value">Réponse sous 24–48h ouvrées</span></li>
          </ul>
        </div>

        <div class="cm-contact__card">
          <h2 class="cm-contact__h2">Conseil</h2>
          <p class="cm-contact__p">
            Indiquez la motorisation, l’année, le kilométrage, et ce que vous recherchez
            (fiabilité, performance, consommation, piste…).
          </p>
        </div>
      </aside>
    </div>

  </div>
</section>
