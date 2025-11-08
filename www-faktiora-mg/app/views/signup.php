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
    <h2>Création du compte</h2>
    <form action="" method='POST'>
        <!-- nom_utilisateur -->
        <div>
            <label
                for="nom-utilisateur">Nom : </label>
            <input type="text" id="nom-utilisateur" name="nom-utilisateur">
        </div>
        <!-- prenoms_utilisateur -->
        <div>
            <label for="prenoms-utilisateur">Prénom(s) : </label>
            <input type="text" id='prenoms-utilisateur'>
        </div>
        <!-- sexe_utilisateur-->
        <div>
            <label for="sexe-utilisateur">Sexe : </label>
            <select name="" id="sexe-utilisateur">
                <option value="" disabled selected>Séléctionner</option>
                <option value="masculin">Masculin</option>
                <option value="féminin">Féminin</option>
            </select>
        </div>

        <!-- email_utilisateur  -->
        <div>
            <label for="email-utilisateur">Email : </label>
            <input type="email" id="email-utilisateur">
        </div>
        <!-- roles - hidden  -->
        <div>
            <label for="role">Email : </label>
            <input type="hidden" id="role" value="caissier">
        </div>
        <!-- mdp  -->
        <div>
            <label for="mdp">Mot de passe : </label>
            <input type="password" id="mdp">
        </div>
        <!-- mdp confirm  -->
        <div>
            <label for="mdp-confirm">Confirmer le mot de passe : </label>
            <input type="password" name="" id="mdp-confirm">
        </div>
        <button type="button" id="btn-add-user">Créer</button>
    </form>
    <!-- script js  -->
    <script src="<?= SITE_URL ?>/js/users/create-user.js"></script>
</body>

</html>