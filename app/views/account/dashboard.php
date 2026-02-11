<?php
require_once APP_ROOT . '/app/helpers/auth.php';

$user = is_array($user ?? null) ? $user : (auth_user() ?? []);

$firstname = trim((string)($user['firstname'] ?? ''));
$lastname  = trim((string)($user['lastname'] ?? ''));
$fullName  = trim($firstname . ' ' . $lastname);

$email = (string)($user['email'] ?? '');
$role  = (string)($user['role'] ?? 'client');
?>

<section class="cm-legal-hero" style="--hero-img: url('<?= BASE_URL ?>/assets/img/hero-car.jpg');">
  <div class="cm-legal-hero__overlay"></div>

  <div class="cm-container cm-legal-hero__inner">
    <p class="cm-hero__kicker reveal reveal--1">ESPACE CLIENT</p>

    <h1 class="cm-legal-hero__title cm-neon-racing reveal reveal--2">
      Mon compte
    </h1>

    <p class="cm-legal-hero__subtitle reveal reveal--3">
      Bonjour <?= htmlspecialchars($fullName !== '' ? $fullName : 'client') ?>.
    </p>
  </div>
</section>

<section class="cm-account">
  <div class="cm-container">

    <div class="cm-account__grid">

      <!-- Actions -->
      <div class="cm-account__card reveal reveal--2">
        <h2 class="cm-account__h2">Actions rapides</h2>
        <p class="cm-account__p">Accédez à vos demandes, rendez-vous, véhicules et avis.</p>

        <div class="cm-account__actions">
          <a class="cm-account__chip" href="<?= BASE_URL ?>/account/requests">
            Mes demandes <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
          </a>

          <a class="cm-account__chip" href="<?= BASE_URL ?>/account/appointments">
            Mes rendez-vous <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
          </a>

          <a class="cm-account__chip" href="<?= BASE_URL ?>/account/vehicles">
            Mes véhicules <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
          </a>

          <a class="cm-account__chip" href="<?= BASE_URL ?>/account/reviews">
            Laisser un avis <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
          </a>

          <?php if (auth_is_admin()): ?>
            <a class="cm-account__chip cm-account__chip--accent" href="<?= BASE_URL ?>/admin">
              Admin <i class="fa-solid fa-shield-halved" aria-hidden="true"></i>
            </a>
          <?php endif; ?>
        </div>

        <div class="cm-account__divider"></div>

        <a class="cm-btn cm-btn--outline" href="<?= BASE_URL ?>/logout">
          Déconnexion
        </a>
      </div>

      <!-- Profil -->
      <div class="cm-account__card reveal reveal--3">
        <h2 class="cm-account__h2">Profil</h2>
        <p class="cm-account__p">Vos informations de compte.</p>

        <ul class="cm-account__list">
          <li>
            <span class="cm-account__label">Nom</span>
            <span class="cm-account__value"><?= htmlspecialchars($fullName !== '' ? $fullName : '—') ?></span>
          </li>

          <li>
            <span class="cm-account__label">Email</span>
            <span class="cm-account__value"><?= htmlspecialchars($email !== '' ? $email : '—') ?></span>
          </li>

          <li>
            <span class="cm-account__label">Rôle</span>
            <span class="cm-account__value"><?= htmlspecialchars($role !== '' ? $role : 'client') ?></span>
          </li>
        </ul>

        <p class="cm-account__fineprint">
          Besoin de modifier vos infos ? Passez par le formulaire de contact.
        </p>
      </div>

    </div>
  </div>
</section>
