document.addEventListener("DOMContentLoaded", () => {
  //================ FUNCTION ================

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
  //function - filter user
  async function filterUser(from, to) {
    try {
      const response = await apiRequest(
        `/user/filter_user?status=all&role=all&sexe=all&order_by=nb_transactions&arrange=DESC&num_caisse=all&date_by=month_year&per=week&from=${from}&to=${to}&month=11&year=2015&search_user=`
      );
      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }
  //function - update user
  async function updateByAdmin(
    id,
    id_update,
    nom,
    prenoms,
    sexe,
    email,
    role,
    mdp
  ) {
    try {
      const response = await apiRequest("/user/update_by_admin", {
        method: "PUT",
        body: {
          id_utilisateur: id,
          id_update: id_update,
          nom_utilisateur: nom,
          prenoms_utilisateur: prenoms,
          sexe_utilisateur: sexe,
          email_utilisateur: email,
          role: role,
          mdp: mdp,
        },
      });

      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }
  //function - update user
  async function updateByUser(id, nom, prenoms, sexe, email, mdp) {
    try {
      const response = await apiRequest("/user/update_by_user", {
        method: "PUT",
        body: {
          id_utilisateur: id,
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
  //function - delete all
  async function deleteAll(ids = []) {
    try {
      const response = await apiRequest("/user/delete_all", {
        method: "DELETE",
        body: {
          id_users: ids,
        },
      });

      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }
  //function - permanent delete all
  async function permanentDeleteAll(ids = []) {
    try {
      const response = await apiRequest("/user/permanent_delete_all", {
        method: "DELETE",
        body: {
          id_users: ids,
        },
      });

      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }

  //==================== EVENTS ===================
  const from = document.getElementById("from");
  const to = document.getElementById("to");

  //btn test
  const btnTest = document.getElementById("btn-test");
  btnTest.addEventListener("click", () => {
    permanentDeleteAll(["U123278VWb", "U123278VW", "U123278VWs"]);
  });
});
