document.addEventListener("DOMContentLoaded", () => {
  //template - placeholder
  const templatePlaceholder = document.getElementById("template-placeholder");
  // template real sidebar
  const templateRealSidebar = document.getElementById("template-sidebar");
  //container
  const container = document.getElementById("container");
  //load placeholder
  container.prepend(templatePlaceholder.content.cloneNode(true));
  // load real content
  setTimeout(async () => {
    container.innerHTML = "";
    //load real side bar
    container.append(templateRealSidebar.content.cloneNode(true));

    //===================== ACCOUNT INFO ==============
    await accountInfo();

    //===================== UPDATE ACCOUNT =================
    //modal update account
    const modalUpdateAccount = document.getElementById("modal-update-account");

    //===== EVENT a update account
    document
      .getElementById("a-update-account")
      .addEventListener("click", () => {
        //input - update user name
        const inputUpdateUserName = modalUpdateAccount.querySelector(
          "#input-update-user-name"
        );
        inputUpdateUserName.addEventListener("input", (e) => {
          e.target.value = e.target.value.replace("  ", " ").toUpperCase();
        });
        inputUpdateUserName.value =
          document.getElementById("a-user_name").dataset.nomUtilisateur;
        //input - update user first name
        const inputUpdateUserFirstName = modalUpdateAccount.querySelector(
          "#input-update-user-first-name"
        );
        inputUpdateUserFirstName.addEventListener("input", (e) => {
          e.target.value = e.target.value.replace("  ", " ");
        });
        inputUpdateUserFirstName.value =
          document.getElementById("a-user_name").dataset.prenomsUtilisateur;
        //select - update user sex
        modalUpdateAccount.querySelector("#select-update-user-sex").value =
          document.getElementById("a-user_name").dataset.sexeUtilisateur;
        //input -update user email
        const inputUpdateUserEmail = modalUpdateAccount.querySelector(
          "#input-update-user-email"
        );
        inputUpdateUserEmail.addEventListener("input", (e) => {
          e.target.value = e.target.value.replace(" ", "");
        });
        inputUpdateUserEmail.value =
          document.getElementById("a-user_name").dataset.emailUtilisateur;

        //show modal update account
        new bootstrap.Modal(modalUpdateAccount).show();

        //===== EVENT form update account submit
        modalUpdateAccount
          .querySelector("form")
          .addEventListener("submit", async (e) => {
            //suspend submit
            e.preventDefault();

            //inputs - not valid
            if (!e.target.checkValidity()) {
              e.target.reportValidity();
              return;
            } else {
              try {
                //FETCH api update account
                const apiUpdateAccount = await apiRequest(
                  "/user/update_account",
                  {
                    method: "PUT",
                    body: {
                      nom_utilisateur: modalUpdateAccount
                        .querySelector("#input-update-user-name")
                        .value.trim(),
                      prenoms_utilisateur: modalUpdateAccount
                        .querySelector("#input-update-user-first-name")
                        .value.trim(),
                      sexe_utilisateur: modalUpdateAccount
                        .querySelector("#select-update-user-sex")
                        .value.trim(),
                      email_utilisateur: modalUpdateAccount
                        .querySelector("#input-update-user-email")
                        .value.trim(),
                      mdp: modalUpdateAccount.querySelector(
                        "#input-update-user-mdp"
                      ).value,
                    },
                  }
                );

                //invalid
                if (apiUpdateAccount.message_type === "invalid") {
                  //alert
                  const alertTemplate =
                    modalUpdateAccount.querySelector(".alert-template");
                  const clone = alertTemplate.content.cloneNode(true);
                  const alert = clone.querySelector(".alert");
                  const progressBar = alert.querySelector(".progress-bar");
                  //alert type
                  alert.classList.add("alert-warning");
                  //icon
                  alert
                    .querySelector(".fad")
                    .classList.add("fa-exclamation-circle");
                  //message
                  alert.querySelector(".alert-message").innerHTML =
                    apiUpdateAccount.message;
                  //progress bar
                  progressBar.style.transition = "width 10s linear";
                  progressBar.style.width = "100%";

                  //add alert
                  modalUpdateAccount
                    .querySelector(".modal-body")
                    .prepend(alert);

                  //progress lanch animation
                  setTimeout(() => {
                    progressBar.style.width = "0%";
                  }, 10);

                  //auto close alert
                  setTimeout(() => {
                    alert.querySelector(".btn-close").click();
                  }, 10000);
                }
                //error
                else if (apiUpdateAccount.message_type === "error") {
                  //alert
                  const alertTemplate =
                    modalUpdateAccount.querySelector(".alert-template");
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
                  alert.querySelector(".alert-message").innerHTML =
                    apiUpdateAccount.message;
                  //progress bar
                  progressBar.style.transition = "width 10s linear";
                  progressBar.style.width = "100%";

                  //add alert
                  modalUpdateAccount
                    .querySelector(".modal-body")
                    .prepend(alert);

                  //progress lanch animation
                  setTimeout(() => {
                    progressBar.style.width = "0%";
                  }, 10);

                  //auto close alert
                  setTimeout(() => {
                    alert.querySelector(".btn-close").click();
                  }, 10000);
                }
                //success
                else {
                  //alert
                  const alertTemplate =
                    modalUpdateAccount.querySelector(".alert-template");
                  const clone = alertTemplate.content.cloneNode(true);
                  const alert = clone.querySelector(".alert");
                  const progressBar = alert.querySelector(".progress-bar");
                  //alert type
                  alert.classList.add("alert-success");
                  //icon
                  alert.querySelector(".fad").classList.add("fa-check-circle");
                  //message
                  alert.querySelector(".alert-message").innerHTML =
                    apiUpdateAccount.message;
                  //progress bar
                  progressBar.style.transition = "width 1s linear";
                  progressBar.style.width = "100%";

                  //add alert
                  modalUpdateAccount
                    .querySelector(".modal-body")
                    .prepend(alert);

                  //progress lanch animation
                  setTimeout(() => {
                    progressBar.style.width = "0%";
                  }, 10);

                  //auto close alert
                  setTimeout(() => {
                    alert.querySelector(".btn-close").click();
                  }, 800);

                  //hide modal
                  setTimeout(() => {
                    modalUpdateAccount
                      .querySelector("#btn-close-modal-update-account")
                      .click();
                  }, 1000);

                  //refesh account info
                  accountInfo();
                }
              } catch (e) {
                console.error(e);
              }
            }
          });
      });

    //================== PING ====================
    ping();
    setInterval(ping, 1000 * 60 * 2);

    //======================= SETTING ==================
    //modal settting
    const modalSetting = document.getElementById("modal-setting");

    //===== EVENT a setting
    document.getElementById("a-setting").addEventListener("click", async () => {
      // config
      fetch(SITE_URL + "/config/config.json")
        .then((res) => res.json())
        .then((data) => {
          //input enterprise name
          const inputEnterPriseName =
            modalSetting.querySelector("#enterprise-name");
          inputEnterPriseName.value = data.enterprise_name;
          //select currency
          const selectCurrency = modalSetting.querySelector("#currency");
          selectCurrency.value = data.currency_units;

          //==== EVENT enterprise name
          inputEnterPriseName.addEventListener("input", (e) => {
            e.target.value = e.target.value.replace("  ", " ");
          });

          //show modal setting
          new bootstrap.Modal(modalSetting).show();

          //===== EVENT form submit
          modalSetting
            .querySelector("form")
            .addEventListener("submit", async (e) => {
              //suspend submit
              e.preventDefault();
              //check validity
              if (!e.target.checkValidity()) {
                e.target.reportValidity();
                return;
              }

              const response = await apiRequest("/setting", {
                method: "PUT",
                body: {
                  enterprise_name: inputEnterPriseName.value.trim(),
                  currency_units: selectCurrency.value.trim(),
                },
              });

              if (response.message_type === "success") window.location.reload();
            });
        })
        .catch((err) => console.error(err));
    });
  }, 1000);

  //======================== FUNCTIONS ==================
  //function - account info
  async function accountInfo() {
    try {
      //get account info
      const accountInfo = await apiRequest("/user/account_info");
      //error
      if (accountInfo.message_type === "error") {
        //div accountinfo
        const divAccountInfo = document.getElementById("account-info");
        divAccountInfo.innerHTML = `<div class='alert alert-danger'/><i class='fad fa-warning'></i>${accountInfo.message}</div>`;
      }
      //success
      else {
        //a- user name
        const aUserName = document.getElementById("a-user_name");
        aUserName.className =
          "mb-1 text-light text-decoration-none text-center fs-6";
        aUserName.innerHTML = `<h4 class='fw-bold'>${accountInfo.data.nom_utilisateur}</h4> <span class='fs-6 green-first'>${accountInfo.data.prenoms_utilisateur}</span>`;
        aUserName.dataset.nomUtilisateur = accountInfo.data.nom_utilisateur;
        aUserName.dataset.prenomsUtilisateur =
          accountInfo.data.prenoms_utilisateur;
        aUserName.dataset.sexeUtilisateur = accountInfo.data.sexe_utilisateur;
        aUserName.dataset.emailUtilisateur = accountInfo.data.email_utilisateur;
        //a- user email
        const aUserEmail = document.getElementById("a-user_email");
        aUserEmail.className =
          "mb-1 green-second text-decoration-none text-center form-text bg-green-third rounded-1 px-2";
        aUserEmail.innerHTML = `${accountInfo.data.email_utilisateur}`;
        //a- user role id user
        const aRoleIdUser = document.getElementById("a-user_role_id_user");
        aRoleIdUser.className =
          "mb-1 green-second text-decoration-none text-center form-text bg-success rounded-1 px-2 fw-bold bg-opacity-25";
        aRoleIdUser.innerHTML = `${accountInfo.data.role_t} - ${accountInfo.data.id_utilisateur}`;
        //role - caissier
        if (accountInfo.data.role === "caissier") {
          // a- user num_caisse
          const aUserNumCaisse = document.getElementById("a-user_num_caisse");
          aUserNumCaisse.className = "fw-bold";
          aUserNumCaisse.innerHTML = accountInfo.data.num_caisse;
        }
      }
    } catch (e) {
      console.log(e);
    }
  }
  //function - ping
  async function ping() {
    try {
      //api ping
      const apiPing = await apiRequest("/user/ping");
    } catch (e) {
      console.log(e);
    }
  }
});
