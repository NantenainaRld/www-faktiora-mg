document.addEventListener("DOMContentLoaded", () => {
  //=================== FUNCTIONS ======================

  //function - create autree entree
  async function createAutreEntree(
    num_ae,
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

  //btn-test
  const btnTest = document.getElementById("btn-test");
  btnTest.addEventListener("click", () => {
    createAutreEntree(
      " test",
      " apport de caisse",
      date.value,
      " 200000",
      " 10004",
      "1"
    );
  });
});
