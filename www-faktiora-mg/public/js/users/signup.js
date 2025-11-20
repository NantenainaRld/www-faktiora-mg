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

  //======================== FUNCTIONS ======================

  //function - signup
  async function signUp(nom, prenoms, sexe, email, mdp, mdp_confirm) {
    try {
      const response = await apiRequest("/user/signup", {
        method: "POST",
        body: {
          nom_utilisateur: nom,
          prenoms_utilisateur: prenoms,
          sexe_utilisateur: sexe,
          email_utilisateur: email,
          mdp: mdp,
          mdp_confirm: mdp_confirm,
        },
      });
      console.log(response);
    } catch (Error) {
      console.log("Error : " + Error);
    }
  }

  //======================== EVENTS =====================

  //--btn-add-user
  const btnAddUser = document.getElementById("btn-add-user");
  btnAddUser.addEventListener("click", () => {
    signUp(
      "Nantenaina   ",
      "Edouardo",
      "  masculin",
      "nantenain@faktiora.mg",
      "123456",
      "123456"
    );
  });
});
