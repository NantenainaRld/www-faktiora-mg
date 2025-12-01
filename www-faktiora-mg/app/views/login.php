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
    <h2>Connexion</h2>
    <form action="" method='POST'>
        <!-- login -->
        <div>
            <label
                for="login">Adresse email ou numéro du compte : </label>
            <input type="text" id="login">
        </div>
        <!-- mdp -->
        <div>
            <label for="mdp">Mot de passe : </label>
            <input type="password" id="mdp">
        </div>
        <button type="button" id="btn-login">Se connecter</button>
    </form>
    <!-- script js  -->
    <script src="<?= SITE_URL ?>/js/users/login.js"></script>
</body>

</html>