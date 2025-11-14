<!DOCTYPE html>
<html lang="en">

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
    <p>Index page</p>
    <button id="btn-test">test</button>

    <!-- script user  -->
    <script src="<?= SITE_URL; ?>/js/users/user.js"></script>
</body>

</html>