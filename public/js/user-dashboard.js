document.addEventListener("DOMContentLoaded", () => {
  //================ FUNCTION ================

  //function - filter user
  async function filterUser() {
    try {
      //order by name
      const response = await apiRequest(
        "/user/filter_user&search_user=&role=&sexe=&by=nb_sorties&order_by=none&from=&to=&per=none&month=none&year=none"
      );
      console.log(response);
    } catch (error) {
      console.log("Error : " + error);
    }
  }
  filterUser();

  //==================== EVENTS ===================

  //btn test
  const btnTest = document.getElementById("test");
  btnTest.addEventListener("click", async () => {
    try {
      //update user
      const response = await apiRequest("/user/update_user", {
        method: "PUT",
        body: {
          id_utilisateur: "id_test",
          nom_utilisateur: "nom_test",
          prenoms_utilisateur: "prenoms_test",
          sexe_utilisateur: "masculin",
          role: "admin",
          email_utilisateur: "email@a.b",
          mdp: "mdp test",
        },
      });
      console.log(response);
    } catch (error) {
      console.log("Error : " + error);
    }
  });
});
