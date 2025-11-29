document.addEventListener("DOMContentLoaded", () => {
  //=================== FUNCTIONS ======================

  //function - create client
  async function createClient(
    nom_client,
    prenoms_client,
    sexe_client,
    telephone,
    adresse
  ) {
    try {
      const response = await apiRequest("/client/create_client", {
        method: "POST",
        body: {
          nom_client: nom_client,
          prenoms_client: prenoms_client,
          sexe_client: sexe_client,
          telephone: telephone,
          adresse: adresse,
        },
      });
      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }

  //================== EVENTS===================

  //btn-test
  const btnTest = document.getElementById("btn-test");
  btnTest.addEventListener("click", () => {
    createClient(
      "ralandison",
      "nantenaina noelly",
      "féminin",
      "+261 32 83 294 40",
      "adresse"
    );
  });
});
