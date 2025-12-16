document.addEventListener("DOMContentLoaded", async () => {
  //template real content
  const templateRealContent = document.getElementById("template-user");
  // //container
  // const container = document.getElementById("container");
  //config
  let config = "",
    currencyUnits = "",
    cookieLangValue = "";
  const f = async () => {
    try {
      //cookie lang value
      cookieLangValue = document.cookie
        .split("; ")
        .find((row) => row.startsWith(`lang=`))
        ?.split("=")[1];

      //get currency units
      config = await fetch(`${SITE_URL}/config/config.json`);
      if (!config.ok) throw new Error(`HTTP ${config.status}`);
      currencyUnits = (await config.json()).currency_units;
    } catch (e) {
      console.error(e);
    }
  };
  await f();
  //formatter
  let formatterNumber, formatterTotal;
  //en
  if (cookieLangValue === "en") {
    formatterNumber = new Intl.NumberFormat("en-US", {
      style: "decimal",
      minimumFractionDigits: 0,
      maximumFractionDigits: 0,
    });
    formatterTotal = new Intl.NumberFormat("en-US", {
      style: "currency",
      currency: currencyUnits,
      minimumFractionDigits: 0,
      maximumFractionDigits: 0,
    });
  }
  //fr && mg
  else {
    formatterNumber = new Intl.NumberFormat("fr-FR", {
      style: "decimal",
      minimumFractionDigits: 0,
      maximumFractionDigits: 2,
    });
    if (cookieLangValue === "mg") {
      formatterTotal = new Intl.NumberFormat("fr-FR", {
        style: "currency",
        currency: currencyUnits,
        minimumFractionDigits: 0,
        maximumFractionDigits: 2,
      });
    } else {
      formatterTotal = new Intl.NumberFormat("fr-FR", {
        style: "currency",
        currency: currencyUnits,
        minimumFractionDigits: 0,
        maximumFractionDigits: 2,
      });
    }
  }

  setTimeout(async () => {
    //load template real
    container.append(templateRealContent.content.cloneNode(true));
    // btn sidebar
    const btnSideBar = container.querySelector("#btn-sidebar");
    //overlay
    const overlay = container.querySelector(".overlay");
    //sidebar
    const sidebar = container.querySelector(".sidebar");
    if (btnSideBar) {
      //toggle sidebar
      btnSideBar.addEventListener("click", () => {
        sidebar.classList.toggle("active");
        overlay.classList.toggle("active");
      });
      //overlay toggle sidebar
      overlay.addEventListener("click", () => {
        overlay.classList.toggle("active");
        sidebar.classList.toggle("active");
      });
    }
    //tbody
    const tbody = container.querySelector("tbody");

    //=================== EVENT btn search bar
    //btn searcbar
    const btnSearchBar = container.querySelector("#btn-searchbar");
    //searchbar
    const searchBar = document.querySelector(".searchbar");
    //overlay search bar
    const overlaySearchBar = document.querySelector(".overlay-searchbar");
    //toggle searchbar
    btnSearchBar.addEventListener("click", () => {
      searchBar.classList.toggle("active");
      overlaySearchBar.classList.toggle("active");
    });
    //overlay toggle searcbar
    overlaySearchBar.addEventListener("click", () => {
      overlaySearchBar.classList.toggle("active");
      searchBar.classList.toggle("active");
    });

    //=======================EVENT date_by change
    //select - date_by
    const selectDateBy = container.querySelector("#date-by");
    const savedSelectDateBy = localStorage.getItem(selectDateBy.id);
    selectDateBy.value = !savedSelectDateBy ? "all" : savedSelectDateBy;
    switch (selectDateBy.value) {
      //per
      case "per":
        container.querySelector("#div-per").classList.add("active");
        break;
      //between
      case "between":
        container.querySelector("#div-between").classList.add("active");
        break;
      //month_year
      case "month_year":
        container.querySelector("#div-month_year").classList.add("active");
        break;
    }
    //event - date_by change
    selectDateBy.addEventListener("change", (e) => {
      //hide all
      container.querySelectorAll(".date-by").forEach((dateBy) => {
        if (dateBy.classList.contains("active")) {
          dateBy.classList.remove("active");
        }
      });

      switch (e.target.value) {
        //per
        case "per":
          container.querySelector("#div-per").classList.add("active");
          break;
        //between
        case "between":
          container.querySelector("#div-between").classList.add("active");
          break;
        //month_year
        case "month_year":
          container.querySelector("#div-month_year").classList.add("active");
          break;
      }
      localStorage.setItem(e.target.id, e.target.value);
    });

    //==================ELEMENTS searchbar
    //input - search
    const inputSearch = document.getElementById("input-search");
    inputSearch.addEventListener("input", () => {});
    const savedInputSearch = localStorage.getItem(inputSearch.id);
    inputSearch.value = !savedInputSearch ? "" : savedInputSearch;
    //input - search searchbar
    const inputSearchSearchBar = document.getElementById(
      "input-search-searchbar"
    );
    inputSearchSearchBar.value = inputSearch.value;
    //select - status
    const selectStatus = document.getElementById("select-status");
    const savedSelectStatus = localStorage.getItem(selectStatus.id);
    selectStatus.value = !savedSelectStatus ? "all" : savedSelectStatus;
    //select - role
    const selectRole = document.getElementById("select-role");
    const savedSelectRole = localStorage.getItem(selectRole.id);
    selectRole.value = !savedSelectRole ? "all" : savedSelectRole;
    //select - sex
    const selectSex = document.getElementById("select-sex");
    const savedSelectSex = localStorage.getItem(selectSex.id);
    selectSex.value = !savedSelectSex ? "all" : savedSelectSex;
    //select - arrange_by
    const selectArrangeBy = document.getElementById("select-arrange-by");
    const savedSelectArrangeBy = localStorage.getItem(selectArrangeBy.id);
    selectArrangeBy.value = !savedSelectArrangeBy
      ? "name"
      : savedSelectArrangeBy;
    //select - order
    const selectOrder = document.getElementById("select-order");
    const savedSelectOrder = localStorage.getItem(selectOrder.id);
    selectOrder.value = !savedSelectOrder ? "asc" : savedSelectOrder;
    //select - num_caisse
    const selectNumCaisse = document.getElementById("select-num-caisse");
    //select - per
    const selectPer = document.getElementById("select-per");
    const savedSelectPer = localStorage.getItem(selectPer.id);
    selectPer.value = !savedSelectPer ? "day" : savedSelectPer;
    //date - from
    const dateFrom = document.getElementById("date-from");
    const savedDateFrom = localStorage.getItem(dateFrom.id);
    dateFrom.value = !savedDateFrom ? "" : savedDateFrom;
    //date - to
    const dateTo = document.getElementById("date-to");
    const savedDateTo = localStorage.getItem(dateTo.id);
    dateTo.value = !savedDateTo ? "" : savedDateTo;
    //select - month
    const selectMonth = document.getElementById("select-month");
    const savedSelectMonth = localStorage.getItem(selectMonth.id);
    selectMonth.value = !savedSelectMonth ? "all" : savedSelectMonth;
    //select - year
    const selectYear = document.getElementById("select-year");
    const savedSelectYear = localStorage.getItem(selectYear.id);
    selectYear.value = !savedSelectYear ? selectYear.value : savedSelectYear;

    //EVENT btn reset
    const btnReset = document.getElementById("btn-reset");
    btnReset.addEventListener("click", () => {
      //reset input search
      inputSearch.value = "";
      inputSearch.dispatchEvent(new Event("input"));
      //reset select status
      selectStatus.value = "all";
      localStorage.removeItem(selectStatus.id);
      //reset role
      selectRole.alue = "all";
      localStorage.removeItem(selectRole.id);
      //reset sex
      selectSex.value = "all";
      //reset select arrange_by
      selectArrangeBy.value = "name";
      localStorage.removeItem(selectRole.id);
      //reset order
      selectOrder.value = "asc";
      localStorage.removeItem(selectRole.id);
      //reset select num_caisse
      selectNumCaisse.value = "all";
      localStorage.removeItem(selectNumCaisse.id);
      //reset date_by
      selectDateBy.value = "all";
      localStorage.removeItem(selectDateBy.id);
      //reset select per
      selectPer.value = "day";
      localStorage.removeItem(selectPer.id);
      //reset date from
      dateFrom.value = "";
      localStorage.removeItem(dateFrom.id);
      //reset date to
      dateTo.value = "";
      localStorage.removeItem(dateTo.id);
      //reset select month
      selectMonth.value = "all";
      localStorage.removeItem(selectMonth.id);
      //reset select year
      let date = new Date();
      let dateString = date.toISOString();
      selectYear.value = dateString.substring(0, 4);
      localStorage.removeItem(selectYear.id);
    });

    //list all num_caisse
    await listNumCaisse(selectNumCaisse, tbody);
    const savedSelectNumCaisse = localStorage.getItem(selectNumCaisse.id);
    selectNumCaisse.value = !savedSelectNumCaisse
      ? "all"
      : savedSelectNumCaisse;

    //list all user
    await filterUser(
      tbody,
      container.querySelector("#chart-role"),
      container.querySelector("#chart-status"),
      selectStatus.value.trim(),
      selectRole.value.trim(),
      selectSex.value.trim(),
      selectArrangeBy.value.trim(),
      selectOrder.value.trim(),
      selectNumCaisse.value.trim(),
      selectDateBy.value.trim(),
      selectPer.value.trim(),
      dateFrom.value.trim(),
      dateTo.value.trim(),
      selectMonth.value.trim(),
      selectYear.value.trim(),
      inputSearch.value.trim()
    );

    //======EVENT input search
    inputSearch.addEventListener("input", (e) => {
      filterUser(
        tbody,
        container.querySelector("#chart-role"),
        container.querySelector("#chart-status"),
        selectStatus.value.trim(),
        selectRole.value.trim(),
        selectSex.value.trim(),
        selectArrangeBy.value.trim(),
        selectOrder.value.trim(),
        selectNumCaisse.value.trim(),
        selectDateBy.value.trim(),
        selectPer.value.trim(),
        dateFrom.value.trim(),
        dateTo.value.trim(),
        selectMonth.value.trim(),
        selectYear.value.trim(),
        inputSearch.value.trim()
      );
      inputSearchSearchBar.value = e.target.value;
      localStorage.setItem(e.target.id, e.target.value);
    });
    //========EVENT input search searchbar
    inputSearchSearchBar.addEventListener("input", (e) => {
      inputSearch.value = e.target.value;
      inputSearch.dispatchEvent(new Event("input"));
    });

    //=========EVENT select status
    selectStatus.addEventListener("change", (e) => {
      filterUser(
        tbody,
        container.querySelector("#chart-role"),
        container.querySelector("#chart-status"),
        selectStatus.value.trim(),
        selectRole.value.trim(),
        selectSex.value.trim(),
        selectArrangeBy.value.trim(),
        selectOrder.value.trim(),
        selectNumCaisse.value.trim(),
        selectDateBy.value.trim(),
        selectPer.value.trim(),
        dateFrom.value.trim(),
        dateTo.value.trim(),
        selectMonth.value.trim(),
        selectYear.value.trim(),
        inputSearch.value.trim()
      );
      localStorage.setItem(e.target.id, e.target.value);
    });

    //=========EVENT select role
    selectRole.addEventListener("change", (e) => {
      filterUser(
        tbody,
        container.querySelector("#chart-role"),
        container.querySelector("#chart-status"),
        selectStatus.value.trim(),
        e.target.value.trim(),
        selectSex.value.trim(),
        selectArrangeBy.value.trim(),
        selectOrder.value.trim(),
        selectNumCaisse.value.trim(),
        selectDateBy.value.trim(),
        selectPer.value.trim(),
        dateFrom.value.trim(),
        dateTo.value.trim(),
        selectMonth.value.trim(),
        selectYear.value.trim(),
        inputSearch.value.trim()
      );
      localStorage.setItem(e.target.id, e.target.value);
    });

    //=========EVENT select sex
    selectSex.addEventListener("change", (e) => {
      filterUser(
        tbody,
        container.querySelector("#chart-role"),
        container.querySelector("#chart-status"),
        selectStatus.value.trim(),
        selectRole.value.trim(),
        e.target.value.trim(),
        selectArrangeBy.value.trim(),
        selectOrder.value.trim(),
        selectNumCaisse.value.trim(),
        selectDateBy.value.trim(),
        selectPer.value.trim(),
        dateFrom.value.trim(),
        dateTo.value.trim(),
        selectMonth.value.trim(),
        selectYear.value.trim(),
        inputSearch.value.trim()
      );
      localStorage.setItem(e.target.id, e.target.value);
    });

    //=========EVENT select arrange_by
    selectArrangeBy.addEventListener("change", (e) => {
      filterUser(
        tbody,
        container.querySelector("#chart-role"),
        container.querySelector("#chart-status"),
        selectStatus.value.trim(),
        selectRole.value.trim(),
        selectSex.value.trim(),
        e.target.value.trim(),
        selectOrder.value.trim(),
        selectNumCaisse.value.trim(),
        selectDateBy.value.trim(),
        selectPer.value.trim(),
        dateFrom.value.trim(),
        dateTo.value.trim(),
        selectMonth.value.trim(),
        selectYear.value.trim(),
        inputSearch.value.trim()
      );
      localStorage.setItem(e.target.id, e.target.value);
    });
    //=========EVENT select order
    selectOrder.addEventListener("change", (e) => {
      filterUser(
        tbody,
        container.querySelector("#chart-role"),
        container.querySelector("#chart-status"),
        selectStatus.value.trim(),
        selectRole.value.trim(),
        selectSex.value.trim(),
        selectArrangeBy.value.trim(),
        e.target.value.trim(),
        selectNumCaisse.value.trim(),
        selectDateBy.value.trim(),
        selectPer.value.trim(),
        dateFrom.value.trim(),
        dateTo.value.trim(),
        selectMonth.value.trim(),
        selectYear.value.trim(),
        inputSearch.value.trim()
      );
      localStorage.setItem(e.target.id, e.target.value);
    });

    //=========EVENT select num_caisse
    selectNumCaisse.addEventListener("change", (e) => {
      filterUser(
        tbody,
        container.querySelector("#chart-role"),
        container.querySelector("#chart-status"),
        selectStatus.value.trim(),
        selectRole.value.trim(),
        selectSex.value.trim(),
        selectArrangeBy.value.trim(),
        selectOrder.value.trim(),
        e.target.value.trim(),
        selectDateBy.value.trim(),
        selectPer.value.trim(),
        dateFrom.value.trim(),
        dateTo.value.trim(),
        selectMonth.value.trim(),
        selectYear.value.trim(),
        inputSearch.value.trim()
      );
      localStorage.setItem(e.target.id, e.target.value);
    });

    //=========EVENT select per
    selectPer.addEventListener("change", (e) => {
      filterUser(
        tbody,
        container.querySelector("#chart-role"),
        container.querySelector("#chart-status"),
        selectStatus.value.trim(),
        selectRole.value.trim(),
        selectSex.value.trim(),
        selectArrangeBy.value.trim(),
        selectOrder.value.trim(),
        selectNumCaisse.value.trim(),
        selectDateBy.value.trim(),
        e.target.value.trim(),
        dateFrom.value.trim(),
        dateTo.value.trim(),
        selectMonth.value.trim(),
        selectYear.value.trim(),
        inputSearch.value.trim()
      );
      localStorage.setItem(e.target.id, e.target.value);
    });

    //=========EVENT date from
    dateFrom.addEventListener("input", (e) => {
      filterUser(
        tbody,
        container.querySelector("#chart-role"),
        container.querySelector("#chart-status"),
        selectStatus.value.trim(),
        selectRole.value.trim(),
        selectSex.value.trim(),
        selectArrangeBy.value.trim(),
        selectOrder.value.trim(),
        selectNumCaisse.value.trim(),
        selectDateBy.value.trim(),
        selectPer.value.trim(),
        e.target.value.trim(),
        dateTo.value.trim(),
        selectMonth.value.trim(),
        selectYear.value.trim(),
        inputSearch.value.trim()
      );
      localStorage.setItem(e.target.id, e.target.value);
      dateTo.min = e.target.value;
    });
    //=========EVENT date to
    dateTo.addEventListener("input", (e) => {
      filterUser(
        tbody,
        container.querySelector("#chart-role"),
        container.querySelector("#chart-status"),
        selectStatus.value.trim(),
        selectRole.value.trim(),
        selectSex.value.trim(),
        selectArrangeBy.value.trim(),
        selectOrder.value.trim(),
        selectNumCaisse.value.trim(),
        selectDateBy.value.trim(),
        selectPer.value.trim(),
        dateFrom.value.trim(),
        e.target.value.trim(),
        selectMonth.value.trim(),
        selectYear.value.trim(),
        inputSearch.value.trim()
      );
      localStorage.setItem(e.target.id, e.target.value);
      dateFrom.max = e.target.value;
    });

    //=========EVENT select month
    selectMonth.addEventListener("change", (e) => {
      filterUser(
        tbody,
        container.querySelector("#chart-role"),
        container.querySelector("#chart-status"),
        selectStatus.value.trim(),
        selectRole.value.trim(),
        selectSex.value.trim(),
        selectArrangeBy.value.trim(),
        selectOrder.value.trim(),
        selectNumCaisse.value.trim(),
        selectDateBy.value.trim(),
        selectPer.value.trim(),
        dateFrom.value.trim(),
        dateTo.value.trim(),
        e.target.value.trim(),
        selectYear.value.trim(),
        inputSearch.value.trim()
      );
      localStorage.setItem(e.target.id, e.target.value);
    });
    //=========EVENT select year
    selectYear.addEventListener("change", (e) => {
      filterUser(
        tbody,
        container.querySelector("#chart-role"),
        container.querySelector("#chart-status"),
        selectStatus.value.trim(),
        selectRole.value.trim(),
        selectSex.value.trim(),
        selectArrangeBy.value.trim(),
        selectOrder.value.trim(),
        selectNumCaisse.value.trim(),
        selectDateBy.value.trim(),
        selectPer.value.trim(),
        dateFrom.value.trim(),
        dateTo.value.trim(),
        selectMonth.value.trim(),
        e.target.value.trim(),
        inputSearch.value.trim()
      );
      localStorage.setItem(e.target.id, e.target.value);
    });

    //========================== ADD USER ================================
    //======elements add user
    //modal
    const modalAddUser = document.getElementById("modal-add-user");
    //input - add user name
    const inputAddUserName = modalAddUser.querySelector("#input-add-user-name");
    //input - add user first name
    const inputAddUserFirstName = modalAddUser.querySelector(
      "#input-add-user-first-name"
    );
    //input - add user email
    const inputAddUserEmail = modalAddUser.querySelector(
      "#input-add-user-email"
    );
    //btn show/hide password
    modalAddUser
      .querySelector("#input-add-user-mdp")
      .closest("div")
      .querySelector("button")
      .addEventListener("click", (e) => {
        e.target.innerHTML = "";
        if (
          modalAddUser.querySelector("#input-add-user-mdp").type === "password"
        ) {
          modalAddUser.querySelector("#input-add-user-mdp").type = "text";
          e.target.innerHTML = `<i class="fad fa-eye"></i>`;
        } else {
          modalAddUser.querySelector("#input-add-user-mdp").type = "password";
          e.target.innerHTML = `<i class="fad fa-eye-slash"></i>`;
        }
      });
    //form add user
    const formAddUser = modalAddUser.querySelector("form");
    //save input modal to local storage
    modalAddUser.querySelectorAll("input, select").forEach((element) => {
      if (element.type !== "password") {
        element.addEventListener("input", (e) => {
          localStorage.setItem(e.target.id, e.target.value);
        });
      }
    });
    //load input modal from local storage
    modalAddUser.querySelectorAll("input, select").forEach((element) => {
      if (element.type !== "password") {
        const savedInputValue = localStorage.getItem(element.id);
        if (savedInputValue) {
          element.value = savedInputValue;
        }
      }
    });

    //=====EVENT input add user name
    inputAddUserName.addEventListener("input", (e) => {
      e.target.value = e.target.value.replace("  ", " ").toUpperCase();
    });
    //=====EVENT input add user first name
    inputAddUserFirstName.addEventListener("input", (e) => {
      e.target.value = e.target.value.replace("  ", " ");
    });
    //=====EVENT input add user email
    inputAddUserEmail.addEventListener("input", (e) => {
      e.target.value = e.target.value.replace(" ", "");
    });
    //=====EVENT form add user submit
    formAddUser.addEventListener("submit", async (e) => {
      //suspend submit
      e.preventDefault();

      //inputs - not valid
      if (!e.target.checkValidity()) {
        e.target.reportValidity();
        return;
      } else {
        try {
          //FETCH api add user by admin
          const response = await apiRequest("/user/create_user", {
            method: "POST",
            body: {
              nom_utilisateur: inputAddUserName.value.trim(),
              prenoms_utilisateur: inputAddUserFirstName.value.trim(),
              sexe_utilisateur: modalAddUser
                .querySelector("#select-add-user-sex")
                .value.trim(),
              email_utilisateur: inputAddUserEmail.value.trim(),
              role: modalAddUser
                .querySelector("#select-add-user-role")
                .value.trim(),
              mdp: modalAddUser.querySelector("#input-add-user-mdp").value,
            },
          });

          //invalid
          if (response.message_type === "invalid") {
            //alert
            const alertTemplate = modalAddUser.querySelector(".alert-template");
            const clone = alertTemplate.content.cloneNode(true);
            const alert = clone.querySelector(".alert");
            const progressBar = alert.querySelector(".progress-bar");
            //alert type
            alert.classList.add("alert-warning");
            //icon
            alert.querySelector(".fad").classList.add("fa-exclamation-circle");
            //message
            alert.querySelector(".alert-message").innerHTML = response.message;
            //progress bar
            progressBar.style.transition = "width 10s linear";
            progressBar.style.width = "100%";

            //add alert
            formAddUser.querySelector(".modal-body").prepend(alert);

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
          else if (response.message_type === "error") {
            //alert
            const alertTemplate = modalAddUser.querySelector(".alert-template");
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
            progressBar.style.transition = "width 10s linear";
            progressBar.style.width = "100%";

            //add alert
            formAddUser.querySelector(".modal-body").prepend(alert);

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
            {
              //alert
              const alertTemplate =
                modalAddUser.querySelector(".alert-template");
              const clone = alertTemplate.content.cloneNode(true);
              const alert = clone.querySelector(".alert");
              const progressBar = alert.querySelector(".progress-bar");
              //alert type
              alert.classList.add("alert-success");
              //icon
              alert.querySelector(".fad").classList.add("fa-check-circle");
              //message
              alert.querySelector(".alert-message").innerHTML =
                response.message;
              //progress bar
              progressBar.style.transition = "width 10s linear";
              progressBar.style.width = "100%";

              //add alert
              tbody.closest("div").prepend(alert);

              //progress lanch animation
              setTimeout(() => {
                progressBar.style.width = "0%";
              }, 10);

              //auto close alert
              setTimeout(() => {
                alert.querySelector(".btn-close").click();
              }, 10000);

              //hide modal
              modalAddUser.querySelector("#btn-close-modal-add-user").click();

              //remove all input value from local storage
              modalAddUser
                .querySelectorAll("input, select")
                .forEach((element) => {
                  const saved = localStorage.getItem(element.id);
                  if (saved) {
                    localStorage.removeItem(element.id);
                  }
                });

              //refesh filter user
              filterUser(
                tbody,
                container.querySelector("#chart-role"),
                container.querySelector("#chart-status"),
                selectStatus.value.trim(),
                selectRole.value.trim(),
                selectSex.value.trim(),
                selectArrangeBy.value.trim(),
                selectOrder.value.trim(),
                selectNumCaisse.value.trim(),
                selectDateBy.value.trim(),
                selectPer.value.trim(),
                dateFrom.value.trim(),
                dateTo.value.trim(),
                selectMonth.value.trim(),
                selectYear.value.trim(),
                inputSearch.value.trim()
              );
            }
          }
        } catch (e) {
          console.error(e);
        }
      }
    });

    //=======================  DELETE USER ==========================
    //modal delete user
    const modalDeleteUser = document.getElementById("modal-delete-user");
    //btn delete user
    const btnDeleteUser = tbody
      .closest("table")
      .parentElement.querySelector("#btn-delete-user");

    //=====EVENT btn delete user
    btnDeleteUser.addEventListener("click", () => {
      //selected user
      const selectedUser = tbody.querySelectorAll(
        "input[type='checkbox']:checked"
      );

      //no selection
      if (selectedUser.length <= 0) {
        //alert
        const alertTemplate = document.getElementById("alert-template");
        const clone = alertTemplate.content.cloneNode(true);
        const alert = clone.querySelector(".alert");
        const progressBar = alert.querySelector(".progress-bar");
        //alert type
        alert.classList.add("alert-warning");
        //icon
        alert.querySelector(".fad").classList.add("fa-exclamation-circle");
        //message
        alert.querySelector(".alert-message").innerHTML =
          lang.user_ids_user_empty;
        //progress bar
        progressBar.style.transition = "width 10s linear";
        progressBar.style.width = "100%";

        //add alert
        tbody.closest("div").prepend(alert);

        //progress lanch animation
        setTimeout(() => {
          progressBar.style.width = "0%";
        }, 10);

        //auto close alert
        setTimeout(() => {
          alert.querySelector(".btn-close").click();
        }, 10000);

        return;
      }
      //selection
      else {
        //modal message 1
        if (selectedUser.length === 1) {
          modalDeleteUser.querySelector(".message").innerHTML =
            lang.question_delete_user_1.replace(
              ":field",
              selectedUser[0].closest("tr").dataset.userId
            );
        }
        //modal message plur
        else {
          modalDeleteUser.querySelector(".message").innerHTML =
            lang.question_delete_user_plur.replace(
              ":field",
              selectedUser.length
            );
        }

        //show modal delete user
        new bootstrap.Modal(modalDeleteUser).show();

        //====== EVENT btn confirm delete user
        modalDeleteUser
          .querySelector("#btn-confirm-delete-user")
          .addEventListener("click", async () => {
            try {
              //ids_user
              let ids_user = [...selectedUser];
              ids_user = ids_user.map(
                (selected) => selected.closest("tr").dataset.userId
              );

              //FETCH api delete all user
              const response = await apiRequest("/user/delete_all_user", {
                method: "PUT",
                body: { ids_user: ids_user },
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
                alert.querySelector(".alert-message").innerHTML =
                  response.message;
                //progress bar
                progressBar.style.transition = "width 20s linear";
                progressBar.style.width = "100%";

                //add alert
                tbody.closest("div").prepend(alert);

                //progress lanch animation
                setTimeout(() => {
                  progressBar.style.width = "0%";
                }, 10);

                //auto close alert
                setTimeout(() => {
                  alert.querySelector(".btn-close").click();
                }, 20000);

                return;
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
                alert
                  .querySelector(".fad")
                  .classList.add("fa-exclamation-circle");
                //message
                alert.querySelector(".alert-message").innerHTML =
                  response.message;
                //progress bar
                progressBar.style.transition = "width 10s linear";
                progressBar.style.width = "100%";

                //add alert
                tbody.closest("div").prepend(alert);

                //progress lanch animation
                setTimeout(() => {
                  progressBar.style.width = "0%";
                }, 10);

                //auto close alert
                setTimeout(() => {
                  alert.querySelector(".btn-close").click();
                }, 10000);

                return;
              }
              //succcess
              else {
                //alert
                const alertTemplate = document.getElementById("alert-template");
                const clone = alertTemplate.content.cloneNode(true);
                const alert = clone.querySelector(".alert");
                const progressBar = alert.querySelector(".progress-bar");
                //alert type
                alert.classList.add("alert-success");
                //icon
                alert.querySelector(".fad").classList.add("fa-check-circle");
                //message
                alert.querySelector(".alert-message").innerHTML =
                  response.message;
                //progress bar
                progressBar.style.transition = "width 10s linear";
                progressBar.style.width = "100%";

                //add alert
                tbody.closest("div").prepend(alert);

                //progress lanch animation
                setTimeout(() => {
                  progressBar.style.width = "0%";
                }, 10);

                //auto close alert
                setTimeout(() => {
                  alert.querySelector(".btn-close").click();
                }, 10000);

                //close modal
                modalDeleteUser
                  .querySelector("#btn-close-modal-delete-user")
                  .click();

                //refesh filter user
                filterUser(
                  tbody,
                  container.querySelector("#chart-role"),
                  container.querySelector("#chart-status"),
                  selectStatus.value.trim(),
                  selectRole.value.trim(),
                  selectSex.value.trim(),
                  selectArrangeBy.value.trim(),
                  selectOrder.value.trim(),
                  selectNumCaisse.value.trim(),
                  selectDateBy.value.trim(),
                  selectPer.value.trim(),
                  dateFrom.value.trim(),
                  dateTo.value.trim(),
                  selectMonth.value.trim(),
                  selectYear.value.trim(),
                  inputSearch.value.trim()
                );

                return;
              }
            } catch (e) {
              console.error(e);
            }
          });
      }
    });

    //=======================  DELETE PERMANENT USER ==========================
    //btn delete permanent user
    const btnDeletePermanentUser = tbody
      .closest("table")
      .parentElement.querySelector("#btn-delete-permanent-user");

    //=====EVENT btn delete permanent user
    btnDeletePermanentUser.addEventListener("click", () => {
      //selected user
      const selectedUser = tbody.querySelectorAll(
        "input[type='checkbox']:checked"
      );

      //no selection
      if (selectedUser.length <= 0) {
        //alert
        const alertTemplate = document.getElementById("alert-template");
        const clone = alertTemplate.content.cloneNode(true);
        const alert = clone.querySelector(".alert");
        const progressBar = alert.querySelector(".progress-bar");
        //alert type
        alert.classList.add("alert-warning");
        //icon
        alert.querySelector(".fad").classList.add("fa-exclamation-circle");
        //message
        alert.querySelector(".alert-message").innerHTML =
          lang.user_ids_user_empty;
        //progress bar
        progressBar.style.transition = "width 10s linear";
        progressBar.style.width = "100%";

        //add alert
        tbody.closest("div").prepend(alert);

        //progress lanch animation
        setTimeout(() => {
          progressBar.style.width = "0%";
        }, 10);

        //auto close alert
        setTimeout(() => {
          alert.querySelector(".btn-close").click();
        }, 10000);

        return;
      }
      //selection
      else {
        //modal message 1
        if (selectedUser.length === 1) {
          modalDeleteUser.querySelector(".message").innerHTML =
            lang.question_delete_permanent_user_1.replace(
              ":field",
              selectedUser[0].closest("tr").dataset.userId
            );
        }
        //modal message plur
        else {
          modalDeleteUser.querySelector(".message").innerHTML =
            lang.question_delete_permanent_user_plur.replace(
              ":field",
              selectedUser.length
            );
        }

        //show modal delete user
        new bootstrap.Modal(modalDeleteUser).show();

        //====== EVENT btn confirm delete user
        modalDeleteUser
          .querySelector("#btn-confirm-delete-user")
          .addEventListener("click", async () => {
            try {
              //ids_user
              let ids_user = [...selectedUser];
              ids_user = ids_user.map(
                (selected) => selected.closest("tr").dataset.userId
              );

              //FETCH api delete permanent all user
              const response = await apiRequest(
                "/user/delete_permanent_all_user",
                {
                  method: "DELETE",
                  body: { ids_user: ids_user },
                }
              );

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
                alert.querySelector(".alert-message").innerHTML =
                  response.message;
                //progress bar
                progressBar.style.transition = "width 20s linear";
                progressBar.style.width = "100%";

                //add alert
                tbody.closest("div").prepend(alert);

                //progress lanch animation
                setTimeout(() => {
                  progressBar.style.width = "0%";
                }, 10);

                //auto close alert
                setTimeout(() => {
                  alert.querySelector(".btn-close").click();
                }, 20000);

                return;
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
                alert
                  .querySelector(".fad")
                  .classList.add("fa-exclamation-circle");
                //message
                alert.querySelector(".alert-message").innerHTML =
                  response.message;
                //progress bar
                progressBar.style.transition = "width 10s linear";
                progressBar.style.width = "100%";

                //add alert
                tbody.closest("div").prepend(alert);

                //progress lanch animation
                setTimeout(() => {
                  progressBar.style.width = "0%";
                }, 10);

                //auto close alert
                setTimeout(() => {
                  alert.querySelector(".btn-close").click();
                }, 10000);

                return;
              }
              //succcess
              else {
                //alert
                const alertTemplate = document.getElementById("alert-template");
                const clone = alertTemplate.content.cloneNode(true);
                const alert = clone.querySelector(".alert");
                const progressBar = alert.querySelector(".progress-bar");
                //alert type
                alert.classList.add("alert-success");
                //icon
                alert.querySelector(".fad").classList.add("fa-check-circle");
                //message
                alert.querySelector(".alert-message").innerHTML =
                  response.message;
                //progress bar
                progressBar.style.transition = "width 10s linear";
                progressBar.style.width = "100%";

                //add alert
                tbody.closest("div").prepend(alert);

                //progress lanch animation
                setTimeout(() => {
                  progressBar.style.width = "0%";
                }, 10);

                //auto close alert
                setTimeout(() => {
                  alert.querySelector(".btn-close").click();
                }, 10000);

                //close modal
                modalDeleteUser
                  .querySelector("#btn-close-modal-delete-user")
                  .click();

                //refesh filter user
                filterUser(
                  tbody,
                  container.querySelector("#chart-role"),
                  container.querySelector("#chart-status"),
                  selectStatus.value.trim(),
                  selectRole.value.trim(),
                  selectSex.value.trim(),
                  selectArrangeBy.value.trim(),
                  selectOrder.value.trim(),
                  selectNumCaisse.value.trim(),
                  selectDateBy.value.trim(),
                  selectPer.value.trim(),
                  dateFrom.value.trim(),
                  dateTo.value.trim(),
                  selectMonth.value.trim(),
                  selectYear.value.trim(),
                  inputSearch.value.trim()
                );

                return;
              }
            } catch (e) {
              console.error(e);
            }
          });
      }
    });

    //======================= DECONNECT USER ==========================
    //modal deconnect user
    const modalDeconnectUser = document.getElementById("modal-deconnect-user");
    //btn deconnect user
    const btnDeconnectUser = tbody
      .closest("table")
      .parentElement.querySelector("#btn-deconnect-user");

    //=====EVENT btn deconnect user
    btnDeconnectUser.addEventListener("click", () => {
      //selected user
      const selectedUser = tbody.querySelectorAll(
        "input[type='checkbox']:checked"
      );

      //no selection
      if (selectedUser.length <= 0) {
        //alert
        const alertTemplate = document.getElementById("alert-template");
        const clone = alertTemplate.content.cloneNode(true);
        const alert = clone.querySelector(".alert");
        const progressBar = alert.querySelector(".progress-bar");
        //alert type
        alert.classList.add("alert-warning");
        //icon
        alert.querySelector(".fad").classList.add("fa-exclamation-circle");
        //message
        alert.querySelector(".alert-message").innerHTML =
          lang.user_ids_user_empty;
        //progress bar
        progressBar.style.transition = "width 10s linear";
        progressBar.style.width = "100%";

        //add alert
        tbody.closest("div").prepend(alert);

        //progress lanch animation
        setTimeout(() => {
          progressBar.style.width = "0%";
        }, 10);

        //auto close alert
        setTimeout(() => {
          alert.querySelector(".btn-close").click();
        }, 10000);

        return;
      }
      //selection
      else {
        //modal message 1
        if (selectedUser.length === 1) {
          modalDeconnectUser.querySelector(".message").innerHTML =
            lang.question_deconnect_user_1.replace(
              ":field",
              selectedUser[0].closest("tr").dataset.userId
            );
        }
        //modal message plur
        else {
          modalDeconnectUser.querySelector(".message").innerHTML =
            lang.question_deconnect_user_plur.replace(
              ":field",
              selectedUser.length
            );
        }

        //show modal deconnect user
        new bootstrap.Modal(modalDeconnectUser).show();

        //====== EVENT btn confirm deconnect user
        modalDeconnectUser
          .querySelector("#btn-confirm-deconnect-user")
          .addEventListener("click", async () => {
            try {
              //ids_user
              let ids_user = [...selectedUser];
              ids_user = ids_user.map(
                (selected) => selected.closest("tr").dataset.userId
              );

              //FETCH api delete permanent all user
              const response = await apiRequest("/user/deconnect_all_user", {
                method: "PUT",
                body: { ids_user: ids_user },
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
                alert.querySelector(".alert-message").innerHTML =
                  response.message;
                //progress bar
                progressBar.style.transition = "width 20s linear";
                progressBar.style.width = "100%";

                //add alert
                tbody.closest("div").prepend(alert);

                //progress lanch animation
                setTimeout(() => {
                  progressBar.style.width = "0%";
                }, 10);

                //auto close alert
                setTimeout(() => {
                  alert.querySelector(".btn-close").click();
                }, 20000);

                return;
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
                alert
                  .querySelector(".fad")
                  .classList.add("fa-exclamation-circle");
                //message
                alert.querySelector(".alert-message").innerHTML =
                  response.message;
                //progress bar
                progressBar.style.transition = "width 10s linear";
                progressBar.style.width = "100%";

                //add alert
                tbody.closest("div").prepend(alert);

                //progress lanch animation
                setTimeout(() => {
                  progressBar.style.width = "0%";
                }, 10);

                //auto close alert
                setTimeout(() => {
                  alert.querySelector(".btn-close").click();
                }, 10000);

                return;
              }
              //succcess
              else {
                //alert
                const alertTemplate = document.getElementById("alert-template");
                const clone = alertTemplate.content.cloneNode(true);
                const alert = clone.querySelector(".alert");
                const progressBar = alert.querySelector(".progress-bar");
                //alert type
                alert.classList.add("alert-success");
                //icon
                alert.querySelector(".fad").classList.add("fa-check-circle");
                //message
                alert.querySelector(".alert-message").innerHTML =
                  response.message;
                //progress bar
                progressBar.style.transition = "width 10s linear";
                progressBar.style.width = "100%";

                //add alert
                tbody.closest("div").prepend(alert);

                //progress lanch animation
                setTimeout(() => {
                  progressBar.style.width = "0%";
                }, 10);

                //auto close alert
                setTimeout(() => {
                  alert.querySelector(".btn-close").click();
                }, 10000);

                //close modal
                modalDeconnectUser
                  .querySelector("#btn-close-modal-deconnect-user")
                  .click();

                //refesh filter user
                filterUser(
                  tbody,
                  container.querySelector("#chart-role"),
                  container.querySelector("#chart-status"),
                  selectStatus.value.trim(),
                  selectRole.value.trim(),
                  selectSex.value.trim(),
                  selectArrangeBy.value.trim(),
                  selectOrder.value.trim(),
                  selectNumCaisse.value.trim(),
                  selectDateBy.value.trim(),
                  selectPer.value.trim(),
                  dateFrom.value.trim(),
                  dateTo.value.trim(),
                  selectMonth.value.trim(),
                  selectYear.value.trim(),
                  inputSearch.value.trim()
                );

                return;
              }
            } catch (e) {
              console.error(e);
            }
          });
      }
    });

    //====================== PRINT ALL USER ===========================
    //modal print all user
    const modalPrintAllUser = document.getElementById("modal-print-all-user");
    //a - print all user
    const aPrintAllUser = document.getElementById("a-print-all-user");

    //===== EVENT a print all user
    aPrintAllUser.addEventListener("click", () => {
      //show modal print all user
      new bootstrap.Modal(modalPrintAllUser).show();

      //===== EVENT btn confirm print all user
      modalPrintAllUser
        .querySelector("#btn-confirm-print-all-user")
        .addEventListener("click", async () => {
          //radio status
          const radioStatusUser = modalPrintAllUser.querySelector(
            "input[type='radio']:checked"
          );
          
          try {
            //FETCH api print all user
            const response = await apiRequest(
              `/user/print_all_user?status=${radioStatusUser.value.trim()}`
            );

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
              alert.querySelector(".alert-message").innerHTML =
                response.message;
              //progress bar
              progressBar.style.transition = "width 20s linear";
              progressBar.style.width = "100%";

              //add alert
              modalPrintAllUser.querySelector(".modal-body").prepend(alert);

              //progress lanch animation
              setTimeout(() => {
                progressBar.style.width = "0%";
              }, 10);

              //auto close alert
              setTimeout(() => {
                alert.querySelector(".btn-close").click();
              }, 20000);

              return;
            }

            //download user report list
            const a = document.createElement("a");
            a.href = `data:application/pdf;base64,${response.pdf}`;
            a.download = response.file_name;
            a.click();

            //close modal print all user
            modalPrintAllUser
              .querySelector("#btn-close-modal-print-all-user")
              .click();

            return;
          } catch (e) {
            console.error(e);
          }
        });
    });
  }, 1050);

  //================ FUNCTIONS ================

  //function - filter user
  async function filterUser(
    tbody,
    divChartRole,
    divChartStatus,
    status,
    role,
    sexe,
    arrange_by,
    order,
    num_caisse,
    date_by,
    per,
    from,
    to,
    month,
    year,
    search_user
  ) {
    try {
      //FETCH api
      const response = await apiRequest(
        `/user/filter_user?status=${status}&role=${role}&sexe=${sexe}&arrange_by=${arrange_by}&order=${order}&num_caisse=${num_caisse}&date_by=${date_by}&per=${per}&from=${from}&to=${to}&month=${month}&year=${year}&search_user=${search_user}`
      );

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
        alert.querySelector(".fad").classList.add("fa-exclamation-triangle");
        //message
        alert.querySelector(".alert-message").innerHTML = response.message;
        //progress bar
        progressBar.style.transition = "width 20s linear";
        progressBar.style.width = "100%";

        //add alert
        tbody.closest("div").prepend(alert);

        //progress lanch animation
        setTimeout(() => {
          progressBar.style.width = "0%";
        }, 10);

        //auto close alert
        setTimeout(() => {
          alert.querySelector(".btn-close").click();
        }, 20000);

        return;
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
        alert.querySelector(".fad").classList.add("fa-exclamation-circle");
        //message
        alert.querySelector(".alert-message").innerHTML = response.message;
        //progress bar
        progressBar.style.transition = "width 10s linear";
        progressBar.style.width = "100%";

        //add alert
        tbody.closest("div").prepend(alert);

        //progress lanch animation
        setTimeout(() => {
          progressBar.style.width = "0%";
        }, 10);

        //auto close alert
        setTimeout(() => {
          alert.querySelector(".btn-close").click();
        }, 10000);

        return;
      }

      //chart effectie title
      const chartEffectieTitle = document.getElementById(
        "chart-effective-title"
      );
      chartEffectieTitle.innerHTML = ` (${formatterNumber.format(
        Number(response.nb_user)
      )})`;

      //====chart role
      //canvas chart role
      const canvasChartRole = document.createElement("canvas");
      new Chart(canvasChartRole, {
        type: "doughnut",
        data: {
          labels: [lang.admin, lang.cashier],
          datasets: [
            {
              label: lang.role,
              data: [response.nb_admin, response.nb_caissier],
              backgroundColor: ["tomato", "#1da033ff"],
              borderColor: "white",
              borderRadius: 5,
            },
          ],
        },
        options: {
          responsive: true,
          cutout: "75%",
          plugins: {
            title: {
              display: true,
              position: "top",
              text: lang.role,
            },
            legend: { position: "bottom", align: "center" },
          },
        },
      });
      divChartRole.innerHTML = "";
      divChartRole.append(canvasChartRole);

      //======chart status
      const canvasCharStatus = document.createElement("canvas");
      new Chart(canvasCharStatus, {
        type: "doughnut",
        data: {
          labels: [lang.connected, lang.disconnected, lang.deleted],
          datasets: [
            {
              label: lang.status,
              data: [
                response.nb_connected,
                response.nb_disconnected,
                response.nb_deleted,
              ],
              backgroundColor: ["green", "blue", "red"],
              borderColor: "white",
              borderRadius: 5,
            },
          ],
        },
        options: {
          responsive: true,
          cutout: "75%",
          plugins: {
            title: {
              display: true,
              position: "top",
              text: lang.status,
            },
            legend: { position: "bottom", align: "center" },
          },
        },
      });
      divChartStatus.innerHTML = "";
      divChartStatus.append(canvasCharStatus);

      //table set list
      tbody.innerHTML = "";
      response.data.forEach((line) => {
        const tr = document.createElement("tr");

        //checkbox
        const tdCheckbox = document.createElement("td");
        const inputCheckbox = document.createElement("input");
        inputCheckbox.type = "checkbox";
        inputCheckbox.classList.add("form-check-input");
        tdCheckbox.appendChild(inputCheckbox);

        //id
        const tdId = document.createElement("td");
        tdId.textContent = line.id_utilisateur;

        //fullname
        const tdFullName = document.createElement("td");
        tdFullName.textContent = line.fullname;

        //sex
        const tdSex = document.createElement("td");
        if (line.sexe_utilisateur === "masculin") {
          tdSex.textContent = lang.male.toLowerCase();
        } else {
          tdSex.textContent = lang.female.toLowerCase();
        }
        tdSex.classList.add("form-text");

        //email
        const tdEmail = document.createElement("td");
        tdEmail.textContent = line.email_utilisateur;

        //role
        const tdRole = document.createElement("td");
        if (line.role === "admin") {
          tdRole.style.color = "red";
          tdRole.textContent = lang.admin.toLowerCase();
        } else {
          tdRole.style.color = "grey";
          tdRole.textContent = lang.cashier.toLowerCase();
        }
        tdRole.classList.add("form-text");

        //status
        const tdStatus = document.createElement("td");
        if (line.etat_utilisateur === "connected") {
          tdStatus.classList.add("text-success");
          tdStatus.textContent = lang.connected.toLowerCase();
        } else if (line.etat_utilisateur === "disconnected") {
          tdStatus.classList.add("text-secondary");
          tdStatus.textContent = lang.disconnected.toLowerCase();
        } else {
          tdStatus.classList.add("text-dark");
          tdStatus.textContent = lang.deleted.toLowerCase();
        }
        tdStatus.classList.add("form-text");

        //cash
        const tdCash = document.createElement("td");
        tdCash.textContent = line.num_caisse;
        tdCash.classList.add("text-center");

        //action
        const tdAction = document.createElement("td");
        tdAction.innerHTML = `<button class='btn btn-sm btn-light text-primary'><i class='fad fa-pen-to-square'></i></button>`;
        tdAction.classList.add("text-center");

        //append
        tr.append(
          tdCheckbox,
          tdId,
          tdFullName,
          tdSex,
          tdEmail,
          tdRole,
          tdStatus,
          tdCash,
          tdAction
        );

        tr.dataset.userId = line.id_utilisateur;
        tr.dataset.nbAe = line.nb_ae;
        tr.dataset.nbFacture = line.nb_facture;
        tr.dataset.nbSortie = line.nb_sortie;
        tr.dataset.totalAe = line.total_ae;
        tr.dataset.totalFacture = line.total_facture;
        tr.dataset.totalSortie = line.total_sortie;
        tr.dataset.userName = line.nom_utilisateur;
        tr.dataset.userFirstName = line.prenoms_utilisateur;
        tr.dataset.userSex = line.sexe_utilisateur;
        tr.dataset.userEmail = line.email_utilisateur;
        tr.dataset.userRole = line.role;
        tbody.appendChild(tr);
      });

      //=========EVENT tr selection
      //div chart nb_transactions
      const divChartNbTransactions = document.getElementById(
        "chart-nb-transactions"
      );
      //div chart total transactions
      const divChartTotalTransactions = document.getElementById(
        "chart-total-transactions"
      );
      //div chart transactions histo nb_transactions
      const divChartHistoNbTransactions = document.getElementById(
        "chart-histo-nb-transactions"
      );
      //div chart transactions histo total_transactions
      const divChartHistoTotalTransactions = document.getElementById(
        "chart-histo-total-transactions"
      );
      //foreach all tr
      const allTr = tbody.querySelectorAll("tr");
      selectedRow = null;
      allTr.forEach((tr) => {
        //========= EVENT tr selection chart
        tr.addEventListener("click", async () => {
          //remove selection
          if (selectedRow && selectedRow === tr) {
            tr.classList.remove("active");
            selectedRow = null;

            //reset chart nb_transactions
            divChartNbTransactions.innerHTML = `<h6 class="placeholder rounded-1 w-25 bg-second mb-3"></h6>
                                        <div class="rounded-circle bg-second placeholder mb-2" style="aspect-ratio: 1/1;width: 35% !important;"></div>
                                        <!-- legends -->
                                        <div class="d-flex justify-content-center gap-2 w-75">
                                            <h6 class="placeholder rounded-1 w-25 bg-second"></h6>
                                            <h6 class="placeholder rounded-1 w-25 bg-second"></h6>
                                            <h6 class="placeholder rounded-1 w-25 bg-second"></h6>
                                        </div>`;
            //reset chart total_transactions
            divChartTotalTransactions.innerHTML = `<h6 class="placeholder rounded-1 w-25 bg-second mb-3"></h6>
                                        <div class="rounded-circle bg-second placeholder mb-2" style="aspect-ratio: 1/1;width: 35% !important;"></div>
                                        <!-- legends -->
                                        <div class="d-flex justify-content-center gap-2 w-75">
                                            <h6 class="placeholder rounded-1 w-25 bg-second"></h6>
                                            <h6 class="placeholder rounded-1 w-25 bg-second"></h6>
                                            <h6 class="placeholder rounded-1 w-25 bg-second"></h6>
                                        </div>`;
            //reset chart histo nb_transactions
            divChartHistoNbTransactions.innerHTML = `   <h6 class="placeholder rounded-1 w-25 bg-second mb-3"></h6>
                                        <div class="bg-second placeholder mb-2 w-75 rounded-1" style="height: 15vh !important;"></div>
                                        <!-- legends -->
                                        <div class="d-flex justify-content-center gap-2 w-75">
                                            <h6 class="placeholder rounded-1 w-25 bg-second"></h6>
                                            <h6 class="placeholder rounded-1 w-25 bg-second"></h6>
                                            <h6 class="placeholder rounded-1 w-25 bg-second"></h6>
                                        </div>`;
            //reset chart histo total_transactions
            divChartHistoTotalTransactions.innerHTML = ` <h6 class="placeholder rounded-1 w-25 bg-second mb-3"></h6>
                                        <div class="bg-second placeholder mb-2 w-75 rounded-1" style="height: 15vh !important;"></div>
                                        <!-- legends -->
                                        <div class="d-flex justify-content-center gap-2 w-75">
                                            <h6 class="placeholder rounded-1 w-25 bg-second"></h6>
                                            <h6 class="placeholder rounded-1 w-25 bg-second"></h6>
                                            <h6 class="placeholder rounded-1 w-25 bg-second"></h6>
                                        </div>`;
          }
          //add selection
          else {
            //deselect all
            allTr.forEach((tr0) => {
              tr0.classList.remove("active");
            });
            //add selection
            tr.classList.add("active");
            selectedRow = tr;

            //=========show chart nb_transactions
            chartNbTransactions(
              divChartNbTransactions,
              tr.dataset.nbAe,
              tr.dataset.nbFacture,
              tr.dataset.nbSortie
            );
            //=========show chart total transactions
            chartTotalTransactions(
              divChartTotalTransactions,
              tr.dataset.totalAe,
              tr.dataset.totalFacture,
              tr.dataset.totalSortie
            );

            //=========FETCH list transactions
            //get list autre entree
            const autreEntreeEffective = await apiRequest(
              `/entree/list_all_autre_entree?num_caisse=${num_caisse}&id_utilisateur=${tr.dataset.userId}&date_by=${date_by}&per=${per}&from=${from}&to=${to}&month=${month}&year=${year}`
            );
            //get list facture
            const factureEffective = await apiRequest(
              `/entree/list_all_facture?num_caisse=${num_caisse}&id_utilisateur=${tr.dataset.userId}&date_by=${date_by}&per=${per}&from=${from}&to=${to}&month=${month}&year=${year}`
            );
            //get list sortie
            const sortieEffective = await apiRequest(
              `/sortie/list_all_demande_sortie?num_caisse=${num_caisse}&id_utilisateur=${tr.dataset.userId}&date_by=${date_by}&per=${per}&from=${from}&to=${to}&month=${month}&year=${year}`
            );

            //error autre entree effective
            if (autreEntreeEffective.message_type !== "success") {
              divChartHisto.innerHTML = `<div class='alert alert-danger form-text'><i class='fad fa-exclamation-circle me-2'></i>${autreEntreeEffective.message}'</div>`;
            }
            //error facture effective
            else if (factureEffective.message_type !== "success") {
              divChartHisto.innerHTML = `<div class='alert alert-danger form-text'><i class='fad fa-exclamation-circle me-2'></i>${factureEffective.message}'</div>`;
            }
            //error sortie effective
            else if (sortieEffective.message_type !== "success") {
              divChartHisto.innerHTML = `<div class='alert alert-danger form-text'><i class='fad fa-exclamation-circle me-2'></i>${sortieEffective.message}'</div>`;
            }
            //success effective transactions
            else {
              //all dates
              const allDates = [
                ...new Set([
                  ...autreEntreeEffective.data.map((d) => d.date),
                  ...factureEffective.data.map((d) => d.date),
                  ...sortieEffective.data.map((d) => d.date),
                ]),
              ].sort();

              //==show chart histo nb_transactions
              chartHistoNbTransactions(
                divChartHistoNbTransactions,
                allDates,
                autreEntreeEffective,
                factureEffective,
                sortieEffective
              );

              //==show chart histo total_transactions
              chartHistoTotalTransactions(
                divChartHistoTotalTransactions,
                allDates,
                autreEntreeEffective,
                factureEffective,
                sortieEffective
              );
            }
          }
        });
        //========= EVENT btn update user
        tr.querySelector("button").addEventListener("click", () => {
          //modal update user
          const modalUpdateUser = document.getElementById("modal-update-user");
          //account number
          modalUpdateUser.querySelector("#account-number").innerHTML =
            tr.dataset.userId;
          //input - update user name
          const inputUpdateUserName = modalUpdateUser.querySelector(
            "#input-update-user-name"
          );
          inputUpdateUserName.addEventListener("input", (e) => {
            e.target.value = e.target.value.replace("  ", " ").toUpperCase();
          });
          inputUpdateUserName.value = tr.dataset.userName;
          //input - update user first name
          const inputUpdateUserFirstName = modalUpdateUser.querySelector(
            "#input-update-user-first-name"
          );
          inputUpdateUserFirstName.addEventListener("input", (e) => {
            e.target.value = e.target.value.replace("  ", " ");
          });
          inputUpdateUserFirstName.value = tr.dataset.userFirstName;
          //select - update user sex
          modalUpdateUser.querySelector("#select-update-user-sex").value =
            tr.dataset.userSex;
          //input -update user email
          const inputUpdateUserEmail = modalUpdateUser.querySelector(
            "#input-update-user-email"
          );
          inputUpdateUserEmail.addEventListener("input", (e) => {
            e.target.value = e.target.value.replace(" ", "");
          });
          inputUpdateUserEmail.value = tr.dataset.userEmail;
          //select - update user role
          modalUpdateUser.querySelector("#select-update-user-role").value =
            tr.dataset.userRole;

          //show modal
          new bootstrap.Modal(modalUpdateUser).show();

          //=====EVENT form update user submit
          modalUpdateUser
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
                  //FETCH api update user by admin
                  const response = await apiRequest(
                    "/user/update_user_by_admin",
                    {
                      method: "PUT",
                      body: {
                        id_utilisateur: modalUpdateUser
                          .querySelector("#account-number")
                          .textContent.trim(),
                        nom_utilisateur: inputUpdateUserName.value.trim(),
                        prenoms_utilisateur:
                          inputUpdateUserFirstName.value.trim(),
                        sexe_utilisateur: modalUpdateUser
                          .querySelector("#select-update-user-sex")
                          .value.trim(),
                        email_utilisateur: inputUpdateUserEmail.value.trim(),
                        role: modalUpdateUser
                          .querySelector("#select-update-user-role")
                          .value.trim(),
                        mdp: modalUpdateUser.querySelector(
                          "#input-update-user-mdp"
                        ).value,
                      },
                    }
                  );

                  //invalid
                  if (response.message_type === "invalid") {
                    //alert
                    const alertTemplate =
                      modalUpdateUser.querySelector(".alert-template");
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
                      response.message;
                    //progress bar
                    progressBar.style.transition = "width 10s linear";
                    progressBar.style.width = "100%";

                    //add alert
                    modalUpdateUser.querySelector(".modal-body").prepend(alert);

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
                  else if (response.message_type === "error") {
                    //alert
                    const alertTemplate =
                      modalUpdateUser.querySelector(".alert-template");
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
                      response.message;
                    //progress bar
                    progressBar.style.transition = "width 10s linear";
                    progressBar.style.width = "100%";

                    //add alert
                    modalUpdateUser.querySelector(".modal-body").prepend(alert);

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
                    {
                      //alert
                      const alertTemplate =
                        modalUpdateUser.querySelector(".alert-template");
                      const clone = alertTemplate.content.cloneNode(true);
                      const alert = clone.querySelector(".alert");
                      const progressBar = alert.querySelector(".progress-bar");
                      //alert type
                      alert.classList.add("alert-success");
                      //icon
                      alert
                        .querySelector(".fad")
                        .classList.add("fa-check-circle");
                      //message
                      alert.querySelector(".alert-message").innerHTML =
                        response.message;
                      //progress bar
                      progressBar.style.transition = "width 10s linear";
                      progressBar.style.width = "100%";

                      //add alert
                      tbody.closest("div").prepend(alert);

                      //progress lanch animation
                      setTimeout(() => {
                        progressBar.style.width = "0%";
                      }, 10);

                      //auto close alert
                      setTimeout(() => {
                        alert.querySelector(".btn-close").click();
                      }, 10000);

                      //hide modal
                      modalUpdateUser
                        .querySelector("#btn-close-modal-update-user")
                        .click();

                      //refesh filter user
                      filterUser(
                        tbody,
                        container.querySelector("#chart-role"),
                        container.querySelector("#chart-status"),
                        selectStatus.value.trim(),
                        selectRole.value.trim(),
                        selectSex.value.trim(),
                        selectArrangeBy.value.trim(),
                        selectOrder.value.trim(),
                        selectNumCaisse.value.trim(),
                        selectDateBy.value.trim(),
                        selectPer.value.trim(),
                        dateFrom.value.trim(),
                        dateTo.value.trim(),
                        selectMonth.value.trim(),
                        selectYear.value.trim(),
                        inputSearch.value.trim()
                      );
                    }
                  }
                } catch (e) {
                  console.error(e);
                }
              }
            });
        });
      });

      //=======EVENT SELECT all
      const checkAll = document.getElementById("check-all");
      checkAll.addEventListener("change", (e) =>
        allTr.forEach((tr) => {
          tr.querySelector("input[type='checkbox']").checked = e.target.checked;
        })
      );
    } catch (e) {
      console.error(e);
    }
  }

  //function - chart nb_transactions
  function chartNbTransactions(
    divChartNbTransactions,
    nb_ae,
    nb_facture,
    nb_sortie
  ) {
    divChartNbTransactions.innerHTML = "";
    const canvasNbTransactions = document.createElement("canvas");
    new Chart(canvasNbTransactions, {
      type: "doughnut",
      data: {
        labels: [lang.autre_entree, lang.bill, lang.outflow],
        datasets: [
          {
            label: lang.nb_transactions,
            data: [nb_ae, nb_facture, nb_sortie],
            backgroundColor: ["#CBF3F0", "#2EC4B6", "#E63946"],
            borderColor: "white",
            borderRadius: 5,
          },
        ],
      },
      options: {
        responsive: true,
        cutout: "75%",
        plugins: {
          title: {
            display: true,
            position: "top",
            text: `${lang.nb_transactions} (${formatterNumber.format(
              Number(nb_ae) + Number(nb_facture) + Number(nb_sortie)
            )})`,
          },
          legend: { position: "bottom", align: "center" },
        },
      },
    });
    divChartNbTransactions.append(canvasNbTransactions);
  }
  //function - chart total_transactions
  function chartTotalTransactions(
    divChartTotalTransactions,
    total_ae,
    total_facture,
    total_sortie
  ) {
    divChartTotalTransactions.innerHTML = "";
    const canvasTotalTransactions = document.createElement("canvas");
    new Chart(canvasTotalTransactions, {
      type: "doughnut",
      data: {
        labels: [lang.autre_entree, lang.bill, lang.outflow],
        datasets: [
          {
            label: lang.total_transactions,
            data: [total_ae, total_facture, total_sortie],
            backgroundColor: ["#CBF3F0", "#2EC4B6", "#E63946"],
            borderColor: "white",
            borderRadius: 5,
          },
        ],
      },
      options: {
        responsive: true,
        cutout: "75%",
        plugins: {
          title: {
            display: true,
            position: "top",
            text: `${lang.total_transactions} (${formatterTotal.format(
              Number(total_ae) + Number(total_facture) + Number(total_sortie)
            )})`,
          },
          legend: { position: "bottom", align: "center" },
        },
      },
    });
    divChartTotalTransactions.append(canvasTotalTransactions);
  }
  //function - chart histo nb_transactions
  function chartHistoNbTransactions(
    divChartHistoNbTransactions,
    allDates,
    autreEntreeEffective,
    factureEffective,
    sortieEffective
  ) {
    Chart.defaults.locale = cookieLangValue;

    //count par date
    const countNbTotalAutreEntree = countNbTotal(autreEntreeEffective);
    const countNbTotalFacture = countNbTotal(factureEffective);
    const countNbTotalSortie = countNbTotal(sortieEffective);

    //=====show chart transactions curves nb
    const canvasHistoNbTransactions = document.createElement("canvas");
    new Chart(canvasHistoNbTransactions, {
      type: "line",
      data: {
        labels: allDates,
        datasets: [
          {
            label: lang.autre_entree,
            data: prepareCount(countNbTotalAutreEntree, allDates),
            borderColor: "#00ffeeff",
            borderWidth: 1,
            backgroundColor: "#cbf3f0a9",
            // barThickness: 10,
            borderRadius: 5,
          },
          {
            label: lang.bill,
            data: prepareCount(countNbTotalFacture, allDates),
            borderColor: "#01a7b9ff",
            borderWidth: 1,
            backgroundColor: "#2ec4b5a1",
            // barThickness: 10,
            borderRadius: 5,
          },
          {
            label: lang.outflow,
            data: prepareCount(countNbTotalSortie, allDates),
            borderColor: "#e10618b9",
            borderWidth: 1,
            backgroundColor: "#e63947b0",
            // barThickness: 10,
            borderRadius: 5,
          },
        ],
      },
      options: {
        responsive: true,
        plugins: {
          title: {
            display: true,
            text: lang.per_nb_trans,
          },
          legend: { display: true, position: "bottom", align: "center" },
        },
        scales: {
          x: {
            title: {
              display: true,
              text: lang.date,
            },
            type: "time",
            time: { unit: "day" },
          },
          y: {
            title: { display: true, text: lang.number },
            beginAtZero: true,
            ticks: { stepSize: 1 },
          },
        },
        zoom: {
          // zoom: {
          //   wheel: {
          //     enabled: true,
          //     minScale: 0.5,
          //     maxScale: 10,
          //     wheelEvent: "wheel",
          //   },
          //   pinch: { enabled: true },
          //   pan: {
          //     enabled: true,
          //     mode: "xy",
          //     modifierKey: "alt",
          //   },
          //   drag: {
          //     mode: "xy",
          //     enabled: true,
          //     backgroundColor: "red",
          //     animation: 100,
          //   },
          // },
        },
      },
      plugins: [ChartZoom],
    });
    divChartHistoNbTransactions.innerHTML = "";
    divChartHistoNbTransactions.append(canvasHistoNbTransactions);
  }
  //function - chart histo total_transactions
  function chartHistoTotalTransactions(
    divChartHistoTotalTransactions,
    allDates,
    autreEntreeEffective,
    factureEffective,
    sortieEffective
  ) {
    Chart.defaults.locale = cookieLangValue;

    //count par date
    const countNbTotalAutreEntree = countNbTotal(autreEntreeEffective);
    const countNbTotalFacture = countNbTotal(factureEffective);
    const countNbTotalSortie = countNbTotal(sortieEffective);

    //=====show chart transactions curves nb
    const canvasHistoTotalTransactions = document.createElement("canvas");
    new Chart(canvasHistoTotalTransactions, {
      type: "line",
      data: {
        labels: allDates,
        datasets: [
          {
            label: lang.autre_entree,
            data: prepareTotal(countNbTotalAutreEntree, allDates),
            borderColor: "#00ffeeff",
            borderWidth: 1,
            backgroundColor: "#cbf3f0a9",
            // barThickness: 10,
            borderRadius: 5,
          },
          {
            label: lang.bill,
            data: prepareTotal(countNbTotalFacture, allDates),
            borderColor: "#01a7b9ff",
            borderWidth: 1,
            backgroundColor: "#2ec4b5a1",
            // barThickness: 10,
            borderRadius: 5,
          },
          {
            label: lang.outflow,
            data: prepareTotal(countNbTotalSortie, allDates),
            borderColor: "#e10618b9",
            borderWidth: 1,
            backgroundColor: "#e63947b0",
            // barThickness: 10,
            borderRadius: 5,
          },
        ],
      },
      options: {
        responsive: true,
        plugins: {
          title: {
            display: true,
            text: `${lang.per_total_trans}`,
          },
          legend: { display: true, position: "bottom", align: "center" },
        },
        scales: {
          x: {
            title: {
              display: true,
              text: lang.date,
            },
            type: "time",
            time: { unit: "day" },
          },
          y: {
            title: {
              display: true,
              text: `${lang.total} (${currencyUnits})`,
            },
            beginAtZero: true,
            ticks: { stepSize: 1 },
          },
        },
        zoom: {
          // zoom: {
          //   wheel: {
          //     enabled: true,
          //     minScale: 0.5,
          //     maxScale: 10,
          //     wheelEvent: "wheel",
          //   },
          //   pinch: { enabled: true },
          //   pan: {
          //     enabled: true,
          //     mode: "xy",
          //     modifierKey: "alt",
          //   },
          //   drag: {
          //     mode: "xy",
          //     enabled: true,
          //     backgroundColor: "red",
          //     animation: 100,
          //   },
          // },
        },
      },
      plugins: [ChartZoom],
    });
    divChartHistoTotalTransactions.innerHTML = "";
    divChartHistoTotalTransactions.append(canvasHistoTotalTransactions);
  }

  //function - count nb && total per date
  const countNbTotal = (effective) => {
    const result = {};

    effective.data.forEach((item) => {
      if (!result[item.date]) {
        //object date {} not exist
        result[item.date] = {
          count: 0,
          total: 0,
        };
      }
      //increment
      result[item.date].count++;
      result[item.date].total += Number(item.montant);
    });
    return result;
  };
  //function - prepare count
  const prepareCount = (countNbTotal, allDates) =>
    allDates.map((date) => countNbTotal[date]?.count || 0);
  //function - prepare total
  const prepareTotal = (countNbTotal, allDates) =>
    allDates.map((date) => countNbTotal[date]?.total || 0);

  //function - list num_caisse
  async function listNumCaisse(selectNumCaisse, tbody) {
    try {
      const response = await apiRequest("/caisse/list_all_caisse");
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
        alert.querySelector(".fad").classList.add("fa-exclamation-triangle");
        //message
        alert.querySelector(".alert-message").innerHTML = response.message;
        //progress bar
        progressBar.style.transition = "width 20s linear";
        progressBar.style.width = "100%";

        //add alert
        tbody.closest("div").prepend(alert);

        //progress lanch animation
        setTimeout(() => {
          progressBar.style.width = "0%";
        }, 10);

        //auto close alert
        setTimeout(() => {
          alert.querySelector(".btn-close").click();
        }, 20000);

        return;
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
        alert.querySelector(".fad").classList.add("fa-exclamation-circle");
        //message
        alert.querySelector(".alert-message").innerHTML = response.message;
        //progress bar
        progressBar.style.transition = "width 10s linear";
        progressBar.style.width = "100%";

        //add alert
        tbody.closest("div").prepend(alert);

        //progress lanch animation
        setTimeout(() => {
          progressBar.style.width = "0%";
        }, 10);

        //auto close alert
        setTimeout(() => {
          alert.querySelector(".btn-close").click();
        }, 10000);

        return;
      }

      selectNumCaisse.innerHTML = "";

      //option all
      const optionAll = document.createElement("option");
      optionAll.selected = true;
      optionAll.value = "all";
      optionAll.innerText = lang.all;

      //append option all
      selectNumCaisse.append(optionAll);

      response.data.forEach((line) => {
        const option = document.createElement("option");
        option.value = line.num_caisse;
        option.innerText = line.num_caisse;

        selectNumCaisse.append(option);
      });
    } catch (e) {
      console.error(e);
    }
  }
  // //function - print all user
  // async function printAllUser(status = "active") {
  //   try {
  //     const response = await apiRequest(
  //       `/user/print_all_user?status=${status}`
  //     );
  //     //not success
  //     if (response.message_type !== "success") {
  //       console.log(response);
  //     }
  //     //success
  //     else {
  //       const a = document.createElement("a");
  //       a.href = `data:application/pdf;base64,${response.pdf}`;
  //       a.download = response.file_name;
  //       a.click();
  //       console.log(response.message);
  //     }
  //   } catch (error) {
  //     console.error(error);
  //   }
  // }
  // //==================== EVENTS ===================
  // const from = document.getElementById("from");
  // const to = document.getElementById("to");
  // //btn test
  // const btnTest = document.getElementById("btn-test");
  // btnTest.addEventListener("click", () => {
  //   // deconnectAll([10000, 10001, 10005, 10003]);
  //   // deconnectAll([1045000, 10004, 10005, 10003]);
  //   printAllUser();
  // });
});
