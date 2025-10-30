document.addEventListener("DOMContentLoaded", () => {
  //input login
  const login = document.getElementById("login");
  //mdp
  const mdp = document.getElementById("mdp");

  //===================== EVENTS =====================

  //btn-login
  const btnLogin = document.getElementById("btn-login");
  btnLogin.addEventListener("click", async () => {
    //   try {
    //     //==call api add user
    //     const responseAddUser = await apiRequest("/user/add_user", {
    //       method: "POST",
    //       body: {
    //         nom_utilisateur: "Anarana",
    //         prenoms_utilisateur: "fanampipny",
    //         sexe_utilisateur: "féminin",
    //         role: "admin",
    //         email_utilisateur: "esa@a.com",
    //         mdp: "mdpsss",
    //         mdpConfirm: "mdpsss",
    //       },
    //     });
    //     console.log(responseAddUser);
    //   } catch (Error) {
    //     console.log("Error : " + Error);
    //   }
  });
});
