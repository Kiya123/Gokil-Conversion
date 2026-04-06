<?php require_once __DIR__ . '/config.php'; ?>
<!DOCTYPE html>
<html lang="id">

<?php require_once __DIR__ . '/components/head.php'; ?>

<body>

  <!-- Background blobs -->
  <div class="bg-blob blob-1"></div>
  <div class="bg-blob blob-2"></div>
  <div class="bg-blob blob-3"></div>

  <div class="app-wrapper">

    <?php require_once __DIR__ . '/components/header.php'; ?>

    <main class="main">

      <?php require_once __DIR__ . '/components/hero.php'; ?>

      <?php require_once __DIR__ . '/components/converter_card.php'; ?>

      <?php require_once __DIR__ . '/components/result_card.php'; ?>

      <?php require_once __DIR__ . '/components/error_card.php'; ?>

      <?php require_once __DIR__ . '/components/arch_info.php'; ?>

    </main>
  </div>

  <?php require_once __DIR__ . '/components/loading_overlay.php'; ?>

  <script src="assets/app.js"></script>
</body>
</html>
