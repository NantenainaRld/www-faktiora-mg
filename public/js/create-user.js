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


    //------------------ FUNCTION --------------------
    // function create_user
    function createUser(nomU, prenomsU,sexeU
        ,emailU,roleU,mdpU,mdpConfirmU){

        // AJAX create_user
         return fetch(apiUrl('/?c=user&a=add_user'),
            {
                method: 'POST',
                headers: {"Content-Type": "application/json"},
                body: JSON.stringify({
                    nom_utilisateur: nomU,
                    prenoms_utilisateur: prenomsU,
                    sexe_utilisateur: sexeU,
                    email_utilisateur: emailU,
                    role: roleU,
                    mdp: mdpU,
                    mdpConfirmU: mdpConfirmU
                })
            }
        )
        .then(response => {
            if(response.ok){
                return response.json();
            }
            else{
                console.log("AJAX no response : create_user");
            }
        })
        .then(data =>{
            return data;
        })
        .catch(error =>{
            console.log("Error ajax , create_user : " + error);
            return null;
        });   
    }

    //-----------------------EVENTS----------------------
    //**btn-add-user
    const btnAddUser = document.getElementById('btn-add-user');
    btnAddUser.addEventListener('click', ()=>{
        createUser("Nom test","prenoms test","masculin","mmm@.c","admin","mdp","mdp").then(x=> {
            console.log(x);
        });

    });
});