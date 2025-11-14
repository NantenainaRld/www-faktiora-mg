<?php

return [
    'catch' => [
        'router' => DEBUG ? "Erreur : :field" : "Une erreur est survenue dans le routage.",
        'database' => DEBUG ? "Erreur de connection à la base de donées : :field" : "Erreur de connection à la base de donées.",
        'query' => DEBUG ? "Erreur de requête : :field" : "Erreur de connection au serveur.",
        'create_default_admin' => DEBUG ? "Erreur de création de compte admin : :field" : "Erreur de création de compte admin .",
        'create_user' => DEBUG ? "Erreur de création du compte : :field" : "Erreur de création du compte",
        'user_findById' => DEBUG ? "Erreur dans user/findyById : :field" : "Une erreur est survenue lors de  la vérification de l'ID d'utilisateur",
        'user_generate_id' => DEBUG ? "Erreur de génération de ID utilisateur : :field" : "Une erreur est servenue",
        'user_isEmailExist' => DEBUG ? "Erreur dans utilisateur/isEmailExist : :field" : "Une erreur est survenue dans la vérification de l'adresse email",
        'user_repositorie_filter_user' => DEBUG ? "Erreur dans user_repositorie/filterUser : :field" : 'Une erreur est survenue lors de listage des utilisateurs',
        'caisse_findById' => DEBUG ? "Erreur dans caisse/findById : :field" : "Une erreur est survenue lors de la vérification de numéro de caisse",
        'auth_isLogedIn' => DEBUG ? "Erreur dans auth/isLogedIn : :field" : "Une erreur est survenue",
        'user_update' => DEBUG ? "Erreur dans user/update : :field" : "Une erreur est survenue lors de la modification des informations d'utilisateur",
        'user_isIdUserExist' => DEBUG ? "Erreur dans user/isIdUserExist : :field" : "Une erreur est survenue",
        'user_isEmailUserExist' => DEBUG ? "Erreur dans user/isEmailUserExist : :field" : "Une erreur est survenue"
    ],
    'not_found' => [
        'page' => "La page demandée n'est pas trouvée : :field",
        'action' => "L'action demandée n'est pas trouvée : :field",
    ]
];
