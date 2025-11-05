document.addEventListener("DOMContentLoaded", () => {
  //=================== FUNCTIONS =============

  //function - add caisse
  async function addCaisse() {
    try {
      const response = await apiRequest("/caisse/add_caisse", {
        method: "POST",
        body: {
          num_caisse: 2,
          solde: 150000,
          seuil: "10000    ",
        },
      });
      console.log(response);
    } catch (error) {
      console.error(error);
    }
  }
  //function - filter caisse
  async function filterCaisse() {
    try {
      const response = await apiRequest(
        "/caisse/filter_caisse&search_caisse=6&by=nb_sorties&order_by=desc&from=202&to=22&per=week&month=4&year=2026&type=!null"
      );
      console.log(response);
    } catch (error) {
      console.error(error);
    }
  }
  //function - update caisse
  async function updateCaisse() {
    try {
      const response = await apiRequest("/caisse/update_caisse", {
        method: "PUT",
        body: {
          num_caisse: 6,
          update_num_caisse: 9,
          solde: 588,
          seuil: 288,
          id_utilisateur: "U087962YH",
        },
      });
      console.log(response);
    } catch (error) {
      console.error(error);
    }
  }
  //function - update solde && seuil
  async function updateSoldeSeuil() {
    const nums = [];

    try {
      const response = await apiRequest("/caisse/update_solde_seuil", {
        method: "PUT",
        body: {
          solde: 500,
          seuil: 500,
          nums: nums,
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
  //function - delete caisse
  async function deleteCaisse() {
    const nums = [8, 9];
    try {
      const response = await apiRequest("/caisse/delete_caisse", {
        method: "DELETE",
        body: {
          nums: nums,
        },
      });
      console.log(response);
    } catch (error) {
      console.error(error);
    }
  }
  //function - affect caisse
  async function affectCaisse() {
    try {
      const response = await apiRequest("/caisse/affect_caisse", {
        method: "PUT",
        body: {
          num_caisse: 58,
          id_utilisateur: "U087962YH",
        },
      });
      console.log(response);
    } catch (error) {
      console.error(error);
    }
  }

  //=================== EVENTS =================
  //btn-test
  const btnTest = document.getElementById("btn-test");
  btnTest.addEventListener("click", () => {
    affectCaisse();
  });
});
