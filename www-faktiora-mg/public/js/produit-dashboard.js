document.addEventListener("DOMContentLoaded", () => {
  //=================== FUNCTIONS ======================

  //function - create produit
  async function createProduit(libelle_produit, prix_produit, nb_stock) {
    try {
      const response = await apiRequest("/produit/create_produit", {
        method: "POST",
        body: {
          libelle_produit: libelle_produit,
          prix_produit: prix_produit,
          nb_stock: nb_stock,
        },
      });
      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }
  //function - filter produit
  async function filterProduit(status, order_by, arrange, search_produit) {
    try {
      const response = await apiRequest(
        `/produit/filter_produit?status=${status}&order_by=${order_by}&arrange=${arrange}&search_produit=${search_produit}`
      );
      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }

  //================== EVENTS===================
  const date = document.getElementById("date");
  const from = document.getElementById("from");
  const to = document.getElementById("to");

  //btn-test
  const btnTest = document.getElementById("btn-test");
  btnTest.addEventListener("click", () => {
    // createProduit("tavoangy", "15000", 34);
    filterProduit("active", "max", "desc", "2");
  });
});
