<?php
require_once APP_ROOT . '/app/helpers/csrf.php';

$title  = 'Connexion';
$active = 'login';

$old    = is_array($old ?? null) ? $old : [];
$errors = is_array($errors ?? null) ? $errors : [];
$flash  = $flash ?? null;
?>

<section class="cm-legal-hero" style="--hero-img: url('<?= BASE_URL ?>/assets/img/hero-car.jpg');">
  <div class="cm-legal-hero__overlay"></div>

  <div class="cm-container cm-legal-hero__inner">
    <p class="cm-hero__kicker reveal reveal--1">ESPACE CLIENT</p>

    <h1 class="cm-legal-hero__title cm-neon-racing reveal reveal--2">
      Connexion
    </h1>

    <p class="cm-legal-hero__subtitle reveal reveal--3">
      Accédez à votre espace client.
    </p>
  </div>
</section>

<section class="cm-auth-page">
  <div class="cm-container">

    <div class="cm-auth-card reveal reveal--2">

      <?php if (!empty($flash)): ?>
        <div class="cm-alert cm-alert--info">
          <?= htmlspecialchars((string)$flash) ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($errors['global'])): ?>
        <div class="cm-alert cm-alert--error">
          <?= htmlspecialchars((string)$errors['global']) ?>
        </div>
      <?php endif; ?>

      <form method="post" action="<?= BASE_URL ?>/login" novalidate>
        <?= csrf_field() ?>

        <div class="cm-field">
          <label class="cm-label" for="email-login">Email</label>
          <input
            class="cm-input"
            id="email-login"
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
          <label class="cm-label" for="password-login">Mot de passe</label>

          <div class="cm-input-wrap">
            <input
              class="cm-input cm-input--withbtn"
              id="password-login"
              type="password"
              name="password"
              required
              autocomplete="current-password"
            >
            <button class="cm-input-btn" type="button" data-toggle-pass aria-label="Afficher le mot de passe">
              <span class="cm-passicon" aria-hidden="true"></span>
            </button>
          </div>

          <?php if (!empty($errors['password'])): ?>
            <small class="cm-error"><?= htmlspecialchars((string)$errors['password']) ?></small>
          <?php endif; ?>
        </div>

        <div class="cm-auth-actions">
          <button class="cm-btn cm-btn--outline" type="submit">Se connecter</button>
          <a class="cm-link-more" href="<?= BASE_URL ?>/register">
            Créer un compte <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
          </a>
        </div>

        <div class="cm-auth-divider"></div>
      </form>

    </div>

  </div>
</section>
