document.addEventListener("DOMContentLoaded", () => {
  //================ FUNCTION ================

  //function - create user by admin
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
  async function filterUser(
    status,
    role,
    sexe,
    order_by,
    arrange,
    date_by,
    per,
    num_caisse,
    from,
    to,
    month,
    year,
    search_user
  ) {
    try {
      const response = await apiRequest(
        `/user/filter_user?status=${status}&role=${role}&sexe=${sexe}&order_by=${order_by}&arrange=${arrange}&num_caisse=${num_caisse}&date_by=${date_by}&per=${per}&from=${from}&to=${to}&month=${month}&year=${year}&search_user=${search_user}`
      );
      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }
  //function - list caissier
  async function listCaissier() {
    try {
      const response = await apiRequest("/user/list_caissier", {});
      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }
  //function - account info
  async function accountInfo() {
    try {
      const response = await apiRequest("/user/account_info", {});
      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }
  //function - update user
  async function updateByAdmin(id, nom, prenoms, sexe, email, role, mdp) {
    try {
      const response = await apiRequest("/user/update_by_admin", {
        method: "PUT",
        body: {
          id_utilisateur: id,
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
        method: "PUT",
        body: {
          ids_user: ids,
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
          ids_user: ids,
        },
      });

      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }
  //function - deconnect all
  async function deconnectAll(ids_user = []) {
    try {
      const response = await apiRequest("/user/deconnect_all", {
        method: "PUT",
        body: {
          ids_user: ids_user,
        },
      });
      console.log(response);
    } catch (error) {
      console.error(error);
    }
  }
  //function - print all user
  async function printAllUser(status = "active") {
    try {
      const response = await apiRequest(
        `/user/print_all_user?status=${status}`
      );
      //not success
      if (response.message_type !== "success") {
        console.log(response);
      }
      //success
      else {
        const a = document.createElement("a");
        a.href = `data:application/pdf;base64,${response.pdf}`;
        a.download = response.file_name;
        a.click();
        console.log(response.message);
      }
    } catch (error) {
      console.error(error);
    }
  }

  //==================== EVENTS ===================
  const from = document.getElementById("from");
  const to = document.getElementById("to");

  //btn test
  const btnTest = document.getElementById("btn-test");
  btnTest.addEventListener("click", () => {
    // deconnectAll([10000, 10001, 10005, 10003]);
    // deconnectAll([1045000, 10004, 10005, 10003]);
    printAllUser();
  });
});
