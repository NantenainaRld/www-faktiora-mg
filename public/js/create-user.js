// document.addEventListener('DOMContentLoaded', ()=>{
//     alert();
//     // input nom_utilisateur
//     const nomUtilisateur = document.getElementById('nom-utilisateur');
//     // input prenoms_utilisateur
//     const prenomsUtilisateur = document.getElementById('prenoms-utilisateur')
//     // input sexe_utilisateur
//     const sexeUtilisateur = document.getElementById('sexe-utilisateur');
//     // input role
//     const role = document.getElementsByName('role');
//     //input email_utilisateur
//     const emailUtilisateur = document.getElementById('email-utilisateur');
//     // input mdp
//     const mdp = document.getElementById('mdp')
//     // input mdp confitm
//     const mdpConfirm = document.getElementById('mdp-confirm');

//     // function create_user
//     function createUser(nomU, prenomsU,sexeU
//         ,emailU,roleU,mdpU){
//         let responseAjax = null;
        
//         // AJAX create_user
//         fetch("../index.php?route=user/create_controller",
//             {
//                 method: 'POST',
//                 headers: {"Content-Type": "application/json"},
//                 body: JSON.stringify({
//                     nom_utilisateur: nomU,
//                     prenoms_utilisateur: prenomsU,
//                     sexe_utilisateur: sexeU,
//                     email_utilisateur: emailU,
//                     role: roleU,
//                     mdp: mdpU
//                 })
//             }
//         )
//         .then(response => {
//             if(response.ok){
//                 return response.json();
//             }
//             else{
//                 console.log("AJAX no response : create_user");
//             }
//         })
//         .then(data =>{
//             responseAjax = data;
//         })
//         .catch(error =>{
//             console.log("Error ajax , create_user : " + error);
//         })

//         return responseAjax;
//     }

//     document.getElementById('btn').addEventListener('click', ()=> {
//         alert("ss");
//     });
//     // function alid_create_user
//     function validCreateUser(){
//         // const nomU = nomUtilisateur.value.trim();
//         alert("ddd");     
//     }
// });
alert();