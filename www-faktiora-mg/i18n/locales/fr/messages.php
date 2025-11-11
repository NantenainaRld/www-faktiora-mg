<?php

return [
    'invalids' => [
        'sexe' => "Sexe invalide : <b>:field</b>",
        'email' => "Adresse email invalide : <b>:field</b>",
        'role' => "Rôle invalide : <b>:field</b>",
        'mdp' => "Le mot de passe doît être au moins 6 caractères",
        'mdp_confirm' => "Le mot de passe de confirmation doît être similaire au mot de passe",
        'email_exist' => "Cette adresse email existe déjà : :field",
        'num_caisse' => "Numéro de caisse invalide : <b>:field</b>",
        'date' => "Date invalide : <b>:field</b>",
    ],
    'empty' => [
        'nom' => "Le champ <b>nom</b> est réquis",
        'email' => "Le champ <b>email</b> est réquis",
        'mdp' => "Le champ <b>mot de passe</b> est réquis",
        'mdp_confirm' => "Veuiller confirmer le mot de passe",
        'from_to' => "Veuiller entrer une date de debut ou fin, ou les deux"
    ],
    'success' => [
        'create_user' => "Compte crée avec succès"
    ],
    'not_found' => [
        'num_caisse' => "Numéro de caisse n'existe pas : <b>:field</b>"
    ],
];
