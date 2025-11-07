async function updateUser() {
  try {
    //update user
    const response = await apiRequest("/user/update_user", {
      method: "PUT",
      body: {
        id_utilisateur: "U025167CF",
        nom_utilisateur: "novaina",
        prenoms_utilisateur: "prenoms_test",
        sexe_utilisateur: "masculin",
        role: "admin",
        email_utilisateur: "emsa@a.com",
        mdp: "123456",
      },
    });
    console.log(response);
  } catch (error) {
    console.log("Error : " + error);
  }
}
