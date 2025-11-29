document.addEventListener("DOMContentLoaded", () => {
  //=================== FUNCTIONS ======================

  //function - create sortie
  async function createSortie(
    articles = [],
    date_ds,
    id_utilisateur,
    num_caisse
  ) {
    try {
      const response = await apiRequest("/sortie/create_sortie", {
        method: "POST",
        body: {
          articles: articles,
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
  //function - filter demande sortie
  async function filterDemandeSortie(
    status,
    num_caisse,
    id_user,
    order_by,
    arrange,
    from,
    to,
    search_ds
  ) {
    try {
      const response = await apiRequest(
        `/sortie/filter_demande_sortie?status=${status}&num_caisse=${num_caisse}&id_user=${id_user}&order_by=${order_by}&arrange=${arrange}&from=${from}&to=${to}&search_ds=${search_ds}`
      );
      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }
  //function - list connection sortie
  async function listConnectionSortie(num_ds) {
    try {
      const response = await apiRequest(
        `/sortie/list_connection_sortie?num_ds=${num_ds}`
      );
      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }
  //function - list all demande sortie
  async function listAllDemandeSortie() {
    try {
      const response = await apiRequest(`/sortie/list_all_demande_sortie`);
      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }
  //function - list lds article
  async function listLdsArticle(num_ds) {
    try {
      const response = await apiRequest(
        `/sortie/list_lds_article?num_ds=${num_ds}`
      );
      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }
  //function - update demande sortie
  async function updateDemandeSortie(
    num_ds,
    date_ds,
    id_utilisateur,
    num_caisse
  ) {
    try {
      const response = await apiRequest("/sortie/update_demande_sortie", {
        method: "PUT",
        body: {
          num_ds: num_ds,
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
  //function - delete all demande sortie
  async function deleteAllDemandeSortie(nums_ds = []) {
    try {
      const response = await apiRequest("/sortie/delete_all_demande_sortie", {
        method: "PUT",
        body: {
          nums_ds: nums_ds,
        },
      });
      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }
  //function - permanent delete all demande sortie
  async function permanentDeleteAllDemandeSortie(nums_ds = []) {
    try {
      const response = await apiRequest(
        "/sortie/permanent_delete_all_demande_sortie",
        {
          method: "DELETE",
          body: {
            nums_ds: nums_ds,
          },
        }
      );
      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }
  //function - correction autre entree
  async function correctionAutreEntree(
    num_ae,
    libelle_article,
    date_ds,
    montant,
    id_utilisateur,
    num_caisse
  ) {
    try {
      const response = await apiRequest("/sortie/correction_autre_entree", {
        method: "POST",
        body: {
          num_ae: num_ae,
          libelle_article: libelle_article,
          date_ds: date_ds,
          montant: montant,
          id_utilisateur: id_utilisateur,
          num_caisse: num_caisse,
        },
      });
      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }
  //function - correction demande sortie
  async function correctionDemandeSortie(
    num_ds,
    libelle_article,
    date_ds,
    montant,
    id_utilisateur,
    num_caisse
  ) {
    try {
      const response = await apiRequest("/sortie/correction_demande_sortie", {
        method: "POST",
        body: {
          num_ds: num_ds,
          libelle_article: libelle_article,
          date_ds: date_ds,
          montant: montant,
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
  const from = document.getElementById("from");
  const to = document.getElementById("to");

  //btn-test
  const btnTest = document.getElementById("btn-test");
  btnTest.addEventListener("click", () => {
    // createSortie(
    //   [
    //     {
    //       id_article: 4,
    //       prix_article: 2500,
    //       quantite_article: 2,
    //     },
    //     {
    //       id_article: 1,
    //       prix_article: 1500,
    //       quantite_article: 2,
    //     },
    //   ],
    //   date.value,
    //   10004,
    //   1
    // );
    // filterDemandeSortie(
    //   "active",
    //   "all",
    //   "all",
    //   "montant",
    //   "asc",
    //   from.value,
    //   to.value,
    //   ""
    // );
    // listConnectionSortie("s202511-12");
    // listAllDemandeSortie();
    // listLdsArticle("s202511-12");
    // updateDemandeSortie("s202511-12", date.value, "10003", "1");
    // deleteAllDemandeSortie(["s202511-12", "s202511-11"]);
    // permanentDeleteAllDemandeSortie(["s202511-12", "s202511-11"]);
    // permanentDeleteAllAutreEntree(["a202511-18", "a202511-19"]);
    // correctionAutreEntree(
    //   "a202511-17",
    //   "ajout de remboursement",
    //   date.value,
    //   "15",
    //   "10004",
    //   "1"
    // );
    // correctionDemandeSortie(
    //   "s202511-21",
    //   "achat de table",
    //   date.value,
    //   "15",
    //   "10004",
    //   "1"
    // );
  });
});
