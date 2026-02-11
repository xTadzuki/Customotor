<?php
require_once APP_ROOT . '/app/helpers/csrf.php';

$title  = 'Admin — Nouveau projet';
$active = 'admin_projects';

$old    = is_array($old ?? null) ? $old : [];
$errors = is_array($errors ?? null) ? $errors : [];
?>

<section class="cm-legal-hero" style="--hero-img: url('<?= BASE_URL ?>/assets/img/hero-car.jpg');">
  <div class="cm-legal-hero__overlay"></div>

  <div class="cm-container cm-legal-hero__inner">
    <p class="cm-hero__kicker reveal reveal--1">ADMIN</p>

    <h1 class="cm-legal-hero__title cm-neon-racing reveal reveal--2">
      Nouveau projet
    </h1>

    <p class="cm-legal-hero__subtitle reveal reveal--3">
      Ajout d’un projet lookbook.
    </p>

    <div class="cm-legal-hero__actions reveal reveal--3">
      <a class="cm-btn cm-btn--outline" href="<?= BASE_URL ?>/admin/projects">Retour</a>
    </div>
  </div>
</section>

<section class="cm-auth-page">
  <div class="cm-container">

    <div class="cm-auth-card cm-admin-card reveal reveal--2">
      <h2 class="cm-admin-card__title">Contenu</h2>
      <p class="cm-admin-card__subtitle">
        Crée la fiche projet qui sera visible dans le lookbook.
      </p>

      <form method="post" action="<?= BASE_URL ?>/admin/projects" novalidate>
        <?= csrf_field() ?>

        <div class="cm-field">
          <label class="cm-label" for="title">Titre *</label>
          <input
            class="cm-input"
            id="title"
            name="title"
            required
            value="<?= htmlspecialchars((string)($old['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
          >
          <?php if (!empty($errors['title'])): ?>
            <small class="cm-error"><?= htmlspecialchars((string)$errors['title'], ENT_QUOTES, 'UTF-8') ?></small>
          <?php endif; ?>
        </div>

        <div class="cm-field">
          <label class="cm-label" for="subtitle">Sous-titre</label>
          <input
            class="cm-input"
            id="subtitle"
            name="subtitle"
            value="<?= htmlspecialchars((string)($old['subtitle'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
          >
          <?php if (!empty($errors['subtitle'])): ?>
            <small class="cm-error"><?= htmlspecialchars((string)$errors['subtitle'], ENT_QUOTES, 'UTF-8') ?></small>
          <?php endif; ?>
        </div>

        <div class="cm-field">
          <label class="cm-label" for="description">Description</label>
          <textarea
            class="cm-textarea"
            id="description"
            name="description"
            rows="7"
          ><?= htmlspecialchars((string)($old['description'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
          <?php if (!empty($errors['description'])): ?>
            <small class="cm-error"><?= htmlspecialchars((string)$errors['description'], ENT_QUOTES, 'UTF-8') ?></small>
          <?php endif; ?>
        </div>

        <div class="cm-auth-actions" style="margin-top:.25rem">
          <button class="cm-btn cm-btn--outline" type="submit">Créer</button>
          <a class="cm-link-more" href="<?= BASE_URL ?>/admin/projects">
            Annuler <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
          </a>
        </div>
      </form>

      <div class="cm-auth-divider"></div>
    </div>

  </div>
</section>
