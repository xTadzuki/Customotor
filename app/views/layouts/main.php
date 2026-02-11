<?php
// Headers de sécurité (à appeler avant tout output)
if (function_exists('security_headers')) {
  security_headers(false); 
}

$base = defined('BASE_URL') ? BASE_URL : '';
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title><?= htmlspecialchars((string)($title ?? 'Customotor')) ?></title>
  <meta name="description" content="<?= htmlspecialchars((string)($metaDescription ?? 'Customotor — performance & préparation sur mesure')) ?>">

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- CSS global -->
  <link rel="stylesheet" href="<?= $base ?>/assets/css/style.css">
</head>
<body>

  <?php require APP_ROOT . '/app/views/layouts/header.php'; ?>

  <!-- Main: -->
  <main id="main" role="main">
    <?php require $contentView; ?>
  </main>

  <?php require APP_ROOT . '/app/views/layouts/footer.php'; ?>

  <script src="<?= $base ?>/assets/js/app.js" defer></script>
</body>
</html>
