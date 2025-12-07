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

    //save input value in local storage
    document.querySelectorAll(".real-input").forEach((element) => {
      element.addEventListener("input", (e) => {
        if (e.target.id !== "password") {
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

    //============================== elements ============================
    //input - login
    const loginInput = document.getElementById("login");
    //input - password
    const passswordInput = document.getElementById("password");
    //form
    const form = document.getElementsByTagName("form")[0];

    //===================== EVENTS =====================================

    //events - login input change
    loginInput.addEventListener("input", (e) => {
      //replace ' ' to ''
      e.target.value = e.target.value.replace(" ", "");

      //type - text (id_utilisateur)
      if (/^\d+$/.test(e.target.value)) {
        e.target.type = "text";
      }
      //type - email
      else {
        e.target.type = "email";
      }
    });

    //form - submit
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
          const response = await apiRequest("/auth/login_user", {
            method: "POST",
            body: {
              login: login.value.trim(),
              password: passswordInput.value.trim(),
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
              .querySelector(".fad")
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
          else {
            //alert
            const alertTemplate = document.getElementById("alert-template");
            const clone = alertTemplate.content.cloneNode(true);
            const alert = clone.querySelector(".alert");
            const progressBar = alert.querySelector(".progress-bar");
            //alert type
            alert.classList.add("alert-warning");
            //icon
            alert.querySelector(".fad").classList.add("fa-info-circle");
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

          //remove saved item
          document.querySelectorAll(".real-input").forEach((element) => {
            localStorage.removeItem(element.id);
          });
        } catch (e) {
          console.log(e);
        }
      }
    });
  }, 1000);
});
