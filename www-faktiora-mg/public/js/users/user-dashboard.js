document.addEventListener("DOMContentLoaded", () => {
  //================ FUNCTION ================

  //function - filter user
  async function filterUser() {
    try {
      const response = await apiRequest(
        "/user/filter_user?role=admin&order_by=nb_ae"
      );
      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }

  //==================== EVENTS ===================

  //btn test
  const btnTest = document.getElementById("btn-test");
  btnTest.addEventListener("click", () => {
    filterUser();
  });
});
