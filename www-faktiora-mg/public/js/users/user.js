document.addEventListener("DOMContentLoaded", () => {
  //====================== FUNCTIONS =====================

  //function - update account
  async function updateAccount(nom, prenoms, sexe, email, mdp) {
    try {
      const response = await apiRequest("/user/update_account", {
        method: "PUT",
        body: {
          nom_utilisateur: nom,
          prenoms_utilisateur: prenoms,
          sexe_utilisateur: sexe,
          email_utilisateur: email,
          mdp: mdp,
        },
      });

      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }
  //function - delete account
  async function deleteAccount(id) {
    try {
      const response = await apiRequest("/user/delete_account", {
        method: "DELETE",
      });
      console.log(response);
    } catch (Error) {
      console.log("Error : " + Error);
    }
  }
  //function - occup caisse
  async function occupCaisse(num_caisse) {
    try {
      const response = await apiRequest("/caisse/occup_caisse", {
        method: "POST",
        body: {
          num_caisse: num_caisse,
          id_utilisateur: "",
        },
      });
      console.log(response);
    } catch (error) {
      console.error(error);
    }
  }
  //
  //function - quit caisse
  async function quitCaisse() {
    try {
      const response = await apiRequest("/caisse/quit_caisse", {
        method: "PUT",
      });
      console.log(response);
    } catch (error) {
      console.error(error);
    }
  }

  //======================== EVENTS ====================

  const btnTest = document.getElementById("btn-test");
  btnTest.addEventListener("click", () => {
    quitCaisse();
  });
});
