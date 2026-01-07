<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('forms.titles.error'); ?></title>
    <link rel="stylesheet" href="<?= SITE_URL ?>/bootstrap-5.3.3/css/bootstrap.min.css">
</head>

<body class="vh-100 wv-100">

    <div class="cotainer h-100 w-100">
        <div class="row h-100 w-100">
            <div class="col-12 d-flex flex-columns justify-content-center align-items-center h-100 w-100">
                <?= $_GET['messages'] ?? __('forms.buttons.return'); ?>
            </div>
        </div>
    </div>
</body>

</html>