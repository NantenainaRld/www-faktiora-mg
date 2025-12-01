document.addEventListener("DOMContentLoaded", () => {
  //input login
  const login = document.getElementById("login");
  //mdp
  const mdp = document.getElementById("mdp");

  //function - login user
  async function loginUser(login, passsword) {
    try {
      const response = await apiRequest("/auth/login_user", {
        method: "POST",
        body: {
          login: login,
          password: passsword,
        },
      });
      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }

  //===================== EVENTS =====================

  //btn-login
  const btnLogin = document.getElementById("btn-login");
  btnLogin.addEventListener("click", () => {
    loginUser("10007", "admin");
  });
});
