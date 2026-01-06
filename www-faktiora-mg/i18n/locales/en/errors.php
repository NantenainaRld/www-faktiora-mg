<?php

return [
    'catch' => [
        'router' => DEBUG ? "Error: :field" : "An error occurred in routing.",
        'database' => DEBUG ? "Database connection error: :field" : "Database connection error.",
        'query' => DEBUG ? "Query error: :field" : "Server connection error.",
        'correction' => DEBUG ? "Correction error: :field" : "An error occurred during correction",
        //user - create default admin
        'user_createDefaultAdmin' => DEBUG ? "Error in user/createDefaultAdmin: :field" : "An error occurred during default admin account creation",
        //auth - is loged in
        'auth_isLogedIn' => DEBUG ? "Error in auth/isLogedIn: :field" : "An error occurred",
        //auth - login
        'auth_loginUser' => DEBUG ? "Error in auth/loginUser: :field" : "An error occurred during login",
        //user - create user
        'user_createUser' => DEBUG ? "Error in user/createUser: :field" : "An error occurred during account creation",
        //user - is email user exist
        'user_isEmailUserExist' => DEBUG ? "Error in user/isEmailUserExist: :field" : "An error occurred during email address verification",
        //user - filter user
        'user_filterUser' => DEBUG ? "Error in user/filterUser: :field" : 'An error occurred during user listing',
        //user - list caissier
        'user_listCaissier' => DEBUG ? "Error in user/listCaissier: :field" : "An error occurred during cashier listing",
        //user find by id
        'user_findById' => DEBUG ? "Error in user/findById: :field" : "An error occurred during user ID verification",
        //user update
        'user_update' => DEBUG ? "Error in user/update: :field" : "An error occurred during user information modification",
        //user - is email user exist
        'user_isEmailUserExist' => DEBUG ? "Error in user/isEmailUserExist: :field" : "An error occurred",
        //user - delete account
        "user_deleteAccount" => DEBUG ? "Error in user/deleteAccount: :field" : "An error occurred during account deletion",
        //user - delete all
        'user_deleteAll' => DEBUG ? "Error in user/deleteAll: :field" : "An error occurred during user deletion",
        //user - deconnect all
        'user_deconnectAll' => DEBUG ? "Error in user/deconnectAll: :field" : "An error occurred during user disconnection",
        //user - find by email
        'user_findByEmail' => DEBUG ? "Error in user/findByEmail: :field" : "An error occurred during user email address verification",
        //user - list all user
        'user_listAllUser' => DEBUG ? "Error in user/listAllUser: :field" : "An error occurred during user list retrieval",
        //user - print all user
        'user_printAllUser' => DEBUG ? "Error in user/printAllUser: :field" : "An error occurred during PDF generation for user list",
        //user - restore all user
        'user_restoreAllUser' => DEBUG ? "Error in user/restoreAllUser: :field" : "An error occurred during user restoration",
        //caisse - create caisse
        'caisse_createCaisse' => DEBUG ? "Error in caisse/createCaisse: :field" : "Error during cash register creation",
        //caisse - is num caisse exist
        'caisse_isNumCaisseExist' => DEBUG ? "Error in caisse/isNumCaisseExist: :field" : "An error occurred during cash register number verification",
        //caisse - filter caisse
        'caisse_filterCaisse' => DEBUG ? "Error in caisse/filterCaisse: :field" : "An error occurred during cash register listing",
        //caisse - find by id
        'caisse_findById' => DEBUG ? "Error in caisse/findById: :field" : "An error occurred during cash register number verification",
        //caisse - filter ligne caisse
        'caisse_filterLigneCaisse' => DEBUG ? "Error in caisse/filterLigneCaisse: :field" : "An error occurred during cash register line listing",
        //caisse - update caisse
        'caisse_updateCaisse' => DEBUG ? "Error in caisse/updateCaisse: :field" : "An error occurred during cash register information modification",
        //caisse - list free caisse
        'caisse_listFreeCaisse' => DEBUG ? "Error in caisse/listFreeCaisse: :field" : "An error occurred during free cash register listing",
        //caisse - list all caisse
        'caisse_listAllCaisse' => DEBUG ? "Error in caisse/listAllCaisse: :field" : "An error occurred during all cash register listing",
        //caisse - delete all
        'caisse_deleteAll' => DEBUG ? "Error in caisse/deleteAll: :field" : "An error occurred during cash register deletion",
        //caisse - delete all ligne caisse
        'caisse_deleteAllLigneCaisse' => DEBUG ? "Error in caisse/deleteAllLigneCaisse: :field" : "An error occurred during cash register line deletion",
        //caisse - add ligne caisse
        'caisse_addLigneCaisse' => DEBUG ? "Error in caisse/addLigneCaisse: :field" : "An error occurred during cash register line addition",
        //caisse - find by id ligne caisse
        'caisse_findByIdLc' => DEBUG ? "Error in caisse/findByIdLc: :field" : "An error occurred during cash register ID verification",
        //caisse - is date interval exist ?
        'caisse_isDateIntervalExist' => DEBUG ? "Error in caisse/isDateIntervalExist: :field" : "An error occurred during cash register line date verification",
        //caisse - create ligne caisse
        'caisse_createLigneCaisse' => DEBUG ? "Error in caisse/createLigneCaisse: :field" : "An error occurred during cash register line creation",
        //caisse - close ligne caisse
        'caisse_closeLigneCaisse' => DEBUG ? "Error in caisse/closeLigneCaisse: :field" : "An error occurred during cash register line closure",
        //caisse - occup caisse
        'caisse_occupCaisse' => DEBUG ? "Error in caisse/occupCaisse: :field" : "An error occurred during cash register occupation",
        //caisse - update etat caisse
        'caisse_updateEtatCaisse' => DEBUG ? "Error in caisse/updateEtatCaisse: :field" : "An error occurred during cash register status update",
        //caisse - quit caisse
        'caisse_quitCaisse' => DEBUG ? "Error in caisse/quitCaisse: :field" : "An error occurred during cash register release",
        //caisse - free caisse
        'caisse_freeCaisse' => DEBUG ? "Error in caisse/freeCaisse: :field" : "An error occurred during cash register release",
        //caisse - is id lc exist 
        'caisse_isIdLcExist' => DEBUG ? "Error in caisse/isIdLcExist: :field" : "An error occurred",
        //caisse - update ligne caisse
        'caisse_updateLigneCaisse' => DEBUG ? "Error in caisse/updateLigneCaisse: :field" : "An error occurred during cash register line modification",
        //caisse - find caisse
        'caisse_findCaisse' => DEBUG ? "Error in caisse/findCaisse: :field" : "An error occurred during user cash register verification",
        //caisse - update solde
        'caisse_updateSolde' => DEBUG ? "Error in caisse/updateSolde: :field" : "An error occurred during cash register balance update",
        //caisse - cash report
        'caisse_cashReport' => DEBUG ? "Error in caisse/cashReport: :field" : "An error occurred during cash register report PDF generation",
        //caisse - restore all
        'caisse_restoreAll' => DEBUG ? "Error in caisse/restoreAll: :field" : "An error occurred during cash register restoration",
        //entree - create autre entree
        'entree_createAutreEntree' => DEBUG ? "Error in entree/createAutreEntree: :field" : "An error occurred during new other income addition",
        //entree - is num_ae exist 
        'entree_isNumAeExist' => DEBUG ? "Error in entree/isNumAeExist: :field" : "An error occurred during other income number verification",
        //entree - filter autre entree
        'entree_filterAutreEntreeAdmin' => DEBUG ? "Error in entree/filterAutreEntree: :field" : "An error occurred during other income listing",
        //entree - list all autre entree
        'entree_listAllAutreEntree' => DEBUG ? "Error in entree/listAllAutreEntree: :field" : "An error occurred during all other income listing",
        //entree - update autre entree
        'entree_updateAutreEntree' => DEBUG ? "Error in entree/updateAutreEntree: :field" : "An error occurred during other income modification",
        //entree - find by id
        'entree_findById' => DEBUG ? "Error in entree/findById: :field" : "An error occurred during other income number verification",
        //entree - delete all autre entree
        'entree_deleteAllAutreEntree' => DEBUG ? "Error in entree/deleteAllAutreEntree: :field" : "An error occurred during other income deletion",
        //entree- list connection autre entree
        'entree_listConnectionAutreEntree' => DEBUG ? "Error in entree/listConnectionAutreEntree: :field" : "An error occurred during connection listing for this other income",
        //entree - connection autree entree
        'entree_connectionAutreEntree' => DEBUG ? "Error in entree/connectionAutreEntree: :field" : "An error occurred during connection listing for income",
        //entree - create facture
        'entree_createFacture' => DEBUG ? "Error in entree/createFacture: :field" : "An error occurred during invoice creation",
        //entree - create ligne facture
        'entree_createLigneFacture' => DEBUG ? "Error in entree/createLigneFacture: :field" : "An error occurred during invoice line creation",
        //entree - filter facture
        'entree_filterFacture' => DEBUG ? "Error in entree/filterFacture: :field" : "An error occurred during invoice listing",
        //entree - list all facture
        'entree_listAllFacture' => DEBUG ? "Error in entree/listAllFacture: :field" : "An error occurred during all invoice listing",
        //entree - ligne facture
        'entree_ligneFacture' => DEBUG ? "Error in entree/ligneFacture: :field" : "An error occurred during invoice line listing",
        //entree - facture find by id
        'facture_findById' => DEBUG ? "Error in entree_facture/findById: :field" : "An error occurred during invoice number verification",
        //entree - list connection facture
        'entree_listConnectionFacture' => DEBUG ? "Error in entree/listConnectionFacture: :field" : "An error occurred during connection listing for this invoice",
        //entree - update facture
        'entree_updateFacture' => DEBUG ? "Error in entree/updateFacture: :field" : "An error occurred during invoice modification",
        //entree - delete all facture
        'entree_deleteAllFacture' => DEBUG ? "Error in entree/deleteAllFacture: :field" : "An error occurred during invoice deletion",
        //entree - lf find by id
        'entree_lf_findById' => DEBUG ? "Error in entree_lf/findById: :field" : "An error occurred during invoice line ID verification",
        //entree - restore all facture
        'entree_restoreAllFacture' => DEBUG ? "Error in entree/restoreAllFacture: :field" : "An error occurred during invoice restoration",
        //entree - restore all ae
        'entree_restoreAllAutreEntree' => DEBUG ? "Error in entree/restoreAllAutreEntree: :field" : "An error occurred during other income restoration",
        //sortie - connection sortie
        'sortie_connectionSortie' => DEBUG ? "Error in sortie/connectionSortie: :field" : "An error occurred during connection listing for expenses",
        //sortie - create sortie 
        'sortie_createSortie' => DEBUG ? "Error in sortie/createSortie: :field" : "An error occurred during expense creation",
        //sortie - create ligne_ds
        'sortie_createLigneDs' => DEBUG ? "Error in sortie/createLigneDs: :field" : "An error occurred during expense line creation",
        //sortie - filter demande sortie
        'sortie_filterDemandeSortie' => DEBUG ? "Error in sortie/filterDemandeSortie: :field" : "An error occurred during expense listing",
        //sortie - list connection sortie
        'sortie_listConnectionSortie' => DEBUG ? "Error in sortie/listConnectionSortie: :field" : "An error occurred during connection listing for this expense",
        //sortie - find by id
        'sortie_findById' => DEBUG ? "Error in sortie/findById: :field" : "An error occurred during expense number verification",
        //sortie - ligne ds
        'sortie_ligneDs' => DEBUG ? "Error in sortie/ligneDs: :field" : "An error occurred during expense line listing",
        //sortie - list all demande sortie
        'sortie_listAllDemandeSortie' => DEBUG ? "Error in sortie/listAllDemandeSortie: :field" : "An error occurred during all expense listing",
        //sortie - update demande sortie
        'sortie_updateDemandeSortie' => DEBUG ? "Error in sortie/updateDemandeSortie: :field" : "An error occurred during expense modification",
        //sortie - delete all demande sortie
        'sortie_deleteAllDemandeSortie' => DEBUG ? "Error in sortie/deleteAllDemandeSortie: :field" : "An error occurred during expense deletion",
        //sortie - get montant_ds
        'sortie_getMontantDs' => DEBUG ? "Error in sortie/getMontantDs: :field" : "An error occurred during expense amount calculation",
        //sortie - correction facture
        'sortie_correctionFacture' => DEBUG ? "Error in sortie/correctionFacture: :field" : "An error occurred during invoice correction",
        //sortie - print demande sortie
        'sortie_printDemandeSortie' => DEBUG ? "Error in sortie/printDemandeSortie: :field" : "An error occurred during expense request PDF generation",
        //sortie - restore all demande sortie
        'sortie_restoreAllDemandeSortie' => DEBUG ? "Error in sortie/restoreAllDemandeSortie: :field" : "An error occurred during expense restoration",
        //produit  - create produit
        'produit_createProduit' => DEBUG ? "Error in produit/createProduit: :field" : "An error occurred during new product addition",
        //produit - filter produit
        'produit_filterProduit' => DEBUG ? "Error in produit/filterProduit: :field" : "An error occurred during product listing",
        //produit - list all produit
        'produit_listAllProduit' => DEBUG ? "Error in produit/listAllProduit: :field" : "An error occurred during all product listing",
        //produit - update produit
        'produit_updateProduit' => DEBUG ? "Error in produit/updateProduit: :field" : "An error occurred during product modification",
        //produit - find by id
        'produit_findById' => DEBUG ? "Error in produit/findById: :field" : "An error occurred during product ID verification",
        //produit - delete all produit
        'produit_deleteAllProduit' => DEBUG ? "Error in produit/deleteAllProduit: :field" : "An error occurred during product deletion",
        //produit - update nb stock
        'produit_updateNbStocke' => DEBUG ? "Error in produit/updateNbStock: :field" : "An error occurred during stock quantity update",
        //produit - restore all produit
        'produit_restoreAllProduit' => DEBUG ? "Error in produit/restoreAllProduit: :field" : "An error occurred during product restoration",
        //article - create article
        'article_createArticle' => DEBUG ? "Error in article/createArticle: :field" : "An error occurred during new item addition",
        //article - is libelle article exist
        'article_isLibelleArticleExist' => DEBUG ? "Error in article/isLibelleArticleExist: :field" : "An error occurred during item verification",
        //article - filter article
        'article_filterArticle' => DEBUG ? "Error in article/filterArticle: :field" : "An error occurred during item listing",
        //article - list all article
        'article_listAllArticle' => DEBUG ? "Error in article/listAllArticle: :field" : "An error occurred during all item listing",
        //article - update article
        'article_updateArticle' => DEBUG ? "Error in article/updateArticle: :field" : "An error occurred during item modification",
        //article - find by id
        'article_findById' => DEBUG ? "Error in article/findById: :field" : "An error occurred during item verification",
        //article - delete all article
        'article_deleteAllArticle' => DEBUG ? "Error in article/deleteAllArticle: :field" : "An error occurred during item deletion",
        //article - restore all article
        'article_restoreAllArticle' => DEBUG ? "Error in article/restoreAllArticle: :field" : "An error occurred during item restoration",
        //client - create client
        'client_createClient' => DEBUG ? "Error in client/createClient: :field" : "An error occurred during client addition",
        //client - filter client
        'client_filterClient' => DEBUG ? "Error in client/filterClient: :field" : "An error occurred during client listing",
        //client - list all client
        'client_listAllClient' => DEBUG ? "Error in client/listAllClient: :field" : "An error occurred during all client listing",
        //client - update client 
        'client_updateClient' => DEBUG ? "Error in client/updateClient: :field" : "An error occurred during client information modification",
        //client - find by id
        'client_findById' => DEBUG ? "Error in client/findById: :field" : "An error occurred during client ID verification",
        //client - delete all client
        'client_deleteAllClient' => DEBUG ? "Error in client/deleteAllClient: :field" : "An error occurred during client deletion",
        //client - restore all client
        'client_restoreAllClient' => DEBUG ? "Error in client/restoreAllClient: :field" : "An error occurred during client restoration",
    ],
    'not_found' => [
        'page' => "The requested page was not found: :field",
        'action' => "The requested action was not found: :field",
        'class' => DEBUG ? "Class autoloading error, class not found: :field" : "An error occurred",
        'config' => "Config file error: undefined key <b>:field</b>",
        'json' => "JSON error: key <b>:field</b> does not exist"
    ]
];