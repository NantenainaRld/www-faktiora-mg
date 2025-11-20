<?php

return [
    'catch' => [
        'router' => DEBUG ? "Erreur : :field" : "Une erreur est survenue dans le routage.",
        'database' => DEBUG ? "Erreur de connection à la base de donées : :field" : "Erreur de connection à la base de donées.",
        'query' => DEBUG ? "Erreur de requête : :field" : "Erreur de connection au serveur.",
        //user - create default admin
        'user_createDefaultAdmin' => DEBUG ? "Erreur dans user/createDefaultAdmin : :field" : "Une erreur est survenue lors de la création du compte admin par défaut",
        //auth - is loged in
        'auth_isLogedIn' => DEBUG ? "Erreur dans auth/isLogedIn : :field" : "Une erreur est survenue",
        //user - create user
        'user_createUser' => DEBUG ? "Erreur dans user/createUser : :field" : "Une erreur est survenue lors de la création du compte",
        //user - is email user exist
        'user_isEmailUserExist' => DEBUG ? "Erreur dans user/isEmailUserExist : :field" : "Une erreur est survenue lors de la vérification de l'adresse email",
        //user - filter user
        'user_filterUser' => DEBUG ? "Erreur dans user/filterUser : :field" : 'Une erreur est survenue lors de listage des utilisateurs',
        //user - list caissier
        'user_listCaissier' => DEBUG ? "Erreur dans user/listCaissier : :field" : "Une erreur est survenue lors de listage des caissiers",
        //user find by id
        'user_findById' => DEBUG ? "Erreur dans user/findyById : :field" : "Une erreur est survenue lors de  la vérification de l'ID d'utilisateur",
        //user update
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
        'user_deconnectAll' => DEBUG ? "Erreur dans user/deconnectAll : :field" : "Une erreur est survenue lors de la déconnection des utilisateurs",
        'caisse_occupCaisse' => DEBUG ? "Erruer dans caisse/occupCaisse : :field" : "Une erreur est survenue lors de l'occupation du caisse",
        'ligne_caisse_createLigneCaisse' => DEBUG ? "Erreur dans lingneCaisse/createLigneCaisse : :field" : "Une erreur est survenue lors de la création de ligne de caisse",
        'caisse_quitCaisse' => DEBUG ? "Erreur dans caisse/quitCaisse : :field" : "Une erreur est survenue lors de libération de la caisse",
        'caisse_freeCaisse' => DEBUG ? "Erreur dans caisse/freeCaisse : :field" : "Une erreur est survenue lors de la libération des caisses",
        'entree_createAutreEntree' => DEBUG ? "Erreur dans entree/createAutreEntree : :field" : "Une erreur est survenue lors d'ajout de nouveau autre entrée",
        'ligne_caisse_findCaisse' => DEBUG ? "Erreur dans ligne_caisse/findCaisse : :field" : "Une erreur est survenue lors de la vérification de caisse d'utilisateur"
    ],
    'not_found' => [
        'page' => "La page demandée n'est pas trouvée : :field",
        'action' => "L'action demandée n'est pas trouvée : :field",
        'class' => DEBUG ? "Erreur d'autochargement de classe, classe n'est pas trouvé : :field" : "Une erreur est survenue"
    ]
];
