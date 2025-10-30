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

  //--------------------------------EVENTS-------------------
  //--btn-add-user
  const btnAddUser = document.getElementById("btn-add-user");
  btnAddUser.addEventListener("click", async () => {
    try {
      //==call api add user
      const responseAddUser = await apiRequest("/user/add_user", {
        method: "POST",
        body: {
          nom_utilisateur: "Anarana",
          prenoms_utilisateur: "fanampipny",
          sexe_utilisateur: "féminin",
          role: "admin",
          email_utilisateur: "test@a.b",
          mdp: "mdp123",
          mdpConfirm: "mdp123",
        },
      });
      console.log(responseAddUser);
    } catch (Error) {
      console.log("Error : " + Error);
    }
  });
});
