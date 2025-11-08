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

  //function - create user
  async function createUser(nom, prenoms, sexe, email, role, mdp, mdp_confirm) {
    try {
      const response = await apiRequest("/user/create_user", {
        method: "POST",
        body: {
          nom_utilisateur: nom,
          prenoms_utilisateur: prenoms,
          sexe_utilisateur: sexe,
          email_utilisateur: email,
          role: role,
          mdp: mdp,
          mdp_confirm: mdp_confirm,
        },
      });
      console.log(response);
    } catch (Error) {
      console.log("Error : " + Error);
    }
  }

  //--------------------------------EVENTS-------------------
  //--btn-add-user
  const btnAddUser = document.getElementById("btn-add-user");
  btnAddUser.addEventListener("click", () => {
    //call api - create user
    createUser(
      "s   ",
      "",
      "  masculin",
      "test@faktiora.mg",
      "adminss",
      "123456",
      "123456"
    );
  });
});
