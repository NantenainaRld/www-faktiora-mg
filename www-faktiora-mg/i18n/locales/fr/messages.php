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
        //
        //user - ids_user empty
        'user_ids_user_empty' => "Veuiller séléctionner au moins un utilisateur",
        //caisse - num_caisse
        'caisse_num_caisse' => "Le numéro du caisse doît être des nombres entiers supérieurs ou égaux à 0",
        //caisse - not selected
        'caisse_not_selected' => "Veuiller séléctionner une caisse",
        //caisse - nums caisse empty
        'caisse_nums_caisse_empty' => "Veuiller séléctionner au moins une caisse",
        //caisse - ids lc empty
        'caisse_ids_lc_empty' => "Veuiller séléctionner au moins une ligne de caisse",
        'caisse_occupCaisse' => "Seule les caissiers peuvent occuper les caisses",
        'caisse_occupCaisse_deleted' => "La caisse choisie est déjà supprimée : <b>:field</b>",
        'caisse_occupCaisse_occuped' => "La caisse choisie est encore occupée : <b>:field</b>",
        'entree_id_ae' => "L'ID de l'autre entrée ne doît pas dépasser de 20 caractères",
        'libelle' => "Le libellé ne doît pas dépasser de 100 caractères",
        'montant' => "Le montant doît être des valeurs décimaux supérieurs ou égaux à 0",
        'user_caisse_not_found' => "Vous n'êtes pas encore sur une caisse"
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
        'libelle' => "Le champ libellé est réquis",
        'montant' => "Le champ <b>montant</b> est réquis"
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
        'caisse_occupCaisse' => "Caisse numéro <b>:field</b> occupée avec succès",
        'caisse_quitCaisse' => "Caisse libérée avec succès",
        'caisse_freeCaisse_0' => "Aucune caisse n'est libérée",
        'caisse_freeCaisse_1' => "Une caisse a été libérée avec succès",
        'caisse_freeCaisse_plur' => "<b>:field</b> caisses ont été libérées avec succès",
    ],
    'not_found' => [
        'user_id' => "L'utilisateur avec l'ID <b>:field</b> n'existe pas",
        //caisse - num_caisse
        'caisse_num_caisse' => "Caisse numéro <b>:field</b> n'existe pas"
    ],
    'duplicate' => [
        //user - email
        'user_email' => "L'adresse email <b>:field</b> existe déjà",
        //caisse - num_caisse
        'caisse_num_caisse' => "Le numéro de caisse <b>:field</b> existe déjà"
    ]
];
