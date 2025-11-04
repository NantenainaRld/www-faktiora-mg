document.addEventListener("DOMContentLoaded", () => {
  //=================== FUNCTIONS =============

  //function - add caisse
  async function addCaisse() {
    try {
      const response = await apiRequest("/caisse/add_caisse", {
        method: "POST",
        body: {
          num_caisse: 58,
          solde: 150000,
          seuil: 10000,
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
        "/caisse/filter_caisse&search_caisse=85&by=nb_sorties&order_by=desc&from=202&to=22&per=week&month=4&year=2026"
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
          num_caisse_update: 8,
          solde: 88,
          seuil: 88,
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
    updateCaisse();
  });
});
