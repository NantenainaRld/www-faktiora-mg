<?php

return [
    'catch' => [
        'router' => DEBUG ? "Erreur dans le routeur : :field" : "Une erreur s'est produite au niveau de routeur.",
        'database' => DEBUG ? "Erreur dans la connexion à la base de données : :field" : "Une erreur est survenue lors de la connexion à la base de données.",
        'query' => DEBUG ? "Erreur dans la requête : :field" : "Une erreur est survenue lors de la requête.",
        'correction' => DEBUG ? "Erreur dans la correction : :field" : "Une erreur est survenue lors de la correction.",
        //user - create default admin
        'user_createDefaultAdmin' => DEBUG ? "Erreur dans user/createDefaultAdmin : :field" : "Une erreur est survenue lors de la création du compte administrateur par défaut.",
        //auth - is loged in
        'auth_isLogedIn' => DEBUG ? "Erreur dans auth/isLogedIn : :field" : "Une erreur est survenue lors de la vérification de la connectivité de l'utilisateur.",
        //auth - login
        'auth_loginUser' => DEBUG ? "Erreur dans auth/loginUser : :field" : "Une erreur est survenue lors de la connexion.",
        //user - create user
        'user_createUser' => DEBUG ? "Erreur dans user/createUser : :field" : "Une erreur est survenue lors de la création du compte.",
        //user - is email user exist
        'user_isEmailUserExist' => DEBUG ? "Erreur dans user/isEmailUserExist : :field" : "Une erreur est survenue lors de la vérification de l'adresse email.",
        //user - filter user
        'user_filterUser' => DEBUG ? "Erreur dans user/filterUser : :field" : 'Une erreur est survenue lors du listage des utilisateurs.',
        //user - list caissier
        'user_listCaissier' => DEBUG ? "Erreur dans user/listCaissier : :field" : "Une erreur est survenue lors du listage des caissiers.",
        //user find by id
        'user_findById' => DEBUG ? "Erreur dans user/findyById : :field" : "Une erreur est survenue lors de  la vérification de l'ID de l'utilisateur.",
        //user update
        'user_update' => DEBUG ? "Erreur dans user/update : :field" : "Une erreur est survenue lors de la modification des informations de l'utilisateur.",
        //user - is email user exist
        'user_isEmailUserExist' => DEBUG ? "Erreur dans user/isEmailUserExist : :field" : "Une erreur est survenue lors de la vérification de l'adresse e-mail de l'utilisateur.",
        //user - delete account
        "user_deleteAccount" => DEBUG ? "Erreur dans user/deleteAccount : :field" : "Une erreur est surenue lors de suppréssion du compte.",
        //user - delete all
        'user_deleteAll' => DEBUG ? "Erreur dans user/deleteAll : :field" : "Une erreur est survenue lors de la suppression des utilisateurs.",
        //user - deconnect all
        'user_deconnectAll' => DEBUG ? "Erreur dans user/deconnectAll : :field" : "Une erreur est survenue lors de la déconnexion des utilisateurs.",
        //user - find by email
        'user_findByEmail' => DEBUG ? "Erreur dans user/findByEmail : :field" : "Une erreur est survenue lors de la vérification de l'utilisateur par adresse e-mail.",
        //user - list all user
        'user_listAllUser' => DEBUG ? "Erreur dans user/listAllUser : :field" : "Une erreur est survenue lors de la récupération de la liste des utilisateurs.",
        //user - print all user
        'user_printAllUser' => DEBUG ? "Erreur dans user/printAllUser : :field" : "Une erreur est survenue lors de la génération du PDF pour la liste des utilisateurs.",
        //user - restore all user
        'user_restoreAllUser' => DEBUG ? "Erreur dans user/restoreAllUser : :field" : "Une erreur est survenue lors de la récupération des utilisateurs.",
        //caisse - create caisse
        'caisse_createCaisse' => DEBUG ? "Erreur dans caisse/createCaisse : :field" : "Une erreur est survenue lors de la création de la caisse.",
        //caisse - is num caisse exist
        'caisse_isNumCaisseExist' => DEBUG ? "Erreur dans caisse/isNumCaisseExist : :field" : "Une erreur est survenue lors de la vérification du numéro de caisse.",
        //caisse - filter caisse
        'caisse_filterCaisse' => DEBUG ? "Erreur dans caisse/filterCaisse : :field" : "Une erreur est survenue lors du listage des caisses.",
        //caisse - find by id
        'caisse_findById' => DEBUG ? "Erreur dans caisse/findById : :field" : "Une erreur est survenue lors de la vérification du numéro de caisse.",
        //caisse - filter ligne caisse
        'caisse_filterLigneCaisse' => DEBUG ? "Erreur dans caisse/filterLigneCaisse : :field" : "Une erreur est survenue lors du listage des lignes de caisse.",
        //caisse - update caisse
        'caisse_updateCaisse' => DEBUG ? "Erreur dans caisse/updateCaisse : :field" : "Une erreur est survenue lors de la modifications des informations de la caisse.",
        //caisse - list free caisse
        'caisse_listFreeCaisse' => DEBUG ? "Erreur dans caisse/listFreeCaisse : :field" : "Une erreur est survenue lors du listage des caisses libres.",
        //caisse - list all caisse
        'caisse_listAllCaisse' => DEBUG ? "Erreur dans caisse/listAllCaisse : :field" : "Une erreur est survenue lors du listage de toutes les caisses.",
        //caisse - delete all caisse
        'caisse_deleteAll' => DEBUG ? "Erreur dans caisse/deleteAll : :field" : "Une erreur est survenue lors de la suppression des caisses.",
        //caisse - delete all ligne caisse
        'caisse_deleteAllLigneCaisse' => DEBUG ? "Erreur dans caisse/deleteAllLigneCaisse : :field" : "Une erreur est survenue lors de la suppression des lignes de caisse.",
        //caisse - add ligne caisse
        'caisse_addLigneCaisse' => DEBUG ? "Erreur dans caisse/addLigneCaisse : :field" : "Une erreur est survenue lors de l'ajout d'une ligne de caisse.",
        //caisse - find by id ligne caisse
        'caisse_findByIdLc' => DEBUG ? "Erreur dans caisser/findByIdLc :field" : "Une erreur est survenue lors de la vérification de la ligne de caisse.",
        //caisse - is date interval exist ?
        'caisse_isDateIntervalExist' => DEBUG ? "Erreur dans caisse/isDateIntervalExist : :field" : "Une erreur est survenue lors de la vérification des dates de la ligne de caisse.",
        //caisse - create ligne caisse
        'caisse_createLigneCaisse' => DEBUG ? "Erreur dans caissse/createLigneCaisse : :field" : "Une erreur est survenue lors de l'ajout d'une ligne de caisse.",
        //caisse - close ligne caisse
        'caisse_closeLigneCaisse' => DEBUG ? "Erreur dans caisse/closeLigneCaisse : :field" : "Une erreur est survenue lors de la clôture de la ligne de caisse.",
        //caisse - occup caisse
        'caisse_occupCaisse' => DEBUG ? "Erreur dans caisse/occupCaisse : :field" : "Une erreur est survenue lors de l'occupation de la caisse.",
        //caisse - update etat caisse
        'caisse_updateEtatCaisse' => DEBUG ? "Erreur dans caisse/updateEtatCaisse : :field" : "Une erreur est survenue lors de la mise à jour de l'état de la caisse.",
        //caisse - quit caisse
        'caisse_quitCaisse' => DEBUG ? "Erreur dans caisse/quitCaisse : :field" : "Une erreur est survenue lors de la libération de la caisse.",
        //caisse - free caisse
        'caisse_freeCaisse' => DEBUG ? "Erreur dans caisse/freeCaisse : :field" : "Une erreur est survenue lors de la libération des caisses.",
        //caisse - update ligne caisse
        'caisse_updateLigneCaisse' => DEBUG ? "Erreur dans caisse/updateLigneCaisse : :field" : "Une erreur est survenue lors de la modification de la ligne de caisse.",
        //caisse - find caisse
        'caisse_findCaisse' => DEBUG ? "Erreur dans caisse/findCaisse : :field" : "Une erreur est survenue lors de la vérification de la caisse de l'utilisateur.",
        //caisse - update solde
        'caisse_updateSolde' => DEBUG ? "Erreur dans caisse/updateSolde : :field" : "Une erreur est survenue lors de la mise à jour du solde de la caisse.",
        //caisse - cash report
        'caisse_cashReport' => DEBUG ? "Erreur dans caisse/cashReport : :field" : "Une erreur est survenue lors de la génération du PDF pour le rapport de caisse.",
        //caisse - restore all
        'caisse_restoreAll' => DEBUG ? "Erreur dans caisse/restoreAll : :field" : "Une erreur est survenue lors de la récupération des caisses.",
        //entree - create autre entree
        'entree_createAutreEntree' => DEBUG ? "Erreur dans entree/createAutreEntree : :field" : "Une erreur est survenue lors de la création d'une autre entrée.",
        //entree - is num_ae exist 
        'entree_isNumAeExist' => DEBUG ? "Erreur dans entree/isNumAeExist : :field" : "Une erreur est survenue lors de la vérification du numéro de l'autre entrée.",
        //entree - filter autre entree
        'entree_filterAutreEntreeAdmin' => DEBUG ? "Erreur dans entree/filterAutreEntree : :field" : "Une erreur est survenue lors du listage des autres entrées.",
        //entree - list all autre entree
        'entree_listAllAutreEntree' => DEBUG ? "Erreur dans entree/listAllAutreEntree : :field" : "Une erreur est survenue lors du listage de toutes les autres entrées.",
        //entree - update autre entree
        'entree_updateAutreEntree' => DEBUG ? "Erreur dans entree/updateAutreEntree : :field" : "Une erreur est survenue lors de la modification de l'autre entrée.",
        //entree - find by id
        'entree_findById' => DEBUG ? "Erreur dans entree/findById : :field" : "Une erreur est survenue lors de la vérification du numéro de l'autre entrée.",
        //entree - delete all autre entree
        'entree_deleteAllAutreEntree' => DEBUG ? "Erreur dans entree/deleteAllAutreEntree : :field" : "Une erreur est survenue lors de la suppression des autres entrées.",
        //entree - list connection autre entree
        'entree_listConnectionAutreEntree' => DEBUG ? "Erreur dans entree/listConnectionAutreEntree : :field" : "Une erreur est survenue lors du listage des connexions pour cette autre entrée.",
        //entree - connection autree entree
        'entree_connectionAutreEntree' => DEBUG ? "Erreur dans entree/connectionAutreEntree : :field" : "Une erreur est survenue lors du listage des connexions pour les entrées.",
        //entree - create facture
        'entree_createFacture' => DEBUG ? "Erreur dans entree/createFacture : :field" : "Une erreur est survenue lors de la création de la facture.",
        //entree - create ligne facture
        'entree_createLigneFacture' => DEBUG ? "Erreur dans entree/createLigneFacture : :field" : "Une erreur est survenue lors de la création de la ligne de facture.",
        //entree - filter facture
        'entree_filterFacture' => DEBUG ? "Erreur dans entree/filterFacture : :field" : "Une erreur est survenue lors du listage des factures.",
        //entree - list all facture
        'entree_listAllFacture' => DEBUG ? "Erreur dans entree/listAllFacture : :field" : "Une erreur est survenue lors du listage de toutes les factures.",
        //entree - ligne facture
        'entree_ligneFacture' => DEBUG ? "Erreur dans entree/ligneFacture : :field" : "Une erreur est survenue lors du listage des lignes de facture.",
        //entree - facture find by id
        'facture_findById' => DEBUG ? "Erreur dans entree_facture/findById : :field" : "Une erreur est survenue lors de la vérification du numéro de facture.",
        //entree - list connection facture
        'entree_listConnectionFacture' => DEBUG ? "Erreur dans entree/listConnectionFacture : :field" : "Une erreur est survenue lors du listage des connexions pour cette facture.",
        //entree - update facture
        'entree_updateFacture' => DEBUG ? "Erreur dans entree/updateFacture : :field" : "Une erreur est survenue lors de la modification de la facture.",
        //entree - delete all facture
        'entree_deleteAllFacture' => DEBUG ? "Erreur dans entree/deleteAllFacture : :field" : "Une erreur est survenue lors de la suppression des factures.",
        //entree - lf find by id
        'entree_lf_findById' => DEBUG ? "Erreur dans entree_lf/findById : :field" : "Une erreur est survenue lors de la vérification de l'ID de la ligne de facture.",
        //entree - restore all facture
        'entree_restoreAllFacture' => DEBUG ? "Erreur dans entree/restoreAllFacture : :field" : "Une erreur est survenue lors de la récupération des factures.",
        //entree - restore all ae
        'entree_restoreAllAutreEntree' => DEBUG ? "Erreur dans entree/restoreAllAutreEntree : :field" : "Une erreur est survenue lors de la récupération des autres entrées.",
        //sortie - connection sortie
        'sortie_connectionSortie' => DEBUG ? "Erreur dans sortie/connectionSortie : :field" : "Une erreur est survenue lors du listage des connections pour les sorties.",
        //sortie - create sortie 
        'sortie_createSortie' => DEBUG ? "Erreur dans sortie/createSortie : :field" : "Une erreur est survenue lors de la création de la sortie.",
        //sortie - create ligne_ds
        'sortie_createLigneDs' => DEBUG ? "Erreur dans sortie/createLigneDs : :field" : "Une erreur est survenue lors de la création de la ligne de sortie.",
        //sortie - filter demande sortie
        'sortie_filterDemandeSortie' => DEBUG ? "Erreur dans sortie/filterDemandeSortie : :field" : "Une erreur est survenue lors du listage des sorties.",
        //sortie - list connection sortie
        'sortie_listConnectionSortie' => DEBUG ? "Erreur dans sortie/listConectionSortie : :field" : "Une erreur est survenue lors du listage des connexions pour cette sortie.",
        //sortie - find by id
        'sortie_findById' => DEBUG ? "Erreur dans sortie/findById : :field" : "Une erreur est survenue lors de la vérification du numéro de sortie.",
        //sortie - ligne ds
        'sortie_ligneDs' => DEBUG ? "Erreur dabs sortie/ligneDs : :field" : "Une erreur est survenue lors du listage des lignes de sortie.",
        //sortie - list all demande sortie
        'sortie_listAllDemandeSortie' => DEBUG ? "Erreur dans sortie/listAllDemandeSortie : :field" : "Une erreur est survenue lors du listage de toutes les sorties.",
        //sortie - update demande sortie
        'sortie_updateDemandeSortie' => DEBUG ? "Erreur dans sortie/updateDemandeSortie : :field" : "Une erreur est survenue lors de la modification de la sortie.",
        //sortie - delete all demande sortie
        'sortie_deleteAllDemandeSortie' => DEBUG ? "Erreur dans sortie/deleteAllDemandeSortie : :field" : "Une erreur est survenue lors de la suppression des sorties.",
        //sortie - get montant_ds
        'sortie_getMontantDs' => DEBUG ? "Erreur dans sortie/getMontantAe : :field" : "Une erreur est survenue lors du calcule du montant de sortie.",
        //sortie - correction facture
        'sortie_correctionFacture' => DEBUG ? "Erreur dans sortie/correctionFacture : :field" : "Une erreur est survenue lors de la correction de la facture.",
        //sortie - print demande sortie
        'sortie_printDemandeSortie' => DEBUG ? "Erreur dans sortie/printDemandeSortie : :field" : "Une erreur est survenue lors de la génération du PDF pour la sortie.",
        //sortie - restore all demande sortie
        'sortie_restoreAllDemandeSortie' => DEBUG ? "Erreur dans sortie/restoreAllDemandeSortie : :field" : "Une erreur est survenue lors de la récupération des sorties.",
        //produit  - create produit
        'produit_createProduit' => DEBUG ? "Erreur dans produit/createProduit : :field" : "Une erreur est survenue lors de l'ajout du nouveau produit.",
        //produit - filter produit
        'produit_filterProduit' => DEBUG ? "Erreur dans produit/filterProduit : :field" : "Une erreur est survenue lors du listage des produits.",
        //produit - list all produit
        'produit_listAllProduit' => DEBUG ? "Erreur dans produit/listAllProduit : :field" : "Une erreur est survenue lors du listage de tous les produits.",
        //produit - update produit
        'produit_updateProduit' => DEBUG ? "Erreur dans produit/updateProduit : :field" : "Une erreur est survenue lors de la modification du produit.",
        //produit - find by id
        'produit_findById' => DEBUG ? "Erreur dans produit/findById : :field" : "Une erreur est survenue lors de vérification de l'ID du produit.",
        //produit - delete all produit
        'produit_deleteAllProduit' => DEBUG ? "Erreur dans produit/deleteAllProduit : :field" : "Une erreur est survenue lors de la suppression des produits.",
        //produit - update nb stock
        'produit_updateNbStocke' => DEBUG ? "Erreur dans produit/updateNbStock  : :field" : "Une erreur est survenue lors de la mise à jour du nombre de stock.",
        //produit - restore all produit
        'produit_restoreAllProduit' => DEBUG ? "Erreur dans produit/restoreAllProduit : :field" : "Une erreur est survenue lors de la récupération des produits.",
        //article - create article
        'article_createArticle' => DEBUG ? "Erreur dans article/createArticle : :field" : "Une erreur est survenue lors de l'ajout du nouvel article.",
        //article - is libelle article exist
        'article_isLibelleArticleExist' => DEBUG ? "Erreur dans article/isLibelleArticleExist : :field" : "Une erreur est survenue lors de la vérification de l'article.",
        //article - filter article
        'article_filterArticle' => DEBUG ? "Erreur dans article/filterArticle : :field" : "Une erreur est survenue lors du listage des articles.",
        //article - list all article
        'article_listAllArticle' => DEBUG ? "Erreur dans article/listAllArticle : :field" : "Une erreur est survenue lors du listage de tous les articles.",
        //article - update article
        'article_updateArticle' => DEBUG ? "Erreur dans article/updateArticle : :field" : "Une erreur est survenue lors de la modification de l'article.",
        //article - find by id
        'article_findById' => DEBUG ? "Erreur dans article/findById : :field" : "Une erreur est survenue lors de la vérification de l'ID de l'article.",
        //article - delete all article
        'article_deleteAllArticle' => DEBUG ? "Erreur dans article/deleteAllArticle : :field" : "Une erreur est survenue lors de la suppression des articles.",
        //article - restore all article
        'article_restoreAllArticle' => DEBUG ? "Erreur dans article/restoreAllArticle : :field" : "Une erreur est survenue lors de la récupération des articles.",
        //client - create client
        'client_createClient' => DEBUG ? "Erreur dans client/createClient : :field" : "Une erreur est survenue lors de l'ajout du client.",
        //client - filter client
        'client_filterClient' => DEBUG ? "Erreur dans client/filterClient : :field" : "Une erreur est survenue lors du listage des clients.",
        //client - list all client
        'client_listAllClient' => DEBUG ? "Erreur dans client/listAllClient : :field" : "Une erreur est survenue lors du listage de tous les clients.",
        //client - update client 
        'client_updateClient' => DEBUG ? "Erreur dans client/updateClient : :field" : "Une erreur est survenue lors de la modification des informations du client.",
        //client - find by id
        'client_findById' => DEBUG ? "Erreur dans client/findById : :field" : "Une erreur est survenue lors de la vérification de l'ID du client.",
        //client - delete all client
        'client_deleteAllClient' => DEBUG ? "Erreur dans client/deleteAllClient : :field" : "Une erreur est survenue lors de la suppression des clients.",
        //client - restore all client
        'client_restoreAllClient' => DEBUG ? "Erreur dans client/restoreAllClient : :field" : "Une erreur est survenue lors de la récupération des clients.",
    ],
    'not_found' => [
        'page' => "La page demandée n'a pas été trouvée : :field",
        'action' => "L'action demandée n'est pas trouvée : :field",
        'class' => DEBUG ? "Erreur d'autochargement de la classe, classe n'a pas été trouvée : :field" : "Une erreur est survenue.",
        'config' => "Erreur de fichier de configuration : clé <b>:field</b> n'existe pas.",
        'json' => "Erreur de JSON : clé <b>:field</b> n'existe pas."
    ]
];
