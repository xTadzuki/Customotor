<?php
require_once APP_ROOT . '/app/helpers/csrf.php';

$title  = 'Mes véhicules';
$active = 'account';

$created  = (bool)($created ?? (isset($_GET['created']) && $_GET['created'] === '1'));
$old      = is_array($old ?? null) ? $old : [];
$errors   = is_array($errors ?? null) ? $errors : [];
$vehicles = is_array($vehicles ?? null) ? $vehicles : [];
?>

<section class="cm-legal-hero" style="--hero-img: url('<?= BASE_URL ?>/assets/img/hero-car.jpg');">
  <div class="cm-legal-hero__overlay"></div>

  <div class="cm-container cm-legal-hero__inner">
    <p class="cm-hero__kicker reveal reveal--1">ESPACE CLIENT</p>

    <h1 class="cm-legal-hero__title cm-neon-racing reveal reveal--2">
      Mes véhicules
    </h1>

    <p class="cm-legal-hero__subtitle reveal reveal--3">
      Ajoutez vos véhicules pour faciliter les demandes et rendez-vous.
    </p>

    <div class="cm-legal-hero__actions reveal reveal--3">
      <a class="cm-btn cm-btn--outline" href="<?= BASE_URL ?>/account">
        Retour compte
      </a>
      <a class="cm-link-more" href="<?= BASE_URL ?>/logout">
        Déconnexion <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
      </a>
    </div>
  </div>
</section>

<section class="cm-contact">
  <div class="cm-container">

    <?php if ($created): ?>
      <div class="cm-alert cm-alert--success reveal reveal--2">
        <strong>Véhicule ajouté</strong>
        <span class="cm-contact__fineprint" style="margin:0;">Vous pouvez maintenant l’utiliser dans vos demandes.</span>
      </div>
    <?php endif; ?>

    <div class="cm-contact__grid">

      <!-- FORM -->
      <div class="cm-contact__card reveal reveal--2">
        <h2 class="cm-contact__h2">Ajouter un véhicule</h2>
        <p class="cm-contact__p">Renseignez les informations principales. L’immatriculation est optionnelle.</p>

        <form method="post" action="<?= BASE_URL ?>/account/vehicles" novalidate>
          <?= csrf_field() ?>

          <div class="cm-form__row">
            <div class="cm-field">
              <label class="cm-label" for="brand">Marque *</label>
              <input
                class="cm-input"
                id="brand"
                name="brand"
                required
                autocomplete="organization"
                value="<?= htmlspecialchars((string)($old['brand'] ?? '')) ?>"
              >
              <?php if (!empty($errors['brand'])): ?>
                <small class="cm-error"><?= htmlspecialchars((string)$errors['brand']) ?></small>
              <?php endif; ?>
            </div>

            <div class="cm-field">
              <label class="cm-label" for="model">Modèle *</label>
              <input
                class="cm-input"
                id="model"
                name="model"
                required
                autocomplete="off"
                value="<?= htmlspecialchars((string)($old['model'] ?? '')) ?>"
              >
              <?php if (!empty($errors['model'])): ?>
                <small class="cm-error"><?= htmlspecialchars((string)$errors['model']) ?></small>
              <?php endif; ?>
            </div>
          </div>

          <div class="cm-form__row">
            <div class="cm-field">
              <label class="cm-label" for="year">Année</label>
              <input
                class="cm-input"
                id="year"
                name="year"
                type="number"
                inputmode="numeric"
                min="1950"
                max="<?= (int)date('Y') + 1 ?>"
                value="<?= htmlspecialchars((string)($old['year'] ?? '')) ?>"
              >
              <?php if (!empty($errors['year'])): ?>
                <small class="cm-error"><?= htmlspecialchars((string)$errors['year']) ?></small>
              <?php endif; ?>
            </div>

            <div class="cm-field">
              <label class="cm-label" for="plate">Immatriculation (optionnel)</label>
              <input
                class="cm-input"
                id="plate"
                name="plate"
                autocomplete="off"
                placeholder="AB-123-CD"
                value="<?= htmlspecialchars((string)($old['plate'] ?? '')) ?>"
              >
              <?php if (!empty($errors['plate'])): ?>
                <small class="cm-error"><?= htmlspecialchars((string)$errors['plate']) ?></small>
              <?php endif; ?>
              <small class="cm-auth-note" style="display:block; margin-top:.45rem;">
                Format conseillé : AB-123-CD
              </small>
            </div>
          </div>

          <div class="cm-form__actions">
            <button class="cm-btn cm-btn--outline" type="submit">
              Ajouter le véhicule
            </button>
          </div>

          <p class="cm-contact__fineprint">
            Astuce : ajoutez vos véhicules une fois, puis sélectionnez-les pour vos demandes et rendez-vous.
          </p>
        </form>
      </div>

      <!-- LIST / STATS -->
      <div class="cm-contact__card reveal reveal--3">
        <h2 class="cm-contact__h2">Mes véhicules</h2>
        <p class="cm-contact__p">
          <?= empty($vehicles) ? 'Aucun véhicule enregistré pour le moment.' : 'Retrouvez ici votre liste.' ?>
        </p>

        <?php if (empty($vehicles)): ?>
          <ul class="cm-contact__list">
            <li>
              <span class="cm-contact__label">Statut</span>
              <span class="cm-contact__value">—</span>
            </li>
            <li>
              <span class="cm-contact__label">Conseil</span>
              <span class="cm-contact__value">Ajoutez un véhicule via le formulaire</span>
            </li>
          </ul>
        <?php else: ?>
          <ul class="cm-contact__list" style="margin-bottom:1.25rem;">
            <li>
              <span class="cm-contact__label">Total</span>
              <span class="cm-contact__value"><?= (int)count($vehicles) ?> véhicule(s)</span>
            </li>
            <li>
              <span class="cm-contact__label">Dernier ajout</span>
              <span class="cm-contact__value">
                <?= htmlspecialchars((string)($vehicles[0]['brand'] ?? '')) ?>
                <?= htmlspecialchars((string)($vehicles[0]['model'] ?? '')) ?>
              </span>
            </li>
          </ul>

          <div style="overflow:auto; border-radius:14px; border:1px solid rgba(255,255,255,.10);">
            <table class="table" style="min-width:760px; margin:0;">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Marque</th>
                  <th>Modèle</th>
                  <th>Année</th>
                  <th>Immatriculation</th>
                  <th>Créé le</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($vehicles as $v): ?>
                  <tr>
                    <td>#<?= (int)($v['id'] ?? 0) ?></td>
                    <td><?= htmlspecialchars((string)($v['brand'] ?? '')) ?></td>
                    <td><?= htmlspecialchars((string)($v['model'] ?? '')) ?></td>
                    <td class="muted"><?= htmlspecialchars((string)($v['year'] ?? '—')) ?></td>
                    <td class="muted"><?= htmlspecialchars((string)($v['plate'] ?? '—')) ?></td>
                    <td class="muted"><?= htmlspecialchars((string)($v['created_at'] ?? '')) ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <p class="cm-contact__fineprint" style="margin-top:1rem;">
            (Prochaine étape) Ajouter “modifier / supprimer” quand tu voudras.
          </p>
        <?php endif; ?>
      </div>

    </div>
  </div>
</section>
