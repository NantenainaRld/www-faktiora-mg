document.addEventListener("DOMContentLoaded", () => {
  //=================== FUNCTIONS =============

  //function - add caisse
  async function addCaisse() {
    try {
      const response = await apiRequest("/caisse/add_caisse", {
        method: "POST",
        body: {
          num_caisse: 5,
          solde: 150000,
          seuil: 10000,
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
    addCaisse();
  });
});
