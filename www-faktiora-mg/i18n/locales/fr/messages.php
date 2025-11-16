<?php

return [
    'invalids' => [
        'sexe' => "Sexe invalide : <b>:field</b>",
        'email' => "Adresse email invalide : <b>:field</b>",
        'email_length' => "L'adresse <b>email</b> ne doit pas dépasser de 150 caractères",
        'role' => "Rôle invalide : <b>:field</b>",
        'mdp' => "Le mot de passe doît être au moins 6 caractères",
        'mdp_confirm' => "Le mot de passe de confirmation doît être similaire au mot de passe",
        'date' => "Date invalide : <b>:field</b>",
        'user_id' => "L'<b>ID</b> utilisateur ne doit pas dépasser de 15 caractères",
        'nom' => "Le <b>nom</b> d'utilisateur ne doit pas dépasser de 100 caractères",
        'prenoms' => "Le <b>prénoms</b> d'utilisateur ne doit pas dépasser de 100 caractères",
        'id_users_empty' => "Veuiller séléctionner au moins un utilisateur",
        'num_caisse' => "Le numéro du caisse doît être des nombres entiers supérieurs ou égaux à 0",
        'solde' => "Le solde doît être des valeurs décimaux supérieures ou égaux à 0",
        'seuil' => "Le seuil doît être des valeurs décimaux supérieures ou égaux à 0",
        'solde_seuil' => "Le solde ne doît pas en dessous de seuil",
        'caisse_not_selected' => "Veuiller séléctionner une caisse"
    ],
    'empty' => [
        'nom' => "Le champ <b>nom</b> est réquis",
        'email' => "Le champ <b>email</b> est réquis",
        'mdp' => "Le champ <b>mot de passe</b> est réquis",
        'mdp_confirm' => "Veuiller confirmer le mot de passe",
        'from_to' => "Veuiller entrer une date de debut ou fin, ou les deux",
        'user_id' => "L'<b>ID</b> d'utilisateur est réquis",
        'solde' => "Le champ <b>solde</b> est réquis",
        'seuil' => "Le champ <b>seuil</b> est réquis",
    ],
    'success' => [
        'create_user' => "Compte crée avec succès",
        'update_user' => "Les informations de l'utilisateur <b>:field</b> ont été modifiées avec succès",
        'user0_delete_all' => "Aucun utilisateur n'est supprimé",
        'user1_delete_all' => "Un utilisateur a été supprimé avec succès",
        'users_delete_all' => "<b>:field</b> utilisateurs ont été supprimés avec succès",
        'create_caisse' => "Caisse numéro <b>:field</b> crée avec succès"
    ],
    'not_found' => [
        'user_id' => "L'utilisateur avec l'ID <b>:field</b> n'existe pas",
        'num_caisse' => "Numéro de caisse n'existe pas : <b>:field</b>"
    ],
    'duplicate' => [
        'user_id' => "L'ID d'utilisateur <b>:field</b> existe déjà",
        'user_email' => "L'adresse email <b>:field</b> est déjà utilisée avec un utilisateur actif. Veuiller modifier",
        'num_caisse' => "Le numéro de caisse <b>:field</b> existe déjà"
    ]
];
