document.addEventListener("DOMContentLoaded", () => {
  //================ FUNCTION ================

  // function - default list user
  async function defaultListUser() {
    try {
      const response = await apiRequest("/user/default_list_user");
      console.log(response);
    } catch (error) {
      console.log("Error : " + error);
    }
  }
  defaultListUser();
  //function - nb user
  async function defaultNbUser() {
    try {
      const response = await apiRequest("/user/default_nb_user");
      console.log(response);
    } catch (error) {
      console.log("Error : " + error);
    }
  }
  defaultNbUser();
  //function - filter user
  async function filterUser() {
    try {
      //order by name
      const response = await apiRequest(
        "/user/filter_user&order_name=asc&search_user=anarana&role=admin&sexe=masculin&by=nb_factures&order_by=asc&from=2024&to=2025&per=week&order_date=desc"
      );
      console.log(response);
    } catch (error) {
      console.log("Error : " + error);
    }
  }

  //==================== EVENTS ===================

  //btn test
  const btnTest = document.getElementById("test");
  btnTest.addEventListener("click", async () => {
    filterUser();
  });
});
