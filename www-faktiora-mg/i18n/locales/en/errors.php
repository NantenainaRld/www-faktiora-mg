<?php

return [
    'catch' => [
        'router' => DEBUG ? "Error in the router : :field" : "An error occurred in router.",
        'database' => DEBUG ? "Error in the database connection : :field" : "A database connection error occured.",
        'query' => DEBUG ? "Error in the query : :field" : "An error occured while processing the request.",
        'correction' => DEBUG ? "Error in the correction : :field" : "An error occurred during correction",
        //user - create default admin
        'user_createDefaultAdmin' => DEBUG ? "Error in user/createDefaultAdmin: :field" : "An error occurred while creating the default administrator account.",
        //auth - is loged in
        'auth_isLogedIn' => DEBUG ? "Error in auth/isLogedIn: :field" : "An error occurred while checking user's connectivity.",
        //auth - login
        'auth_loginUser' => DEBUG ? "Error in auth/loginUser: :field" : "Login connection error.",
        //user - create user
        'user_createUser' => DEBUG ? "Error in user/createUser: :field" : "An error occured while creating the account.",
        //user - is email user exist
        'user_isEmailUserExist' => DEBUG ? "Error in user/isEmailUserExist: :field" : "An error occurred during email address verification.",
        //user - filter user
        'user_filterUser' => DEBUG ? "Error in user/filterUser: :field" : 'An error occurred while listing the users.',
        //user - list caissier
        'user_listCaissier' => DEBUG ? "Error in user/listCaissier: :field" : "An error occurred while listing the cashiers.",
        //user find by id
        'user_findById' => DEBUG ? "Error in user/findById: :field" : "An error occurred while verifying the user ID.",
        //user update
        'user_update' => DEBUG ? "Error in user/update: :field" : "An error occurred while updating the user's information.",
        //user - is email user exist
        'user_isEmailUserExist' => DEBUG ? "Error in user/isEmailUserExist: :field" : "An error occurred while verifying the user's email address.",
        //user - delete account
        "user_deleteAccount" => DEBUG ? "Error in user/deleteAccount: :field" : "An error occurred while deleting the account.",
        //user - delete all
        'user_deleteAll' => DEBUG ? "Error in user/deleteAll: :field" : "An error occurred while deleting the users.",
        //user - deconnect all
        'user_deconnectAll' => DEBUG ? "Error in user/deconnectAll: :field" : "An error occurred while logging out the users.",
        //user - find by email
        'user_findByEmail' => DEBUG ? "Error in user/findByEmail: :field" : "An error occurred while verifying the user b email address.",
        //user - list all user
        'user_listAllUser' => DEBUG ? "Error in user/listAllUser: :field" : "An error occurred while retrieving the list of users.",
        //user - print all user
        'user_printAllUser' => DEBUG ? "Error in user/printAllUser: :field" : "An error occurred while generating the PDF for the list of users.",
        //user - restore all user
        'user_restoreAllUser' => DEBUG ? "Error in user/restoreAllUser: :field" : "An error occurred while restoring the users.",
        //caisse - create caisse
        'caisse_createCaisse' => DEBUG ? "Error in caisse/createCaisse: :field" : "An error occured while creating the cash register.",
        //caisse - is num caisse exist
        'caisse_isNumCaisseExist' => DEBUG ? "Error in caisse/isNumCaisseExist: :field" : "An error occurred while verifying the cash register number.",
        //caisse - filter caisse
        'caisse_filterCaisse' => DEBUG ? "Error in caisse/filterCaisse: :field" : "An error occured while listing the cash registers.",
        //caisse - find by id
        'caisse_findById' => DEBUG ? "Error in caisse/findById: :field" : "An error occurred while verifying the cash register number.",
        //caisse - filter ligne caisse
        'caisse_filterLigneCaisse' => DEBUG ? "Error in caisse/filterLigneCaisse: :field" : "An error occurred while listing the cash register lines.",
        //caisse - update caisse
        'caisse_updateCaisse' => DEBUG ? "Error in caisse/updateCaisse: :field" : "An error occurred while updating the cash register information.",
        //caisse - list free caisse
        'caisse_listFreeCaisse' => DEBUG ? "Error in caisse/listFreeCaisse: :field" : "An error occurred while listing the available cash registers.",
        //caisse - list all caisse
        'caisse_listAllCaisse' => DEBUG ? "Error in caisse/listAllCaisse: :field" : "An error occurred while listing all the cash registers.",
        //caisse - delete all caisse 
        'caisse_deleteAll' => DEBUG ? "Error in caisse/deleteAll: :field" : "An error occurred while deleting the cash registers.",
        //caisse - delete all ligne caisse
        'caisse_deleteAllLigneCaisse' => DEBUG ? "Error in caisse/deleteAllLigneCaisse: :field" : "An error occurred while deleting the cash register lines.",
        //caisse - add ligne caisse
        'caisse_addLigneCaisse' => DEBUG ? "Error in caisse/addLigneCaisse: :field" : "An error occurred while adding a cash register line.",
        //caisse - find by id ligne caisse
        'caisse_findByIdLc' => DEBUG ? "Error in caisse/findByIdLc: :field" : "An error occurred while verifying the cash register line.",
        //caisse - is date interval exist ?
        'caisse_isDateIntervalExist' => DEBUG ? "Error in caisse/isDateIntervalExist: :field" : "An error occurred while verifying the dates of the cash register line.",
        //caisse - create ligne caisse
        'caisse_createLigneCaisse' => DEBUG ? "Error in caisse/createLigneCaisse: :field" : "An error occurred while adding a cash register line.",
        //caisse - close ligne caisse
        'caisse_closeLigneCaisse' => DEBUG ? "Error in caisse/closeLigneCaisse: :field" : "An error occurred while closing the cash register line.",
        //caisse - occup caisse
        'caisse_occupCaisse' => DEBUG ? "Error in caisse/occupCaisse: :field" : "An error occurred while occupying the cash register.",
        //caisse - update etat caisse
        'caisse_updateEtatCaisse' => DEBUG ? "Error in caisse/updateEtatCaisse: :field" : "An error occurred while updating the status of the cash register.",
        //caisse - quit caisse
        'caisse_quitCaisse' => DEBUG ? "Error in caisse/quitCaisse: :field" : "An error occurred while releasing the cash register.",
        //caisse - free caisse
        'caisse_freeCaisse' => DEBUG ? "Error in caisse/freeCaisse: :field" : "An error occurred while releasing the cash registers.",
        //caisse - update ligne caisse
        'caisse_updateLigneCaisse' => DEBUG ? "Error in caisse/updateLigneCaisse: :field" : "An error occurred while updating the cash register line.",
        //caisse - find caisse
        'caisse_findCaisse' => DEBUG ? "Error in caisse/findCaisse: :field" : "An error occurred while verifying the user's cash register.",
        //caisse - update solde
        'caisse_updateSolde' => DEBUG ? "Error in caisse/updateSolde: :field" : "An error occurred while updating the cash register balance.",
        //caisse - cash report
        'caisse_cashReport' => DEBUG ? "Error in caisse/cashReport: :field" : "An error occurred while generating the PDF of the cash register report.",
        //caisse - restore all
        'caisse_restoreAll' => DEBUG ? "Error in caisse/restoreAll: :field" : "An error occurred while restoring the cash registers.",
        //entree - create autre entree
        'entree_createAutreEntree' => DEBUG ? "Error in entree/createAutreEntree: :field" : "An error occurred while creating an another entry.",
        //entree - is num_ae exist 
        'entree_isNumAeExist' => DEBUG ? "Error in entree/isNumAeExist: :field" : "An error occurred while verifying the other entry number.",
        //entree - filter autre entree
        'entree_filterAutreEntreeAdmin' => DEBUG ? "Error in entree/filterAutreEntree: :field" : "An error occurred while listing the other entries.",
        //entree - list all autre entree
        'entree_listAllAutreEntree' => DEBUG ? "Error in entree/listAllAutreEntree: :field" : "An error occurred while listing all the other entries.",
        //entree - update autre entree
        'entree_updateAutreEntree' => DEBUG ? "Error in entree/updateAutreEntree: :field" : "An error occurred while updating the other entry.",
        //entree - find by id
        'entree_findById' => DEBUG ? "Error in entree/findById: :field" : "An error occurred while verifying the other entry number.",
        //entree - delete all autre entree
        'entree_deleteAllAutreEntree' => DEBUG ? "Error in entree/deleteAllAutreEntree: :field" : "An error occurred while deleting the other entries.",
        //entree- list connection autre entree
        'entree_listConnectionAutreEntree' => DEBUG ? "Error in entree/listConnectionAutreEntree: :field" : "An error occurred while listing the connections for this other entry.",
        //entree - connection autree entree
        'entree_connectionAutreEntree' => DEBUG ? "Error in entree/connectionAutreEntree: :field" : "An error occurred while listing the connections for entries.",
        //entree - create facture
        'entree_createFacture' => DEBUG ? "Error in entree/createFacture: :field" : "An error occurred while creating the invoice.",
        //entree - create ligne facture
        'entree_createLigneFacture' => DEBUG ? "Error in entree/createLigneFacture: :field" : "An error occurred while creating the invoice line.",
        //entree - filter facture
        'entree_filterFacture' => DEBUG ? "Error in entree/filterFacture: :field" : "An error occurred while listing the invoices.",
        //entree - list all facture
        'entree_listAllFacture' => DEBUG ? "Error in entree/listAllFacture: :field" : "An error occurred while listing all the invoices.",
        //entree - ligne facture
        'entree_ligneFacture' => DEBUG ? "Error in entree/ligneFacture: :field" : "An error occurred while listing the invoice lines.",
        //entree - facture find by id
        'facture_findById' => DEBUG ? "Error in entree_facture/findById: :field" : "An error occurred while verifying the invoice number.",
        //entree - list connection facture
        'entree_listConnectionFacture' => DEBUG ? "Error in entree/listConnectionFacture: :field" : "An error occurred while listing the connections for this invoice.",
        //entree - update facture
        'entree_updateFacture' => DEBUG ? "Error in entree/updateFacture: :field" : "An error occurred while updating the invoice.",
        //entree - delete all facture
        'entree_deleteAllFacture' => DEBUG ? "Error in entree/deleteAllFacture: :field" : "An error occurred while deleting the invoices.",
        //entree - lf find by id
        'entree_lf_findById' => DEBUG ? "Error in entree_lf/findById: :field" : "An error occurred while verifying the invoice line ID.",
        //entree - restore all facture
        'entree_restoreAllFacture' => DEBUG ? "Error in entree/restoreAllFacture: :field" : "An error occurred while restoring the invoices.",
        //entree - restore all ae
        'entree_restoreAllAutreEntree' => DEBUG ? "Error in entree/restoreAllAutreEntree: :field" : "An error occurred while restoring the other entries.",
        //sortie - connection sortie
        'sortie_connectionSortie' => DEBUG ? "Error in sortie/connectionSortie: :field" : "An error occurred while listing the connections for the cash outflows.",
        //sortie - create sortie 
        'sortie_createSortie' => DEBUG ? "Error in sortie/createSortie: :field" : "An error occurred while creating the cash outflow.",
        //sortie - create ligne_ds
        'sortie_createLigneDs' => DEBUG ? "Error in sortie/createLigneDs: :field" : "An error occurred while creating the cash outflow line.",
        //sortie - filter demande sortie
        'sortie_filterDemandeSortie' => DEBUG ? "Error in sortie/filterDemandeSortie: :field" : "An error occurred while listing the cash outflows.",
        //sortie - list connection sortie
        'sortie_listConnectionSortie' => DEBUG ? "Error in sortie/listConnectionSortie: :field" : "An error occurred while listing the connections for this cash outflow.",
        //sortie - find by id
        'sortie_findById' => DEBUG ? "Error in sortie/findById: :field" : "An error occurred while verifiyng the cash outflow number.",
        //sortie - ligne ds
        'sortie_ligneDs' => DEBUG ? "Error in sortie/ligneDs: :field" : "An error occurred while listing the cash outflow lines.",
        //sortie - list all demande sortie
        'sortie_listAllDemandeSortie' => DEBUG ? "Error in sortie/listAllDemandeSortie: :field" : "An error occurred while listing all the cash outflows.",
        //sortie - update demande sortie
        'sortie_updateDemandeSortie' => DEBUG ? "Error in sortie/updateDemandeSortie: :field" : "An error occurred while updating the cash outflow.",
        //sortie - delete all demande sortie
        'sortie_deleteAllDemandeSortie' => DEBUG ? "Error in sortie/deleteAllDemandeSortie: :field" : "An error occurred while deleting the cash outflows.",
        //sortie - get montant_ds
        'sortie_getMontantDs' => DEBUG ? "Error in sortie/getMontantDs: :field" : "An error occurred while calculating the cash outflow amount.",
        //sortie - correction facture
        'sortie_correctionFacture' => DEBUG ? "Error in sortie/correctionFacture: :field" : "An error occurred while correcting the invoice.",
        //sortie - print demande sortie
        'sortie_printDemandeSortie' => DEBUG ? "Error in sortie/printDemandeSortie: :field" : "An error occurred while generating the PDF for the cash outflow.",
        //sortie - restore all demande sortie
        'sortie_restoreAllDemandeSortie' => DEBUG ? "Error in sortie/restoreAllDemandeSortie: :field" : "An error occurred while restoring the cash outflow.",
        //produit  - create produit
        'produit_createProduit' => DEBUG ? "Error in produit/createProduit: :field" : "An error occurred while adding the new product.",
        //produit - filter produit
        'produit_filterProduit' => DEBUG ? "Error in produit/filterProduit: :field" : "An error occurred while listing the products.",
        //produit - list all produit
        'produit_listAllProduit' => DEBUG ? "Error in produit/listAllProduit: :field" : "An error occurred while listing all the products.",
        //produit - update produit
        'produit_updateProduit' => DEBUG ? "Error in produit/updateProduit: :field" : "An error occurred while updating the product.",
        //produit - find by id
        'produit_findById' => DEBUG ? "Error in produit/findById: :field" : "An error occurred while verifying the product ID.",
        //produit - delete all produit
        'produit_deleteAllProduit' => DEBUG ? "Error in produit/deleteAllProduit: :field" : "An error occurred while deleting the products.",
        //produit - update nb stock
        'produit_updateNbStocke' => DEBUG ? "Error in produit/updateNbStock: :field" : "An error occurred while updating the stock qunatity.",
        //produit - restore all produit
        'produit_restoreAllProduit' => DEBUG ? "Error in produit/restoreAllProduit: :field" : "An error occurred while restoring the products.",
        //article - create article
        'article_createArticle' => DEBUG ? "Error in article/createArticle: :field" : "An error occurred while adding the new item.",
        //article - is libelle article exist
        'article_isLibelleArticleExist' => DEBUG ? "Error in article/isLibelleArticleExist: :field" : "An error occurred while verifying the item.",
        //article - filter article
        'article_filterArticle' => DEBUG ? "Error in article/filterArticle: :field" : "An error occurred while listing the items.",
        //article - list all article
        'article_listAllArticle' => DEBUG ? "Error in article/listAllArticle: :field" : "An error occurred while listing all the items.",
        //article - update article
        'article_updateArticle' => DEBUG ? "Error in article/updateArticle: :field" : "An error occurred while updating the item.",
        //article - find by id
        'article_findById' => DEBUG ? "Error in article/findById: :field" : "An error occurred while verifying the item ID.",
        //article - delete all article
        'article_deleteAllArticle' => DEBUG ? "Error in article/deleteAllArticle: :field" : "An error occurred while deleting the articles.",
        //article - restore all article
        'article_restoreAllArticle' => DEBUG ? "Error in article/restoreAllArticle: :field" : "An error occurred while restoring the articles.",
        //client - create client
        'client_createClient' => DEBUG ? "Error in client/createClient: :field" : "An error occurred while adding the customer.",
        //client - filter client
        'client_filterClient' => DEBUG ? "Error in client/filterClient: :field" : "An error occurred while listing the customers.",
        //client - list all client
        'client_listAllClient' => DEBUG ? "Error in client/listAllClient: :field" : "An error occurred while listing all the customers.",
        //client - update client 
        'client_updateClient' => DEBUG ? "Error in client/updateClient: :field" : "An error occurred while updating the customer information.",
        //client - find by id
        'client_findById' => DEBUG ? "Error in client/findById: :field" : "An error occurred while verifying the customer ID.",
        //client - delete all client
        'client_deleteAllClient' => DEBUG ? "Error in client/deleteAllClient: :field" : "An error occurred while deleting the customers.",
        //client - restore all client
        'client_restoreAllClient' => DEBUG ? "Error in client/restoreAllClient: :field" : "An error occurred while restoring the customers.",
    ],
    'not_found' => [
        'page' => "The requested page was not found : :field",
        'action' => "The requested action was not found : :field",
        'class' => DEBUG ? "Class autoloading error, class not found : :field" : "An error occurred.",
        'config' => "Config file error: undefined key <b>:field</b>.",
        'json' => "JSON error: key <b>:field</b> does not exist."
    ]
];
