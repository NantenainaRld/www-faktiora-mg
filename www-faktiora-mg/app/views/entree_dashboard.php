<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <!-- JS base URL -->
    <script>
        const SITE_URL = "<?= SITE_URL ?>";
    </script>
    <!-- helper js  -->
    <script src="<?= SITE_URL ?>/js/helpers/helper.js"></script>
</head>

<body>
    <?= "entree dashboard"; ?>
    <input type="datetime-local" id="date">
    <button id='btn-test'>Test</button>
    <!-- script js -->
    <script src="<?= SITE_URL ?>/js/entree-dashboard.js"></script>
</body>

</html>