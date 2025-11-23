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
        'montant' => "Le montant doît être des nombres décimaux supérieurs ou égaux à 1",
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
        'entree_id_ae' => "L'ID de l'autre entrée ne doît pas dépasser de 20 caractères",
        //caisse - line open / num_caisse
        'caisse_line_open_num_caisse' => "La ligne est encore ouverte, vous ne pouvez pas modifier le numéro de caisse pour le moment",
        //entree - num_ae
        'entree_num_ae' => "Le numéro d'autre entrée ne doît pas dépasser de 20 caractères",
        'entree_id_ae' => "L'ID de l'autre entrée ne doît pas dépasser de 20 caractères",
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
        'from' => "La date de début est réquis",
        'libelle' => "Le champ libellé est réquis",
        'date' => "La date est réquise",
        'montant' => "Le montant est réquis"
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
        //caisse - create caisse
        'caisse_createCaisse' => "Caisse numéro <b>:field</b> crée avec succès",
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
    ],
    'not_found' => [
        //user - id
        'user_id' => "L'utilisateur avec l'ID <b>:field</b> n'existe pas",
        //user - caisse
        'user_caisse' => "Vous n'êtes pas encore sur une caisse",
        //caisse - num_caisse
        'caisse_num_caisse' => "Caisse numéro <b>:field</b> n'existe pas",
        //caisse - id lc
        'caisse_id_lc' => "La ligne caisse numéro <b>:field</b> n'existe pas",
    ],
    'duplicate' => [
        //user - email
        'user_email' => "L'adresse email <b>:field</b> existe déjà",
        //caisse - num_caisse
        'caisse_num_caisse' => "Le numéro de caisse <b>:field</b> existe déjà",
        //caisse - id_lc
        'caisse_id_lc' => "Le numéro de ligne caisse <b>:field</b> existe déjà"
    ]
];
