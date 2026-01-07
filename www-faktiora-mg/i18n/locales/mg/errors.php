<?php

return [
    'catch' => [
        'router' => DEBUG ? "Tsy fetezana teo amin'ny `routeur` : :field" : "Nisy tsy fetezana nitranga teo amin'ny fandaminana ny rohy.",
        'database' => DEBUG ? "Tsy fetezana teo amin'ny fifandraisana amin'ny `base de données`: :field" : "Nisy tsy fetezana nitranga teo amin'ny fifandraisana amin'ny `base de données`.",
        'query' => DEBUG ? "Tsy fetezana teo amin'ny fangatahana any amin'ny `base de données` : :field" : "Nisy tsy fetezana nitranga teo amin'ny fangatahana any amin'ny `base de données`.",
        'correction' => DEBUG ? "Tsy fetezana teo amin'ny fanitsiana : :field" : "Nisy fetezana nitranga teo amin'ny fanitsiana.",
        //user - create default admin
        'user_createDefaultAdmin' => DEBUG ? "Tsy fetezana teo amin'ny user/createDefaultAdmin : :field" : "Nisy tsy fetezana nitranga teo amin'ny famoronana kaonty ho an'i tomponandraikitra fototra.",
        //auth - is loged in
        'auth_isLogedIn' => DEBUG ? "Tsy fetezana teo amin'ny auth/isLogedIn : :field" : "Nisy tsy fetezana nitranga teo amin'ny fanamarinana fa efa tafiditra ny mpampiasa.",
        //auth - login
        'auth_loginUser' => DEBUG ? "Tsy fetezana teo ami'ny auth/loginUser : :field" : "Nisy tsy fetezana nitranga nandritra ny fidirana.",
        //user - create user
        'user_createUser' => DEBUG ? "Tsy fetezana teo amin'ny user/createUser : :field" : "Nisy tsy fetezana nitranga teo amin'ny famoronana kaonty.",
        //user - is email user exist
        'user_isEmailUserExist' => DEBUG ? "Tsy fetezana teo amin'ny user/isEmailUserExist : :field" : "Nisy tsy fetezana nitranga teo amin'ny fanamarinana ny adiresy mailaka.",
        //user - filter user
        'user_filterUser' => DEBUG ? "Tsy fetezana teo amin'ny user/filterUser : :field" : "Nisy tsy fetezana nitranga teo amin'ny lisitry ny mpampiasa.",
        //user - list caissier
        'user_listCaissier' => DEBUG ? "Tsy fetezana teo amin'ny user/listCaissier : :field" : "Nisy tsy fetezana nitranga teo amin'ny lisitry ny mpikajy vola.",
        //user find by id
        'user_findById' => DEBUG ? "Tsy fetezana teo amin'ny user/findById : :field" : "Nisy tsy fetezana nitranga teo amin'ny fanamarinana ny ID-n'ny mpampiasa.",
        //user update
        'user_update' => DEBUG ? "Tsy fetezana teo amin'ny user/update : :field" : "Nisy tsy fetezana nitranga teo amin'ny fanovana ny mombamomba ny mpampiasa.",
        //user - is email user exist
        'user_isEmailUserExist' => DEBUG ? "Tsy fetezana teo amin'ny user/isEmailUserExist : :field" : "Nisy tsy fetezana nitranga teo amin'ny fanamarinana ny adiresy mailaka-n'ny mpampiasa.",
        //user - delete account
        "user_deleteAccount" => DEBUG ? "Tsy fetezana teo amin'ny user/deleteAccount : :field" : "Nisy tsy fetezana nitranga teo amin'ny famafana ny kaonty.",
        //user - delete all
        'user_deleteAll' => DEBUG ? "Tsy fetezana teo amin'ny user/deleteAll : :field" : "Nisy tsy fetezana nitranga teo amin'ny famafana an'ireo mpampiasa.",
        //user - deconnect all
        'user_deconnectAll' => DEBUG ? "Tsy fetezana amin'ny user/deconnectAll : :field" : "Nisy tsy fetezana nitranga teo amin'ny famoahana an'ireo mpampiasa.",
        //user - find by email
        'user_findByEmail' => DEBUG ? "Tsy fetezana teo amin'ny user/findByEmail : :field" : "Nisy tsy fetezana nitranga teo amin'ny fanamarinana ny mpampiasa tamin'ny alalan'ny adiresy mailaka.",
        //user - list all user
        'user_listAllUser' => DEBUG ? "Tsy fetezana teo amin'ny user/listAllUser : :field" : "Nisy tsy fetezana nitranga teo amin'ny fakana ny lisitry ny mpampiasa.",
        //user - print all user
        'user_printAllUser' => DEBUG ? "Tsy fetezana teo amin'ny user/printAllUser : :field" : "Nisy tsy fetezana nitranga tamin'ny famoronana ny PDF ho an'ny lisitry ny mpampiasa.",
        //user - restore all user
        'user_restoreAllUser' => DEBUG ? "Tsy fetezana teo amin'ny user/restoreAllUser : :field" : "Nisy fetezana nitranga teo amin'ny famerenana ny mpampiasa.",
        //caisse - create caisse
        'caisse_createCaisse' => DEBUG ? "Tsy fetezana teo amin'ny caisse/createCaisse : :field" : "Nisy tsy fetezana nitranga teo amin'ny famoronana ny toeram-pandrotsaham-bola.",
        //caisse - is num caisse exist
        'caisse_isNumCaisseExist' => DEBUG ? "Tsy fetezana teo amin'ny caisse/isNumCaisseExist : :field" : "Nisy tsy fetezana nitranga teo amin'ny fanamarinana ny laharan'ny toeram-pandrotsaham-bola.",
        //caisse - filter caisse
        'caisse_filterCaisse' => DEBUG ? "Tsy fetezana teo amin'ny caisse/filterCaisse : :field" : "Nisy tsy fetezana nitranga teo amin'ny lisitry ny toeram-pandrotsaham-bola.",
        //caisse - find by id
        'caisse_findById' => DEBUG ? "Tsy fetezana teo amin'ny caisse/findById : :field" : "Nisy tsy fetezana nitranga teo amin'ny fanamarinana ny laharan'ny toeram-pandrotsaham-bola.",
        //caisse - filter ligne caisse
        'caisse_filterLigneCaisse' => DEBUG ? "Tsy fetezana teo amin'ny caisse/filterLigneCaisse : :field" : "Nisy tsy fetezana nitranga teo amin'ny lisitry ny fitantanana ny toeram-pandrotsaham-bola.",
        //caisse - update caisse
        'caisse_updateCaisse' => DEBUG ? "Tsy fetezana teo amin'ny caisse/updateCaisse : :field" : "Nisy tsy fetezana nitranga teo amin'ny fanovana ny mombamomba ny toeram-pandrotsaham-bola.",
        //caisse - list free caisse
        'caisse_listFreeCaisse' => DEBUG ? "Tsy fetezana teo amin'ny caisse/listFreeCaisse : :field" : "Nisy tsy fetezana nitranga teo amin'ny lisitry ny toeram-pandrotsaham-bola malalaka.",
        //caisse - list all caisse
        'caisse_listAllCaisse' => DEBUG ? "Tsy fetezana teo amin'ny caisse/listAllCaisse : :field" : "Nisy tsy fetezana nitranga teo amin'ny lisitry ny toeram-pandrotsaham-bola rehetra.",
        //caisse - delete all caisse
        'caisse_deleteAll' => DEBUG ? "Tsy fetezana teo amin'ny caisse/deleteAll : :field" : "Nisy tsy fetezana nitranga teo amin'ny famafana an'ireo toeram-pandrotsaham-bola.",
        //caisse - delete all ligne caisse
        'caisse_deleteAllLigneCaisse' => DEBUG ? "Tsy fetezana teo  amin'ny caisse/deleteAllLigneCaisse : :field" : "Nisy tsy fetezana nitranga teo amin'ny famafana an'ireo andalana ao amin'ny fitantanana ny toeram-pandrotsaham-bola.",
        //caisse - add ligne caisse
        'caisse_addLigneCaisse' => DEBUG ? "Tsy fetezana teo amin'ny caisse/addLigneCaisse : :field" : "Nisy tsy fetezana nitranga teo amin'ny fanampiana ny andalana ao amin'ny fitantanana ny toeram-pandrotsaham-bola.",
        //caisse - find by id ligne caisse
        'caisse_findByIdLc' => DEBUG ? "Tsy fetezana teo amin'ny caisse/findByIdLc : :field" : "Nisy tsy fetezana nitranga teo amin'ny fanamarinana ny andalana ao amin'ny fitantanana ny toeram-pandrotsaham-bola.",
        //caisse - is date interval exist ?
        'caisse_isDateIntervalExist' => DEBUG ? "Tsy fetezana teo amin'ny caisse/isDateIntervalExist : :field" : "Nisy tsy fetezana nitranga teo amin'ny fanamarinana ny daty ao amin'ny andalana ao amin'ny fitantanana ny toeram-pandrotsaham-bola.",
        //caisse - create ligne caisse
        'caisse_createLigneCaisse' => DEBUG ? "Tsy fetezana teo amin'ny caisse/addLigneCaisse : :field" : "Nisy tsy fetezana nitranga teo amin'ny fanampiana ny andalana ao amin'ny fitantanana ny toeram-pandrotsaham-bola.",
        //caisse - close ligne caisse
        'caisse_closeLigneCaisse' => DEBUG ? "Tsy fetezana teo amin'ny caisse/closeLigneCaisse : :field" : "Nisy tsy fetezana nitranga teo amin'ny fanakatonana ny andalana ao amin'ny fitantanana ny toeram-pandrotsaham-bola.",
        //caisse - occup caisse
        'caisse_occupCaisse' => DEBUG ? "Tsy fetezana amin'ny caisse/occupCaisse : :field" : "Nisy tsy fetezana nitranga teo amin'ny fandraisana ny toeram-pandrotsaham-bola.",
        //caisse - update etat caisse
        'caisse_updateEtatCaisse' => DEBUG ? "Tsy fetezana teo amin'ny caisse/updateEtatCaisse : :field" : "Nisy tsy fetezana nitranga teo amin'ny fanavaozana ny toetry ny toeram-pandrotsaham-bola.",
        //caisse - quit caisse
        'caisse_quitCaisse' => DEBUG ? "Tsy fetezana teo amin'ny caisse/quitCaisse : :field" : "Nisy tsy fetezana nitranga teo amin'ny famelana ny toeram-pandrotsaham-bola.",
        //caisse - free caisse
        'caisse_freeCaisse' => DEBUG ? "Tsy fetezana teo amin'ny caisse/quitCaisse : :field" : "Nisy tsy fetezana nitranga teo amin'ny famelana an'ireo toeram-pandrotsaham-bola.",
        //caisse - update ligne caisse
        'caisse_updateLigneCaisse' => DEBUG ? "Tsy fetezana teo amin'ny caisse/updateLigneCaisse : :field" : "Nisy tsy fetezana nitranga teo amin'ny fanovana ny mombamomba ny andalana ao amin'ny fitantanana ny toeram-pandrotsaham-bola.",
        //caisse - find caisse
        'caisse_findCaisse' => DEBUG ? "Tsy fetezana teo amin'ny caisse/findCaisse : :field" : "Nisy tsy fetezana nitranga teo amin'ny fanamarinana toeram-pandrotsaham-bolan'ny mpampiasa.",
        //caisse - update solde
        'caisse_updateSolde' => DEBUG ? "Tsy fetezana teo amin'ny caisse/updateSolde : :field" : "Nisy tsy fetezana nitranga teo amin'ny fanavaozana ny vola ao amin'ny toeram-pandrotsaham-bola.",
        //caisse - cash report
        'caisse_cashReport' => DEBUG ? "Tsy fetezana teo amin'ny caisse/cashReport : :field" : "Nisy tsy fetezana nitranga teo amin'ny famoronana ny PDF ho an'ny tatitra ny toeram-pandrotsaham-bola.",
        //caisse - restore all
        'caisse_restoreAll' => DEBUG ? "Tsy fetezana teo amin'ny caisse/restoreAll : :field" : "Nisy tsy fetezana nitranga teo amin'ny famerenana an'ireo toeram-pandrotsaham-bola.",
        //entree - create autre entree
        'entree_createAutreEntree' => DEBUG ? "Tsy fetezana teo amin'ny entree/createAutreEntree : :field" : "Nisy tsy fetezana nitranga teo amin'ny famoronana ny `vola miditra hafa`.",
        //entree - is num_ae exist 
        'entree_isNumAeExist' => DEBUG ? "Tsy fetezana teo amin'ny entree/isNumAeExist : :field" : "Nisy tsy fetezana nitranga teo amin'ny fanamarinana ny laharan'ny `vola miditra hafa`.",
        //entree - filter autre entree
        'entree_filterAutreEntreeAdmin' => DEBUG ? "Tsy fetezana teo amin'ny entree/filterAutreEntree : :field" : "Nisy tsy fetezana nitranga teo amin'ny lisitry ny `vola miditra hafa`.",
        //entree - list all autre entree
        'entree_listAllAutreEntree' =>  DEBUG ? "Tsy fetezana teo amin'ny entree/listAutreEntree : :field" : "Nisy tsy fetezana nitranga teo amin'ny lisitry ny `vola miditra hafa` rehetra.",
        //entree - update autre entree
        'entree_updateAutreEntree' => DEBUG ? "Tsy fetezana teo amin'ny entree/updateAutreEntree : :field" : "Nisy tsy fetezana nitranga teo amin'ny fanovana ny mombamomba ny `vola midira hafa`.",
        //entree - find by id
        'entree_findById' => DEBUG ? "Tsy fetezana teo amin'ny entree/findById : :field" : "Nisy tsy fetezana nitranga teo amin'ny fanamarinana ny laharan'ny `vola miditra hafa`.",
        //entree - delete all autre entree
        'entree_deleteAllAutreEntree' => DEBUG ? "Tsy fetezana teo amin'ny entree/deleteAllAutreEntree : :field" : "Nisy tsy fetezana nitranga teo amin'ny famafana an'ireo `vola miditra hafa`.",
        //entree- list connection autre entree
        'entree_listConnectionAutreEntree' => DEBUG ? "Tsy fetezana teo amin'ny entree/listConnectionAutreEntree : :field" : "Nisy tsy fetezana nitranga teo amin'ny lisitry ny fifandraisana ho an'ity `vola miditra hafa` ity.",
        //entree - connection autree entree
        'entree_connectionAutreEntree' => DEBUG ? "Tsy fetezana teo amin'ny entree/connectionAutreEntree : :field" : "Nisy tsy fetezana nitranga teo amin'ny lisitry ny fifandraisan'ny vola miditra.",
        //entree - create facture
        'entree_createFacture' => DEBUG ? "Tsy fetezana teo amin'ny entree/createFacture : :field" : "Nisy tsy fetezana nitranga teo amin'ny famoronana ny faktiora.",
        //entree - create ligne facture
        'entree_createLigneFacture' => DEBUG ? "Tsy fetezana teo amin'ny entree/createLigneFacture : :field" : "Nisy tsy fetezana nitranga teo amin'ny famoronana ny andalana ho an'ny faktiora.",
        //entree - filter facture
        'entree_filterFacture' => DEBUG ? "Tsy fetezana teo amin'ny entree/filterFacture : :field" : "Nisy tsy fetezana nitranga teo amin'ny lisitry ny faktiora.",
        //entree - list all facture
        'entree_listAllFacture' => DEBUG ? "Tsy fetezana teo amin'ny entree/listAllFacture : :field" : "Nisy tsy fetezana nitranga teo amin'ny lisitry ny  faktiora rehetra.",
        //entree - ligne facture
        'entree_ligneFacture' => DEBUG ? "Tsy fetezana teo amin'ny entree/ligneFacture : :field" : "Nisy tsy fetezana nitranga teo amin'ny lisitry ny andalana ao amin'ny faktiora.",
        //entree - facture find by id
        'facture_findById' => DEBUG ? "Tsy fetezana teo amin'ny entree_facture/findById : :field" : "Nisy tsy fetezana nitranga teo amin'ny fanamarinana ny laharan'ny faktiora.",
        //entree - list connection facture
        'entree_listConnectionFacture' => DEBUG ? "Tsy fetezana teo amin'ny entree/listConnectionFacture : :field" : "Nisy tsy fetezana nitranaga teo amin'ny lisitry ny fifandraisana ho an'ity faktiora ity.",
        //entree - update facture
        'entree_updateFacture' => DEBUG ? "Tsy fetezana ao amin'ny entree/updateFacture : :field" : "Nisy tsy fetezana nitranga teo amin'ny fanovana ny faktiora.",
        //entree - delete all facture
        'entree_deleteAllFacture' => DEBUG ? "Tsy fetezana teo amin'ny entree/deleteAllFacture : :field" : "Nisy tsy fetezana nitranga teo amin'ny famafana an'ireo faktiora.",
        //entree - lf find by id
        'entree_lf_findById' => DEBUG ? "Tsy fetezana teo amin'ny entree_lf/findById : :field" : "Nisy tsy fetezana nitranga teo amin'ny fanamarinana ny ID-n'ny andalana ao amin'ny faktiora.",
        //entree - restore all facture
        'entree_restoreAllFacture' => DEBUG ? "Tsy fetezana teo amin'ny entree/restoreAllFacture : :field" : "Nisy tsy fetezana nitranga teo amin'ny famerenana an'ireo faktiora.",
        //entree - restore all ae
        'entree_restoreAllAutreEntree' => DEBUG ? "Tsy fetezana teo amin'ny entree/restoreAllAutreEntree : :field" : "Nisy tsy fetezana nitranga teo amin'ny famerenana an'ireo `vola miditra hafa`.",
        //sortie - connection sortie
        'sortie_connectionSortie' => DEBUG ? "Tsy fetezana teo amin'ny sortie/connectionSortie : :field" : "Nisy tsy fetezana nitranga teo amin'ny vola mivoaka.",
        //sortie - create sortie 
        'sortie_createSortie' => DEBUG ? "Tsy fetezana teo amin'ny sortie/createSortie : :field" : "Nisy tsy fetezana nitranga teo amin'ny famoronana ny vola mivoaka.",
        //sortie - create ligne_ds
        'sortie_createLigneDs' => DEBUG ? "Tsy fetezana teo amin'ny sortie/createLigneDs : :field" : "Nisy tsy fetezana nitranga teo amin'ny famoronana ny andalana ao amin'ny vola mivoaka.",
        //sortie - filter demande sortie
        'sortie_filterDemandeSortie' => DEBUG ? "Tsy fetezana teo amin'ny sortie/filterDemandeSortie : :field" : "Nisy tsy fetezana nitranga teo amin'ny lisitry ny vola mivoaka.",
        //sortie - list connection sortie
        'sortie_listConnectionSortie' => DEBUG ? "Tsy fetezana teo amin'ny sortie/listConnectionSortie : :field" : "Nisy tsy fetezana nitranga teo amin'ny fifandraisana ho an'ity vola mivoaka ity.",
        //sortie - find by id
        'sortie_findById' => DEBUG ? "Tsy fetezana teo amin'ny sortie/findById : :field" : "Nisy tsy fetezana nitranga teo amin'ny fanamarinana ny laharan'ny vola mivoaka.",
        //sortie - ligne ds
        'sortie_ligneDs' => DEBUG ? "Tsy fetezana teo amin'ny sortie/ligneDs : :field" : "Nisy tsy fetezana nitranga teo amin'ny lisitry ny andalana ao amin'ny vola mivoaka.",
        //sortie - list all demande sortie
        'sortie_listAllDemandeSortie' => DEBUG ? "Tsy fetezana teo amin'ny sortie/listAllDemandeSortie : :field" : "Nisy tsy fetezana nitranga teo amin'ny lisitry ny vola mivoaka rehetra.",
        //sortie - update demande sortie
        'sortie_updateDemandeSortie' => DEBUG ? "Tsy fetezana teo amin'ny sortie/updateDemandeSortie : :field" : "Nisy tsy fetezana nitranga teo amin'ny fanovana ny vola mivoaka.",
        //sortie - delete all demande sortie
        'sortie_deleteAllDemandeSortie' => DEBUG ? "Tsy fetezana teo amin'ny sortie/deleteAllDemandeSortie : :field" : "Nisy tsy fetezana nitranga teo amin'ny famafana an'ireo vola mivoaka.",
        //sortie - get montant_ds
        'sortie_getMontantDs' => DEBUG ? "Tsy fetezana teo amin'ny sortie/getMontantDs : :field" : "Nisy tsy fetezana nitranga teo amin'ny fikajiana ny vola mivoaka.",
        //sortie - correction facture
        'sortie_correctionFacture' => DEBUG ? "Tsy fetezana teo amin'ny sortie/correctionFacture : :field" : "Nisy tsy fetezana nitranga teo amin'ny fanitsiana ny faktiora.",
        //sortie - print demande sortie
        'sortie_printDemandeSortie' => DEBUG ? "Tsy fetezana teo amin'ny sortie/printDemandeSortie : :field" : "Nisy tsy fetezana nitranga teo amin'ny famoronana ny PDF ho an'ny vola mivoaka.",
        //sortie - restore all demande sortie
        'sortie_restoreAllDemandeSortie' => DEBUG ? "Tsy fetezana teo amin'ny sortie/restoreAllDemandeSortie : :field" : "Nisy tsy fetezana nitranga teo amin'ny famerenana an'ireo vola mivoaka.",
        //produit  - create produit
        'produit_createProduit' => DEBUG ? "Tsy fetezana teo amin'ny produit/createProduit : :field" : "Nisy tsy fetezana nitranga teo amin'ny fanampiana ny vokatra vaovao.",
        //produit - filter produit
        'produit_filterProduit' => DEBUG ? "Tsy fetezana teo amin'ny produit/filterProduit : :field" : "Nisy tsy fetezana nitranga teo amin'ny lisitry ny vokatra.",
        //produit - list all produit
        'produit_listAllProduit' => DEBUG ? "Tsy fetezana teo amin'ny produit/listAllProduit : :field" : "Nisy tsy fetezana nitranga teo amin'ny lisitry ny vokatra rehetra.",
        //produit - update produit
        'produit_updateProduit' => DEBUG ? "Tsy fetezana teo amin'ny produit/updateProduit : :field" : "Nisy tsy fetezana nitranga teo amin'ny fanovana ny mombamomba ny vokatra.",
        //produit - find by id
        'produit_findById' => DEBUG ? "Tsy fetezana teo amin'ny produit/findById : :field" : "Nisy tsy fetezana teo amin'ny fanamarinana ny ID-n'ny vokatra.",
        //produit - delete all produit
        'produit_deleteAllProduit' => DEBUG ? "Tsy fetezana teo amin'ny produit/deleteAllProduit : :field" : "Nisy tsy fetezana nitranga teo amin'ny famafana an'ireo vokatra.",
        //produit - update nb stock
        'produit_updateNbStocke' => DEBUG ? "Tsy fetezana teo amin'ny produit/updateNbStock : :field" : "Nisy tsy fetezana nitranga teo amin'ny fanavozana ny isan'ny tahiry.",
        //produit - restore all produit
        'produit_restoreAllProduit' => DEBUG ? "Tsy fetezana teo amin'ny produit/restoreAllProduit : :field" : "Nisy tsy fetezana nitranga teo amin'ny famerenana an'ireo vokatra.",
        //article - create article
        'article_createArticle' => DEBUG ? "Tsy fetezana teo amin'ny article/createArticle : :field" : "Nisy tsy fetezana nitranga teo amin'ny fanampiana ny fandaniana.",
        //article - is libelle article exist
        'article_isLibelleArticleExist' => DEBUG ? "Tsy fetezana teo amin'ny article/isLibelleArticleExist : :field" : "Nisy tsy fetezana nitranga teo amin'ny fanamarinana ny fandaniana.",
        //article - filter article
        'article_filterArticle' => DEBUG ? "Tsy fetezana teo amin'ny article/filterArticle : :field" : "Nisy tsy fetezana nitranga teo amin'ny lisitry ny  fandaniana.",
        //article - list all article
        'article_listAllArticle' => DEBUG ? "Tsy fetezana teo amin'ny article/listAllArticle : :field" : "Nisy tsy fetezana nitranga teo amin'ny lisitry ny fandaniana rehetra.",
        //article - update article
        'article_updateArticle' => DEBUG ? "Tsy fetezana teo amin'ny article/updateArticle : :field" : "Nisy tsy fetezana nitranga teo amin'ny fanovana ny mombamomba ny fandaniana.",
        //article - find by id
        'article_findById' => DEBUG ? "Tsy fetezana teo amin'ny article/findById : :field" : "Nisy tsy fetezana nitranga teo amin'ny fanamarinana ny ID-n'ny fandaniana.",
        //article - delete all article
        'article_deleteAllArticle' => DEBUG ? "Tsy fetezana teo amin'ny article/deleteAllArticle : :field" : "Nisy tsy fetezana nitranga teo amin'ny famafana an'ireo fandaniana.",
        //article - restore all article
        'article_restoreAllArticle' => DEBUG ? "Tsy fetezana teo amin'ny article/restoreAllArticle : :field" : "Nisy tsy fetezana nitranga teo amin'ny famerenana an'ireo fandaniana.",
        //client - create client
        'client_createClient' => DEBUG ? "Tsy fetezana teo amin'ny client/createClient : :field" : "Nisy tsy fetezana nitranga teo amin'ny fanampiana ny mpanjifa.",
        //client - filter client
        'client_filterClient' => DEBUG ? "Tsy fetezana teo amin'ny client/filterClient : :field" : "Nisy tsy fetezana nitranga teo amin'ny lisitry ny mpanjifa.",
        //client - list all client
        'client_listAllClient' => DEBUG ? "Tsy fetezana teo amin'ny client/listAllClient : :field" : "Nisy tsy fetezana nitranga teo amin'ny lisitry ny mpanjifa rehetra.",
        //client - update client 
        'client_updateClient' => DEBUG ? "Tsy fetezana teo amin'ny client/updateClient : :field" : "Nisy tsy fetezana nitranga teo amin'ny fanovana ny mombamomba ny mpanjifa.",
        //client - find by id
        'client_findById' => DEBUG ? "Tsy fetezana teo amin'ny client/findById : :field" : "Nisy tsy fetezana nitranga teo amin'ny fanamarinana ID-n'ny mpanjifa.",
        //client - delete all client
        'client_deleteAllClient' => DEBUG ? "Tsy fetezana teo amin'ny client/deleteAllClient : :field" : "Nisy tsy fetezana nitranga teo amin'ny famafana an'ireo mpanjifa.",
        //client - restore all client
        'client_restoreAllClient' => DEBUG ? "Tsy fetezana teo amin'ny client/restoreAllClient : :field" : "Nisy tsy fetezana nitranga teo amin'ny famerenana ny mpanjifa.",
    ],
    'not_found' => [
        'page' => "Tsy hita ny pejy nangatahina : :field",
        'action' => "Tsy hita ny hetsika nangatahina : :field",
        'class' => DEBUG ? "Tsy fetezana teo amin'ny fampdirana ny `classe`, tsy hita ny `classe` : :field" : "Nisy tsy fetezana nitranga.",
        'config' => "Tsy fetezana teo amin'ny fikirakirana : tsy voafaritra ny fanalahidy <b>:field</b>.",
        'json' => "Tsy fetezana teo amin'ny JSON : tsy misy ny fanalahidy <b>:field</b>."
    ]
];
