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
        //user - is email user exist
        'user_isEmailUserExist' => DEBUG ? "Erreur dans user/isEmailUserExist : :field" : "Une erreur est survenue",
        //user - delete account
        "user_deleteAccount" => DEBUG ? "Erreur dans user/deleteAccount : :field" : "Une erreur est surenue lors de suppréssion du compte",
        //user - delete all
        'user_deleteAll' => DEBUG ? "Erreur dans user/deleteAll : :field" : "Une erreur est survenue lors de suppréssion des utilisateurs",
        //user - deconnect all
        'user_deconnectAll' => DEBUG ? "Erreur dans user/deconnectAll : :field" : "Une erreur est survenue lors de la déconnection des utilisateurs",
        //caisse - create caisse
        'caisse_createCaisse' => DEBUG ? "Erreur dans caisse/createCaisse : :field" : "Erreur lors de la création de caisse",
        //caisse - is num caisse exist
        'caisse_isNumCaisseExist' => DEBUG ? "Erreur dans caisse/isNumCaisseExist : :field" : "Une erreur est survenue lors de la vérification de numéro de caisse",
        //caisse - filter caisse
        'caisse_filterCaisse' => DEBUG ? "Erreur dans caisse/filterCaisse : :field" : "Une erreur est survenue lors de listage des caisses",
        //caisse - find by id
        'caisse_findById' => DEBUG ? "Erreur dans caisse/findById : :field" : "Une erreur est survenue lors de la vérification de numéro de caisse",
        //caisse - filter ligne caisse
        'caisse_filterLigneCaisse' => DEBUG ? "Erreur dans caisse/filterLigneCaisse : :field" : "Une erreur est survenue lors de listage des lignes de caisse",
        //caisse - update caisse
        'caisse_updateCaisse' => DEBUG ? "Erreur dans caisse/updateCaisse : :field" : "Une erreur est survenue lors de la modifications des informations du caisse",
        //caisse - list free caisse
        'caisse_listFreeCaisse' => DEBUG ? "Erreur dans caisse/listFreeCaisse : :field" : "Une erreur est survenue lors de listage des caisses libres",
        //caisse - list all caisse
        'caisse_listAllCaisse' => DEBUG ? "Erreur dans caisse/listAllCaisse : :field" : "Une erreur est survenue lors de listage de tous les caisses",
        //caisse - delete all
        'caisse_deleteAll' => DEBUG ? "Erreur dans caisse/deleteAll : :field" : "Une erreur est survenue lors de la suppréssion des caisses",
        //caisse - delete all ligne caisse
        'caisse_deleteAllLigneCaisse' => DEBUG ? "Erreur dans caisse/deleteAllLigneCaisse : :field" : "Une erreur est survenue lors de la suppréssion des lignes de caisse",
        //caisse - add ligne caisse
        'caisse_addLigneCaisse' => DEBUG ? "Erreur dans caisse/addLigneCaisse : :field" : "Une erreur est survenue lors de l'ajout de ligne caisse",
        //caisse - find by id ligne caisse
        'caisse_findByIdLc' => DEBUG ? "Erreur dans caisser/findByIdLc :field" : "Une erreur est survenue lors de la vérification de l'ID de numéro de caisse",
        //caisse - is date interval exist ?
        'caisse_isDateIntervalExist' => DEBUG ? "Erreur dans caisse/isDateIntervalExist : :field" : "Une erreur est survenue lors de la vérification des dates de ligne de caisse",
        //caisse - create ligne caisse
        'caisse_createLigneCaisse' => DEBUG ? "Erreur dans caissse/createLigneCaisse : :field" : "Une erreur est survenue lors de la création de ligne de caisse",
        //caisse - close ligne caisse
        'caisse_closeLigneCaisse' => DEBUG ? "Erreur dans caisse/closeLigneCaisse : :field" : "Une erreur est survenue lors de la clôture de ligne de caisse",
        //caisse - occup caisse
        'caisse_occupCaisse' => DEBUG ? "Erreur dans caisse/occupCaisse : :field" : "Une erreur est survenue lors de l'occupation de caisse",
        //caisse - update etat caisse
        'caisse_updateEtatCaisse' => DEBUG ? "Erreur dans caisse/updateEtatCaisse : :field" : "Une erreur est survenue lors de noueau mis à jour d'état de caisse",
        //caisse - quit caisse
        'caisse_quitCaisse' => DEBUG ? "Erreur dans caisse/quitCaisse : :field" : "Une erreur est survenue lors de la libération de caisse",
        //caisse - free caisse
        'caisse_freeCaisse' => DEBUG ? "Erreur dans caisse/freeCaisse : :field" : "Une erreur est survenue lors de la libération des caisses",
        //caisse - is id lc exist 
        'caisse_isIdLcExist' => DEBUG ? "Erreur dans caisse/isIdLcExist : :field" : "Une erreur est survenue",
        //caisse - update ligne caisse
        'caisse_updateLigneCaisse' => DEBUG ? "Erreur dans caisse/updateLigneCaisse : :field" : "Une erreur est survenue lors de la modification de ligne caisse",
        //caisse - find caisse
        'caisse_findCaisse' => DEBUG ? "Erreur dans caisse/findCaisse : :field" : "Une erreur est survenue lors de la vérification de caisse d'utilisateur",
        //caisse - update solde
        'caisse_updateSolde' => DEBUG ? "Erreur dans caisse/updateSolde : :field" : "Une erreur est survenue lors de mis à jour du solde de caisse",
        //entree - create autre entree
        'entree_createAutreEntree' => DEBUG ? "Erreur dans entree/createAutreEntree : :field" : "Une erreur est survenue lors d'ajout de nouveau autre entrée",
        //entree - is num_ae exist 
        'entree_isNumAeExist' => DEBUG ? "Erreur dans entree/isNumAeExist : :field" : "Une erreur est survenue lors de la vérification de numéro d'autre entrée",
        //entree - filter autre entree
        'entree_filterAutreEntreeAdmin' => DEBUG ? "Erreur dans entree/filterAutreEntree : :field" : "Une erreur est survenue lors de listage des autres entrées",
        //entree - list all autre entree
        'entree_listAllAutreEntree' => DEBUG ? "Erreur dans entree/listAllAutreEntree : :field" : "Une erreur est survenue lors de listage de tout les autres entrées",
        //entree - update autre entree
        'entree_updateAutreEntree' => DEBUG ? "Erreur dans entree/updateAutreEntree : :field" : "Une erreur est survenue lors de la modification d'autre entrée",
        //entree - find by id
        'entree_findById' => DEBUG ? "Erreur dans entree/findById : :field" : "Une erreur est survenue lors de la vérification de numéro d'autre entrée",
        //entree - delete all autre entree
        'entree_deleteAllAutreEntree' => DEBUG ? "Erreur dans entree/deleteAllAutreEntree : :field" : "Une erreur est survenue lors de la suppréssion des autres entrées",
        //entree- list connection autre entree
        'entree_listConnectionAutreEntree' => DEBUG ? "Erreur dans entree/listConnectionAutreEntree : :field" : "Une erreur est survenue lors de listage des connections pour cette autre entrée",
        //entree - connection autree entree
        'entree_connectionAutreEntree' => DEBUG ? "Erreur dans entree/connectionAutreEntree : :field" : "Une erreur est survenue lors de listage des connections pour les entrées",
        //sortie - connection sortie
        'sortie_connectionSortie' => DEBUG ? "Erreur dans sortie/connectionSortie : :field" : "Une erreur est survenue lors de listage des connections pour les sorties",
        //sortie - create sortie 
        'sortie_createSortie' => DEBUG ? "Erreur dans sortie/createSortie : :field" : "Une erreur est survenue lors de la création de sortie",
        //sortie - create ligne_ds
        'sortie_createLigneDs' => DEBUG ? "Erreur dans sortie/createLigneDs : :field" : "Une erreur est survenue lors de la création de ligne sortie",
        //sortie - filter demande sortie
        'sortie_filterDemandeSortie' => DEBUG ? "Erreur dans sortie/filterDemandeSortie : :field" : "Une erreur est survenue lors de listage des sorties",
        //sortie - list connection sortie
        'sortie_listConnectionSortie' => DEBUG ? "Erreur dans sortie/listConectionSortie : :field" : "Une erreur est survenue lors de listage des connections pour cette sortie",
        //sortie - find by id
        'sortie_findById' => DEBUG ? "Erreur dans sortie/findById : :field" : "Une erreur est survenue lors de la vérification de numéro de sortie",
        //sortie - ligne ds
        'sortie_ligneDs' => DEBUG ? "Erreur dabs sortie/ligneDs : :field" : "Une erreur est survenue de listage des lignes sorties",
        //sortie - list all demande sortie
        'sortie_listAllDemandeSortie' => DEBUG ? "Erreur dans sortie/listAllDemandeSortie : :field" : "Une erreur est survenue lors de listage de tous les sorties",
        //produit  - create produit
        'produit_createProduit' => DEBUG ? "Erreur dans produit/createProduit : :field" : "Une erreur est survenue lors d'ajout de nouveau produit",
        //produit - filter produit
        'produit_filterProduit' => DEBUG ? "Erreur dans produit/filterProduit : :field" : "Une erreur est survenue lors de listage des produits",
        //produit - list all produit
        'produit_listAllProduit' => DEBUG ? "Erreur dans produit/listAllProduit : :field" : "Une erreur est survenue lors de listage de tout les produits",
        //produit - update produit
        'produit_updateProduit' => DEBUG ? "Erreur dans produit/updateProduit : :field" : "Une erreur est survenue lors de la modification de produit",
        //produit - find by id
        'produit_findById' => DEBUG ? "Erreur dans produit/findById : :field" : "Une erreur est survenue lors de vérification d'ID de produit",
        //produit - delete all produit
        'produit_deleteAllProduit' => DEBUG ? "Erreur dans produit/deleteAllProduit : :field" : "Une erreur est survenue lors de la suppréssion des produits",
        //article - create article
        'article_createArticle' => DEBUG ? "Erreur dans article/createArticle : :field" : "Une erreur est survenue lors d'ajout de nouveau article",
        //article - is libelle article exist
        'article_isLibelleArticleExist' => DEBUG ? "Erreur dans article/isLibelleArticleExist : :field" : "Une erreur est survenue lors de la vérification d'article",
        //article - filter article
        'article_filterArticle' => DEBUG ? "Erreur dans article/filterArticle : :field" : "Une erreur est survenue lors de listage des articles",
        //article - list all article
        'article_listAllArticle' => DEBUG ? "Erreur dans article/listAllArticle : :field" : "Une erreur est survenue lors de listage de tous les articles",
        //article - update article
        'article_updateArticle' => DEBUG ? "Erreur dans article/updateArticle : :field" : "Une erreur est survenue lors de la modification d'article",
        //article - find by id
        'article_findById' => DEBUG ? "Erreur dans article/findById : :field" : "Une erreur est survenue lors de la vérification d'article",
        //article - delete all article
        'article_deleteAllArticle' => DEBUG ? "Erreur dans article/deleteAllArticle : :field" : "Une erreur est survenue lors de la suppréssion des articles",
    ],
    'not_found' => [
        'page' => "La page demandée n'est pas trouvée : :field",
        'action' => "L'action demandée n'est pas trouvée : :field",
        'class' => DEBUG ? "Erreur d'autochargement de classe, classe n'est pas trouvé : :field" : "Une erreur est survenue",
    ]
];
