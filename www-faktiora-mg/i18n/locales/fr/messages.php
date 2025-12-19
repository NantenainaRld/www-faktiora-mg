<?php

return [
    'invalids' => [
        //
        'nom' => "Le <b>nom</b> d'utilisateur ne doit pas dépasser de 100 caractères",
        'prenoms' => "Le <b>prénoms</b> d'utilisateur ne doit pas dépasser de 100 caractères",
        'sexe' => "Sexe invalide : <b>:field</b>",
        'email' => "Adresse email invalide : <b>:field</b>",
        'email_length' => "L'adresse <b>email</b> ne doit pas dépasser de 150 caractères",
        'role' => "Rôle invalide : <b>:field</b>",
        'mdp' => "Le mot de passe doît être au moins 6 caractères",
        'mdp_confirm' => "Le mot de passe de confirmation doît être similaire au mot de passe",
        'from_to' => "La date de début ne doît pas dépasser la date fin",
        'date' => "Date invalide : <b>:field</b>",
        'solde' => "Le solde doît être des valeurs décimaux supérieures ou égaux à 0",
        'seuil' => "Le seuil doît être des valeurs décimaux supérieures ou égaux à 0",
        'solde_seuil' => "Le solde ne doît pas en dessous de seuil",
        'date_future' => "La date de doît pas être dans le future",
        'date_fusion' => "Les dates choisies ne sont pas valides (chevauchement des dates)",
        'libelle' => "Le libellé ne doit pas dépasser de 100 caractères",
        'montant' => "Le montant doît être des nombres décimaux supérieurs à 0",
        'prix' => "Le prix doît être des nombres décimaux supérieurs à 0",
        'nb_stock' => "Le nombre de stock ne doît pas être inférieur à 0",
        'telephone' => "Téléphone invalide : :field",
        'adresse' => "L'adresse ne doît pas dépasser de 100 caractères",
        'correction_quantite' => "Quantité invalide pour la ligne avec l'ID <b>:field</b> : la quantité de correction ne doît pas supérieure au quantité originale",
        'mdp_incorrect' => "Mot de passe incorrecte",
        //
        //user - ids_user empty
        'user_ids_user_empty' => "Veuiller séléctionner au moins un utilisateur",
        //user - id user
        'user_id_user' => "L'ID d'utilisateur invalide : <b>:field</b>",
        //user - deleted
        'user_deleted' => "L'utilisateur avec l'ID <b>:field</b> est déjà supprimé",
        //caisse - num_caisse
        'caisse_num_caisse' => "Le numéro de caisse doît être des nombres entiers supérieurs ou égaux à 0",
        //caisse - not selected
        'caisse_not_selected' => "Veuiller séléctionner une caisse",
        //caisse - nums caisse empty
        'caisse_nums_caisse_empty' => "Veuiller séléctionner au moins une caisse",
        //caisse - ids lc empty
        'caisse_ids_lc_empty' => "Veuiller séléctionner au moins une ligne de caisse",
        //caisse - id lc
        'caisse_id_lc' => "Le numéro de ligne caisse doît être des nombres entiers supérieurs ou égaux à 1",
        //caisse - occuped
        'caisse_occuped' => "La caisse numéro <b>:field</b> est encore occupéé",
        //caisse - deleted
        'caisse_deleted' => "La caisse numéro <b>:field</b> est déjà supprimée",
        //caisse - occupCaisse
        'caisse_occupCaisse' => "Seule les caissiers peuvent occuper les caisses",
        //caisse - date_fin reopen
        'caisse_date_fin_reopen' => "Une ligne fermée ne peut pas réouvrir",
        //caisse - line open / user id
        'caisse_line_open_user_id' => "La ligne est encore ouverte, vous ne pouvez pas modifier l'ID d'utilisateur pour le moment",
        //caisse - line open / num_caisse
        'caisse_line_open_num_caisse' => "La ligne est encore ouverte, vous ne pouvez pas modifier le numéro de caisse pour le moment",
        //entree - nums_ae empty
        'entree_nums_ae_empty' => "Veuiller séléctionner au moins une autre entrée",
        //entree - correction autre entree
        'entree_correctionAutreEntree' => "Cette autre entrée n'est pas enregistrée sur votre caisse actuelle",
        //entree - ae deleted
        'entree_ae_deleted' => "L'autre entrée numéro <b>:field</b> est déjà supprimée ",
        //entree - montant_ds
        'entree_montant_ds' => "Montant invalide : le montant saisi dépasse le montant total de ce sortie",
        //entree - quantite produit
        'entree_quantite_produit' => "La quantite est invalide pour le produit avec l'ID <b>:id_produit</b> : :quantite_produit",
        //entree - nums_facture empty
        'entree_nums_facture_empty' => "Veuiller séléctionner au moins une facture",
        //entree - produits empty
        'entree_produits_empty' => "Veuiller ajouter au moins un produit",
        //entree - facture id_lf
        'entree_facture_id_lf' => "La ligne facture avec l'ID <b>:id_lf</b> n'appartient pas au facture numéro <b>:num_facture</b>",
        //entree - facture lf id_produit
        'entree_facture_lf_id_produit' => "Le produit avec l'ID <b>:id_produit</b> n'appartient pas au ligne facture avec l'ID <b>:id_lf</b>",
        //entree - facture deleted
        'entree_facture_deleted' => "La facture numéro <b>:field</b> est déjà supprimée",
        //entree - facture not selected 
        'entree_facture_not_selected' => "Veuiller séléctionner une facture",
        //produit - ids_produit empty
        'produit_ids_produit_empty' => "Veuiller séléctionner au moins un produit",
        //produit - libelle prix
        'produit_libelle_prix' => "Le produit avec le libellé <b>:libelle</b> et prix <b>:prix</b> existe déjà",
        //produit - deleted
        'produit_deleted' => "Le produit avec l'ID <b>:field</b> est déjà supprimé",
        //produit - nb stock less
        'produit_nb_stock_less' => "Le nombre de stock n'est pas suffisant pour le produit avec l'ID <b>:id_produit</b>, stock reste : :nb_stock",
        //article - libelle_article 
        'article_libelle_article' => "L'article avec le libellé <b>:field</b> existe déjà",
        //article - ids_article empty
        'article_ids_article_empty' => "Veuiller séléctionner au moins un article",
        //article - deleted
        'article_deleted' => "L'article avec l'ID <b>:field</b> est déjà supprimé",
        //sortie - prix article
        'sortie_prix_article' => "Le prix est invalide pour l'article avec l'ID <b>:id_article</b> : :prix_article",
        //sortie - quantite article
        'sortie_quantite_article' => "La quantité est invalide pour l'article avec l'ID <b>:id_article</b> : :quantite_article",
        //sortie - reste_solde
        'sortie_reste_solde' => "Demande de sortie refusée, le seuil de <b>:field</b> n'est pas respecté",
        //sortie - nums ds empty
        'sortie_nums_ds_empty' => "Veuiller séléctionner au moins une sortie",
        //sortie - montant ae
        'sortie_montant_ae' => "Montant invalide: le montant saisi dépasse le montant d'autre entrée",
        //sortie - deleted
        'sortie_deleted' => "La sortie numéro <b>:field</b> est déjà supprimée",
        //sortie - correction demande sortie
        'sortie_correctionDemandeSortie' => "Cette sortie n'est pas enregistrée sur votre caisse actuelle",
        //sortie - articles empty
        'sortie_articles_empty' => "Veuiller ajouter au moins un produit",
        //sortie - correction facture
        'sortie_correctionFacture' => "Cette facture n'est pas enregistrée sur votre caisse actuelle",
        //sortie - not selected
        'sortie_not_selected' => "Veuiller séléctionner une caisse",
        //sortie - not ds
        'sortie_not_ds' => "La sortie numéro <b>:field</b> n'est pas une demande d'engagement de dépense",
        //client - ids client empty
        'client_ids_client_empty' => "Veuiller séléctionner au moins un client",
        //client  - deleted
        'client_deleted' => "Le client avec l'ID <b>:field</b> est déjà supprimé",
        //auth - login empty
        'auth_login_empty' => "Veuiller entrer votre numéro de compte ou email",
        //auth - password empty
        'auth_password_empty' => "Veuiller entrer votre mot de passe"
    ],
    'empty' => [
        'nom' => "Le champ <b>nom</b> est réquis",
        'email' => "Le champ <b>email</b> est réquis",
        'mdp' => "Le champ <b>mot de passe</b> est réquis",
        'mdp_confirm' => "Veuiller confirmer le mot de passe",
        'from_to' => "Veuiller entrer une date de debut ou fin, ou les deux",
        'solde' => "Le champ <b>solde</b> est réquis",
        'user_id' => "L'<b>ID</b> d'utilisateur est réquis",
        'seuil' => "Le champ <b>seuil</b> est réquis",
        'montant' => "Le champ <b>montant</b> est réquis",
        'from' => "La date début est réquise",
        'to' => "La date fin est réquise",
        'libelle' => "Le champ <b>libellé</b> est réquis",
        'date' => "La <b>date</b> est réquise",
        'prix' => "Le <b>prix</b> est réquis",
    ],
    'success' => [
        //user - create user
        'user_createUser' => "Compte crée avec succès",
        //user - update user
        'user_update' => "Les informations de l'utilisateur <b>:field</b> ont été modifiées avec succès",
        //user - delete account
        'user_deleteAccount' => "Compte supprimé avec succès",
        //user - delete all 0
        'user_deleteAll_0' => "Aucun utilisateur n'est supprimé",
        //user - delete all 1
        'user_deleteAll_1' => "Un utilisateur a été supprimé avec succès",
        //user - delete all plur
        'user_deleteAll_plur' => "<b>:field</b> utilisateurs ont été supprimés avec succès",
        //user - deconnect all 0
        'user_deconnectAll_0' => "Aucun utilisateur n'est déconnecté",
        //user - deconnect all 1
        'user_deconnectAll_1' => "Un utilisateur a été déconnectés avec succès",
        //user - deconnect all plur
        'user_deconnectAll_plur' => "<b>:field</b> utilisateurs ont été déconnecté avec succès",
        //user - signup
        'user_signup' => "Compte crée avec succès. Redirection vers la page de connexion",
        //caisse - create caisse
        'caisse_createCaisse' => "Caisse numéro <b>:field</b> ajoutée avec succès",
        //caisse - filter ligne caisse 0 
        'caisse_filterLigneCaisse_0' => "Aucune ligne caisse trouvée",
        //caisse - filter ligne caisse 1 
        'caisse_filterLigneCaisse_1' => "Une ligne caisse trouvée",
        //caisse - filter ligne caisse plur
        'caisse_filterLigneCaisse_plut' => "<b>:field</b> lignes caisse on été trouvées",
        //caisse  - update caisse
        'caisse_updateCaisse' => "Les informations de caisse numéro <b>:field</b> ont été modifiées avec succès",
        //caisse - delete all 0
        'caisse_deleteAll_0' => "Aucune caisse n'est supprimée",
        //caisse - delete all 1
        'caisse_deleteAll_1' => "Une caisse a été supprimée avec succès",
        //caisse - delete all plur
        'caisse_deleteAll_plur' => "<b>:field</b> caisses ont été supprimées avec succès",
        //caisse - delete all ligne caisse 0
        'caisse_deleteAllLigneCaisse_0' => "Aucune ligne caisse n'est supprimée",
        //caisse - delete all ligne caisse 1
        'caisse_deleteAllLigneCaisse_1' => "Une ligne caisse a été supprimée avec succès",
        //caisse - delete all ligne caisse plur
        'caisse_deleteAllLigneCaisse_plur' => "<b>:field</b> lignes caisse ont été supprimées avec succès",
        //caisse - add ligne caisse
        'caisse_addLigneCaisse' => "Ligne caisse ajoutée avec succès",
        //caisse - occup caisse
        'caisse_occupCaisse' => "Caisse numéro <b>:field</b> occupée avec succès",
        //caisse - quit caisse
        'caisse_quitCaisse' => "Caisse libérée avec succès",
        //caisse - free caisse 0
        'caisse_freeCaisse_0' => "Aucune caisse n'est libérée",
        //caisse - free caisse 1
        'caisse_freeCaisse_1' => "Une caisse a été libérée avec succès",
        //caisse - free caisse plur
        'caisse_freeCaisse_plur' => "<b>:field</b> caisses ont été libérées avec succès",
        //caisse - update ligne caisse
        'caisse_updateLigneCaisse' => "Ligne caisse numéro <b>:field</b> est modifiée avec succès",
        //entree - add autre entree
        'entree_createAutreEntree' => "Autre entrée ajoutée avec succès",
        //entree - update autre entree
        'entree_updateAutreEntree' => "Autre entrée numéro <b>:field</b> modifiée avec succès",
        //entree - delete all autre entree 0
        'entree_deleteAllAutreEntree_0' => "Aucune autre entrée n'est supprimée",
        //entree - delete all autre entree 1
        'entree_deleteAllAutreEntree_1' => "Une autre entrée a été supprimée avec succès",
        //entree - delete all autre entree plur
        'entree_deleteAllAutreEntree_plur' => "<b>:field</b> autres entrées ont été supprimées avec succès",
        //entree - create facture
        'entree_createFacture' => "Nouvelle facture générée avec succès",
        //entree - create ligne facture
        'entree_createLigneFacture' => "Nouvelle ligne facture créée avec succès",
        //entree - update facture
        'entree_updateFacture' => "La facture numéro <b>:field</b> est modifiée avec succès",
        //entree - delete all facture 0
        'entree_deleteAllFacture_0' => "Aucune facture n'est supprimée",
        //entree - delete all facture 1
        'entree_deleteAllFacture_1' => "Une facture est supprimée avec succès",
        //entree - delete all facture plur
        'entree_deleteAllFacture_plur' => "<b>:field</b> factures sont supprimées avec succès",
        //produit - create produit
        'produit_createProduit' => "Nouveau produit ajouté avec succès",
        //produit - update produit
        'produit_updateProduit' => "Produit avec l'ID <b>:field</b> a été modifié avec succès",
        //produit - delete all produit 0 
        'produit_deleteAllProduit_0' => "Aucun produit n'est supprimé",
        //produit - delete all produit 1
        'produit_deleteAllProduit_1' => "Un produit a été supprimé avec succès",
        //produit - delete all produit plur
        'produit_deleteAllProduit_plur' => "<b>:field</b> produits ont été supprimés avec succès",
        //article - create article
        'article_createArticle' => "Nouveau article ajouté avec succès",
        //article - update article
        'article_updateArticle' => "L'article avec l'ID <b>:field</b> a été modifié avec succès",
        //article - delete all article 0
        'article_deleteAllArticle_0' => "Aucun article n'est supprimé",
        //article - delete all article 1
        'article_deleteAllArticle_1' => "Un article a été supprimé avec succès",
        //article - delete all article plur
        'article_deleteAllArticle_plur' => "<b>:field</b> ont été supprimés avec succès",
        //sortie - create ligne_ds
        'sortie_createLigneDs' => "Nouvele ligne sortie ajoutée avec succès",
        //sortie - create sortie
        'sortie_createSortie' => "Nouvelle sortie ajoutée avec succès",
        //sortie - update demande sortie 
        'sortie_updateDemandeSortie' => "Sortie numéro <b>:field</b> est modifiée avec succès",
        //sortie - delete all demande sortie 0
        'sortie_deleteAllDemandeSortie_0' => "Aucune sortie n'est supprimée",
        //sortie - delete all demande sortie 1
        'sortie_deleteAllDemandeSortie_1' => "AUne sortie a été supprimée avec succès",
        //sortie - delete all demande sortie plur
        'sortie_deleteAllDemandeSortie_plur' => "<b>:field</b> sorties ont été supprimées avec succès",
        //sortie - correction facture not mofidifed
        'sortie_correctionFacture_not_corrected' => "Aucune correction n'est faite pour cette facture",
        //client - create client
        'client_createClient' => "Nouveau client ajouté avec succès",
        //client - update client
        'client_updateClient' => "Les informations du client avec l'ID <b>:field</b> ont été modifiées avec succès",
        //client - delete all client 0
        'client_deleteAllClient_0' => "Aucun client n'est supprimé",
        //client - delete all client 1
        'client_deleteAllClient_1' => "Un client a été supprimé avec succès",
        //client - delete all client plur
        'client_deleteAllClient_plur' => "<b>:field</b> on été supprimés avec succès",
        //print
        'print' => "PDF généré avec succès",
    ],
    'not_found' => [
        //user - id
        'user_id' => "L'utilisateur avec l'ID <b>:field</b> n'existe pas",
        //user - caisse
        'user_caisse' => "Vous n'êtes pas encore sur une caisse",
        //user - email
        'user_email' => "L'utilisateur avec l'adresse email <b>:field</b> n'existe pas",
        //caisse - num_caisse
        'caisse_num_caisse' => "Caisse numéro <b>:field</b> n'existe pas",
        //caisse - id lc
        'caisse_id_lc' => "La ligne caisse numéro <b>:field</b> n'existe pas",
        //entree - num_ae
        'entree_num_ae' => "L'autre entrée numéro <b>:field</b> n'existe pas",
        //entree - num_facture
        'entree_num_facture' => "La facture numéro <b>:field</b> n'existe pas ",
        //entree - id lf
        'entree_id_lf' => "La ligne facture avec l'ID <b>:field</b> n'existe pas",
        //produit - id_produit
        'produit_id_produit' => "Le produit avec l'ID <b>:field</b> n'existe pas",
        //article - id_article
        'article_id_article' => "L'article avec l'ID <b>:field</b> n'existe pas",
        //sortie - num_ds
        'sortie_num_ds' => "La sortie numéro <b>:field</b> n'existe pas",
        //client - id_client
        'client_id_client' => "Le client avec l'ID <b>:field</b> n'existe pas"
    ],
    'duplicate' => [
        //user - email
        'user_email' => "L'adresse email <b>:field</b> existe déjà",
        //caisse - num_caisse
        'caisse_num_caisse' => "La caisse numéro <b>:field</b> existe déjà",
        //caisse - id_lc
        'caisse_id_lc' => "Le numéro de ligne caisse <b>:field</b> existe déjà",
        //entree - num_ae
        'entree_num_ae' => "Le numéro d'autre entréé <b>:field</b> existe déjà",
    ]
];
