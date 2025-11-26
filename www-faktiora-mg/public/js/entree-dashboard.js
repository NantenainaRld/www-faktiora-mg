document.addEventListener("DOMContentLoaded", () => {
  //=================== FUNCTIONS ======================

  //function - create autree entree
  async function createAutreEntree(
    libelle_ae,
    date_ae,
    montant_ae,
    id_utilisateur,
    num_caisse
  ) {
    try {
      const response = await apiRequest("/entree/create_autre_entree", {
        method: "POST",
        body: {
          libelle_ae: libelle_ae,
          date_ae: date_ae,
          montant_ae: montant_ae,
          id_utilisateur: id_utilisateur,
          num_caisse: num_caisse,
        },
      });
      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }
  //function - filter autre entree
  async function filterAutreEntree(
    status,
    num_caisse,
    id_user,
    order_by,
    arrange,
    from,
    to,
    search_ae
  ) {
    try {
      const response = await apiRequest(
        `/entree/filter_autre_entree?status=${status}&num_caisse=${num_caisse}&id_user=${id_user}&order_by=${order_by}&arrange=${arrange}&from=${from}&to=${to}&search_ae=${search_ae}`,
        {}
      );
      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }
  //function - list all autre entree
  async function listAllAutreEntree() {
    try {
      const response = await apiRequest(`/entree/list_all_autre_entree`);
      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }
  //function - update autre entree
  async function updateAutreEntree(
    num_ae,
    libelle_ae,
    date_ae,
    id_utilisateur,
    num_caisse
  ) {
    try {
      const response = await apiRequest("/entree/update_autre_entree", {
        method: "PUT",
        body: {
          num_ae: num_ae,
          libelle_ae: libelle_ae,
          date_ae: date_ae,
          id_utilisateur: id_utilisateur,
          num_caisse: num_caisse,
        },
      });
      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }
  //function - delete all autre entree
  async function deleteAllAutreEntree(nums_ae = []) {
    try {
      const response = await apiRequest("/entree/delete_all_autre_entree", {
        method: "PUT",
        body: {
          nums_ae: nums_ae,
        },
      });
      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }
  //function - permanent delete all autre entree
  async function permanentDeleteAllAutreEntree(nums_ae = []) {
    try {
      const response = await apiRequest(
        "/entree/permanent_delete_all_autre_entree",
        {
          method: "DELETE",
          body: {
            nums_ae: nums_ae,
          },
        }
      );
      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }
  //function - list connection autre entree
  async function listConnectionAutreEntree(num_ae) {
    try {
      const response = await apiRequest(
        `/entree/list_connection_autre_entree?num_ae=${num_ae}`
      );
      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }
  //function - correction autre entree
  async function correctionAutreEntree(
    num_ae,
    libelle_ae,
    date_ae,
    montant_ae,
    id_utilisateur,
    num_caisse
  ) {
    try {
      const response = await apiRequest("/entree/correction_autre_entree", {
        method: "POST",
        body: {
          num_ae: num_ae,
          libelle_ae: libelle_ae,
          date_ae: date_ae,
          montant_ae: montant_ae,
          id_utilisateur: id_utilisateur,
          num_caisse: num_caisse,
        },
      });
      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }

  //================== EVENTS===================
  const date = document.getElementById("date");
  const from = document.getElementById("from");
  const to = document.getElementById("to");

  //btn-test
  const btnTest = document.getElementById("btn-test");
  btnTest.addEventListener("click", () => {
    // updateAutreEntree("a202511-19", "ohhatra", date.value, "10004", "2");
    // deleteAllAutreEntree(["a202511-18", "a202511-19"]);
    // permanentDeleteAllAutreEntree(["a202511-18", "a202511-19"]);
    // listConnectionAutreEntree("a202511-17");
    // correctionAutreEntree(
    //   "a202511-17",
    //   "libelle",
    //   date.value,
    //   "122",
    //   "10004",
    //   "2"
    // );
  });
});
