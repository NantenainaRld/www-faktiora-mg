<?php

return [
    'catch' => [
        'router' => DEBUG ? "Erreur : :field" : "Une erreur est survenue dans le routage.",
        'database' => DEBUG ? "Erreur de connection à la base de donées : :field" : "Erreur de connection à la base de donées.",
        'query' => DEBUG ? "Erreur de requête : :field" : "Erreur de connection au serveur.",
        'create_default_admin' => DEBUG ? "Erreur de création de compte admin : :field" : "Erreur de création de compte admin .",
        'create_user' => DEBUG ? "Erreur de création du compte : :field" : "Erreur de création du compte",
        'user_findById' => DEBUG ? "Erreur dans user/findyById : :field" : "Une erreur est survenue lors de  la vérification de l'ID d'utilisateur",
        'user_generate_id' => DEBUG ? "Erreur de génération de l'ID d'utilisateur : :field" : "Une erreur est servenue lors de la création du compte",
        'user_isEmailExist' => DEBUG ? "Erreur dans user/isEmailExist : :field" : "Une erreur est survenue lors de la vérification de l'adresse email",
        'user_repositorie_filter_user' => DEBUG ? "Erreur dans user_repositorie/filterUser : :field" : 'Une erreur est survenue lors de listage des utilisateurs',
        'auth_isLogedIn' => DEBUG ? "Erreur dans auth/isLogedIn : :field" : "Une erreur est survenue",
        'user_update' => DEBUG ? "Erreur dans user/update : :field" : "Une erreur est survenue lors de la modification des informations d'utilisateur",
        'user_isIdUserExist' => DEBUG ? "Erreur dans user/isIdUserExist : :field" : "Une erreur est survenue",
        'user_isEmailUserExist' => DEBUG ? "Erreur dans user/isEmailUserExist : :field" : "Une erreur est survenue",
        "user_delete_account" => DEBUG ? "Erreur de suppréssion du compte : :field" : "Erreur de suppréssion du compte",
        'user_deleteAll' => DEBUG ? "Erreur dans user/deleteAll : :field" : "Erreur de suppréssion des utilisateurs",
        'caisse_create_caisse' => DEBUG ? "Erreur dans caisse/createCaisse : :field" : "Erreur lors de la création du caisse",
        'caisse_isNumCaisseExist' => DEBUG ? "Erreur dans caisse/isNumCaisseExist : :field" : "Une erreur est survenue lors de la vérification de numéro de caisse",
        'caisse_repositorie_filter_caisse' => DEBUG ? "Erreur dans caisse_repositorie/filterCaisse : :field" : "Une erreur est survenue lors de listage des caisses",
        'filter_ligne_caisse' => DEBUG ? "Erreur dans caisse/filterLigneCaisse : :field" : "Une erreur est survenue lors de listage des lignes de caisse",
        'caisse_update_caisse' => DEBUG ? "Erreur dans caisse/updateCaisse : :field" : "Une erreur est survenue lors de la modifications des informations du caisse",
        'caisse_deleteAll' => DEBUG ? "Erreur dans caisse/deleteAll : :field" : "Une erreur est survenue lors de la suppréssion des caisses",
        'user_deconnectAll' => DEBUG ? "Erreur dans user/deconnectAll : :field" : "Une erreur est survenue lors de la déconnection des utilisateurs"
    ],
    'not_found' => [
        'page' => "La page demandée n'est pas trouvée : :field",
        'action' => "L'action demandée n'est pas trouvée : :field",
    ]
];
