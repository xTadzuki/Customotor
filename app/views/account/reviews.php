<?php
require_once APP_ROOT . '/app/helpers/csrf.php';

$title  = 'Avis';
$active = 'account';

$sent = (bool)($sent ?? (isset($_GET['sent']) && $_GET['sent'] === '1'));

$old    = is_array($old ?? null) ? $old : [];
$errors = is_array($errors ?? null) ? $errors : [];


$reviews = is_array($reviews ?? null) ? $reviews : [];
?>

<section class="cm-legal-hero" style="--hero-img: url('<?= BASE_URL ?>/assets/img/hero-car.jpg');">
  <div class="cm-legal-hero__overlay"></div>

  <div class="cm-container cm-legal-hero__inner">
    <p class="cm-hero__kicker reveal reveal--1">ESPACE CLIENT</p>

    <h1 class="cm-legal-hero__title cm-neon-racing reveal reveal--2">
      Laisser un avis
    </h1>

    <p class="cm-legal-hero__subtitle reveal reveal--3">
      Votre avis sera publié après validation.
    </p>

    <div class="cm-legal-hero__actions reveal reveal--3">
      <a class="cm-btn cm-btn--outline" href="<?= BASE_URL ?>/account">Retour compte</a>
      <a class="cm-btn cm-btn--outline" href="<?= BASE_URL ?>/logout">Déconnexion</a>
    </div>
  </div>
</section>

<section class="cm-account">
  <div class="cm-container">

    <?php if ($sent): ?>
      <div class="cm-alert cm-alert--success reveal reveal--1">
        <strong>Avis envoyé</strong>
        Merci ! Votre avis est en attente de validation.
      </div>
    <?php endif; ?>

    <div class="cm-account__grid">

      <!-- FORM -->
      <div class="cm-account__card reveal reveal--2">
        <h2 class="cm-account__h2">Votre expérience</h2>
        <p class="cm-account__p">Donnez une note et un commentaire. (Publication après validation admin.)</p>

        <form method="post" action="<?= BASE_URL ?>/account/reviews" novalidate>
          <?= csrf_field() ?>

          <div class="cm-field">
            <label class="cm-label" for="rating">Note (1 à 5) *</label>

            <?php $current = (string)($old['rating'] ?? ''); ?>
            <div class="cm-rating">
              <select class="cm-input cm-select" id="rating" name="rating" required>
                <option value="" <?= $current === '' ? 'selected' : '' ?> disabled>— choisir —</option>
                <?php for ($i = 5; $i >= 1; $i--): ?>
                  <option value="<?= $i ?>" <?= $current === (string)$i ? 'selected' : '' ?>><?= $i ?></option>
                <?php endfor; ?>
              </select>

              <div class="cm-rating__stars" aria-hidden="true" data-stars>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
              </div>
            </div>

            <?php if (!empty($errors['rating'])): ?>
              <small class="cm-error"><?= htmlspecialchars((string)$errors['rating']) ?></small>
            <?php else: ?>
              <small class="cm-auth-note" style="display:block; margin-top:.45rem;">
                5 = excellent, 1 = à améliorer.
              </small>
            <?php endif; ?>
          </div>

          <div class="cm-field">
            <label class="cm-label" for="comment">Commentaire *</label>
            <textarea
              class="cm-textarea"
              id="comment"
              name="comment"
              rows="5"
              required
            ><?= htmlspecialchars((string)($old['comment'] ?? '')) ?></textarea>

            <?php if (!empty($errors['comment'])): ?>
              <small class="cm-error"><?= htmlspecialchars((string)$errors['comment']) ?></small>
            <?php endif; ?>
          </div>

          <div class="cm-form__actions">
            <button class="cm-btn cm-btn--outline" type="submit">Envoyer mon avis</button>
          </div>

          <p class="cm-account__fineprint" style="margin-top:1rem;">
            Merci de rester factuel et respectueux (modération avant publication).
          </p>
        </form>
      </div>

      <!-- LIST (OPTIONNEL / MVP) -->
      <div class="cm-account__card reveal reveal--3">
        <div class="cm-table-head">
          <div>
            <h2 class="cm-account__h2" style="margin:0;">Vos avis</h2>
          </div>
        </div>

        <?php if (empty($reviews)): ?>
          <div class="cm-empty">
            <div class="cm-empty__icon" aria-hidden="true">
              <i class="fa-regular fa-message" aria-hidden="true"></i>
            </div>
            <div>
              <p class="cm-empty__title">Aucun avis enregistré</p>
              <p class="cm-empty__text">Envoyez un premier avis via le formulaire.</p>
            </div>
          </div>
        <?php else: ?>
          <div class="cm-table-wrap" role="region" aria-label="Table des avis" tabindex="0">
            <table class="cm-table">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Note</th>
                  <th>Commentaire</th>
                  <th class="cm-th-right">Statut</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($reviews as $r): ?>
                  <?php
                    $dt = (string)($r['created_at'] ?? '');
                    $ts = $dt ? strtotime($dt) : false;
                    $date = $ts ? date('d/m/Y', $ts) : ($dt ?: '—');

                    $rating = (int)($r['rating'] ?? 0);
                    $comment = trim((string)($r['comment'] ?? ''));
                    $status = strtolower(trim((string)($r['status'] ?? 'pending')));

                    // badges reuse
                    $badgeClass = $status === 'published'
                      ? 'cm-badge cm-badge--ok'
                      : 'cm-badge cm-badge--warn';
                    $badgeText = $status === 'published'
                      ? 'Publié'
                      : 'En attente';
                  ?>
                  <tr>
                    <td class="cm-td-muted"><?= htmlspecialchars($date) ?></td>
                    <td><?= htmlspecialchars((string)$rating) ?>/5</td>
                    <td class="cm-td-muted"><?= htmlspecialchars($comment !== '' ? $comment : '—') ?></td>
                    <td class="cm-td-right">
                      <span class="<?= htmlspecialchars($badgeClass) ?>"><?= htmlspecialchars($badgeText) ?></span>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>

      </div>

    </div>
  </div>
</section>
