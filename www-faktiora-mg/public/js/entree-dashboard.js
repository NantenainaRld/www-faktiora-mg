document.addEventListener("DOMContentLoaded", () => {
  //=================== FUNCTIONS ======================

  //function - create autree entree
  async function createAutreEntree(id_ae, libelle_ae, date_ae, montant_ae) {
    try {
      const response = await apiRequest("/entree/create_autre_entree", {
        method: "POST",
        body: {
          id_ae: id_ae,
          libelle_ae: libelle_ae,
          date_ae: date_ae,
          montant_ae: montant_ae,
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
    createAutreEntree(" id", "  apport de caisse", "2025-12-11T11:12", " 2000");
  });
});
