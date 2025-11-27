document.addEventListener("DOMContentLoaded", () => {
  //=================== FUNCTIONS ======================

  //function - create article
  async function createArticle(libelle_article) {
    try {
      const response = await apiRequest("/article/create_article", {
        method: "POST",
        body: {
          libelle_article: libelle_article,
        },
      });
      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }
  //function - filter article
  async function filterArticle(status, order_by, arrange, search_article) {
    try {
      const response = await apiRequest(
        `/article/filter_article?status=${status}&order_by=${order_by}&arrange=${arrange}&search_article=${search_article}`
      );
      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }
  //   //function - list all produit
  //   async function listAllProduit() {
  //     try {
  //       const response = await apiRequest("/produit/list_all_produit");
  //       console.log(response);
  //     } catch (e) {
  //       console.error(e);
  //     }
  //   }
  //   //function - update produtit
  //   async function updateProduit(
  //     id_produit,
  //     libelle_produit,
  //     prix_produit,
  //     nb_stock
  //   ) {
  //     try {
  //       const response = await apiRequest("/produit/update_produit", {
  //         method: "PUT",
  //         body: {
  //           id_produit: id_produit,
  //           libelle_produit: libelle_produit,
  //           prix_produit: prix_produit,
  //           nb_stock: nb_stock,
  //         },
  //       });
  //       console.log(response);
  //     } catch (e) {
  //       console.error(e);
  //     }
  //   }
  //   //function - delete all produit
  //   async function deleteAllProduit(ids_produit = []) {
  //     try {
  //       const response = await apiRequest("/produit/delete_all_produit", {
  //         method: "PUT",
  //         body: {
  //           ids_produit: ids_produit,
  //         },
  //       });
  //       console.log(response);
  //     } catch (e) {
  //       console.error(e);
  //     }
  //   }
  //   //function - permanent delete all produit
  //   async function permanentDeleteAllProduit(ids_produit = []) {
  //     try {
  //       const response = await apiRequest(
  //         "/produit/permanent_delete_all_produit",
  //         {
  //           method: "DELETE",
  //           body: {
  //             ids_produit: ids_produit,
  //           },
  //         }
  //       );
  //       console.log(response);
  //     } catch (e) {
  //       console.error(e);
  //     }
  //   }

  //================== EVENTS===================

  //btn-test
  const btnTest = document.getElementById("btn-test");
  btnTest.addEventListener("click", () => {
    // createArticle("achat de capsule");
    filterArticle("active", "or", "desc", "correction");
  });
});
