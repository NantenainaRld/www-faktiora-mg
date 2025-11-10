document.addEventListener("DOMContentLoaded", () => {
  //================ FUNCTION ================

  //function - filter user
  async function filterUser() {
    try {
      const response = await apiRequest(
        "/user/filter_user?status=all&role=all&sexe=all&order_by=nb_transactions&arrange=DESC&num_caisse=all&date_by=per&per=DAY"
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
