document.addEventListener("DOMContentLoaded", () => {
  //================ FUNCTION ================

  //function - filter user
  async function filterUser() {
    try {
      //order by name
      const response = await apiRequest(
        "/user/filter_user&order_name=asc&search_user=67&role=caissier&sexe=féminin&by=nb_factures&order_by=none&from=&to=&per=none&month=none&year=none"
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
