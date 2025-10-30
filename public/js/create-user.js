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
  async function addUser(
    nomU,
    prenomsU,
    sexeU,
    roleU,
    emailU,
    mdpU,
    mdpConfirmU
  ) {
    try {
      const response = await apiRequest("/user/add_user", {
        method: "POST",
        body: {
          nom_utilisateur: nomU,
          prenoms_utilisateur: prenomsU,
          sexe_utilisateur: sexeU,
          role: roleU,
          email_utilisateur: emailU,
          mdp: mdpU,
          mdpConfirm: mdpConfirmU,
        },
      });
      console.log(response);
    } catch (Error) {
      console.log("Error : ".Error);
    }
  }
  //--------------------------------EVENTS-------------------
  //--btn-add-user
  const btnAddUser = document.getElementById("btn-add-user");
  btnAddUser.addEventListener("click", () => {
    addUser(
      "Anarana",
      "fanampipny",
      "féminin",
      "admin",
      "ema@a.b",
      "mdpsss",
      "mdpsss"
    );
  });
});
