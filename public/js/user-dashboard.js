document.addEventListener("DOMContentLoaded", () => {
  //================ FUNCTION ================

  //function - list user
  async function listUser() {
    try {
      const response = await apiRequest("/user/list_user");
      console.log(response);
    } catch (error) {
      console.log("Error : " + error);
    }
  }
  listUser();
  //function - nb user
  async function nbUser() {
    try {
      const response = await apiRequest("/user/nb_user");
      console.log(response);
    } catch (error) {
      console.log("Error : " + error);
    }
  }
  nbUser();
  //function - transactions user
  async function transactionsUser() {
    try {
      const response = await apiRequest("/user/transactions_user?id=U025167CG");
      console.log(response);
    } catch (error) {
      console.log("Error : " + error);
    }
  }

  //==================== EVENTS ===================
  //btn test
  const btnTest = document.getElementById("test");
  btnTest.addEventListener("click", async () => {
    transactionsUser();
  });
});
