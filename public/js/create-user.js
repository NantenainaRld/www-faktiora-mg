document.addEventListener("DOMContentLoaded", () => {
  // input nom_utilisateur
  const nomUtilisateur = document.getElementById("nom-utilisateur");
  // input prenoms_utilisateur
  const prenomsUtilisateur = document.getElementById("prenoms-utilisateur");
  // input sexe_utilisateur
  const sexeUtilisateur = document.getElementById("sexe-utilisateur");
  // input role
  const role = document.getElementsByName("role");
  //input email_utilisateur
  const emailUtilisateur = document.getElementById("email-utilisateur");
  // input mdp
  const mdp = document.getElementById("mdp");
  // input mdp confitm
  const mdpConfirm = document.getElementById("mdp-confirm");

  //------------------ FUNCTION --------------------
  // function create_user
  function createUser(nomU, prenomsU, sexeU, emailU, roleU, mdpU, mdpConfirmU) {
    try {
    } catch (Error) {
      console.log("Error : ".Error);
    }
  }
});
