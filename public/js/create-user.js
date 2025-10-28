document.addEventListener('DOMContentLoaded', ()=>{
    // input nom_utilisateur
    const nomUtilisateur = document.getElementById('nom-utilisateur');
    // input prenoms_utilisateur
    const prenomsUtilisateur = document.getElementById('prenoms-utilisateur')
    // input sexe_utilisateur
    const sexeUtilisateur = document.getElementById('sexe-utilisateur');
    // input role
    const role = document.getElementsByName('role');
    //input email_utilisateur
    const emailUtilisateur = document.getElementById('email-utilisateur');
    // input mdp
    const mdp = document.getElementById('mdp')
    // input mdp confitm
    const mdpConfirm = document.getElementById('mdp-confirm');

    // function create_user
    function createUser(){
        // AJAX create_user
        fetch("")
    }
         // AJAX create_user
        // fetch("../public/index.php?route=user/create_controller", {
        //     method: "POST",
        //     headers: { "Content-Type": "application/json" },
        //     body: JSON.stringify({ 
        //         nom_utilisateur : 
        //     })
        // })
        //     .then(response => {
        //         if (response.ok) {
        //             return response.json();
        //         }
        //         else {
        //             console.log("AJAX no response / delete client");
        //         }
        //     })
        //     .then(data => {

        //         // //delete success
        //         if (data === "success") {

        //             //alert delete-client-success
        //             const alert = document.createElement('div');
        //             alert.classList.add("alert", "alert-success", "fade",
        //                 "show", "alert-dismissible", "mt-2");
        //             alert.setAttribute("role", "alert");
        //             alert.innerHTML = `
        //                          <i class='fas fa-check-circle me-2'></i>
        //                          Le client avec le numéro du compte 
        //                          <b>${hiddenNumCompte.value}</b>
        //                           a été supprimé avec succès .
        //                          <button type='button' class='btn btn-close'
        //                              data-bs-dismiss='alert'></button>
        //                      `;
        //             //insert alert
        //             const divTable = document.querySelector(".table-container");
        //             divTable.parentNode.insertBefore(alert, divTable);

        //             //hide modal
        //             modalDCB.hide();

        //             if (inputSearchClient.value.trim() === "") {
        //                 //#refresh listClientAll
        //                 listClientAll();
        //             }
        //             else {
        //                 //#refresh searchClient
        //                 searchClient();
        //             }
        //         }
        //         else {

        //             //alert delete-client-error
        //             const alert = document.createElement('div');
        //             alert.classList.add("alert", "alert-warning", "fade",
        //                 "show", "alert-dismissible", "my-2");
        //             alert.setAttribute("role", "alert");
        //             alert.innerHTML = `
        //                                 <i class='fas fa-warning me-2'></i>
        //                                 ${data}
        //                                 <button type='button' class='btn btn-close'
        //                                     data-bs-dismiss='alert'></button>
        //                             `;
        //             modalDC.querySelector(".modal-body").prepend(alert);
        //         }
        //     })
        //     .catch(error => {
        //         console.error("Erreur ajax delete client : " + error);
        //     });//AJAX delete client
        // fetch("../../../public/index.php?route=delete_client/controller", {
        //     method: "DELETE",
        //     headers: { "Content-Type": "application/json" },
        //     body: JSON.stringify({ numCompte: hiddenNumCompte.value.trim() })
        // })
        //     .then(response => {
        //         if (response.ok) {
        //             return response.json();
        //         }
        //         else {
        //             console.log("AJAX no response / delete client");
        //         }
        //     })
        //     .then(data => {

        //         // //delete success
        //         if (data === "success") {

        //             //alert delete-client-success
        //             const alert = document.createElement('div');
        //             alert.classList.add("alert", "alert-success", "fade",
        //                 "show", "alert-dismissible", "mt-2");
        //             alert.setAttribute("role", "alert");
        //             alert.innerHTML = `
        //                          <i class='fas fa-check-circle me-2'></i>
        //                          Le client avec le numéro du compte 
        //                          <b>${hiddenNumCompte.value}</b>
        //                           a été supprimé avec succès .
        //                          <button type='button' class='btn btn-close'
        //                              data-bs-dismiss='alert'></button>
        //                      `;
        //             //insert alert
        //             const divTable = document.querySelector(".table-container");
        //             divTable.parentNode.insertBefore(alert, divTable);

        //             //hide modal
        //             modalDCB.hide();

        //             if (inputSearchClient.value.trim() === "") {
        //                 //#refresh listClientAll
        //                 listClientAll();
        //             }
        //             else {
        //                 //#refresh searchClient
        //                 searchClient();
        //             }
        //         }
        //         else {

        //             //alert delete-client-error
        //             const alert = document.createElement('div');
        //             alert.classList.add("alert", "alert-warning", "fade",
        //                 "show", "alert-dismissible", "my-2");
        //             alert.setAttribute("role", "alert");
        //             alert.innerHTML = `
        //                                 <i class='fas fa-warning me-2'></i>
        //                                 ${data}
        //                                 <button type='button' class='btn btn-close'
        //                                     data-bs-dismiss='alert'></button>
        //                             `;
        //             modalDC.querySelector(".modal-body").prepend(alert);
        //         }
        //     })
        //     .catch(error => {
        //         console.error("Erreur ajax delete client : " + error);
        //     });
});