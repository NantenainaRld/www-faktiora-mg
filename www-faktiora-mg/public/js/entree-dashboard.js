document.addEventListener("DOMContentLoaded", () => {
  //=================== FUNCTIONS ======================

  //function - add autree entree
  async function addAutreEntree() {
    try {
      const response = await apiRequest("/entree/add_autre_entree", {
        method: "POST",
        body: {
          libelle_entree: "divay",
          date_entree: "2025",
          montant_entree: 15000,
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
    addAutreEntree();
  });
});
