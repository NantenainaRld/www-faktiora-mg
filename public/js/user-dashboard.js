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
  // //function - transactions user
  // async function transactionsUser() {
  //   try {
  //     const response = await apiRequest("/user/transactions_user?id=U025167CG");
  //     console.log(response);
  //   } catch (error) {
  //     console.log("Error : " + error);
  //   }
  // }
  //==================== EVENTS ===================
  //btn test
  // const btnTest = document.getElementById("test");
  // btnTest.addEventListener("click", async () => {
  //   transactionsUser();
  // });
});
