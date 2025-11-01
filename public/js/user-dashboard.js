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
});
