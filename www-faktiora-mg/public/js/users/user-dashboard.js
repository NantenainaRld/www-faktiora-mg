document.addEventListener("DOMContentLoaded", () => {
  //================ FUNCTION ================

  //function - filter user
  async function filterUser(from, to) {
    try {
      const response = await apiRequest(
        `/user/filter_user?status=all&role=all&sexe=all&order_by=nb_transactions&arrange=DESC&num_caisse=all&date_by=month_year&per=week&from=${from}&to=${to}&month=11&year=2015&search_user=`
      );
      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }

  //==================== EVENTS ===================
  const from = document.getElementById("from");
  const to = document.getElementById("to");

  //btn test
  const btnTest = document.getElementById("btn-test");
  btnTest.addEventListener("click", () => {
    filterUser(from.value, to.value);
  });
});
