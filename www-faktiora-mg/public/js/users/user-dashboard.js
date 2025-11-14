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
  //function - update user
  async function updateUser(
    id,
    id_update,
    nom,
    prenoms,
    sexe,
    email,
    role,
    mdp
  ) {
    try {
      const response = await apiRequest("/user/update_by_admin", {
        method: "PUT",
        body: {
          id_utilisateur: id,
          id_update: id_update,
          nom_utilisateur: nom,
          prenoms_utilisateur: prenoms,
          sexe_utilisateur: sexe,
          email_utilisateur: email,
          role: role,
          mdp: mdp,
        },
      });

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
    updateUser(
      "U556488QI",
      "IDUPDATE",
      "Nantenaina",
      "prenoms",
      "féminin",
      "test@faktiora.mg",
      "admin",
      "123456"
    );
  });
});
