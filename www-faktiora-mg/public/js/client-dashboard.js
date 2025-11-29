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
  //function - filter client
  async function filterClient(
    status,
    sexe,
    order_by,
    arrange,
    date_by,
    per,
    from,
    to,
    month,
    year,
    search_client
  ) {
    try {
      const response = await apiRequest(
        `/client/filter_client?status=${status}&sexe=${sexe}&order_by=${order_by}&arrange=${arrange}&date_by=${date_by}&per=${per}&from=${from}&to=${to}&month=${month}&year=${year}&search_client=${search_client}`,
        {}
      );
      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }
  //function - list all client
  async function listAllClient() {
    try {
      const response = await apiRequest("/client/list_all_client");
      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }
  //function - update_client
  async function updateClient(
    id_client,
    nom_client,
    prenoms_client,
    sexe_client,
    telephone,
    adresse
  ) {
    try {
      const response = await apiRequest("/client/update_client", {
        method: "PUT",
        body: {
          id_client: id_client,
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
  //function - delete all client
  async function deleteAllClient(ids_client = []) {
    try {
      const response = await apiRequest("/client/delete_all_client", {
        method: "PUT",
        body: {
          ids_client: ids_client,
        },
      });
      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }

  //================== EVENTS===================
  const from = document.getElementById("from");
  const to = document.getElementById("to");

  //btn-test
  const btnTest = document.getElementById("btn-test");
  btnTest.addEventListener("click", () => {
    // createClient(
    //   "ralandison",
    //   "nantenaina noelly",
    //   "féminin",
    //   "+261 32 83 294 40",
    //   "adresse"
    // );
    // filterClient(
    //   "active",
    //   "all",
    //   "nb_factures",
    //   "desc",
    //   "month_year",
    //   "week",
    //   from.value,
    //   to.value,
    //   "5",
    //   "2025",
    //   "oh"
    // );
    // listAllClient();
    // updateClient(
    //   "10002",
    //   "ralandison",
    //   "nantenaina noelly",
    //   "féminin",
    //   "+261 3",
    //   "adresse"
    // );
    deleteAllClient([10002, 10001]);
  });
});
