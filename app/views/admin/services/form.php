<?php
$service = is_array($service ?? null) ? $service : [];
$categories = is_array($categories ?? null) ? $categories : [];
$action = (string)($action ?? '');

$val = function(string $k, $default = '') use ($service) {
  return $service[$k] ?? $default;
};

$isActive = ((int)$val('is_active', 1) === 1);
?>

<form method="post" action="<?= htmlspecialchars($action, ENT_QUOTES, 'UTF-8') ?>" class="cm-admin-form">
  <?= csrf_field() ?>

  <div class="cm-field">
    <label class="cm-label" for="name">Nom</label>
    <input
      class="cm-input"
      id="name"
      name="name"
      type="text"
      required
      minlength="2"
      placeholder="Ex: Reprogrammation moteur"
      value="<?= htmlspecialchars((string)$val('name'), ENT_QUOTES, 'UTF-8') ?>"
    >
  </div>

  <div class="cm-field">
    <label class="cm-label" for="category_id">Catégorie</label>
    <select class="cm-select cm-select--pill" id="category_id" name="category_id">
      <option value="">— aucune —</option>
      <?php foreach ($categories as $c): ?>
        <?php
          $cid = (int)($c['id'] ?? 0);
          $selected = ((string)$val('category_id', '') !== '' && (int)$val('category_id') === $cid);
        ?>
        <option value="<?= $cid ?>" <?= $selected ? 'selected' : '' ?>>
          <?= htmlspecialchars((string)($c['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="cm-field">
    <label class="cm-label" for="price_from">Prix dès (€)</label>
    <input
      class="cm-input"
      id="price_from"
      name="price_from"
      type="number"
      min="0"
      step="1"
      placeholder="Ex: 299"
      value="<?= htmlspecialchars((string)$val('price_from'), ENT_QUOTES, 'UTF-8') ?>"
    >
  </div>

  <div class="cm-field">
    <label class="cm-label" for="sort_order">Ordre</label>
    <input
      class="cm-input"
      id="sort_order"
      name="sort_order"
      type="number"
      min="0"
      step="1"
      value="<?= htmlspecialchars((string)$val('sort_order', 0), ENT_QUOTES, 'UTF-8') ?>"
    >
  </div>

  <div class="cm-field">
    <label class="cm-label" for="description">Description</label>
    <textarea
      class="cm-textarea"
      id="description"
      name="description"
      rows="6"
      placeholder="Décris la prestation, les bénéfices, les conditions…"
    ><?= htmlspecialchars((string)$val('description'), ENT_QUOTES, 'UTF-8') ?></textarea>
  </div>

    <div class="cm-field">
    <div class="cm-admin-toggleRow">
      <div>
        <div class="cm-admin-toggleTitle">Actif</div>
        <div class="cm-admin-toggleHint muted">Visible sur le site</div>
      </div>

      <label class="cm-switch" aria-label="Activer le service">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" <?= $isActive ? 'checked' : '' ?>>
        <span class="cm-switch__track" aria-hidden="true"></span>
      </label>
    </div>
  </div>


  <div class="cm-auth-actions">
    <button class="cm-btn cm-btn--outline" type="submit">Enregistrer</button>

    <a class="cm-link-more" href="<?= BASE_URL ?>/admin/services">
      Retour liste <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
    </a>
  </div>
</form>
