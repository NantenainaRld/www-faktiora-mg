<?php

return [
    'invalids' => [
        'nom' => "Ny <b>anarana</b> dia tsy tokony mihoatra litera 100.",
        'prenoms' => "Ny <b>fanampin'anarana</b> dia tsy tokony mihoatra ny litera 100.",
        'sexe' => "Fananahana diso : <b>:field</b>.",
        'email' => "Adiresy mailaka tsy mety : <b>:field</b>.",
        'email_length' => "Ny adiresy <b>mailaka</b> tsy tokony mihoatra ny litera 150.",
        'role' => "Andraikitra tsy mety : <b>:field</b>.",
        'mdp' => "Ny <b>tenimiafina</b> dia tsy maintsy ahitana litera 6 farafahakeliny.",
        'mdp_confirm' => "Ny tenimiafina fanamafisana dia tokony hitovy amin'ny tenimiafina.",
        'from_to' => "Ny daty fanombohana dia tsy tokony mihoatra ny daty fiafarana.",
        'date' => "Daty tsy mety : <b>:field</b>.",
        'solde' => "Ny ambim-bola dia tsy maintsy ho isa desimaly lehibe na mitovy amin'ny aotra (0).",
        'seuil' => "Ny fetram-bola dia tsy maintsy ho isa desimaly mihoatra na mitovy amin'ny aotra (0).",
        'solde_seuil' => "Ny ambim-bola dia tsy tokony ho ambany noho ny fetram-bola.",
        'date_future' => "Ny daty dia tsy tokony ho amin'ny ho avy.",
        'date_fusion' => "Ny daty voafidy dia tsy manankery : mifandona izy ireo.",
        'libelle' => "Ny fanamarihana dia tsy tokony mihoatra litera 100.
        ",
        'montant' => "Ny vola dia tsy maintsy ho isa desimaly lehibe noho ny 0.",
        'prix' => "Ny vidiny dia tsy maintsy ho isa desimaly lehibe noho ny 0.",
        'nb_stock' => "Ny isan'ny tahiry dia tsy tokony ho latsaky ny 0.",
        'telephone' => "Laharan-telefaonina tsy manankery : <b>:field</b>.",
        'adresse' => "Ny adiresy dia tsy tokony mihoatra ny litera 100.",
        'correction_quantite' => "Habetsahana tsy mety ho an'ny andalana misy ny ID <b>:field</b> : ny habetsahana ho an'ny fanitsiana dia tsy tokony mihoatra ny habetsahana tany am-boalohany.",
        'mdp_incorrect' => "Diso ny tenimiafina.",
        'quantite' => "Ampidiro azafady ny habetsahana manankery.",
        //
        //user - ids_user empty
        'user_ids_user_empty' => "Misafidiana mpampiasa iray farafahakeliny.",
        //user - id user
        'user_id_user' => "Tsy manakery ny ID-n'ny mpampiasa : <b>:field</b>.",
        //user - deleted
        'user_deleted' => "Efa voafafa ny mpampiasa manana ny ID <b>:field</b>.",
        //caisse - num_caisse
        'caisse_num_caisse' => "Ny laharan'ny toeram-pandrotsaham-bola dia tsy maintsy isa bontolo lehibe na mitovy amin'ny 0.",
        //caisse - not selected
        'caisse_not_selected' => "Misafidiana toeram-pandrotsaham-bola iray azafady.",
        //caisse - nums caisse empty
        'caisse_nums_caisse_empty' => "Misafidiana toeram-pandrotsaham-bola iray farafahakeliny azafady.",
        //caisse - ids lc empty
        'caisse_ids_lc_empty' => "Misafidiana andalana iray farafakeliny ao amin'ny fitantanana ny toeram-pandrotsaham-bola azafady.",
        //caisse - id lc
        'caisse_id_lc' => "Ny laharan'ny andalana ao amin'ny fitantanana ny toeram-pandrotsaham-bola dia tsy maintsy isa bontolo lehibe na mitovy amin'ny 0.",
        //caisse - occuped
        'caisse_occuped' => "Ny toeram-pandrotsaham-bola laharana <b>:field</b> dia mbola misy mampiasa.",
        //caisse - deleted
        'caisse_deleted' => "Ny toeram-pandrotsaham-bola laharana <b>:field</b> dia efa voafafa.",
        //caisse - occupCaisse
        'caisse_occupCaisse' => "Ireo mpikajy vola ihany no afaka mampiasa ny toeram-pandrotsaham-bola.",
        //caisse - date_fin reopen
        'caisse_date_fin_reopen' => "Tsy afaka sokafana intsony ny andalana efa voafarana.",
        //caisse - line open / user id
        'caisse_line_open_user_id' => "Mbola misokatra ny andalana nosafidiana, mbola tsy afaka manova ny ID-n'ny mpampiasa ianao noho izany.",
        //entree - nums_ae empty
        'entree_nums_ae_empty' => "Misafidiana `vola miditra hafa` iray farafakeliny azafady.",
        //entree - correction autre entree
        'entree_correctionAutreEntree' => "Tsy voarakitra ao amin'ny toeram-pandrotsaham-bola misy anao ankehitriny ity `vola miditra hafa` ity.",
        //entree - ae deleted
        'entree_ae_deleted' => "Ny `vola miditra hafa` laharana <b>:field</b> dia efa voafafa.",
        //entree - quantite produit
        'entree_quantite_produit' => "Tsy manankery ny habetsahana ho an'ny vokatra manana ny ID <b>:id_produit</b> : :quantite_produit",
        //entree - nums_facture empty
        'entree_nums_facture_empty' => "Misafidiana faktiora iray farafahakeliny azafady.",
        //entree - correction facture
        'entree_correctionFacture' => "Ity faktiora ity dia tsy voarakitra ao amin'ny toeram-pandrotsaham-bola misy anao ankehitriny.",
        //entree - produits empty
        'entree_produits_empty' => "Mampidira vokatra iray farafahakeliny azafady.",
        //entree - facture id_lf
        'entree_facture_id_lf' => "Ny andalana faktiora manana ny ID <b>:id_lf</b> dia tsy an'ny faktiora laharana <b>:num_facture</b>.",
        //entree - facture lf id_produit
        'entree_facture_lf_id_produit' => "Ny vokatra manana ny ID <b>:id_produit</b> dia tsy an'ny andalana faktiora manana ny ID <b>:id_lf</b>.",
        //entree - facture deleted
        'entree_facture_deleted' => "Ny faktiora laharana <b>:field</b> dia efa voafafa.",
        //entree - facture not selected 
        'entree_facture_not_selected' => "Misafidiana faktiora iray azafady.",
        //entree - correction demande_sortie montant_ae
        'entree_correctionDemandeSortie_montant_ae' => "Ny totalin'ny vola fampihenana ho an'ity `vola mivoaka` ity dia mihoatra ny vola tany am-boalohany. Hamarino ny andalana fanitsiana ho an'ity `vola mivoaka` ity.",
        //produit - ids_produit empty
        'produit_ids_produit_empty' => "Misafidian vokatra iray farafahakeliny azafady.",
        //produit - libelle prix
        'produit_libelle_prix' => "Ny vokatra manana ny anarana <b>:libelle</b> sy vidiny <b>:prix</b> dia efa misy.",
        //produit - deleted
        'produit_deleted' => "Ny vokatra manana ny ID <b>:field</b> dia efa voafafa.",
        //produit - nb stock less
        'produit_nb_stock_less' => "Ny isan'ny tahiry dia tsy ampy ho an'ny vokatra manana ny ID <b>:id_produit</b>, tahiry sisa : <b>:nb_stock</b>.",
        //produit - not selected
        'produit_not_selected' => "Misafidiana vokatra iray azafady.",
        //article - libelle_article 
        'article_libelle_article' => "Ny fandaniana manana ny fanamarihana <b>:field</b> dia efa misy.",
        //article - ids_article empty
        'article_ids_article_empty' => "Misafidiana fandaniana iray farafahakeliny azafady.",
        //article - deleted
        'article_deleted' => "Ny fandaniana manana ny ID <b>:field</b> dia efa voafafa.",
        //article - not selected
        'article_not_selected' => "Misafidiana fandaniana iray azafady.",
        //sortie - prix article
        'sortie_prix_article' => "Tsy manankery ny vidiny ho an'ny fandaniana manana ny ID <b>:id_article</b> : :prix_article.",
        //sortie - quantite article
        'sortie_quantite_article' => "Tsy manankery ny habetsahana ho an'ny fandaniana manana ny ID <b>:id_article</b> : :quantite_article.",
        //sortie - reste_solde
        'sortie_reste_solde' => "Nolavina ny famoahana vola, ny fetram-bola <b>:field</b> tsy voahaja.",
        //sortie - nums ds empty
        'sortie_nums_ds_empty' => "Misafidiana `vola mivoaka` iray farafahakeliny azafady.",
        //sortie - montant ae
        'sortie_montant_ae' => "Tsy mety ny vola nampidirina : ny vola nampidirana dia mihoatra ny volan'ny `vola miditra hafa`.",
        //sortie - deleted
        'sortie_deleted' => "Ny `vola mivoaka` laharana <b>:field</b> dia efa voafafa.",
        //sortie - correction demande sortie
        'sortie_correctionDemandeSortie' => "Ity `vola mivoaka` ity dia tsy voarakitra ao amin'ny toeram-pandrotsaham-bola misy anao ankehitriny.",
        //sortie - articles empty
        'sortie_articles_empty' => "Mampidira fandaniana iray farafahakeliny azafady.",
        //sortie - correction facture
        'sortie_correctionFacture' => "Ity `vola mivoaka` ity dia tsy voarakitra ao amin'ny toeram-pandrotsaham-bola misy anao ankehitriny.",
        //sortie - not selected
        'sortie_not_selected' => "Misafidiana `vola mivoaka` iray azafady.",
        //sortie - not ds
        'sortie_not_ds' => "Ny `vola mivoaka` laharana <b>:field</b> dia tsy fangatahana famoahana vola ho an'ny fandaniana.",
        //sortie - correction autre_entree montant_ds
        'sortie_correctionAutreEntree_montant_ds' => "Ny totalin'ny vola fampihenana ho an'ity `vola miditra hafa` ity dia mihoatra ny vola tany am-boalohany. Hamarino ny andalana fanitsiana ho an'ity `vola miditra` ity.",
        //client - ids client empty
        'client_ids_client_empty' => "Misafidiana mpanjifa iray farafahakeliny azafady.",
        //client  - deleted
        'client_deleted' => "Ny mpanjifa manana ny ID <b>:field</b> dia efa voafafa.",
        //auth - login empty
        'auth_login_empty' => "Ampidiro azafady ny laharan'ny kaonty na mailaka.",
        //auth - password empty
        'auth_password_empty' => "Ampidiro azafady ny tenimiafina.",
    ],
    'empty' => [
        'nom' => "Tsy maintsy fenoina ny fampidirana ny <b>anarana</b>.",
        'email' => "Tsy maintsy fenoina ny fampidirana ny <b>mailaka</b>.",
        'mdp' => "Tsy maintsy fenoina ny fampidirana ny <b>tenimiafina</b>.",
        'mdp_confirm' => "Hamafiso ny tenimiafina.",
        'from_to' => "Ampidiro ny daty fanombohana na fiafarana, na samy izy roa.",
        'solde' => "Tsy maintsy fenoina ny fampidirana ny <b>ambim-bola</b>.",
        'user_id' => "Tsy maintsy fenoina ny fampidirina ny <b>ID-n'ny</b> mpampiasa.",
        'seuil' => "Tsy maintsy fenoina ny fampidirina ny <b>fetram-bola</b>.",
        'montant' => "Tsy maintsy fenoina ny fampidirana ny <b>vola</b>.",
        'from' => "Tsy maintsy fenoina ny fampifirana ny <b>daty fanombohana</b>.",
        'to' => "Tsy maintsy fenoina ny fampidirana ny <b>daty fiafarana</b>.",
        'libelle' => "Tsy maintsy fenoina ny fampidirana ny <b>teny fanamarihana</b>.",
        'date' => "Tsy maintsy fenoina ny fampidirana ny <b>daty</b>.",
        'prix' => "Tsy maintsy fenoina ny fampidirana ny <b>vidiny</b>.",
    ],
    'success' => [
        //user - create user
        'user_createUser' => "Kaonty voaforona soa aman-tsara.",
        //user - update user
        'user_update' => "Ny mombamomba ny mpampiasa manana ny ID <b>:field</b> dia voaova soa aman-tsara.",
        //user - delete account
        'user_deleteAccount' => "Kaonty voafafa soa aman-tsara.",
        //user - delete all 0
        'user_deleteAll_0' => "Tsy misy mpampiasa voafafa.",
        //user - delete all 1
        'user_deleteAll_1' => "Mpampiasa iray no voafafa soa aman-tsara.",
        //user - delete all plur
        'user_deleteAll_plur' => "Mpampiasa <b>:field</b> no voafafa soa aman-tsara.",
        //user - deconnect all 0
        'user_deconnectAll_0' => "Tsy misy mpampiasa navoaka.",
        //user - deconnect all 1
        'user_deconnectAll_1' => "Mpampiasa iray no navoaka soa aman-tsara.",
        //user - deconnect all plur
        'user_deconnectAll_plur' => "Mpampiasa <b>:field</b> no navoaka soa aman-tsara.",
        //user - signup
        'user_signup' => "Kaonty voaforona soa aman-tsara. Famerenana mankany amin'ny pejy fidirana.",
        //user - restore all user 0
        'user_restoreAllUser_0' => "Tsy misy mpampiasa naverina.",
        //user - restore all user 1
        'user_restoreAllUser_1' => "Mpampiasa iray no naverina soa aman-tsara.",
        //user - restore all user plur
        'user_restoreAllUser_plur' => "Mpampiasa <b>:field</b> no naverina soa aman-tsara.",
        //caisse - create caisse
        'caisse_createCaisse' => "Toeram-pandrotsaham-bola laharana <b>:field</b> voampiditra soa aman-tsara.",
        //caisse  - update caisse
        'caisse_updateCaisse' => "Ny mombamomba ny toeram-pandrotsaham-bola laharana <b>:field</b> dia voaova soa aman-tsara.",
        //caisse - delete all 0
        'caisse_deleteAll_0' => "Tsy misy toeram-pandrotsaham-bola voafafa.",
        //caisse - delete all 1
        'caisse_deleteAll_1' => "Toeram-pandrotsaham-bola iray iray no voafafa soa aman-tsara.",
        //caisse - delete all plur
        'caisse_deleteAll_plur' => "Toeram-pandrotsaham-bola <b>:field</b> no voafafa soa aman-tsara.",
        //caisse - delete all ligne caisse 0
        'caisse_deleteAllLigneCaisse_0' => "Tsy misy andalana fitantanana toeram-pandrotsaham-bola voafafa.",
        //caisse - delete all ligne caisse 1
        'caisse_deleteAllLigneCaisse_1' => "Andalana fitantanana toeram-pandrotsaham-bola iray no voafafa soa aman-tsara.",
        //caisse - delete all ligne caisse plur
        'caisse_deleteAllLigneCaisse_plur' => "Andalana fitantanana toeram-pandrotsaham-bola <b>:field</b> no voafafa soa aman-tsara.",
        //caisse - add ligne caisse
        'caisse_addLigneCaisse' => "Andalana fitantanana toeram-pandrotsaham-bola nampiana soa aman-tsara.",
        //caisse - occup caisse
        'caisse_occupCaisse' => "Toeram-pandrotsaham-bola laharana <b>:field</b> no nampiasaina soa aman-tsara.",
        //caisse - quit caisse
        'caisse_quitCaisse' => "Toeram-pandrotsaham-bola navela soa aman-tsara.",
        //caisse - free caisse 0
        'caisse_freeCaisse_0' => "Tsy misy toeram-pandrotsaham-bola navela.",
        //caisse - free caisse 1
        'caisse_freeCaisse_1' => "Vata fitehirizam-bola iray navela soa aman-tsara.",
        //caisse - free caisse plur
        'caisse_freeCaisse_plur' => "Toeram-pandrotsaham-bola <b>:field</b> no navela soa aman-tsara.",
        //caisse - update ligne caisse
        'caisse_updateLigneCaisse' => "Andalana fitantanana toeram-pandrotsaham-bola laharana <b>:field</b> novaina soa aman-tsara.",
        //caisse - restore all caisse 0
        'caisse_restoreAllCaisse_0' => "Tsy misy toeram-pandrotsaham-bola naverina.",
        //caisse - restore all caisse 1,
        'caisse_restoreAllCaisse_1' => "Toeram-pandrotsaham-bola iray no voaverina soa aman-tsara.",
        //caisse - restore all caisse plur
        'caisse_restoreAllCaisse_plur' => "Toeram-pandrotsaham-bola <b>:field</b> no voaverina soa aman-tsara.",
        //entree - add autre entree
        'entree_createAutreEntree' => "`Vola miditra hafa` tafiditra soa aman-tsara.",
        //entree - update autre entree
        'entree_updateAutreEntree' => "`Vola miditra hafa` laharana <b>:field</b> dia voaova soa aman-tsara.",
        //entree - delete all autre entree 0
        'entree_deleteAllAutreEntree_0' => "Tsy misy `vola miditra hafa` voafafa.",
        //entree - delete all autre entree 1
        'entree_deleteAllAutreEntree_1' => "`Vola miditra hafa` iray no voafafa soa aman-tsara.",
        //entree - delete all autre entree plur
        'entree_deleteAllAutreEntree_plur' => "`Vola miditra hafa` <b>:field</b> no voafafa soa aman-tsara.",
        //entree - create facture
        'entree_createFacture' => "Faktiora vaovao voaforona soa aman-tsara.",
        //entree - create ligne facture
        'entree_createLigneFacture' => "Andalana faktiora vaovao voaforona soa aman-tsara.",
        //entree - update facture
        'entree_updateFacture' => "Ny faktiora laharana <b>:field</b> dia voaova soa aman-tsara.",
        //entree - delete all facture 0
        'entree_deleteAllFacture_0' => "Tsy misy faktiora voafafa.",
        //entree - delete all facture 1
        'entree_deleteAllFacture_1' => "Faktiora iray no voafafa soa aman-tsara.",
        //entree - delete all facture plur
        'entree_deleteAllFacture_plur' => "Faktiora <b>:field</b> no voafafa soa aman-tsara.",
        //entree - restore all facture 0
        'entree_restoreAllFacture_0' => "Tsy misy faktiora voaverina.",
        //entree - restore all facture 1
        'entree_restoreAllFacture_1' => "Faktiora iray no voaverina soa aman-tsara.",
        //entree - restore all facture plur
        'entree_restoreAllFacture_plur' => "Faktiora <b>:field</b> no voaverina soa aman-tsara.",
        //entree - restore all entree 0
        'entree_restoreAllAutreEntree_0' => "Tsy misy `vola miditra hafa` voaverina.",
        //entree - restore all entree 1
        'entree_restoreAllAutreEntree_1' => "`Vola miditra hafa` iray no voaverina soa aman-tsara.",
        //entree - restore all entree plur
        'entree_restoreAllAutreEntree_plur' => "`Vola miditra hafa` <b>:field</b> no voaverina soa aman-tsara.",
        //produit - create produit
        'produit_createProduit' => "Vokatra vaovao tafiditra soa aman-tsara.",
        //produit - update produit
        'produit_updateProduit' => "Ny vokatra manana ny ID <b>:field</b> dia voaova soa aman-tsara.",
        //produit - delete all produit 0 
        'produit_deleteAllProduit_0' => "Tsy misy vokatra voafafa.",
        //produit - delete all produit 1
        'produit_deleteAllProduit_1' => "Vokatra iray no voafafa soa aman-tsara.",
        //produit - delete all produit plur
        'produit_deleteAllProduit_plur' => "Vokatra <b>:field</b> no voafafa soa aman-tsara.",
        //produit - restore all produit 0
        'produit_restoreAllProduit_0' => "Tsy misy vokatra voaverina.",
        //produit - restore all produit 1
        'produit_restoreAllProduit_1' => "Vokatra iray no voaverina soa aman-tsara.",
        //produit - restore all produit plur
        'produit_restoreAllProduit_plur' => "Vokatra <b>:field</b> no voaverina soa aman-tsara.",
        //article - create article
        'article_createArticle' => "Fandaniana vaovao tafiditra soa aman-tsara.",
        //article - update article
        'article_updateArticle' => "Ny fandaniana manana ny ID <b>:field</b> dia voaova soa aman-tsara.",
        //article - delete all article 0
        'article_deleteAllArticle_0' => "Tsy misy fandaniana voafafa.",
        //article - delete all article 1
        'article_deleteAllArticle_1' => "Fandaniana iray no voafafa soa aman-tsara.",
        //article - delete all article plur
        'article_deleteAllArticle_plur' => "Fandaniana <b>:field</b> no voafafa soa aman-tsara.",
        //article - restore all article 0
        'article_restoreAllArticle_0' => "Tsy misy fandaniana voaverina.",
        //article - restore all article 1
        'article_restoreAllArticle_1' => "Fandaniana iray no voaverina soa aman-tsara.",
        //article - restore all article plur
        'article_restoreAllArticle_plur' => "Fandaniana <b>:field</b> no voaverina soa aman-tsara.",
        //sortie - create ligne_ds
        'sortie_createLigneDs' => "Andalana amin'ny `vola mivoaka` vaovao tafiditra soa aman-tsara.",
        //sortie - create sortie
        'sortie_createSortie' => "`Vola mivoaka` vaovao tafiditra soa aman-tsara.",
        //sortie - update demande sortie 
        'sortie_updateDemandeSortie' => "`Vola mivoaka` laharana <b>:field</b> no voaova soa aman-tsara.",
        //sortie - delete all demande sortie 0
        'sortie_deleteAllDemandeSortie_0' => "Tsy misy `vola mivoaka` voafafa.",
        //sortie - delete all demande sortie 1
        'sortie_deleteAllDemandeSortie_1' => "`Vola mivoaka` iray no voafafa soa aman-tsara.",
        //sortie - delete all demande sortie plur
        'sortie_deleteAllDemandeSortie_plur' => "`Vola mivoaka` <b>:field</b> ,no voafafa soa aman-tsara.",
        //sortie - correction facture not mofidifed
        'sortie_correctionFacture_not_corrected' => "Tsy nisy fanitsiana natao ho an'ity faktiora ity.",
        //sortie - restore all demande sortie 0
        'sortie_restoreAllDemandeSortie_0' => "Tsy misy `vola mivoaka` voaverina.",
        //sortie - restore all demande sortie 1
        'sortie_restoreAllDemandeSortie_1' => "`Vola mivoaka` iray no voaverina soa aman-tsara.",
        //sortie - restore all demande sortie plur
        'sortie_restoreAllDemandeSortie_plur' => "`Vola mivoaka` <b>:field</b> no voaverina soa aman-tsara.",
        //client - create client
        'client_createClient' => "Mpanjifa vaovao tafiditra soa aman-tsara.",
        //client - update client
        'client_updateClient' => "Ny mombamomba ny mpanjifa manana ny ID <b>:field</b> dia voaova soa aman-tsara.",
        //client - delete all client 0
        'client_deleteAllClient_0' => "Tsy misy mpanjifa voafafa.",
        //client - delete all client 1
        'client_deleteAllClient_1' => "Mpanjifa iray no voafafa soa aman-tsara.",
        //client - delete all client plur
        'client_deleteAllClient_plur' => "Mpanjifa <b>:field</b> no voafafa soa aman-tsara.",
        //client - restore all client 0
        'client_restoreAllClient_0' => "Tsy misy mpanjifa voaverina.",
        //client - restore all client 1
        'client_restoreAllClient_1' => "Mpanjifa iray no voaverina soa aman-tsara.",
        //client - restore all client plur
        'client_restoreAllClient_plur' => "Mpanjifa <b>:field</b> no voaverina soa aman-tsara.",
        //print
        'print' => "PDF voaforona soa aman-tsara.",
    ],
    'not_found' => [
        //user - id
        'user_id' => "Tsy misy ny mpampiasa manana ny ID <b>:field</b>.",
        //user - caisse
        'user_caisse' => "Mbola tsy miasa amin'ny toeram-pandrotsaham-bola ianao izao.",
        //user - email
        'user_email' => "Tsy misy ny mpampiasa manana ny adiresy mailaka <b>:field</b>.",
        //caisse - num_caisse
        'caisse_num_caisse' => "Tsy misy ny toeram-pandrotsaham-bola laharana <b>:field</b>.",
        //caisse - id lc
        'caisse_id_lc' => "Tsy misy ny andalana fitantanana toeram-pandrotsaham-bola manana ny ID <b>:field</b>.",
        //entree - num_ae
        'entree_num_ae' => "Tsy misy ny `vola miditra hafa` laharana <b>:field</b>.",
        //entree - num_facture
        'entree_num_facture' => "Tsy misy ny faktiora laharana <b>:field</b>.",
        //entree - id lf
        'entree_id_lf' => "Tsy misy ny andalana faktiora manana ny ID <b>:field</b>.",
        //produit - id_produit
        'produit_id_produit' => "Tsy misy ny vokatra manana ny ID <b>:field</b>.",
        //article - id_article
        'article_id_article' => "Tsy misy ny fandaniana manana ny ID <b>:field</b>.",
        //sortie - num_ds
        'sortie_num_ds' => "Tsy misy ny `vola mivoaka` laharana <b>:field</b>.",
        //client - id_client
        'client_id_client' => "Tsy misy ny mpanjifa manana ny ID <b>:field</b>."
    ],
    'duplicate' => [
        //user - email
        'user_email' => "Misy efa nampiasa ny adiresy mailaka <b>:field</b>.",
        //caisse - num_caisse
        'caisse_num_caisse' => "Efa misy ny toeram-pandrotsaham-bola laharana <b>:field</b>.",
        //caisse - id_lc
        'caisse_id_lc' => "Efa misy ny andalana fitantanana toeram-pandrotsaham-bola manana ny ID <b>:field</b>.",
        //entree - num_ae
        'entree_num_ae' => "Efa misy ny `vola miditra hafa` laharana <b>:field</b>.",
    ]
];
