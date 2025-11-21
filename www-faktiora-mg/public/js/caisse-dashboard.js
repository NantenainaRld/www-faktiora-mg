document.addEventListener("DOMContentLoaded", () => {
  //=================== FUNCTIONS =============

  //function - add caisse
  async function createCaisse(num_caisse, solde, seuil) {
    try {
      const response = await apiRequest("/caisse/create_caisse", {
        method: "POST",
        body: {
          num_caisse: num_caisse,
          solde: solde,
          seuil: seuil,
        },
      });
      console.log(response);
    } catch (error) {
      console.error(error);
    }
  }
  //function - filter caisse
  async function filterCaisse(
    status,
    order_by,
    arrange,
    date_by,
    per,
    from,
    to,
    month,
    year,
    search_caisse
  ) {
    try {
      const response = await apiRequest(
        `/caisse/filter_caisse?status=${status}&order_by=${order_by}&arrange=${arrange}&date_by=${date_by}&per=${per}&from=${from}&to=${to}&month=${month}&year=${year}&search_caisse=${search_caisse}`
      );
      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }
  //function - filter ligne_caisse
  async function filterLigneCaisse(num_caisse, fromLs, toLs, id_utilisateur) {
    try {
      const response = await apiRequest(
        `/caisse/filter_ligne_caisse?num_caisse=${num_caisse}&id_utilisateur=${id_utilisateur}&from=${fromLs}&to=${toLs}`
      );
      console.log(response);
    } catch (error) {
      console.error(error);
    }
  }
  //function - list free caisse
  async function filterFreeCaisse() {
    try {
      const response = await apiRequest("/caisse/list_free_caisse");
      console.log(response);
    } catch (error) {
      console.error(error);
    }
  }
  //function - update caisse
  async function updateCaisse(num_caisse, num_caisse_update, solde, seuil) {
    try {
      const response = await apiRequest("/caisse/update_caisse", {
        method: "PUT",
        body: {
          num_caisse: num_caisse,
          num_caisse_update: num_caisse_update,
          solde: solde,
          seuil: seuil,
        },
      });
      console.log(response);
    } catch (error) {
      console.error(error);
    }
  }
  //function - delete all caisse
  async function deleteAll(nums_caisse = []) {
    try {
      const response = await apiRequest("/caisse/delete_all", {
        method: "PUT",
        body: {
          nums_caisse: nums_caisse,
        },
      });
      console.log(response);
    } catch (error) {
      console.error(error);
    }
  }
  //function - permanent delete all caisse
  async function permanentDeleteAll(nums_caisse = []) {
    try {
      const response = await apiRequest("/caisse/permanent_delete_all", {
        method: "DELETE",
        body: {
          nums_caisse: nums_caisse,
        },
      });
      console.log(response);
    } catch (error) {
      console.error(error);
    }
  }
  //function - occup caisse
  async function occupCaisse(num_caisse, id_utilisateur = null) {
    try {
      const response = await apiRequest("/caisse/occup_caisse", {
        method: "POST",
        body: {
          num_caisse: num_caisse,
          id_utilisateur: id_utilisateur,
        },
      });
      console.log(response);
    } catch (error) {
      console.error(error);
    }
  }

  //function - free caisse
  async function freeCaisse(nums_caisse = []) {
    try {
      const response = await apiRequest("/caisse/free_caisse", {
        method: "PUT",
        body: {
          nums_caisse: nums_caisse,
        },
      });
      console.log(response);
    } catch (error) {
      console.error(error);
    }
  }

  //=================== EVENTS =================
  const from = document.getElementById("from");
  const to = document.getElementById("to");
  const fromLs = document.getElementById("from-ls");
  const toLs = document.getElementById("to-ls");

  //btn-test
  const btnTest = document.getElementById("btn-test");
  btnTest.addEventListener("click", () => {
    deleteAll([1, 3, 4]);
  });
});
