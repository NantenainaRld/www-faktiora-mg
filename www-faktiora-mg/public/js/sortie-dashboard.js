document.addEventListener("DOMContentLoaded", () => {
  //=================== FUNCTIONS ======================

  //function - create sortie
  async function createSortie() {
    try {
      const response = await apiRequest("/sortie/create_sortie", {
        method: "POST",
        body: {},
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
    createSortie();
    // filterAutreEntree("active", "all", "all", "num_ae", "ASC", "", "", "");
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
