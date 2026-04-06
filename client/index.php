<?php require_once __DIR__ . '/api/config.php'; ?>
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

  <!-- JS: load berurutan (dom → utils → ui → file → dragdrop → format → convert) -->
  <script defer src="assets/js/dom.js"></script>
  <script defer src="assets/js/utils.js"></script>
  <script defer src="assets/js/ui.js"></script>
  <script defer src="assets/js/file.js"></script>
  <script defer src="assets/js/dragdrop.js"></script>
  <script defer src="assets/js/format.js"></script>
  <script defer src="assets/js/convert.js"></script>
</body>
</html>
