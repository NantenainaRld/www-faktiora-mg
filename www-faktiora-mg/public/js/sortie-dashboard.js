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
    filterDemandeSortie(
      "active",
      "all",
      "all",
      "montant",
      "asc",
      from.value,
      to.value,
      ""
    );
    // updateAutreEntree("a202511-19", "ohhatra", date.value, "10004", "2");
    // deleteAllAutreEntree(["a202511-18", "a202511-19"]);
    // permanentDeleteAllAutreEntree(["a202511-18", "a202511-19"]);
    // listConnectionAutreEntree("a202511-17");
    // correctionAutreEntree(
    //   "a202511-17",
    //   "libelle",
    //   date.value,
    //   "122",
    //   "10004",
    //   "2"
    // );
  });
});
