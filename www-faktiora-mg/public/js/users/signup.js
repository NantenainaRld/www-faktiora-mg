document.addEventListener("DOMContentLoaded", () => {
  //template - placeholder
  const templatePlaceholder = document.getElementById("template-placeholder");
  //template - real
  const templateReal = document.getElementById("template-real");
  //container
  const container = document.getElementById("container");
  //load placeholder
  container.prepend(templatePlaceholder.content.cloneNode(true));
  //load real content
  setTimeout(() => {
    container.innerHTML = "";
    container.prepend(templateReal.content.cloneNode(true));

    //=======================================
    //save input value in local storage
    document.querySelectorAll(".real-input").forEach((element) => {
      element.addEventListener("input", (e) => {
        if (e.target.id !== "password" && e.target.id !== "password-confirm") {
          localStorage.setItem(e.target.id, e.target.value);
        }
      });
    });
    //load input value from local storage
    document.querySelectorAll(".real-input").forEach((element) => {
      const saved = localStorage.getItem(element.id);
      if (saved !== null) {
        element.value = saved;
      }
    });
    //save selectvalue in local storage
    const select = document.querySelector(".real-select");
    select.addEventListener("change", (e) => {
      localStorage.setItem(e.target.id, e.target.value);
    });
    //load select value from local storage
    const saved = localStorage.getItem(select.id);
    if (saved !== null) {
      select.value = saved;
    }

    //============================== elements ============================
    //input - user name
    const userNameInput = document.getElementById("user-name");
    //input - user sirst name
    const userFirstNameInput = document.getElementById("user-first-name");
    //input - user email
    const userEmailInput = document.getElementById("user-email");
    //select - sex
    const sex = select;
    //input - password
    const passswordInput = document.getElementById("password");
    //input - password confirm
    const passswordConfirmInput = document.getElementById("password-confirm");
    //form
    const form = document.getElementsByTagName("form")[0];

    //===================== EVENTS =====================================

    //events - user name change
    userNameInput.addEventListener("input", (e) => {
      // replace '  ' to ' '
      e.target.value = e.target.value.replace("  ", " ");
    });

    //events - user first name change
    userFirstNameInput.addEventListener("input", (e) => {
      // replace '  ' to ' '
      e.target.value = e.target.value.replace("  ", " ");
    });

    //events - user email change
    userEmailInput.addEventListener("input", (e) => {
      // replace ' ' to ' '
      e.target.value = e.target.value.replace(" ", "");
    });

    // form - submit;
    form.addEventListener("submit", async (e) => {
      //suspend submit
      e.preventDefault();

      //inputs - not valid
      if (!e.target.checkValidity()) {
        e.target.reportValidity();
        return;
      } else {
        try {
          //FETCH api
          const response = await apiRequest("/auth/signup", {
            method: "POST",
            body: {
              nom_utilisateur: userNameInput.value.trim(),
              prenoms_utilisateur: userFirstNameInput.value.trim(),
              sexe_utilisateur: sex.value.trim(),
              email_utilisateur: userEmailInput.value.trim(),
              mdp: passswordInput.value.trim(),
              mdp_confirm: passswordConfirmInput.value.trim(),
            },
          });

          //error
          if (response.message_type === "error") {
            //alert
            const alertTemplate = document.getElementById("alert-template");
            const clone = alertTemplate.content.cloneNode(true);
            const alert = clone.querySelector(".alert");
            const progressBar = alert.querySelector(".progress-bar");
            //alert type
            alert.classList.add("alert-danger");
            //icon
            alert
              .querySelector(".fas")
              .classList.add("fa-exclamation-triangle");
            //message
            alert.querySelector(".alert-message").innerHTML = response.message;
            //progress bar
            progressBar.style.transition = "width 20s linear";
            progressBar.style.width = "100%";

            //add alert
            document.getElementById("alert-container").prepend(alert);

            //progress lanch animation
            setTimeout(() => {
              progressBar.style.width = "0%";
            }, 10);

            //auto close alert
            setTimeout(() => {
              alert.querySelector(".btn-close").click();
            }, 20000);
          }
          //invalid
          else if (response.message_type === "invalid") {
            //alert
            const alertTemplate = document.getElementById("alert-template");
            const clone = alertTemplate.content.cloneNode(true);
            const alert = clone.querySelector(".alert");
            const progressBar = alert.querySelector(".progress-bar");
            //alert type
            alert.classList.add("alert-warning");
            //icon
            alert.querySelector(".fas").classList.add("fa-info-circle");
            //message
            alert.querySelector(".alert-message").innerHTML = response.message;
            //progress bar
            progressBar.style.transition = "width 15s linear";
            progressBar.style.width = "100%";

            //add alert
            document.getElementById("alert-container").prepend(alert);

            //progress lanch animation
            setTimeout(() => {
              progressBar.style.width = "0%";
            }, 10);

            //auto close alert
            setTimeout(() => {
              alert.querySelector(".btn-close").click();
            }, 15000);
          }
          //success
          else {
            //alert
            const alertTemplate = document.getElementById("alert-template");
            const clone = alertTemplate.content.cloneNode(true);
            const alert = clone.querySelector(".alert");
            const progressBar = alert.querySelector(".progress-bar");
            //alert type
            alert.classList.add("alert-success");
            //icon
            alert.querySelector(".fas").classList.add("fa-check-circle");
            //message
            alert.querySelector(".alert-message").innerHTML = response.message;
            //progress bar
            progressBar.style.transition = "width 3s linear";
            progressBar.style.width = "100%";

            //add alert
            document.getElementById("alert-container").prepend(alert);

            //progress lanch animation
            setTimeout(() => {
              progressBar.style.width = "0%";
            }, 10);

            //auto close alert
            setTimeout(() => {
              alert.querySelector(".btn-close").click();
              window.location.href = SITE_URL + "/auth/page_login";
            }, 3000);
          }

          //remove input value from local storage
          document.querySelectorAll(".real-input").forEach((element) => {
            localStorage.removeItem(element.id);
          });
          //remove select value from local storage
          localStorage.removeItem(select.id);
        } catch (e) {
          console.log(e);
        }
      }
    });
  }, 1000);
});
