document.addEventListener("DOMContentLoaded", () => {
  //input login
  const login = document.getElementById("login");
  //mdp
  const mdp = document.getElementById("mdp");

  //===================== EVENTS =====================

  //btn-login
  const btnLogin = document.getElementById("btn-login");
  btnLogin.addEventListener("click", async () => {
    try {
      const responseAddUser = await apiRequest("/login/login_user", {
        method: "POST",
        body: {
          login: "test",
          mdp: "mdp",
        },
      });
      console.log(responseAddUser);
    } catch (Error) {
      console.log("Error : " + Error);
    }
  });
});
