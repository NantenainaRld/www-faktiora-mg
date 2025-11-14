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
    <button type="button" id="btn-add-user">add</button>
    <input type="date" id="from">
    <input type="date" id="to">
    <button type="button" id="btn-test">test</button>

    <!-- script - user dashboard  -->
    <script src="<?= SITE_URL ?>/js/users/user-dashboard.js"></script>
</body>

</html>