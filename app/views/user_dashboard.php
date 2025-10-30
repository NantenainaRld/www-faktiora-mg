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
    user <!-- helper js  -->
    <script src="<?= SITE_URL ?>/js/helper.js"></script>
</head>

<body>
    <?= "user dashboard"; ?>
    <!-- script js -->
    <script src="<?= SITE_URL ?>/js/user-dashboard.js"></script>
</body>

</html>