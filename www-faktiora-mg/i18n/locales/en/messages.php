<?php

return [
    'invalids' => [
        'nom' => "The <b>name</b> must not exceed 100 characters.",
        'prenoms' => "The <b>first names</b> must not exceed 100 characters.",
        'sexe' => "Invalid gender : <b>:field</b>.",
        'email' => "Invalid email address : <b>:field</b>.",
        'email_length' => "The <b>email</b> address must not exceed 150 characters.",
        'role' => "Invalid role : <b>:field</b>.",
        'mdp' => "<b>Password</b> must be at least 6 characters long.",
        'mdp_confirm' => "The confirmation password must match the password.",
        'from_to' => "The start date must not be later than the end date.",
        'date' => "Invalid date : <b>:field</b>.",
        'solde' => "The balance must be a decimal value greater than or equal to 0.",
        'seuil' => "The threshold must be a decimal value greater than or equal to 0.",
        'solde_seuil' => "The balance must not be below the threshold.",
        'date_future' => "The date must not be in the future.",
        'date_fusion' => "The selected dates are not valid : they overlap.",
        'libelle' => "The label must not exceed 100 characters.",
        'montant' => "The amount must be a decimal value greater than 0.",
        'prix' => "The price must be a decimal value greater than 0.",
        'nb_stock' => "The stock quantity must not be less than 0.",
        'telephone' => "Invalid phone number : <b>:field</b>.",
        'adresse' => "The address must not exceed 100 characters.",
        'correction_quantite' => "Invalid quantity for line with ID <b>:field</b>: correction quantity must not exceed the original quantity.",
        'mdp_incorrect' => "Incorrect password.",
        'quantite' => "Please enter a valid quantity.",
        //
        //user - ids_user empty
        'user_ids_user_empty' => "Please select at least one user.",
        //user - id user
        'user_id_user' => "Invalid user ID : <b>:field</b>.",
        //user - deleted
        'user_deleted' => "The user with ID <b>:field</b> has already been deleted.",
        //caisse - num_caisse
        'caisse_num_caisse' => "The cash register number must be an integer greater than or equal to 0.",
        //caisse - not selected
        'caisse_not_selected' => "Please select a cash register.",
        //caisse - nums caisse empty
        'caisse_nums_caisse_empty' => "Please select at least one cash register.",
        //caisse - ids lc empty
        'caisse_ids_lc_empty' => "Please select at least one cash register line.",
        //caisse - id lc
        'caisse_id_lc' => "The cash register line number must be an integer greater than or equal to 1.",
        //caisse - occuped
        'caisse_occuped' => "The cash register number <b>:field</b> is still occupied.",
        //caisse - deleted
        'caisse_deleted' => "The cash register number <b>:field</b> has already been deleted.",
        //caisse - occupCaisse
        'caisse_occupCaisse' => "Only cashiers can occupy cash registers.",
        //caisse - date_fin reopen
        'caisse_date_fin_reopen' => "A closed line cannot be reopened.",
        //caisse - line open / user id
        'caisse_line_open_user_id' => "The line is still open, you cannot modify the user ID at the moment.",
        //entree - nums_ae empty
        'entree_nums_ae_empty' => "Please select at least one other entry.",
        //entree - correction autre entree
        'entree_correctionAutreEntree' => "This other entry is not registered on your current cash register.",
        //entree - ae deleted
        'entree_ae_deleted' => "The other entry number <b>:field</b> has already been deleted.",
        //entree - quantite produit
        'entree_quantite_produit' => "Invalid quantity for product with ID <b>:id_produit</b>: :quantite_produit.",
        //entree - nums_facture empty
        'entree_nums_facture_empty' => "Please select at least one invoice.",
        //entree - correction facture
        'entree_correctionFacture' => "This invoice is not registered on your current cash register.",
        //entree - produits empty
        'entree_produits_empty' => "Please add at least one product.",
        //entree - facture id_lf
        'entree_facture_id_lf' => "The invoice line with ID <b>:id_lf</b> does not belong to invoice number <b>:num_facture</b>.",
        //entree - facture lf id_produit
        'entree_facture_lf_id_produit' => "The product with ID <b>:id_produit</b> does not belong to invoice line with ID <b>:id_lf</b>.",
        //entree - facture deleted
        'entree_facture_deleted' => "THe invoice number <b>:field</b> has already been deleted.",
        //entree - facture not selected 
        'entree_facture_not_selected' => "Please select an invoice.",
        //entree - correction demande_sortie montant_ae
        'entree_correctionDemandeSortie_montant_ae' => "The total reduction amounts for this outcome exceed the original amount. Please check the correction lines for this outcome.",
        //produit - ids_produit empty
        'produit_ids_produit_empty' => "Please select at least one product.",
        //produit - libelle prix
        'produit_libelle_prix' => "The product with label <b>:libelle</b> and price <b>:prix</b> already exists.",
        //produit - deleted
        'produit_deleted' => "The product with ID <b>:field</b> has already een deleted.",
        //produit - nb stock less
        'produit_nb_stock_less' => "Insufficient stock quantity for the product with ID <b>:id_produit</b>, remaining stock : <b>:nb_stock</b>.",
        //produit - not selected
        'produit_not_selected' => "Please select a product.",
        //article - libelle_article 
        'article_libelle_article' => "THe item with label <b>:field</b> already exists.",
        //article - ids_article empty
        'article_ids_article_empty' => "Please select at least one item.",
        //article - deleted
        'article_deleted' => "The item with ID <b>:field</b> has already been deleted.",
        //article - not selected
        'article_not_selected' => "Please select an item.",
        //sortie - prix article
        'sortie_prix_article' => "Invalid price for the item with ID <b>:id_article</b>: :prix_article.",
        //sortie - quantite article
        'sortie_quantite_article' => "Invalid quantity for the item with ID <b>:id_article</b>: :quantite_article.",
        //sortie - reste_solde
        'sortie_reste_solde' => "Cash outflow request denied, threshold of <b>:field</b> is not respected.",
        //sortie - nums ds empty
        'sortie_nums_ds_empty' => "Please select at least one cash outflow.",
        //sortie - montant ae
        'sortie_montant_ae' => "Invalid amount : entered amount exceeds the other entry amount.",
        //sortie - deleted
        'sortie_deleted' => "Cash outflow number <b>:field</b> has already been deleted.",
        //sortie - correction demande sortie
        'sortie_correctionDemandeSortie' => "This cash outflow is not refistered on your current cash register.",
        //sortie - articles empty
        'sortie_articles_empty' => "Please add at least one item.",
        //sortie - correction facture
        'sortie_correctionFacture' => "This invoice is not registered on your current cash register.",
        //sortie - not selected
        'sortie_not_selected' => "Please select a cash outflow.",
        //sortie - not ds
        'sortie_not_ds' => "The cash outflow number <b>:field</b> is not an expenditure commitment request.",
        //sortie - correction autre_entree montant_ds
        'sortie_correctionAutreEntree_montant_ds' => "The total reduction amounts for this other entry exceed the original amount. Please check the correction lines for this other entry.",
        //client - ids client empty
        'client_ids_client_empty' => "Please select at least one customer.",
        //client  - deleted
        'client_deleted' => "The customer with ID <b>:field</b> has already been deleted.",
        //auth - login empty
        'auth_login_empty' => "Please enter your account number or email.",
        //auth - password empty
        'auth_password_empty' => "Please enter your password.",
    ],
    'empty' => [
        'nom' => "The <b>last name</b> field is required.",
        'email' => "The <b>email</b> field is required",
        'mdp' => "The <b>password</b> field is required",
        'mdp_confirm' => "Please confirm the password",
        'from_to' => "Please enter a start or end date, or both",
        'solde' => "The <b>balance</b> field is required",
        'user_id' => "User <b>ID</b> is required",
        'seuil' => "The <b>threshold</b> field is required",
        'montant' => "The <b>amount</b> field is required",
        'from' => "Start date is required",
        'to' => "End date is required",
        'libelle' => "The <b>label</b> field is required",
        'date' => "The <b>date</b> is required",
        'prix' => "The <b>price</b> is required",
    ],
    'success' => [
        //user - create user
        'user_createUser' => "Account created successfully.",
        //user - update user
        'user_update' => "The informations for user with ID <b>:field</b> has been modified successfully.",
        //user - delete account
        'user_deleteAccount' => "Account deleted successfully.",
        //user - delete all 0
        'user_deleteAll_0' => "No users has been deleted.",
        //user - delete all 1
        'user_deleteAll_1' => "One user has been deleted successfully.",
        //user - delete all plur
        'user_deleteAll_plur' => "<b>:field</b> users has been deleted successfully.",
        'user_deconnectAll_0' => "No users has been disconnected.",
        //user - deconnect all 1
        'user_deconnectAll_1' => "One user has been disconnected successfully.",
        //user - deconnect all plur
        'user_deconnectAll_plur' => "<b>:field</b> users has been disconnected successfully.",
        //user - signup
        'user_signup' => "Account created successfully. Redirecting to login page.",
        //user - restore all user 0
        'user_restoreAllUser_0' => "No users has been restored.",
        //user - restore all user 1
        'user_restoreAllUser_1' => "One user has been restored successfully.",
        //user - restore all user plur
        'user_restoreAllUser_plur' => "<b>:field</b> users has been restored successfully.",
        //caisse - create caisse
        'caisse_createCaisse' => "Cash register number <b>:field</b> added successfully.",
        //caisse  - update caisse
        'caisse_updateCaisse' => "The informations for the cash register number <b>:field</b> has been modified successfully.",
        //caisse - delete all 0
        'caisse_deleteAll_0' => "No cash registers has been deleted.",
        //caisse - delete all 1
        'caisse_deleteAll_1' => "One cash register has been deleted successfully.",
        //caisse - delete all plur
        'caisse_deleteAll_plur' => "<b>:field</b> cash registers has been deleted successfully.",
        //caisse - delete all ligne caisse 0
        'caisse_deleteAllLigneCaisse_0' => "No cash register lines has been deleted.",
        //caisse - delete all ligne caisse 1
        'caisse_deleteAllLigneCaisse_1' => "One cash register line has been deleted successfully.",
        //caisse - delete all ligne caisse plur
        'caisse_deleteAllLigneCaisse_plur' => "<b>:field</b> cash register lines has been deleted successfully.",
        //caisse - add ligne caisse
        'caisse_addLigneCaisse' => "Cash register line added successfully.",
        //caisse - occup caisse
        'caisse_occupCaisse' => "Cash register number <b>:field</b> has been occupied successfully.",
        //caisse - quit caisse
        'caisse_quitCaisse' => "Cash register released successfully.",
        //caisse - free caisse 0
        'caisse_freeCaisse_0' => "No cash registers has been released.",
        //caisse - free caisse 1
        'caisse_freeCaisse_1' => "One cash register has been released successfully.",
        //caisse - free caisse plur
        'caisse_freeCaisse_plur' => "<b>:field</b> cash registers has been released successfully.",
        //caisse - update ligne caisse
        'caisse_updateLigneCaisse' => "Cash register line number <b>:field</b> has been modified successfully.",
        //caisse - restore all caisse 0
        'caisse_restoreAllCaisse_0' => "No cash registers has been restored.",
        //caisse - restore all caisse 1,
        'caisse_restoreAllCaisse_1' => "One cash register has been restored successfully.",
        //caisse - restore all caisse plur
        'caisse_restoreAllCaisse_plur' => "<b>:field</b> cash registers has been restored successfully.",
        //entree - add autre entree
        'entree_createAutreEntree' => "Other entry added successfully.",
        //entree - update autre entree
        'entree_updateAutreEntree' => "Other entry number <b>:field</b> has been modified successfully.",
        //entree - delete all autre entree 0
        'entree_deleteAllAutreEntree_0' => "No other entry has been deleted.",
        //entree - delete all autre entree 1
        'entree_deleteAllAutreEntree_1' => "One other entry has been deleted successfully.",
        //entree - delete all autre entree plur
        'entree_deleteAllAutreEntree_plur' => "<b>:field</b> other entries has been deleted successfully.",
        //entree - create facture
        'entree_createFacture' => "New invoice has been generated successfully.",
        //entree - create ligne facture
        'entree_createLigneFacture' => "New invoice line has been created successfully.",
        //entree - update facture
        'entree_updateFacture' => "The invoice number <b>:field</b> has been modified successfully.",
        //entree - delete all facture 0
        'entree_deleteAllFacture_0' => "No invoices has been deleted.",
        //entree - delete all facture 1
        'entree_deleteAllFacture_1' => "One invoice has been deleted successfully.",
        //entree - delete all facture plur
        'entree_deleteAllFacture_plur' => "<b>:field</b> invoices has been deleted successfully.",
        //entree - restore all facture 0
        'entree_restoreAllFacture_0' => "No invoices has been restored.",
        //entree - restore all facture 1
        'entree_restoreAllFacture_1' => "One invoice has been restored successfully.",
        //entree - restore all facture plur
        'entree_restoreAllFacture_plur' => "<b>:field</b> invoices has been restored successfully.",
        //entree - restore all entree 0
        'entree_restoreAllAutreEntree_0' => "No other entries has been restored.",
        //entree - restore all entree 1
        'entree_restoreAllAutreEntree_1' => "One other entry has been restored successfully.",
        //entree - restore all entree plur
        'entree_restoreAllAutreEntree_plur' => "<b>:field</b> other entries has been restored successfully.",
        //produit - create produit
        'produit_createProduit' => "New product added successfully.",
        //produit - update produit
        'produit_updateProduit' => "The product with ID <b>:field</b> has been modified successfully.",
        //produit - delete all produit 0 
        'produit_deleteAllProduit_0' => "No products has been deleted.",
        //produit - delete all produit 1
        'produit_deleteAllProduit_1' => "One product has been deleted successfully.",
        //produit - delete all produit plur
        'produit_deleteAllProduit_plur' => "<b>:field</b> products has been deleted successfully.",
        //produit - restore all produit 0
        'produit_restoreAllProduit_0' => "No products has been restored.",
        //produit - restore all produit 1
        'produit_restoreAllProduit_1' => "One product has been restored successfully.",
        //produit - restore all produit plur
        'produit_restoreAllProduit_plur' => "<b>:field</b> products has been restored successfully.",
        //article - create article
        'article_createArticle' => "New item added successfully.",
        //article - update article
        'article_updateArticle' => "The item with ID <b>:field</b> has been modified successfully.",
        //article - delete all article 0
        'article_deleteAllArticle_0' => "No items has been deleted.",
        //article - delete all article 1
        'article_deleteAllArticle_1' => "One item has been deleted successfully.",
        //article - delete all article plur
        'article_deleteAllArticle_plur' => "<b>:field</b> items has been deleted successfully.",
        //article - restore all article 0
        'article_restoreAllArticle_0' => "No items has been restored.",
        //article - restore all article 1
        'article_restoreAllArticle_1' => "One item has been restored successfully.",
        //article - restore all article plur
        'article_restoreAllArticle_plur' => "<b>:field</b> items has been restored successfully.",
        //sortie - create ligne_ds
        'sortie_createLigneDs' => "New cash outflow line added successfully.",
        //sortie - create sortie
        'sortie_createSortie' => "New cash outflow added successfully.",
        //sortie - update demande sortie 
        'sortie_updateDemandeSortie' => "The cash outflow number <b>:field</b> has been updated successfully.",
        //sortie - delete all demande sortie 0
        'sortie_deleteAllDemandeSortie_0' => "No cash outflow has been deleted.",
        //sortie - delete all demande sortie 1
        'sortie_deleteAllDemandeSortie_1' => "One cash outflow has been deleted successfully.",
        //sortie - delete all demande sortie plur
        'sortie_deleteAllDemandeSortie_plur' => "<b>:field</b> cash outflows has been deleted successfully.",
        //sortie - correction facture not mofidifed
        'sortie_correctionFacture_not_corrected' => "No corrections has been made for this invoice.",
        //sortie - restore all demande sortie 0
        'sortie_restoreAllDemandeSortie_0' => "No cash outflow has been restored.",
        //sortie - restore all demande sortie 1
        'sortie_restoreAllDemandeSortie_1' => "One ash outflow has been restored successfully.",
        //sortie - restore all demande sortie plur
        'sortie_restoreAllDemandeSortie_plur' => "<b>:field</b> cash outflow has been restored successfully.",
        //client - create client
        'client_createClient' => "New customer added successfully.",
        //client - update client
        'client_updateClient' => "The informations for client with ID <b>:field</b> has been modified successfully.",
        //client - delete all client 0
        'client_deleteAllClient_0' => "No clients has been deleted.",
        //client - delete all client 1
        'client_deleteAllClient_1' => "One customer has been deleted successfully.",
        //client - delete all client plur
        'client_deleteAllClient_plur' => "<b>:field</b> customers has been deleted successfully.",
        //client - restore all client 0
        'client_restoreAllClient_0' => "No clients has been restored.",
        //client - restore all client 1
        'client_restoreAllClient_1' => "One customer has been restored successfully.",
        //client - restore all client plur
        'client_restoreAllClient_plur' => "<b>:field</b> customers has been restored successfully.",
        //print
        'print' => "PDF generated successfully.",
    ],
    'not_found' => [
        //user - id
        'user_id' => "User with ID <b>:field</b> does not exist.",
        //user - caisse
        'user_caisse' => "You are not yet on a cash register.",
        //user - email
        'user_email' => "The user with email address <b>:field</b> does not exist.",
        //caisse - num_caisse
        'caisse_num_caisse' => "The cash register number <b>:field</b> does not exist.",
        //caisse - id lc
        'caisse_id_lc' => "Cash register line with ID <b>:field</b> does not exist.",
        //entree - num_ae
        'entree_num_ae' => "THe other entry number <b>:field</b> does not exist.",
        //entree - num_facture
        'entree_num_facture' => "The invoice number <b>:field</b> does not exist.",
        //entree - id lf
        'entree_id_lf' => "The invoice line with ID <b>:field</b> does not exist.",
        //produit - id_produit
        'produit_id_produit' => "The product with ID <b>:field</b> does not exist.",
        //article - id_article
        'article_id_article' => "The item with ID <b>:field</b> does not exist.",
        //sortie - num_ds
        'sortie_num_ds' => "The cash outflow number <b>:field</b> does not exist.",
        //client - id_client
        'client_id_client' => "The customer with ID <b>:field</b> does not exist."
    ],
    'duplicate' => [
        //user - email
        'user_email' => "The email address <b>:field</b> already exists.",
        //caisse - num_caisse
        'caisse_num_caisse' => "The cash register number <b>:field</b> already exists.",
        //caisse - id_lc
        'caisse_id_lc' => "The cash register line with ID <b>:field</b> already exists.",
        //entree - num_ae
        'entree_num_ae' => "THe other entry number <b>:field</b> already exists.",
    ]
];
