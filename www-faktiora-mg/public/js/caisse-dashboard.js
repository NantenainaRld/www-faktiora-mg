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
  //function- filter caisse
  async function filterCaisse(from, to) {
    try {
      const response = await apiRequest(
        `/caisse/filter_caisse?status=all&order_by=nb_transactions&arrange=DESC&date_by=month_year&per=week&from=${from}&to=${to}&month=11&year=2025&search_caisse=`
      );
      console.log(response);
    } catch (error) {
      console.error(error);
    }
  }
  //function - filter ligne_caisse
  async function filterLigneCaisse(fromLs, toLs) {
    try {
      const response = await apiRequest(
        `/caisse/filter_ligne_caisse?num_caisse=2&id_utilisateur=&from=${fromLs}&to=${toLs}`
      );
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

  //function - free caisse (remove user)
  async function freeCaisse() {
    // const nums = [58, 8];
    const nums = [8, 9];

    try {
      const response = await apiRequest("/caisse/free_caisse", {
        method: "PUT",
        body: {
          nums: nums,
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
    occupCaisse(3, "U123278VP");
  });
});
