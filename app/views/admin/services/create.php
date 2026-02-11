<?php
require_once APP_ROOT . '/app/helpers/csrf.php';

$title  = 'Admin — Services';
$active = 'admin_services';

$errors = is_array($errors ?? null) ? $errors : [];
$service = is_array($service ?? null) ? $service : [];
?>

<section class="cm-legal-hero" style="--hero-img: url('<?= BASE_URL ?>/assets/img/hero-car.jpg');">
  <div class="cm-legal-hero__overlay"></div>

  <div class="cm-container cm-legal-hero__inner">
    <p class="cm-hero__kicker reveal reveal--1">ADMIN</p>

    <h1 class="cm-legal-hero__title cm-neon-racing reveal reveal--2">
      Nouveau service
    </h1>

    <p class="cm-legal-hero__subtitle reveal reveal--3">
      Création d’une prestation (nom, catégorie, prix dès, description…).
    </p>

    <div class="cm-legal-hero__actions reveal reveal--3">
      <a class="cm-btn cm-btn--outline" href="<?= BASE_URL ?>/admin/services">Retour</a>
      <a class="cm-link-more" href="<?= BASE_URL ?>/admin">
        Dashboard <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
      </a>
    </div>
  </div>
</section>

<section class="cm-auth-page">
  <div class="cm-container">

    <?php if (!empty($errors)): ?>
      <div class="cm-alert cm-alert--danger reveal reveal--2">
        <strong>Erreur</strong>
        <?php foreach ($errors as $err): ?>
          <div><?= htmlspecialchars((string)$err, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endforeach; ?>
      </div>
      <div style="height:.75rem"></div>
    <?php endif; ?>

    <div class="cm-admin-edit-grid">

      <!-- Aperçu -->
      <div class="cm-auth-card cm-admin-card reveal reveal--2">
        <h2 class="cm-admin-card__title">Aperçu</h2>
        <p class="cm-admin-card__subtitle">Vérifie les informations avant d’enregistrer.</p>

        <?php
          $name = trim((string)($service['name'] ?? ''));
          $cat  = (string)($service['category_name'] ?? '');
          $price = $service['price_from'] ?? null;
          $desc = trim((string)($service['description'] ?? ''));
          $activeVal = (int)($service['is_active'] ?? 1);
        ?>

        <ul class="cm-admin-meta">
          <li>
            <span class="cm-admin-meta__label">Nom</span>
            <span class="cm-admin-meta__value"><?= htmlspecialchars($name !== '' ? $name : '—', ENT_QUOTES, 'UTF-8') ?></span>
          </li>
          <li>
            <span class="cm-admin-meta__label">Catégorie</span>
            <span class="cm-admin-meta__value muted"><?= htmlspecialchars($cat !== '' ? $cat : '—', ENT_QUOTES, 'UTF-8') ?></span>
          </li>
          <li>
            <span class="cm-admin-meta__label">Prix dès</span>
            <span class="cm-admin-meta__value">
              <?= ($price !== null && $price !== '') ? number_format((float)$price, 0, ',', ' ') . ' €' : '—' ?>
            </span>
          </li>
          <li>
            <span class="cm-admin-meta__label">Statut</span>
            <span class="status status--<?= $activeVal === 1 ? 'confirmed' : 'cancelled' ?>">
              <?= $activeVal === 1 ? 'Actif' : 'Inactif' ?>
            </span>
          </li>
        </ul>

        <div class="cm-auth-divider"></div>

        <div class="cm-admin-reviewbox">
          <div class="cm-admin-reviewbox__label">Description</div>
          <div class="cm-admin-reviewbox__text">
            <?= $desc !== '' ? nl2br(htmlspecialchars($desc, ENT_QUOTES, 'UTF-8')) : '<span class="muted">—</span>' ?>
          </div>
        </div>
      </div>

      <!-- Form -->
      <div class="cm-auth-card cm-admin-card reveal reveal--3">
        <h2 class="cm-admin-card__title">Création</h2>
        <p class="cm-admin-card__subtitle">Renseigne les champs, puis enregistre.</p>

        <?php $action = BASE_URL . '/admin/services'; ?>
        <?php include APP_ROOT . '/app/views/admin/services/form.php'; ?>

        <div class="cm-auth-divider"></div>

        <p class="cm-auth-note">
          Astuce : tu peux laisser “Prix dès” vide si le tarif dépend du véhicule.
        </p>
      </div>

    </div>

  </div>
</section>
