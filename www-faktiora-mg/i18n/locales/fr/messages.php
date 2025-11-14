<?php

return [
    'invalids' => [
        'sexe' => "Sexe invalide : <b>:field</b>",
        'email' => "Adresse email invalide : <b>:field</b>",
        'email_length' => "L'adresse <b>email</b> ne doit pas dépasser de 150 caractères",
        'role' => "Rôle invalide : <b>:field</b>",
        'mdp' => "Le mot de passe doît être au moins 6 caractères",
        'mdp_confirm' => "Le mot de passe de confirmation doît être similaire au mot de passe",
        'num_caisse' => "Numéro de caisse invalide : <b>:field</b>",
        'date' => "Date invalide : <b>:field</b>",
        'user_id' => "L'<b>ID</b> utilisateur ne doit pas dépasser de 15 caractères",
        'nom' => "Le <b>nom</b> d'utilisateur ne doit pas dépasser de 100 caractères",
        'prenoms' => "Le <b>prénoms</b> d'utilisateur ne doit pas dépasser de 100 caractères",
    ],
    'empty' => [
        'nom' => "Le champ <b>nom</b> est réquis",
        'email' => "Le champ <b>email</b> est réquis",
        'mdp' => "Le champ <b>mot de passe</b> est réquis",
        'mdp_confirm' => "Veuiller confirmer le mot de passe",
        'from_to' => "Veuiller entrer une date de debut ou fin, ou les deux",
        'user_id' => "L'<b>ID</b> d'utilisateur est réquis"
    ],
    'success' => [
        'create_user' => "Compte crée avec succès",
        'update_user' => "Les informations de l'utilisateur <b>:field</b> ont été modifiées avec succès"
    ],
    'not_found' => [
        'user_id' => "L'utilisateur avec l'ID <b>:field</b> n'existe pas",
        'num_caisse' => "Numéro de caisse n'existe pas : <b>:field</b>"
    ],
    'duplicate' => [
        'user_id' => "L'ID d'utilisateur <b>:field</b> existe déjà",
        'user_email' => "L'adresse email <b>:field</b> est déjà utilisée avec un utilisateur actif. Veuiller modifier",
    ]
];
