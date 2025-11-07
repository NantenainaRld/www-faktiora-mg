async function deleteUser() {
  try {
    //update user
    const response = await apiRequest("/user/delete_user", {
      method: "DELETE",
      body: {
        id_utilisateur: "U025167CF",
      },
    });
    console.log(response);
  } catch (error) {
    console.log("Error : " + error);
  }
}
