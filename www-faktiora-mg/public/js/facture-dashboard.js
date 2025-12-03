document.addEventListener("DOMContentLoaded", () => {
  //=================== FUNCTIONS ======================

  //function - create facture
  async function createFacture(
    produits = [],
    date_facture,
    id_utilisateur,
    num_caisse,
    id_client
  ) {
    try {
      const response = await apiRequest("/entree/create_facture", {
        method: "POST",
        body: {
          produits: produits,
          date_facture: date_facture,
          id_utilisateur: id_utilisateur,
          num_caisse: num_caisse,
          id_client: id_client,
        },
      });
      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }
  //function - filter facture
  async function filterFacture(
    status,
    num_caisse,
    id_user,
    order_by,
    arrange,
    from,
    to,
    search_facture
  ) {
    try {
      const response = await apiRequest(
        `/entree/filter_facture?status=${status}&num_caisse=${num_caisse}&id_user=${id_user}&order_by=${order_by}&arrange=${arrange}&from=${from}&to=${to}&search_facture=${search_facture}`
      );
      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }
  //function - list facture
  async function listAllFacture() {
    try {
      const response = await apiRequest(`/entree/list_all_facture`);
      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }
  //function - list lf and produit
  async function listLfProduit(num_facture) {
    try {
      const response = await apiRequest(
        `/entree/list_lf_produit?num_facture=${num_facture}`
      );
      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }
  //function - list connection facture
  async function listConnectionFacture(num_facture) {
    try {
      const response = await apiRequest(
        `/entree/list_connection_facture?num_facture=${num_facture}`
      );
      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }
  //function - update facture
  async function updateFacture(
    num_facture,
    date_facture,
    id_utilisateur,
    num_caisse,
    id_client
  ) {
    try {
      const response = await apiRequest("/entree/update_facture", {
        method: "PUT",
        body: {
          num_facture: num_facture,
          date_facture: date_facture,
          id_utilisateur: id_utilisateur,
          num_caisse: num_caisse,
          id_client: id_client,
        },
      });
      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }
  //function - delete all facture
  async function deleteAllFacture(nums_facture = []) {
    try {
      const response = await apiRequest("/entree/delete_all_facture", {
        method: "PUT",
        body: {
          nums_facture: nums_facture,
        },
      });
      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }
  //function - permanent delete all facture
  async function permanentDeleteAllFacture(nums_facture = []) {
    try {
      const response = await apiRequest(
        "/entree/permanent_delete_all_facture",
        {
          method: "PUT",
          body: {
            nums_facture: nums_facture,
          },
        }
      );
      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }
  //function - correction facture
  async function correctionFacture(
    num_facture,
    lf = [],
    date_ds,
    id_utilisateur,
    num_caisse
  ) {
    try {
      const response = await apiRequest("/sortie/correction_facture", {
        method: "POST",
        body: {
          num_facture: num_facture,
          lf: lf,
          date_ds: date_ds,
          id_utilisateur: id_utilisateur,
          num_caisse: num_caisse,
        },
      });
      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }
  //function - print facture
  async function printFacture(num_facture = "") {
    try {
      const response = await apiRequest(
        `/entree/print_facture?num_facture=${num_facture}`
      );
      //not success
      if (response.message_type !== "success") {
        console.log(response);
      }
      // success
      else {
        const a = document.createElement("a");
        a.href = `data:application/pdf;base64,${response.pdf}`;
        a.download = response.file_name;
        a.click();
        console.log(response.message);
      }
    } catch (error) {
      console.error(error);
    }
  }

  //================== EVENTS===================
  const date = document.getElementById("date");
  const from = document.getElementById("from");
  const to = document.getElementById("to");

  //btn-test
  const btnTest = document.getElementById("btn-test");
  btnTest.addEventListener("click", () => {
    // createFacture(
    //   [
    //     {
    //       id_produit: 3,
    //       quantite_produit: 2,
    //     },
    //     {
    //       id_produit: 1,
    //       quantite_produit: 32,
    //     },
    //   ],
    //   date.value,
    //   10004,
    //   1,
    //   10000
    // );
    // filterFacture(
    //   "active",
    //   "all",
    //   "all",
    //   "num",
    //   "desc",
    //   from.value,
    //   to.value,
    //   ""
    // );
    // listAllFacture();
    // listLfProduit("f202511-7");
    // listConnectionFacture("f202511-7");
    // updateFacture("f202511-7", date.value, "10003", "1", "10000");
    // deleteAllFacture(["f202511-7", "f202511-5", "f202511-4"]);
    // permanentDeleteAllFacture(["f202511-7", "f202511-5", "f202511-4"]);
    // correctionFacture(
    //   "f202511-6",
    //   [
    //     {
    //       id_lf: 6,
    //       id_produit: 3,
    //       quantite_produit: 1,
    //     },
    //     {
    //       id_lf: 1,
    //       id_produit: 1,
    //       quantite_produit: 3,
    //     },
    //   ],
    //   date.value,
    //   10004,
    //   1
    // );
    printFacture("f202511-2");
  });
});
