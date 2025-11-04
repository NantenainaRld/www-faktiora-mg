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
        "/caisse/filter_caisse&search_caisse=5&by=nb_sorties&order_by=desc&from=&to=&per=none&month=none&year=none"
      );
      console.log(response);
    } catch (error) {
      console.error(error);
    }
  }

  //=================== EVENTS =================
  //btn-test
  const btnTest = document.getElementById("btn-test");
  btnTest.addEventListener("click", () => {
    addCaisse();
  });
});
