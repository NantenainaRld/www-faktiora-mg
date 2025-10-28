<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$title?></title>
</head>
<body>
    <h2>Création du compte</h2>
    <form action="" method='POST'>
         <!-- nom_utilisateur -->
        <div>
             <label for="nom-utilisateur">Nom : </label>
             <input type="text" id="nom-utilisateur" name="nom-utilisateur">
        </div>
        <!-- prenoms_utilisateur -->
         <div>
            <label for="prenoms-utilisateur">Prénom(s) : </label>
            <input type="text">
         </div>
         <!-- sexe_utilisateur-->
        <div>
            <label for="sexe-utilisateur">Sexe : </label>
            <select name="" id="">
                <option value="" disabled selected>Séléctionner</option>
                <option value="masculin">Masculin</option>
                <option value="féminin">Féminin</option>
            </select>
        </div>
        <!-- role  -->
         <div>
            <label for="role">Rôle : </label>
            <input type="radio" value="admin" id="admin" name="sexe-utilisateur">
            <label for="admin">Administrateur</label>
            <input type="radio" value="caissier" id="caissier" name="sexe-utilisateur">
            <label for="caissier">Caissier</label>
         </div>
         <!-- email_utilisateur  -->
          <div>
            <label for="email-utilisateur">Email : </label>
            <input type="email">
          </div>
          <!-- mdp  -->
           <div>
            <label for="mdp">Mot de passe : </label>
            <input type="password">
           </div>
           <!-- mdp confirm  -->
            <div>
                <label for="mdp-confirm">Confirmer le mot de passe : </label>
                <input type="password" name="" id="">
            </div>
            <button type="button">Créer</button>
    </form>

    <!-- script js  -->
     <script src="../../public"></script>
</body>
</html>