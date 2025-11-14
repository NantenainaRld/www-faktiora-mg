document.addEventListener("DOMContentLoaded", () => {
  //====================== FUNCTIONS =====================

  //function - delete account
  async function deleteAccount(id) {
    try {
      const response = await apiRequest("/user/delete_account", {
        method: "DELETE",
        body: {
          id_utilisateur: id,
        },
      });
      console.log(response);
    } catch (Error) {
      console.log("Error : " + Error);
    }
  }

  //======================== EVENTS ====================

  const btnTest = document.getElementById("btn-test");
  btnTest.addEventListener("click", () => {
    deleteAccount("U123278VW");
  });
});
