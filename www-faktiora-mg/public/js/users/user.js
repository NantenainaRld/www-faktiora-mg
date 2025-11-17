document.addEventListener("DOMContentLoaded", () => {
  //====================== FUNCTIONS =====================

  //function - delete account
  async function deleteAccount(id) {
    try {
      const response = await apiRequest("/user/delete_account", {
        method: "DELETE",
      });
      console.log(response);
    } catch (Error) {
      console.log("Error : " + Error);
    }
  }
  //function - occup caisse
  async function occupCaisse(num_caisse) {
    try {
      const response = await apiRequest("/caisse/occup_caisse", {
        method: "POST",
        body: {
          num_caisse: num_caisse,
          id_utilisateur: "",
        },
      });
      console.log(response);
    } catch (error) {
      console.error(error);
    }
  }

  //======================== EVENTS ====================

  const btnTest = document.getElementById("btn-test");
  btnTest.addEventListener("click", () => {
    occupCaisse(3);
  });
});
