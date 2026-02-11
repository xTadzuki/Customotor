<?php
require_once APP_ROOT . '/app/helpers/csrf.php';

$title  = 'Inscription';
$active = 'register';

$old    = is_array($old ?? null) ? $old : [];
$errors = is_array($errors ?? null) ? $errors : [];
?>

<section class="cm-legal-hero" style="--hero-img: url('<?= BASE_URL ?>/assets/img/hero-car.jpg');">
  <div class="cm-legal-hero__overlay"></div>

  <div class="cm-container cm-legal-hero__inner">
    <p class="cm-hero__kicker reveal reveal--1">ESPACE CLIENT</p>

    <h1 class="cm-legal-hero__title cm-neon-racing reveal reveal--2">
      Inscription
    </h1>

    <p class="cm-legal-hero__subtitle reveal reveal--3">
      Créez votre compte pour suivre vos demandes et rendez-vous.
    </p>
  </div>
</section>

<section class="cm-auth-page">
  <div class="cm-container">

    <div class="cm-auth-card reveal reveal--2">
      <form method="post" action="<?= BASE_URL ?>/register" novalidate>
        <?= csrf_field() ?>

        <div class="cm-form__row">
          <div class="cm-field">
            <label class="cm-label" for="firstname">Prénom *</label>
            <input
              class="cm-input"
              id="firstname"
              name="firstname"
              required
              autocomplete="given-name"
              value="<?= htmlspecialchars((string)($old['firstname'] ?? '')) ?>"
            >
            <?php if (!empty($errors['firstname'])): ?>
              <small class="cm-error"><?= htmlspecialchars((string)$errors['firstname']) ?></small>
            <?php endif; ?>
          </div>

          <div class="cm-field">
            <label class="cm-label" for="lastname">Nom *</label>
            <input
              class="cm-input"
              id="lastname"
              name="lastname"
              required
              autocomplete="family-name"
              value="<?= htmlspecialchars((string)($old['lastname'] ?? '')) ?>"
            >
            <?php if (!empty($errors['lastname'])): ?>
              <small class="cm-error"><?= htmlspecialchars((string)$errors['lastname']) ?></small>
            <?php endif; ?>
          </div>
        </div>

        <div class="cm-field">
          <label class="cm-label" for="email-register">Email *</label>
          <input
            class="cm-input"
            id="email-register"
            type="email"
            name="email"
            required
            autocomplete="email"
            inputmode="email"
            value="<?= htmlspecialchars((string)($old['email'] ?? '')) ?>"
          >
          <?php if (!empty($errors['email'])): ?>
            <small class="cm-error"><?= htmlspecialchars((string)$errors['email']) ?></small>
          <?php endif; ?>
        </div>

        <div class="cm-field">
          <label class="cm-label" for="password-register">Mot de passe *</label>

          <div class="cm-input-wrap">
            <input
              class="cm-input cm-input--withbtn"
              id="password-register"
              type="password"
              name="password"
              required
              autocomplete="new-password"
              minlength="8"
            >
            <button class="cm-input-btn" type="button" data-toggle-pass aria-label="Afficher le mot de passe">
              <span class="cm-passicon" aria-hidden="true"></span>
            </button>
          </div>

          <?php if (!empty($errors['password'])): ?>
            <small class="cm-error"><?= htmlspecialchars((string)$errors['password']) ?></small>
          <?php endif; ?>

          <small class="cm-auth-note" style="display:block; margin-top:.45rem;">
            8 caractères minimum.
          </small>
        </div>

        <div class="cm-auth-actions">
          <button class="cm-btn cm-btn--outline" type="submit">Créer mon compte</button>
          <a class="cm-link-more" href="<?= BASE_URL ?>/login">
            Se connecter <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
          </a>
        </div>

        <div class="cm-auth-divider"></div>

        <p class="cm-contact__fineprint" style="margin:0;">
          En créant un compte, vous acceptez notre
          <a href="<?= BASE_URL ?>/confidentialite">politique de confidentialité</a>.
        </p>
      </form>
    </div>

  </div>
</section>
