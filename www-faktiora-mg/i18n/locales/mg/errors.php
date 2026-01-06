<?php

return [
    'catch' => [
        'router' => DEBUG ? "Fahadisoana : :field" : "Nisy fahadisoana nitranga tamin'ny fitantanana ny lalana.",
        'database' => DEBUG ? "Fahadisoana amin'ny fandraisana fifandraisana amin'ny tahirin-kevitra : :field" : "Fahadisoana amin'ny fandraisana fifandraisana amin'ny tahirin-kevitra.",
        'query' => DEBUG ? "Fahadisoana amin'ny fangatahana : :field" : "Fahadisoana amin'ny fandraisana fifandraisana amin'ny mpizara.",
        'correction' => DEBUG ? "Fahadisoana fanitsiana : :field" : "Nisy fahadisoana nitranga tamin'ny fanitsiana",
        //user - create default admin
        'user_createDefaultAdmin' => DEBUG ? "Fahadisoana ao amin'ny user/createDefaultAdmin : :field" : "Nisy fahadisoana nitranga tamin'ny famoronana kaonty admin tsotra",
        //auth - is loged in
        'auth_isLogedIn' => DEBUG ? "Fahadisoana ao amin'ny auth/isLogedIn : :field" : "Nisy fahadisoana nitranga",
        //auth - login
        'auth_loginUser' => DEBUG ? "Fahadisoana ao amin'ny auth/loginUser : :field" : "Nisy fahadisoana nitranga tamin'ny fidirana",
        //user - create user
        'user_createUser' => DEBUG ? "Fahadisoana ao amin'ny user/createUser : :field" : "Nisy fahadisoana nitranga tamin'ny famoronana kaonty",
        //user - is email user exist
        'user_isEmailUserExist' => DEBUG ? "Fahadisoana ao amin'ny user/isEmailUserExist : :field" : "Nisy fahadisoana nitranga tamin'ny fanamarinana ny adiresy mailaka",
        //user - filter user
        'user_filterUser' => DEBUG ? "Fahadisoana ao amin'ny user/filterUser : :field" : "sNisy fahadisoana nitranga tamin'ny fanisana ny mpampiasa",
        //user - list caissier
        'user_listCaissier' => DEBUG ? "Fahadisoana ao amin'ny user/listCaissier : :field" : "Nisy fahadisoana nitranga tamin'ny fanisana ny mpitahiry vola",
        //user find by id
        'user_findById' => DEBUG ? "Fahadisoana ao amin'ny user/findById : :field" : "Nisy fahadisoana nitranga tamin'ny fanamarinana ny ID mpampiasa",
        //user update
        'user_update' => DEBUG ? "Fahadisoana ao amin'ny user/update : :field" : "Nisy fahadisoana nitranga tamin'ny fanovana ny fampahalalana mpampiasa",
        //user - is email user exist
        'user_isEmailUserExist' => DEBUG ? "Fahadisoana ao amin'ny user/isEmailUserExist : :field" : "Nisy fahadisoana nitranga",
        //user - delete account
        "user_deleteAccount" => DEBUG ? "Fahadisoana ao amin'ny user/deleteAccount : :field" : "Nisy fahadisoana nitranga tamin'ny famafana kaonty",
        //user - delete all
        'user_deleteAll' => DEBUG ? "Fahadisoana ao amin'ny user/deleteAll : :field" : "Nisy fahadisoana nitranga tamin'ny famafana ny mpampiasa",
        //user - deconnect all
        'user_deconnectAll' => DEBUG ? "Fahadisoana ao amin'ny user/deconnectAll : :field" : "Nisy fahadisoana nitranga tamin'ny fanalana ny fifandraisana ny mpampiasa",
        //user - find by email
        'user_findByEmail' => DEBUG ? "Fahadisoana ao amin'ny user/findByEmail : :field" : "Nisy fahadisoana nitranga tamin'ny fanamarinana ny adiresy mailaka mpampiasa",
        //user - list all user
        'user_listAllUser' => DEBUG ? "Fahadisoana ao amin'ny user/listAllUser : :field" : "Nisy fahadisoana nitranga tamin'ny fandraisana ny lisitry ny mpampiasa",
        //user - print all user
        'user_printAllUser' => DEBUG ? "Fahadisoana ao amin'ny user/printAllUser : :field" : "Nisy fahadisoana nitranga tamin'ny famokarana PDF ho an'ny lisitry ny mpampiasa",
        //user - restore all user
        'user_restoreAllUser' => DEBUG ? "Fahadisoana ao amin'ny user/restoreAllUser : :field" : "Nisy fahadisoana nitranga tamin'ny famerenana ny mpampiasa",
        //caisse - create caisse
        'caisse_createCaisse' => DEBUG ? "Fahadisoana ao amin'ny caisse/createCaisse : :field" : "Fahadisoana tamin'ny famoronana vata fitehirizam-bola",
        //caisse - is num caisse exist
        'caisse_isNumCaisseExist' => DEBUG ? "Fahadisoana ao amin'ny caisse/isNumCaisseExist : :field" : "Nisy fahadisoana nitranga tamin'ny fanamarinana ny isa vata fitehirizam-bola",
        //caisse - filter caisse
        'caisse_filterCaisse' => DEBUG ? "Fahadisoana ao amin'ny caisse/filterCaisse : :field" : "Nisy fahadisoana nitranga tamin'ny fanisana ny vata fitehirizam-bola",
        //caisse - find by id
        'caisse_findById' => DEBUG ? "Fahadisoana ao amin'ny caisse/findById : :field" : "Nisy fahadisoana nitranga tamin'ny fanamarinana ny isa vata fitehirizam-bola",
        //caisse - filter ligne caisse
        'caisse_filterLigneCaisse' => DEBUG ? "Fahadisoana ao amin'ny caisse/filterLigneCaisse : :field" : "Nisy fahadisoana nitranga tamin'ny fanisana ny tsanganana vata fitehirizam-bola",
        //caisse - update caisse
        'caisse_updateCaisse' => DEBUG ? "Fahadisoana ao amin'ny caisse/updateCaisse : :field" : "Nisy fahadisoana nitranga tamin'ny fanovana ny fampahalalana vata fitehirizam-bola",
        //caisse - list free caisse
        'caisse_listFreeCaisse' => DEBUG ? "Fahadisoana ao amin'ny caisse/listFreeCaisse : :field" : "Nisy fahadisoana nitranga tamin'ny fanisana ny vata fitehirizam-bola malalaka",
        //caisse - list all caisse
        'caisse_listAllCaisse' => DEBUG ? "Fahadisoana ao amin'ny caisse/listAllCaisse : :field" : "Nisy fahadisoana nitranga tamin'ny fanisana ny vata fitehirizam-bola rehetra",
        //caisse - delete all
        'caisse_deleteAll' => DEBUG ? "Fahadisoana ao amin'ny caisse/deleteAll : :field" : "Nisy fahadisoana nitranga tamin'ny famafana ny vata fitehirizam-bola",
        //caisse - delete all ligne caisse
        'caisse_deleteAllLigneCaisse' => DEBUG ? "Fahadisoana ao amin'ny caisse/deleteAllLigneCaisse : :field" : "Nisy fahadisoana nitranga tamin'ny famafana ny tsanganana vata fitehirizam-bola",
        //caisse - add ligne caisse
        'caisse_addLigneCaisse' => DEBUG ? "Fahadisoana ao amin'ny caisse/addLigneCaisse : :field" : "Nisy fahadisoana nitranga tamin'ny fanampiana tsanganana vata fitehirizam-bola",
        //caisse - find by id ligne caisse
        'caisse_findByIdLc' => DEBUG ? "Fahadisoana ao amin'ny caisse/findByIdLc : :field" : "Nisy fahadisoana nitranga tamin'ny fanamarinana ny ID isa vata fitehirizam-bola",
        //caisse - is date interval exist ?
        'caisse_isDateIntervalExist' => DEBUG ? "Fahadisoana ao amin'ny caisse/isDateIntervalExist : :field" : "Nisy fahadisoana nitranga tamin'ny fanamarinana ny datin'ny tsanganana vata fitehirizam-bola",
        //caisse - create ligne caisse
        'caisse_createLigneCaisse' => DEBUG ? "Fahadisoana ao amin'ny caisse/createLigneCaisse : :field" : "Nisy fahadisoana nitranga tamin'ny famoronana tsanganana vata fitehirizam-bola",
        //caisse - close ligne caisse
        'caisse_closeLigneCaisse' => DEBUG ? "Fahadisoana ao amin'ny caisse/closeLigneCaisse : :field" : "Nisy fahadisoana nitranga tamin'ny fanakatonana tsanganana vata fitehirizam-bola",
        //caisse - occup caisse
        'caisse_occupCaisse' => DEBUG ? "Fahadisoana ao amin'ny caisse/occupCaisse : :field" : "Nisy fahadisoana nitranga tamin'ny fakan'ny vata fitehirizam-bola",
        //caisse - update etat caisse
        'caisse_updateEtatCaisse' => DEBUG ? "Fahadisoana ao amin'ny caisse/updateEtatCaisse : :field" : "Nisy fahadisoana nitranga tamin'ny fanavaozana ny toetry ny vata fitehirizam-bola",
        //caisse - quit caisse
        'caisse_quitCaisse' => DEBUG ? "Fahadisoana ao amin'ny caisse/quitCaisse : :field" : "Nisy fahadisoana nitranga tamin'ny famotsorana vata fitehirizam-bola",
        //caisse - free caisse
        'caisse_freeCaisse' => DEBUG ? "Fahadisoana ao amin'ny caisse/freeCaisse : :field" : "Nisy fahadisoana nitranga tamin'ny famotsorana ny vata fitehirizam-bola",
        //caisse - is id lc exist 
        'caisse_isIdLcExist' => DEBUG ? "Fahadisoana ao amin'ny caisse/isIdLcExist : :field" : "Nisy fahadisoana nitranga",
        //caisse - update ligne caisse
        'caisse_updateLigneCaisse' => DEBUG ? "Fahadisoana ao amin'ny caisse/updateLigneCaisse : :field" : "Nisy fahadisoana nitranga tamin'ny fanovana tsanganana vata fitehirizam-bola",
        //caisse - find caisse
        'caisse_findCaisse' => DEBUG ? "Fahadisoana ao amin'ny caisse/findCaisse : :field" : "Nisy fahadisoana nitranga tamin'ny fanamarinana ny vata fitehirizam-bolan'ny mpampiasa",
        //caisse - update solde
        'caisse_updateSolde' => DEBUG ? "Fahadisoana ao amin'ny caisse/updateSolde : :field" : "Nisy fahadisoana nitranga tamin'ny fanavaozana ny vola ao amin'ny vata fitehirizam-bola",
        //caisse - cash report
        'caisse_cashReport' => DEBUG ? "Fahadisoana ao amin'ny caisse/cashReport : :field" : "Nisy fahadisoana nitranga tamin'ny famokarana PDF tatitra vata fitehirizam-bola",
        //caisse - restore all
        'caisse_restoreAll' => DEBUG ? "Fahadisoana ao amin'ny caisse/restoreAll : :field" : "Nisy fahadisoana nitranga tamin'ny famerenana ny vata fitehirizam-bola",
        //entree - create autre entree
        'entree_createAutreEntree' => DEBUG ? "Fahadisoana ao amin'ny entree/createAutreEntree : :field" : "Nisy fahadisoana nitranga tamin'ny fanampiana fidirana hafa vaovao",
        //entree - is num_ae exist 
        'entree_isNumAeExist' => DEBUG ? "Fahadisoana ao amin'ny entree/isNumAeExist : :field" : "Nisy fahadisoana nitranga tamin'ny fanamarinana ny isa fidirana hafa",
        //entree - filter autre entree
        'entree_filterAutreEntreeAdmin' => DEBUG ? "Fahadisoana ao amin'ny entree/filterAutreEntree : :field" : "Nisy fahadisoana nitranga tamin'ny fanisana ny fidirana hafa",
        //entree - list all autre entree
        'entree_listAllAutreEntree' => DEBUG ? "Fahadisoana ao amin'ny entree/listAllAutreEntree : :field" : "Nisy fahadisoana nitranga tamin'ny fanisana ny fidirana hafa rehetra",
        //entree - update autre entree
        'entree_updateAutreEntree' => DEBUG ? "Fahadisoana ao amin'ny entree/updateAutreEntree : :field" : "Nisy fahadisoana nitranga tamin'ny fanovana fidirana hafa",
        //entree - find by id
        'entree_findById' => DEBUG ? "Fahadisoana ao amin'ny entree/findById : :field" : "Nisy fahadisoana nitranga tamin'ny fanamarinana ny isa fidirana hafa",
        //entree - delete all autre entree
        'entree_deleteAllAutreEntree' => DEBUG ? "Fahadisoana ao amin'ny entree/deleteAllAutreEntree : :field" : "Nisy fahadisoana nitranga tamin'ny famafana ny fidirana hafa",
        //entree- list connection autre entree
        'entree_listConnectionAutreEntree' => DEBUG ? "Fahadisoana ao amin'ny entree/listConnectionAutreEntree : :field" : "Nisy fahadisoana nitranga tamin'ny fanisana ny fifandraisana ho an'ity fidirana hafa ity",
        //entree - connection autree entree
        'entree_connectionAutreEntree' => DEBUG ? "Fahadisoana ao amin'ny entree/connectionAutreEntree : :field" : "Nisy fahadisoana nitranga tamin'ny fanisana ny fifandraisana ho an'ny fidirana",
        //entree - create facture
        'entree_createFacture' => DEBUG ? "Fahadisoana ao amin'ny entree/createFacture : :field" : "Nisy fahadisoana nitranga tamin'ny famoronana faktiora",
        //entree - create ligne facture
        'entree_createLigneFacture' => DEBUG ? "Fahadisoana ao amin'ny entree/createLigneFacture : :field" : "Nisy fahadisoana nitranga tamin'ny famoronana tsanganana faktiora",
        //entree - filter facture
        'entree_filterFacture' => DEBUG ? "Fahadisoana ao amin'ny entree/filterFacture : :field" : "Nisy fahadisoana nitranga tamin'ny fanisana ny faktiora",
        //entree - list all facture
        'entree_listAllFacture' => DEBUG ? "Fahadisoana ao amin'ny entree/listAllFacture : :field" : "Nisy fahadisoana nitranga tamin'ny fanisana ny faktiora rehetra",
        //entree - ligne facture
        'entree_ligneFacture' => DEBUG ? "Fahadisoana ao amin'ny entree/ligneFacture : :field" : "Nisy fahadisoana nitranga tamin'ny fanisana ny tsanganana faktiora",
        //entree - facture find by id
        'facture_findById' => DEBUG ? "Fahadisoana ao amin'ny entree_facture/findById : :field" : "Nisy fahadisoana nitranga tamin'ny fanamarinana ny isa faktiora",
        //entree - list connection facture
        'entree_listConnectionFacture' => DEBUG ? "Fahadisoana ao amin'ny entree/listConnectionFacture : :field" : "Nisy fahadisoana nitranga tamin'ny fanisana ny fifandraisana ho an'ity faktiora ity",
        //entree - update facture
        'entree_updateFacture' => DEBUG ? "Fahadisoana ao amin'ny entree/updateFacture : :field" : "Nisy fahadisoana nitranga tamin'ny fanovana faktiora",
        //entree - delete all facture
        'entree_deleteAllFacture' => DEBUG ? "Fahadisoana ao amin'ny entree/deleteAllFacture : :field" : "Nisy fahadisoana nitranga tamin'ny famafana ny faktiora",
        //entree - lf find by id
        'entree_lf_findById' => DEBUG ? "Fahadisoana ao amin'ny entree_lf/findById : :field" : "Nisy fahadisoana nitranga tamin'ny fanamarinana ny ID tsanganana faktiora",
        //entree - restore all facture
        'entree_restoreAllFacture' => DEBUG ? "Fahadisoana ao amin'ny entree/restoreAllFacture : :field" : "Nisy fahadisoana nitranga tamin'ny famerenana ny faktiora",
        //entree - restore all ae
        'entree_restoreAllAutreEntree' => DEBUG ? "Fahadisoana ao amin'ny entree/restoreAllAutreEntree : :field" : "Nisy fahadisoana nitranga tamin'ny famerenana ny fidirana hafa",
        //sortie - connection sortie
        'sortie_connectionSortie' => DEBUG ? "Fahadisoana ao amin'ny sortie/connectionSortie : :field" : "Nisy fahadisoana nitranga tamin'ny fanisana ny fifandraisana ho an'ny fivoahana",
        //sortie - create sortie 
        'sortie_createSortie' => DEBUG ? "Fahadisoana ao amin'ny sortie/createSortie : :field" : "Nisy fahadisoana nitranga tamin'ny famoronana fivoahana",
        //sortie - create ligne_ds
        'sortie_createLigneDs' => DEBUG ? "Fahadisoana ao amin'ny sortie/createLigneDs : :field" : "Nisy fahadisoana nitranga tamin'ny famoronana tsanganana fivoahana",
        //sortie - filter demande sortie
        'sortie_filterDemandeSortie' => DEBUG ? "Fahadisoana ao amin'ny sortie/filterDemandeSortie : :field" : "Nisy fahadisoana nitranga tamin'ny fanisana ny fivoahana",
        //sortie - list connection sortie
        'sortie_listConnectionSortie' => DEBUG ? "Fahadisoana ao amin'ny sortie/listConnectionSortie : :field" : "Nisy fahadisoana nitranga tamin'ny fanisana ny fifandraisana ho an'ity fivoahana ity",
        //sortie - find by id
        'sortie_findById' => DEBUG ? "Fahadisoana ao amin'ny sortie/findById : :field" : "Nisy fahadisoana nitranga tamin'ny fanamarinana ny isa fivoahana",
        //sortie - ligne ds
        'sortie_ligneDs' => DEBUG ? "Fahadisoana ao amin'ny sortie/ligneDs : :field" : "Nisy fahadisoana nitranga tamin'ny fanisana ny tsanganana fivoahana",
        //sortie - list all demande sortie
        'sortie_listAllDemandeSortie' => DEBUG ? "Fahadisoana ao amin'ny sortie/listAllDemandeSortie : :field" : "Nisy fahadisoana nitranga tamin'ny fanisana ny fivoahana rehetra",
        //sortie - update demande sortie
        'sortie_updateDemandeSortie' => DEBUG ? "Fahadisoana ao amin'ny sortie/updateDemandeSortie : :field" : "Nisy fahadisoana nitranga tamin'ny fanovana fivoahana",
        //sortie - delete all demande sortie
        'sortie_deleteAllDemandeSortie' => DEBUG ? "Fahadisoana ao amin'ny sortie/deleteAllDemandeSortie : :field" : "Nisy fahadisoana nitranga tamin'ny famafana ny fivoahana",
        //sortie - get montant_ds
        'sortie_getMontantDs' => DEBUG ? "Fahadisoana ao amin'ny sortie/getMontantDs : :field" : "Nisy fahadisoana nitranga tamin'ny kajy ny volan'ny fivoahana",
        //sortie - correction facture
        'sortie_correctionFacture' => DEBUG ? "Fahadisoana ao amin'ny sortie/correctionFacture : :field" : "Nisy fahadisoana nitranga tamin'ny fanitsiana ny faktiora",
        //sortie - print demande sortie
        'sortie_printDemandeSortie' => DEBUG ? "Fahadisoana ao amin'ny sortie/printDemandeSortie : :field" : "Nisy fahadisoana nitranga tamin'ny famokarana PDF fangatahana fivoahana",
        //sortie - restore all demande sortie
        'sortie_restoreAllDemandeSortie' => DEBUG ? "Fahadisoana ao amin'ny sortie/restoreAllDemandeSortie : :field" : "Nisy fahadisoana nitranga tamin'ny famerenana ny fivoahana",
        //produit  - create produit
        'produit_createProduit' => DEBUG ? "Fahadisoana ao amin'ny produit/createProduit : :field" : "Nisy fahadisoana nitranga tamin'ny fanampiana vokatra vaovao",
        //produit - filter produit
        'produit_filterProduit' => DEBUG ? "Fahadisoana ao amin'ny produit/filterProduit : :field" : "Nisy fahadisoana nitranga tamin'ny fanisana ny vokatra",
        //produit - list all produit
        'produit_listAllProduit' => DEBUG ? "Fahadisoana ao amin'ny produit/listAllProduit : :field" : "Nisy fahadisoana nitranga tamin'ny fanisana ny vokatra rehetra",
        //produit - update produit
        'produit_updateProduit' => DEBUG ? "Fahadisoana ao amin'ny produit/updateProduit : :field" : "Nisy fahadisoana nitranga tamin'ny fanovana vokatra",
        //produit - find by id
        'produit_findById' => DEBUG ? "Fahadisoana ao amin'ny produit/findById : :field" : "Nisy fahadisoana nitranga tamin'ny fanamarinana ny ID vokatra",
        //produit - delete all produit
        'produit_deleteAllProduit' => DEBUG ? "Fahadisoana ao amin'ny produit/deleteAllProduit : :field" : "Nisy fahadisoana nitranga tamin'ny famafana ny vokatra",
        //produit - update nb stock
        'produit_updateNbStocke' => DEBUG ? "Fahadisoana ao amin'ny produit/updateNbStock : :field" : "Nisy fahadisoana nitranga tamin'ny fanavaozana ny isan'ny tahiry",
        //produit - restore all produit
        'produit_restoreAllProduit' => DEBUG ? "Fahadisoana ao amin'ny produit/restoreAllProduit : :field" : "Nisy fahadisoana nitranga tamin'ny famerenana ny vokatra",
        //article - create article
        'article_createArticle' => DEBUG ? "Fahadisoana ao amin'ny article/createArticle : :field" : "Nisy fahadisoana nitranga tamin'ny fanampiana singa vaovao",
        //article - is libelle article exist
        'article_isLibelleArticleExist' => DEBUG ? "Fahadisoana ao amin'ny article/isLibelleArticleExist : :field" : "Nisy fahadisoana nitranga tamin'ny fanamarinana ny singa",
        //article - filter article
        'article_filterArticle' => DEBUG ? "Fahadisoana ao amin'ny article/filterArticle : :field" : "Nisy fahadisoana nitranga tamin'ny fanisana ny singa",
        //article - list all article
        'article_listAllArticle' => DEBUG ? "Fahadisoana ao amin'ny article/listAllArticle : :field" : "Nisy fahadisoana nitranga tamin'ny fanisana ny singa rehetra",
        //article - update article
        'article_updateArticle' => DEBUG ? "Fahadisoana ao amin'ny article/updateArticle : :field" : "Nisy fahadisoana nitranga tamin'ny fanovana singa",
        //article - find by id
        'article_findById' => DEBUG ? "Fahadisoana ao amin'ny article/findById : :field" : "Nisy fahadisoana nitranga tamin'ny fanamarinana ny singa",
        //article - delete all article
        'article_deleteAllArticle' => DEBUG ? "Fahadisoana ao amin'ny article/deleteAllArticle : :field" : "Nisy fahadisoana nitranga tamin'ny famafana ny singa",
        //article - restore all article
        'article_restoreAllArticle' => DEBUG ? "Fahadisoana ao amin'ny article/restoreAllArticle : :field" : "Nisy fahadisoana nitranga tamin'ny famerenana ny singa",
        //client - create client
        'client_createClient' => DEBUG ? "Fahadisoana ao amin'ny client/createClient : :field" : "Nisy fahadisoana nitranga tamin'ny fanampiana mpanjifa",
        //client - filter client
        'client_filterClient' => DEBUG ? "Fahadisoana ao amin'ny client/filterClient : :field" : "Nisy fahadisoana nitranga tamin'ny fanisana ny mpanjifa",
        //client - list all client
        'client_listAllClient' => DEBUG ? "Fahadisoana ao amin'ny client/listAllClient : :field" : "Nisy fahadisoana nitranga tamin'ny fanisana ny mpanjifa rehetra",
        //client - update client 
        'client_updateClient' => DEBUG ? "Fahadisoana ao amin'ny client/updateClient : :field" : "Nisy fahadisoana nitranga tamin'ny fanovana ny fampahalalana mpanjifa",
        //client - find by id
        'client_findById' => DEBUG ? "Fahadisoana ao amin'ny client/findById : :field" : "Nisy fahadisoana nitranga tamin'ny fanamarinana ny ID mpanjifa",
        //client - delete all client
        'client_deleteAllClient' => DEBUG ? "Fahadisoana ao amin'ny client/deleteAllClient : :field" : "Nisy fahadisoana nitranga tamin'ny famafana ny mpanjifa",
        //client - restore all client
        'client_restoreAllClient' => DEBUG ? "Fahadisoana ao amin'ny client/restoreAllClient : :field" : "Nisy fahadisoana nitranga tamin'ny famerenana ny mpanjifa",
    ],
    'not_found' => [
        'page' => "Tsy hita ny pejy nangatahana : :field",
        'action' => "Tsy hita ny hetsika nangatahana : :field",
        'class' => DEBUG ? "Fahadisoana fampidirana kilasy, tsy hita ny kilasy : :field" : "Nisy fahadisoana nitranga",
        'config' => "Fahadisoana rakitra config : tsy voafaritra ny fanalahidy <b>:field</b>",
        'json' => "Fahadisoana JSON : tsy misy ny fanalahidy <b>:field</b>"
    ]
];
