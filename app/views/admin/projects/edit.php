<?php
require_once APP_ROOT . '/app/helpers/csrf.php';

$title  = 'Admin — Modifier projet';
$active = 'admin_projects';

$errors  = is_array($errors ?? null) ? $errors : [];
$images  = is_array($images ?? null) ? $images : [];

if (empty($project)) {
  http_response_code(404);
  echo '404';
  exit;
}

$id = (int)($project['id'] ?? 0);

$img_added   = !empty($img_added);
$img_deleted = !empty($img_deleted);
$img_err     = (string)($img_err ?? '');
?>

<section class="cm-legal-hero" style="--hero-img: url('<?= BASE_URL ?>/assets/img/hero-car.jpg');">
  <div class="cm-legal-hero__overlay"></div>

  <div class="cm-container cm-legal-hero__inner">
    <p class="cm-hero__kicker reveal reveal--1">ADMIN</p>

    <h1 class="cm-legal-hero__title cm-neon-racing reveal reveal--2">
      Modifier projet #<?= $id ?>
    </h1>

    <p class="cm-legal-hero__subtitle reveal reveal--3">
      Mise à jour d’un projet lookbook + gestion des photos.
    </p>

    <div class="cm-legal-hero__actions reveal reveal--3">
      <a class="cm-btn cm-btn--outline" href="<?= BASE_URL ?>/admin/projects">Retour</a>
      <a class="cm-link-more" href="<?= BASE_URL ?>/lookbook/<?= $id ?>">
        Voir public <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
      </a>
    </div>
  </div>
</section>

<section class="cm-auth-page">
  <div class="cm-container">

    <?php if ($img_added): ?>
      <div class="cm-alert cm-alert--success reveal reveal--2">
        <strong>OK</strong> Photo ajoutée.
      </div>
    <?php endif; ?>

    <?php if ($img_deleted): ?>
      <div class="cm-alert cm-alert--success reveal reveal--2">
        <strong>OK</strong> Photo supprimée.
      </div>
    <?php endif; ?>

    <?php if ($img_err !== ''): ?>
      <div class="cm-alert cm-alert--danger reveal reveal--2">
        <strong>Erreur</strong>
        <?php
          $msg = match ($img_err) {
            'type'   => "Format invalide (jpg, jpeg, png, webp).",
            'move'   => "Impossible d'enregistrer le fichier.",
            'upload' => "Upload invalide.",
            default  => "Une erreur est survenue.",
          };
        ?>
        <div><?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?></div>
      </div>
    <?php endif; ?>

    <!-- CONTENU -->
    <div class="cm-auth-card cm-admin-card reveal reveal--2">
      <h2 class="cm-admin-card__title">Contenu</h2>
      <p class="cm-admin-card__subtitle">
        Modifie le titre, le sous-titre et la description.
      </p>

      <form method="post" action="<?= BASE_URL ?>/admin/projects/<?= $id ?>" novalidate>
        <?= csrf_field() ?>

        <div class="cm-field">
          <label class="cm-label" for="title">Titre *</label>
          <input class="cm-input" id="title" name="title" required
            value="<?= htmlspecialchars((string)($project['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
          <?php if (!empty($errors['title'])): ?>
            <small class="cm-error"><?= htmlspecialchars((string)$errors['title'], ENT_QUOTES, 'UTF-8') ?></small>
          <?php endif; ?>
        </div>

        <div class="cm-field">
          <label class="cm-label" for="subtitle">Sous-titre</label>
          <input class="cm-input" id="subtitle" name="subtitle"
            value="<?= htmlspecialchars((string)($project['subtitle'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
          <?php if (!empty($errors['subtitle'])): ?>
            <small class="cm-error"><?= htmlspecialchars((string)$errors['subtitle'], ENT_QUOTES, 'UTF-8') ?></small>
          <?php endif; ?>
        </div>

        <div class="cm-field">
          <label class="cm-label" for="description">Description</label>
          <textarea class="cm-textarea" id="description" name="description" rows="7"><?= htmlspecialchars((string)($project['description'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
          <?php if (!empty($errors['description'])): ?>
            <small class="cm-error"><?= htmlspecialchars((string)$errors['description'], ENT_QUOTES, 'UTF-8') ?></small>
          <?php endif; ?>
        </div>

        <div class="cm-auth-actions" style="margin-top:.25rem">
          <button class="cm-btn cm-btn--outline" type="submit">Enregistrer</button>
          <a class="cm-link-more" href="<?= BASE_URL ?>/admin/projects">
            Annuler <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
          </a>
        </div>
      </form>

      <div class="cm-auth-divider"></div>
    </div>

    <!-- PHOTOS -->
    <div class="cm-auth-card cm-admin-card reveal reveal--3" style="margin-top:1rem">
      <h2 class="cm-admin-card__title">Photos</h2>
      <p class="cm-admin-card__subtitle">
        Ajoute des images (jpg/png/webp), définis l’ordre, et supprime si besoin.
      </p>

      <form method="post" action="<?= BASE_URL ?>/admin/projects/<?= $id ?>/images" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="cm-field">
          <label class="cm-label" for="image">Image *</label>
          <input class="cm-input" id="image" name="image" type="file"
                 accept="image/jpeg,image/png,image/webp" required>
        </div>

        <div class="cm-field">
          <label class="cm-label" for="alt_text">Texte alternatif</label>
          <input class="cm-input" id="alt_text" name="alt_text" type="text" placeholder="Ex: Mustang — vue arrière">
        </div>

        <div class="cm-field">
          <label class="cm-label" for="sort_order_img">Ordre</label>
          <input class="cm-input" id="sort_order_img" name="sort_order" type="number" min="0" step="1" value="0">
        </div>

        <div class="cm-auth-actions" style="margin-top:.25rem">
          <button class="cm-btn cm-btn--outline" type="submit">Ajouter</button>
        </div>
      </form>

      <div class="cm-auth-divider"></div>

      <?php if (empty($images)): ?>
        <p class="cm-admin-empty">Aucune photo pour ce projet.</p>
      <?php else: ?>
        <div class="cm-admin-imggrid">
          <?php foreach ($images as $im): ?>
            <?php
              $imgId = (int)($im['id'] ?? 0);
              $src   = (string)($im['image_path'] ?? '');
              $alt   = (string)($im['alt_text'] ?? '');
              $ord   = (int)($im['sort_order'] ?? 0);
            ?>
            <div class="cm-admin-imgcard">
              <div class="cm-admin-imgcard__media">
                <img src="<?= BASE_URL . htmlspecialchars($src, ENT_QUOTES, 'UTF-8') ?>"
                     alt="<?= htmlspecialchars($alt, ENT_QUOTES, 'UTF-8') ?>">
              </div>

              <div class="cm-admin-imgcard__meta">
                <div class="muted">Ordre : <strong><?= $ord ?></strong></div>
                <?php if ($alt !== ''): ?>
                  <div class="muted" title="<?= htmlspecialchars($alt, ENT_QUOTES, 'UTF-8') ?>">
                    <?= htmlspecialchars(mb_strimwidth($alt, 0, 44, '…'), ENT_QUOTES, 'UTF-8') ?>
                  </div>
                <?php endif; ?>

                <form method="post" action="<?= BASE_URL ?>/admin/project-images/<?= $imgId ?>/delete" style="margin-top:.6rem">
                  <?= csrf_field() ?>
                  <button class="cm-btn cm-btn--outline cm-btn--sm" type="submit"
                          onclick="return confirm('Supprimer cette image ?');">
                    Supprimer
                  </button>
                </form>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

  </div>
</section>
