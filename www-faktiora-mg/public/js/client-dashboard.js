document.addEventListener("DOMContentLoaded", async () => {
  //template real content
  const templateRealContent = document.getElementById("template-client");
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
    formatterTotal = new Intl.NumberFormat("fr-FR", {
      style: "currency",
      currency: currencyUnits,
      minimumFractionDigits: 0,
      maximumFractionDigits: 2,
    });
  }

  //TIMEOUT
  setTimeout(async () => {
    //load template real
    container.append(templateRealContent.content.cloneNode(true));

    //========================= SIDEBAR ====================
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

    //===================== SEARCH BAR =====================
    //btn searchbar
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
    //overlay toggsle searcbar
    overlaySearchBar.addEventListener("click", () => {
      overlaySearchBar.classList.toggle("active");
      searchBar.classList.toggle("active");
    });

    //===== EVENT date_by change
    //select - date_by
    const selectDateBy = searchBar.querySelector("#select-date-by");
    const savedSelectDateBy = localStorage.getItem(selectDateBy.id);
    selectDateBy.value = !savedSelectDateBy ? "all" : savedSelectDateBy;
    //load select date by value from local storage
    switch (selectDateBy.value) {
      //per
      case "per":
        searchBar.querySelector("#div-per").classList.add("active");
        break;
      //between
      case "between":
        searchBar.querySelector("#div-between").classList.add("active");
        break;
      //month_year
      case "month_year":
        searchBar.querySelector("#div-month_year").classList.add("active");
        break;
    }
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

    //===== ELEMENTS searchbar
    //input - search
    const inputSearch = document.getElementById("input-search");
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
    //select - sex
    const selectSex = document.getElementById("select-sex");
    const savedSelectSex = localStorage.getItem(selectSex.id);
    selectSex.value = !savedSelectSex ? "all" : savedSelectSex;
    //select - arrange_by
    const selectArrangeBy = document.getElementById("select-arrange-by");
    const savedSelectArrangeBy = localStorage.getItem(selectArrangeBy.id);
    selectArrangeBy.value = !savedSelectArrangeBy ? "id" : savedSelectArrangeBy;
    //select - order
    const selectOrder = document.getElementById("select-order");
    const savedSelectOrder = localStorage.getItem(selectOrder.id);
    selectOrder.value = !savedSelectOrder ? "asc" : savedSelectOrder;
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
      //reset select sex
      selectSex.value = "all";
      localStorage.removeItem(selectSex.id);
      //reset select arrange_by
      selectArrangeBy.value = "id";
      localStorage.removeItem(selectArrangeBy.id);
      //reset order
      selectOrder.value = "asc";
      localStorage.removeItem(selectOrder.id);
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

    //===== EVENT input search
    inputSearch.addEventListener("input", (e) => {
      filterClient(
        selectStatus.value.trim(),
        selectSex.value.trim(),
        selectArrangeBy.value.trim(),
        selectOrder.value.trim(),
        selectDateBy.value.trim(),
        selectPer.value.trim(),
        dateFrom.value.trim(),
        dateTo.value.trim(),
        selectMonth.value.trim(),
        selectYear.value.trim(),
        e.target.value.trim()
      );
      inputSearchSearchBar.value = e.target.value;
      localStorage.setItem(e.target.id, e.target.value);
    });
    //===== EVENT select status
    selectStatus.addEventListener("change", (e) => {
      filterClient(
        e.target.value.trim(),
        selectSex.value.trim(),
        selectArrangeBy.value.trim(),
        selectOrder.value.trim(),
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
    //===== EVENT select sex
    selectSex.addEventListener("change", (e) => {
      filterClient(
        selectStatus.value.trim(),
        e.target.value.trim(),
        selectArrangeBy.value.trim(),
        selectOrder.value.trim(),
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
    //===== EVENT select arrange_by
    selectArrangeBy.addEventListener("change", (e) => {
      filterClient(
        selectStatus.value.trim(),
        selectSex.value.trim(),
        e.target.value.trim(),
        selectOrder.value.trim(),
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
    //===== EVENT select order
    selectOrder.addEventListener("change", (e) => {
      filterClient(
        selectStatus.value.trim(),
        selectSex.value.trim(),
        selectArrangeBy.value.trim(),
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
    //===== EVENT select per
    selectPer.addEventListener("change", (e) => {
      filterClient(
        selectStatus.value.trim(),
        selectSex.value.trim(),
        selectArrangeBy.value.trim(),
        selectOrder.value.trim(),
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
    //===== EVENT date from
    dateFrom.addEventListener("input", (e) => {
      filterClient(
        selectStatus.value.trim(),
        selectSex.value.trim(),
        selectArrangeBy.value.trim(),
        selectOrder.value.trim(),
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
    //===== EVENT date to
    dateTo.addEventListener("input", (e) => {
      filterClient(
        selectStatus.value.trim(),
        selectSex.value.trim(),
        selectArrangeBy.value.trim(),
        selectOrder.value.trim(),
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
    //===== EVENT select month
    selectMonth.addEventListener("change", (e) => {
      filterClient(
        selectStatus.value.trim(),
        selectSex.value.trim(),
        selectArrangeBy.value.trim(),
        selectOrder.value.trim(),
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
    //===== EVENT select year
    selectYear.addEventListener("change", (e) => {
      filterClient(
        selectStatus.value.trim(),
        selectSex.value.trim(),
        selectArrangeBy.value.trim(),
        selectOrder.value.trim(),
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

    //======================== FILTER CLIENT ==================
    filterClient(
      selectStatus.value.trim(),
      selectSex.value.trim(),
      selectArrangeBy.value.trim(),
      selectOrder.value.trim(),
      selectDateBy.value.trim(),
      selectPer.value.trim(),
      dateFrom.value.trim(),
      dateTo.value.trim(),
      selectMonth.value.trim(),
      selectYear.value.trim(),
      inputSearch.value.trim()
    );

    //======================== ADD CLIENT ======================
    //modal add client
    const modalAddClient = container.querySelector("#modal-add-client");
    //input - add nom_client
    const inputAddNomClient = modalAddClient.querySelector(
      "#input-add-nom-client"
    );
    const savedInputAddNomClient = localStorage.getItem(inputAddNomClient.id);
    inputAddNomClient.value = !savedInputAddNomClient
      ? ""
      : savedInputAddNomClient;
    //input - add prenoms_client
    const inputAddPrenomsClient = modalAddClient.querySelector(
      "#input-add-prenoms-client"
    );
    const savedInputAddPrenomsClient = localStorage.getItem(
      inputAddPrenomsClient.id
    );
    inputAddPrenomsClient.value = !savedInputAddPrenomsClient
      ? ""
      : savedInputAddPrenomsClient;
    //select - add sexe_client
    const selectAddSexeClient = modalAddClient.querySelector(
      "#select-add-sexe-client"
    );
    const savedSelectAddSexeClient = localStorage.getItem(
      selectAddSexeClient.id
    );
    selectAddSexeClient.value = !savedSelectAddSexeClient
      ? "masculin"
      : savedSelectAddSexeClient;
    //input - add telephone
    const inputAddTelephone = modalAddClient.querySelector(
      "#input-add-telephone"
    );
    const savedInputAddTelephone = localStorage.getItem(inputAddTelephone.id);
    inputAddTelephone.value = !savedInputAddTelephone
      ? ""
      : savedInputAddTelephone;
    //input - add adresse
    const inputAddAdresse = modalAddClient.querySelector("#input-add-adresse");
    const savedInputAddAdresse = localStorage.getItem(inputAddAdresse.id);
    inputAddAdresse.value = !savedInputAddAdresse ? "" : savedInputAddAdresse;

    //===== EVENT input add nom_client
    inputAddNomClient.addEventListener("input", (e) => {
      //remove double space && change into uppercase
      e.target.value = e.target.value.replace("  ", " ").toUpperCase();

      //save value into localstorage
      localStorage.setItem(e.target.id, e.target.value);
    });
    //===== EVENT input add prenoms_client
    inputAddPrenomsClient.addEventListener("input", (e) => {
      //remove double space && change into uppercase
      e.target.value = e.target.value.replace("  ", " ");

      //save value into localstorage
      localStorage.setItem(e.target.id, e.target.value);
    });
    //===== EVENT select add sexe_client
    selectAddSexeClient.addEventListener("change", (e) => {
      //save value into localstorage
      localStorage.setItem(e.target.id, e.target.value);
    });
    //===== EVENT input add telephone
    inputAddTelephone.addEventListener("input", (e) => {
      //remove invalid
      e.target.value = e.target.value.replace(/[^\d+\s]/g, "");
      //remove double space
      e.target.value = e.target.value.replace("  ", " ");

      //save value into localstorage
      localStorage.setItem(e.target.id, e.target.value);
    });
    //===== EVENT input add adresse
    inputAddAdresse.addEventListener("input", (e) => {
      //remove double space
      e.target.value = e.target.value.replace("  ", " ");

      //save value into localstorage
      localStorage.setItem(e.target.id, e.target.value);
    });

    //===== EVENT form add client submit
    modalAddClient
      .querySelector("form")
      .addEventListener("submit", async (e) => {
        //suspend submit
        e.preventDefault();
        //check validity
        if (!e.target.checkValidity()) {
          e.target.reportValidity();
        }

        try {
          //FETCH api add client
          const apiAddClient = await apiRequest("/client/create_client", {
            method: "POST",
            body: {
              nom_client: inputAddNomClient.value.trim(),
              prenoms_client: inputAddPrenomsClient.value.trim(),
              sexe_client: selectAddSexeClient.value.trim(),
              telephone: inputAddTelephone.value.trim(),
              adresse: inputAddAdresse.value.trim(),
            },
          });

          //error
          if (apiAddClient.message_type === "error") {
            //alert
            const alertTemplate = document.querySelector(".alert-template");
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
              apiAddClient.message;
            //progress bar
            progressBar.style.transition = "width 20s linear";
            progressBar.style.width = "100%";

            //add alert
            modalAddClient.querySelector(".modal-body").prepend(alert);

            //progress launch animation
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
          else if (apiAddClient.message_type === "invalid") {
            //alert
            const alertTemplate = document.querySelector(".alert-template");
            const clone = alertTemplate.content.cloneNode(true);
            const alert = clone.querySelector(".alert");
            const progressBar = alert.querySelector(".progress-bar");
            //alert type
            alert.classList.add("alert-warning");
            //icon
            alert.querySelector(".fad").classList.add("fa-exclamation-circle");
            //message
            alert.querySelector(".alert-message").innerHTML =
              apiAddClient.message;
            //progress bar
            progressBar.style.transition = "width 10s linear";
            progressBar.style.width = "100%";

            //add alert
            modalAddClient.querySelector(".modal-body").prepend(alert);

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

          //success add client
          //alert
          const alertTemplate = document.querySelector(".alert-template");
          const clone = alertTemplate.content.cloneNode(true);
          const alert = clone.querySelector(".alert");
          const progressBar = alert.querySelector(".progress-bar");
          //alert type
          alert.classList.add("alert-success");
          //icon
          alert.querySelector(".fad").classList.add("fa-check-circle");
          //message
          alert.querySelector(".alert-message").innerHTML =
            apiAddClient.message;
          //progress bar
          progressBar.style.transition = "width 10s linear";
          progressBar.style.width = "100%";

          //add alert
          container
            .querySelector("#tbody-client")
            .closest("div")
            .prepend(alert);

          //progress lanch animation
          setTimeout(() => {
            progressBar.style.width = "0%";
          }, 10);
          //auto close alert
          setTimeout(() => {
            alert.querySelector(".btn-close").click();
          }, 10000);

          //auto close modal
          modalAddClient.querySelector("#btn-close-modal-add-client").click();

          //refresh filter client
          filterClient(
            selectStatus.value.trim(),
            selectSex.value.trim(),
            selectArrangeBy.value.trim(),
            selectOrder.value.trim(),
            selectDateBy.value.trim(),
            selectPer.value.trim(),
            dateFrom.value.trim(),
            dateTo.value.trim(),
            selectMonth.value.trim(),
            selectYear.value.trim(),
            inputSearch.value.trim()
          );

          return;
        } catch (e) {
          console.error(e);
        }
      });
  }, 1050);

  //====================== FUNCTIONS ========================

  //function - filter client
  async function filterClient(
    status,
    sexe,
    arrange_by,
    order,
    date_by,
    per,
    from,
    to,
    month,
    year,
    search_client
  ) {
    //tbody
    const tbodyClient = container.querySelector("#tbody-client");

    try {
      //FETCH api filter client
      const apiFilterClient = await apiRequest(
        `/client/filter_client?status=${status}&sexe=${sexe}&arrange_by=${arrange_by}&order=${order}&date_by=${date_by}&per=${per}&from=${from}&to=${to}&month=${month}&year=${year}&search_client=${search_client}`
      );

      //error
      if (apiFilterClient.message_type === "error") {
        //alert
        const alertTemplate = document.querySelector(".alert-template");
        const clone = alertTemplate.content.cloneNode(true);
        const alert = clone.querySelector(".alert");
        const progressBar = alert.querySelector(".progress-bar");
        //alert type
        alert.classList.add("alert-danger");
        //icon
        alert.querySelector(".fad").classList.add("fa-exclamation-triangle");
        //message
        alert.querySelector(".alert-message").innerHTML =
          apiFilterClient.message;
        //progress bar
        progressBar.style.transition = "width 20s linear";
        progressBar.style.width = "100%";

        //add alert
        tbodyClient.closest("div").prepend(alert);

        //progress launch animation
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
      else if (apiFilterClient.message_type === "invalid") {
        //alert
        const alertTemplate = document.querySelector(".alert-template");
        const clone = alertTemplate.content.cloneNode(true);
        const alert = clone.querySelector(".alert");
        const progressBar = alert.querySelector(".progress-bar");
        //alert type
        alert.classList.add("alert-warning");
        //icon
        alert.querySelector(".fad").classList.add("fa-exclamation-circle");
        //message
        alert.querySelector(".alert-message").innerHTML =
          apiFilterClient.message;
        //progress bar
        progressBar.style.transition = "width 10s linear";
        progressBar.style.width = "100%";

        //add alert
        tbodyClient.closest("div").prepend(alert);

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

      //===== TABLE client
      //set table list client
      tbodyClient.innerHTML = "";
      apiFilterClient.data.forEach((line) => {
        const tr = document.createElement("tr");

        //td - id_client
        const tdIdClient = document.createElement("td");
        tdIdClient.textContent = line.id_client;
        tdIdClient.classList.add("text-center");

        //td - fullname
        const tdFullName = document.createElement("td");
        tdFullName.textContent = line.fullname;
        tdFullName.classList.add("text-center");

        //td - sex
        const tdSex = document.createElement("td");
        if (line.sexe_client === "masculin") {
          tdSex.textContent = lang.male_m;
        } else {
          tdSex.textContent = lang.female_f;
        }
        tdSex.classList.add("text-center", "form-text", "text-secondary");

        //td - phone
        const tdPhone = document.createElement("td");
        tdPhone.textContent = line.telephone;
        tdPhone.classList.add("text-center");

        //td - address
        const tdAddress = document.createElement("td");
        tdAddress.textContent = line.adresse;
        tdAddress.classList.add("text-center");

        //td - status
        const tdStatus = document.createElement("td");
        if (line.etat_client === "supprimé") {
          tdStatus.textContent = lang.deleted;
        } else {
          tdStatus.textContent = lang.active.toLowerCase();
        }
        tdStatus.classList.add("text-center");

        //td - nb_facture
        const tdNbFacture = document.createElement("td");
        tdNbFacture.textContent = formatterNumber.format(
          Number(line.nb_facture)
        );
        tdNbFacture.classList.add("text-center");

        //td - total_facture
        const tdTotalFacture = document.createElement("td");
        tdTotalFacture.textContent = formatterNumber.format(
          Number(line.total_facture)
        );
        tdTotalFacture.classList.add("text-center");

        //td - actions
        const tdActions = document.createElement("td");
        //btn update client
        const btnUpdateClient = document.createElement("button");
        btnUpdateClient.type = "button";
        btnUpdateClient.classList.add(
          "btn-light",
          "btn",
          "btn-sm",
          "text-primary"
        );
        btnUpdateClient.innerHTML = "<i class='fad fa-user-pen'></i>";
        //btn view facture
        const btnViewFacture = document.createElement("button");
        btnViewFacture.type = "button";
        btnViewFacture.classList.add("btn-light", "btn", "btn-sm");
        btnViewFacture.innerHTML =
          "<i class='fad fa-chart-mixed-up-circle-dollar'></i>";
        //append btn actions
        const divActions = document.createElement("div");
        divActions.classList.add(
          "d-flex",
          "justify-content-center",
          "align-items-center",
          "gap-2"
        );
        divActions.append(btnUpdateClient, btnViewFacture);
        tdActions.append(divActions);

        //append
        tr.append(
          tdIdClient,
          tdFullName,
          tdSex,
          tdPhone,
          tdAddress,
          tdStatus,
          tdNbFacture,
          tdTotalFacture,
          tdActions
        );
        tbodyClient.appendChild(tr);
      });
    } catch (e) {
      console.error(e);
    }
  }

  // //function - caisse
  // async function chartCaisse(date_by, per, from, to, month, year) {
  //   //div chart nb_transactions
  //   const divChartNbTransactions = container.querySelector(
  //     "#chart-nb-transactions"
  //   );
  //   //div chart total transactions
  //   const divChartTotalTransactions = document.getElementById(
  //     "chart-total-transactions"
  //   );
  //   //div chart transactions histo nb_transactions
  //   const divChartHistoNbTransactions = document.getElementById(
  //     "chart-histo-nb-transactions"
  //   );
  //   //div chart transactions histo total_transactions
  //   const divChartHistoTotalTransactions = document.getElementById(
  //     "chart-histo-total-transactions"
  //   );

  //   try {
  //     //===== FETCH
  //     //FETCH ae effective
  //     const autreEntreeEffective = await apiRequest(
  //       `/entree/list_all_autre_entree?date_by=${date_by}&per=${per}&from=${from}&to=${to}&month=${month}&year=${year}`
  //     );
  //     //FETCH facture effective
  //     const factureEffective = await apiRequest(
  //       `/entree/list_all_facture?date_by=${date_by}&per=${per}&from=${from}&to=${to}&month=${month}&year=${year}`
  //     );
  //     //FETCH sortie effective
  //     const sortieEffective = await apiRequest(
  //       `/sortie/list_all_demande_sortie?date_by=${date_by}&per=${per}&from=${from}&to=${to}&month=${month}&year=${year}`
  //     );

  //     //error autre entree effective
  //     if (autreEntreeEffective.message_type !== "success") {
  //       //alert
  //       const alertTemplate = document.querySelector(".alert-template");
  //       const clone = alertTemplate.content.cloneNode(true);
  //       const alert = clone.querySelector(".alert");
  //       const progressBar = alert.querySelector(".progress-bar");
  //       //alert type
  //       alert.classList.add("alert-danger");
  //       //icon
  //       alert.querySelector(".fad").classList.add("fa-exclamation-triangle");
  //       //message
  //       alert.querySelector(".alert-message").innerHTML =
  //         autreEntreeEffective.message;
  //       //progress bar
  //       progressBar.style.transition = "width 20s linear";
  //       progressBar.style.width = "100%";

  //       //add alert
  //       container.querySelector(".searchbar .alert-container").prepend(alert);

  //       //progress lanch animation
  //       setTimeout(() => {
  //         progressBar.style.width = "0%";
  //       }, 10);
  //       //auto close alert
  //       setTimeout(() => {
  //         alert.querySelector(".btn-close").click();
  //       }, 20000);

  //       return;
  //     }
  //     //error facture effective
  //     else if (factureEffective.message_type !== "success") {
  //       //alert
  //       const alertTemplate = document.querySelector(".alert-template");
  //       const clone = alertTemplate.content.cloneNode(true);
  //       const alert = clone.querySelector(".alert");
  //       const progressBar = alert.querySelector(".progress-bar");
  //       //alert type
  //       alert.classList.add("alert-danger");
  //       //icon
  //       alert.querySelector(".fad").classList.add("fa-exclamation-triangle");
  //       //message
  //       alert.querySelector(".alert-message").innerHTML =
  //         factureEffective.message;
  //       //progress bar
  //       progressBar.style.transition = "width 20s linear";
  //       progressBar.style.width = "100%";

  //       //add alert
  //       container.querySelector(".searchbar .alert-container").prepend(alert);

  //       //progress lanch animation
  //       setTimeout(() => {
  //         progressBar.style.width = "0%";
  //       }, 10);
  //       //auto close alert
  //       setTimeout(() => {
  //         alert.querySelector(".btn-close").click();
  //       }, 20000);

  //       return;
  //     }
  //     //error sortie effective
  //     else if (sortieEffective.message_type !== "success") {
  //       //alert
  //       const alertTemplate = document.querySelector(".alert-template");
  //       const clone = alertTemplate.content.cloneNode(true);
  //       const alert = clone.querySelector(".alert");
  //       const progressBar = alert.querySelector(".progress-bar");
  //       //alert type
  //       alert.classList.add("alert-danger");
  //       //icon
  //       alert.querySelector(".fad").classList.add("fa-exclamation-triangle");
  //       //message
  //       alert.querySelector(".alert-message").innerHTML =
  //         sortieEffective.message;
  //       //progress bar
  //       progressBar.style.transition = "width 20s linear";
  //       progressBar.style.width = "100%";

  //       //add alert
  //       container.querySelector(".searchbar .alert-container").prepend(alert);

  //       //progress lanch animation
  //       setTimeout(() => {
  //         progressBar.style.width = "0%";
  //       }, 10);
  //       //auto close alert
  //       setTimeout(() => {
  //         alert.querySelector(".btn-close").click();
  //       }, 20000);

  //       return;
  //     }

  //     //success effective transactions
  //     //all dates
  //     const allDates = [
  //       ...new Set([
  //         ...autreEntreeEffective.data.map((d) => d.date),
  //         ...factureEffective.data.map((d) => d.date),
  //         ...sortieEffective.data.map((d) => d.date),
  //       ]),
  //     ].sort();

  //     //===== show chart nb_transactions
  //     chartNbTransactions(
  //       divChartNbTransactions,
  //       autreEntreeEffective.nb_ae,
  //       factureEffective.nb_facture,
  //       sortieEffective.nb_sortie
  //     );
  //     //===== show chart total_transactions
  //     chartTotalTransactions(
  //       divChartTotalTransactions,
  //       autreEntreeEffective.total_ae,
  //       factureEffective.total_facture,
  //       sortieEffective.total_sortie
  //     );

  //     //rest
  //     let rest =
  //         Number(autreEntreeEffective.total_ae) +
  //         Number(factureEffective.total_facture) -
  //         Number(sortieEffective.total_sortie),
  //       status;
  //     //loss
  //     if (rest < 0) {
  //       status = `(<span class='text-danger'>${
  //         lang.loss
  //       } ${formatterTotal.format(rest)}</span>)`;
  //     }
  //     //neutral
  //     else if (rest === 0) {
  //       status = `(<span class='text-secondary'>${lang.neutral}</span>)`;
  //     }
  //     //benefice
  //     else {
  //       status = `(<span class='text-success'>${
  //         lang.benefice
  //       } +${formatterTotal.format(rest)}</span>)`;
  //     }
  //     //chart histo title
  //     const chartHistoTransactionsTitle = document.getElementById(
  //       "chart-histo-transactions-title"
  //     );
  //     chartHistoTransactionsTitle.innerHTML = `<i class="fad fa-chart-mixed-up-circle-dollar me-2"></i>${lang.curves_transactions} ${status}`;
  //     //===== show chart histo nb_transactions
  //     chartHistoNbTransactions(
  //       divChartHistoNbTransactions,
  //       allDates,
  //       autreEntreeEffective,
  //       factureEffective,
  //       sortieEffective
  //     );
  //     //===== show chart histo total_transactions
  //     chartHistoTotalTransactions(
  //       divChartHistoTotalTransactions,
  //       allDates,
  //       autreEntreeEffective,
  //       factureEffective,
  //       sortieEffective
  //     );
  //   } catch (e) {
  //     console.error(e);
  //   }
  // }
  // //function chart nb_transactions
  // function chartNbTransactions(
  //   divChartNbTransactions,
  //   nb_ae,
  //   nb_facture,
  //   nb_sortie
  // ) {
  //   divChartNbTransactions.innerHTML = "";
  //   const canvasNbTransactions = document.createElement("canvas");
  //   new Chart(canvasNbTransactions, {
  //     type: "doughnut",
  //     data: {
  //       labels: [lang.autre_entree, lang.bill, lang.outflow],
  //       datasets: [
  //         {
  //           label: lang.nb_transactions,
  //           data: [nb_ae, nb_facture, nb_sortie],
  //           backgroundColor: ["#CBF3F0", "#2EC4B6", "#E63946"],
  //           borderColor: "white",
  //           borderRadius: 5,
  //         },
  //       ],
  //     },
  //     options: {
  //       responsive: true,
  //       cutout: "75%",
  //       plugins: {
  //         title: {
  //           display: true,
  //           position: "top",
  //           text: `${lang.nb_transactions} (${formatterNumber.format(
  //             Number(nb_ae) + Number(nb_facture) + Number(nb_sortie)
  //           )})`,
  //         },
  //         legend: { position: "bottom", align: "center" },
  //       },
  //     },
  //   });
  //   divChartNbTransactions.append(canvasNbTransactions);
  // }
  // //function - chart total_transactions
  // function chartTotalTransactions(
  //   divChartTotalTransactions,
  //   total_ae,
  //   total_facture,
  //   total_sortie
  // ) {
  //   divChartTotalTransactions.innerHTML = "";
  //   const canvasTotalTransactions = document.createElement("canvas");
  //   new Chart(canvasTotalTransactions, {
  //     type: "doughnut",
  //     data: {
  //       labels: [lang.autre_entree, lang.bill, lang.outflow],
  //       datasets: [
  //         {
  //           label: lang.total_transactions,
  //           data: [total_ae, total_facture, total_sortie],
  //           backgroundColor: ["#CBF3F0", "#2EC4B6", "#E63946"],
  //           borderColor: "white",
  //           borderRadius: 5,
  //         },
  //       ],
  //     },
  //     options: {
  //       responsive: true,
  //       cutout: "75%",
  //       plugins: {
  //         title: {
  //           display: true,
  //           position: "top",
  //           text: `${lang.total_transactions} (${formatterTotal.format(
  //             Number(total_ae) + Number(total_facture) + Number(total_sortie)
  //           )})`,
  //         },
  //         legend: { position: "bottom", align: "center" },
  //       },
  //     },
  //   });
  //   divChartTotalTransactions.append(canvasTotalTransactions);
  // }
  // //function - chart histo nb_transactions
  // function chartHistoNbTransactions(
  //   divChartHistoNbTransactions,
  //   allDates,
  //   autreEntreeEffective,
  //   factureEffective,
  //   sortieEffective
  // ) {
  //   Chart.defaults.locale = cookieLangValue;
  //   //count par date
  //   const countNbTotalAutreEntree = countNbTotal(autreEntreeEffective);
  //   const countNbTotalFacture = countNbTotal(factureEffective);
  //   const countNbTotalSortie = countNbTotal(sortieEffective);
  //   //=====show chart transactions curves nb
  //   const canvasHistoNbTransactions = document.createElement("canvas");
  //   new Chart(canvasHistoNbTransactions, {
  //     type: "line",
  //     data: {
  //       labels: allDates,
  //       datasets: [
  //         {
  //           label: lang.autre_entree,
  //           data: prepareCount(countNbTotalAutreEntree, allDates),
  //           borderColor: "#00ffeeff",
  //           borderWidth: 1,
  //           backgroundColor: "#cbf3f0a9",
  //           // barThickness: 10,
  //           borderRadius: 5,
  //         },
  //         {
  //           label: lang.bill,
  //           data: prepareCount(countNbTotalFacture, allDates),
  //           borderColor: "#01a7b9ff",
  //           borderWidth: 1,
  //           backgroundColor: "#2ec4b5a1",
  //           // barThickness: 10,
  //           borderRadius: 5,
  //         },
  //         {
  //           label: lang.outflow,
  //           data: prepareCount(countNbTotalSortie, allDates),
  //           borderColor: "#e10618b9",
  //           borderWidth: 1,
  //           backgroundColor: "#e63947b0",
  //           // barThickness: 10,
  //           borderRadius: 5,
  //         },
  //       ],
  //     },
  //     options: {
  //       responsive: true,
  //       plugins: {
  //         title: {
  //           display: true,
  //           text: lang.per_nb_trans,
  //         },
  //         legend: { display: true, position: "bottom", align: "center" },
  //       },
  //       scales: {
  //         x: {
  //           title: {
  //             display: true,
  //             text: lang.date,
  //           },
  //           type: "time",
  //           time: { unit: "day" },
  //         },
  //         y: {
  //           title: { display: true, text: lang.number },
  //           beginAtZero: true,
  //           ticks: { stepSize: 1 },
  //         },
  //       },
  //       zoom: {
  //         // zoom: {
  //         //   wheel: {
  //         //     enabled: true,
  //         //     minScale: 0.5,
  //         //     maxScale: 10,
  //         //     wheelEvent: "wheel",
  //         //   },
  //         //   pinch: { enabled: true },
  //         //   pan: {
  //         //     enabled: true,
  //         //     mode: "xy",
  //         //     modifierKey: "alt",
  //         //   },
  //         //   drag: {
  //         //     mode: "xy",
  //         //     enabled: true,
  //         //     backgroundColor: "red",
  //         //     animation: 100,
  //         //   },
  //         // },
  //       },
  //     },
  //     plugins: [ChartZoom],
  //   });
  //   divChartHistoNbTransactions.innerHTML = "";
  //   divChartHistoNbTransactions.append(canvasHistoNbTransactions);
  // }
  // //function - chart histo total_transactions
  // function chartHistoTotalTransactions(
  //   divChartHistoTotalTransactions,
  //   allDates,
  //   autreEntreeEffective,
  //   factureEffective,
  //   sortieEffective
  // ) {
  //   Chart.defaults.locale = cookieLangValue;
  //   //count par date
  //   const countNbTotalAutreEntree = countNbTotal(autreEntreeEffective);
  //   const countNbTotalFacture = countNbTotal(factureEffective);
  //   const countNbTotalSortie = countNbTotal(sortieEffective);
  //   //=====show chart transactions curves nb
  //   const canvasHistoTotalTransactions = document.createElement("canvas");
  //   new Chart(canvasHistoTotalTransactions, {
  //     type: "line",
  //     data: {
  //       labels: allDates,
  //       datasets: [
  //         {
  //           label: lang.autre_entree,
  //           data: prepareTotal(countNbTotalAutreEntree, allDates),
  //           borderColor: "#00ffeeff",
  //           borderWidth: 1,
  //           backgroundColor: "#cbf3f0a9",
  //           // barThickness: 10,
  //           borderRadius: 5,
  //         },
  //         {
  //           label: lang.bill,
  //           data: prepareTotal(countNbTotalFacture, allDates),
  //           borderColor: "#01a7b9ff",
  //           borderWidth: 1,
  //           backgroundColor: "#2ec4b5a1",
  //           // barThickness: 10,
  //           borderRadius: 5,
  //         },
  //         {
  //           label: lang.outflow,
  //           data: prepareTotal(countNbTotalSortie, allDates),
  //           borderColor: "#e10618b9",
  //           borderWidth: 1,
  //           backgroundColor: "#e63947b0",
  //           // barThickness: 10,
  //           borderRadius: 5,
  //         },
  //       ],
  //     },
  //     options: {
  //       responsive: true,
  //       plugins: {
  //         title: {
  //           display: true,
  //           text: `${lang.per_total_trans}`,
  //         },
  //         legend: { display: true, position: "bottom", align: "center" },
  //       },
  //       scales: {
  //         x: {
  //           title: {
  //             display: true,
  //             text: lang.date,
  //           },
  //           type: "time",
  //           time: { unit: "day" },
  //         },
  //         y: {
  //           title: {
  //             display: true,
  //             text: `${lang.total} (${currencyUnits})`,
  //           },
  //           beginAtZero: true,
  //           ticks: { stepSize: 1 },
  //         },
  //       },
  //       zoom: {
  //         // zoom: {
  //         //   wheel: {
  //         //     enabled: true,
  //         //     minScale: 0.5,
  //         //     maxScale: 10,
  //         //     wheelEvent: "wheel",
  //         //   },
  //         //   pinch: { enabled: true },
  //         //   pan: {
  //         //     enabled: true,
  //         //     mode: "xy",
  //         //     modifierKey: "alt",
  //         //   },
  //         //   drag: {
  //         //     mode: "xy",
  //         //     enabled: true,
  //         //     backgroundColor: "red",
  //         //     animation: 100,
  //         //   },
  //         // },
  //       },
  //     },
  //     plugins: [ChartZoom],
  //   });
  //   divChartHistoTotalTransactions.innerHTML = "";
  //   divChartHistoTotalTransactions.append(canvasHistoTotalTransactions);
  // }
  // //function - count nb && total per date
  // const countNbTotal = (effective) => {
  //   const result = {};
  //   effective.data.forEach((item) => {
  //     if (!result[item.date]) {
  //       //object date {} not exist
  //       result[item.date] = {
  //         count: 0,
  //         total: 0,
  //       };
  //     }
  //     //increment
  //     result[item.date].count++;
  //     result[item.date].total += Number(item.montant);
  //   });
  //   return result;
  // };
  // //function - prepare count
  // const prepareCount = (countNbTotal, allDates) =>
  //   allDates.map((date) => countNbTotal[date]?.count || 0);
  // //function - prepare total
  // const prepareTotal = (countNbTotal, allDates) =>
  //   allDates.map((date) => countNbTotal[date]?.total || 0);
  // //function - list free caisse
  // async function listFreeCaisse(select) {
  //   //initialize select2
  //   $(select).select2({
  //     theme: "bootstrap-5",
  //     placeholder: lang.select.toLowerCase(),
  //     dropdownParent: $(select.closest(".card")),
  //   });
  //   try {
  //     //FETCH api list free caisse
  //     const response = await apiRequest("/caisse/list_free_caisse");

  //     //error
  //     if (response.message_type === "error") {
  //       //alert
  //       const alertTemplate = document.querySelector(".alert-template");
  //       const clone = alertTemplate.content.cloneNode(true);
  //       const alert = clone.querySelector(".alert");
  //       const progressBar = alert.querySelector(".progress-bar");
  //       //alert type
  //       alert.classList.add("alert-danger");
  //       //icon
  //       alert.querySelector(".fad").classList.add("fa-exclamation-triangle");
  //       //message
  //       alert.querySelector(".alert-message").innerHTML = response.message;
  //       //progress bar
  //       progressBar.style.transition = "width 20s linear";
  //       progressBar.style.width = "100%";

  //       //add alert
  //       select.closest("div").prepend(alert);

  //       //progress launch animation
  //       setTimeout(() => {
  //         progressBar.style.width = "0%";
  //       }, 10);
  //       //auto close alert
  //       setTimeout(() => {
  //         alert.querySelector(".btn-close").click();
  //       }, 20000);

  //       return;
  //     }

  //     //set options
  //     select.innerHTML = "<option></option>";
  //     response.data.forEach((line) => {
  //       const option = document.createElement("option");
  //       option.value = line.num_caisse;
  //       option.innerText = line.num_caisse;

  //       //append option to select
  //       select.append(option);
  //     });
  //   } catch (e) {
  //     console.error(e);
  //   }
  // }
  //
  // //function - cash report
  // function cashReport() {
  //   //a - cash report
  //   const aCashReport = container.querySelector("#a-cash-report");
  //   //modal cash report
  //   const modalCashReport = container.querySelector("#modal-cash-report");
  //   //select - date_by
  //   const cashReportSelectDateBy = modalCashReport.querySelector(
  //     "#cash-report-select-date-by"
  //   );
  //   //select per
  //   const cashReportSelectPer = modalCashReport.querySelector(
  //     "#cash-report-select-per"
  //   );
  //   //date - from
  //   const cashReportDateFrom = modalCashReport.querySelector(
  //     "#cash-report-date-from"
  //   );
  //   //date - to
  //   const cashReportDateTo = modalCashReport.querySelector(
  //     "#cash-report-date-to"
  //   );
  //   //select - month
  //   const cashReportSelectMonth = modalCashReport.querySelector(
  //     "#cash-report-select-month"
  //   );
  //   //select - year
  //   const cashReportSelectYear = modalCashReport.querySelector(
  //     "#cash-report-select-year"
  //   );

  //   //load select date_by value from local storage for div
  //   const savedCashReportSelectDateBy = localStorage.getItem(
  //     cashReportSelectDateBy.id
  //   );
  //   cashReportSelectDateBy.value = !savedCashReportSelectDateBy
  //     ? "all"
  //     : savedCashReportSelectDateBy;
  //   switch (cashReportSelectDateBy.value) {
  //     //per
  //     case "per":
  //       modalCashReport
  //         .querySelector("#cash-report-div-per")
  //         .classList.add("active");
  //       break;
  //     //between
  //     case "between":
  //       modalCashReport
  //         .querySelector("#cash-report-div-between")
  //         .classList.add("active");
  //       break;
  //     //month_year
  //     case "month_year":
  //       modalCashReport
  //         .querySelector("#cash-report-div-month_year")
  //         .classList.add("active");
  //       break;
  //   }

  //   //===== EVENT select date_by
  //   cashReportSelectDateBy.addEventListener("change", (e) => {
  //     //hide all
  //     modalCashReport.querySelectorAll(".date-by").forEach((dateBy) => {
  //       if (dateBy.classList.contains("active")) {
  //         dateBy.classList.remove("active");
  //       }
  //     });
  //     //active
  //     switch (e.target.value) {
  //       //per
  //       case "per":
  //         modalCashReport
  //           .querySelector("#cash-report-div-per")
  //           .classList.add("active");
  //         break;
  //       //between
  //       case "between":
  //         modalCashReport
  //           .querySelector("#cash-report-div-between")
  //           .classList.add("active");
  //         break;
  //       //month_year
  //       case "month_year":
  //         modalCashReport
  //           .querySelector("#cash-report-div-month_year")
  //           .classList.add("active");
  //         break;
  //     }

  //     localStorage.setItem(e.target.id, e.target.value);
  //   });
  //   //===== EVENT date from
  //   cashReportDateFrom.addEventListener("input", (e) => {
  //     cashReportDateTo.min = e.target.value;
  //   });
  //   //===== EVENT date to
  //   cashReportDateTo.addEventListener("input", (e) => {
  //     cashReportDateFrom.max = e.target.value;
  //   });

  //   ///===== EVENT a cash report
  //   aCashReport.addEventListener("click", () => {
  //     //show modal cash report
  //     new bootstrap.Modal(modalCashReport).show();
  //   });

  //   //===== EVENT form submit
  //   modalCashReport
  //     .querySelector("form")
  //     .addEventListener("submit", async (e) => {
  //       //suspend submit
  //       e.preventDefault();
  //       //check validity
  //       if (!e.target.checkValidity()) {
  //         e.target.reportValidity();
  //         return;
  //       }

  //       try {
  //         //FETCH api cash report
  //         const cashReport = await apiRequest(
  //           `/caisse/cash_report?&date_by=${cashReportSelectDateBy.value}&per=${cashReportSelectPer.value}&from=${cashReportDateFrom.value}&to=${cashReportDateTo.value}&month=${cashReportSelectMonth.value}&year=${cashReportSelectYear.value}`
  //         );

  //         //error
  //         if (cashReport.message_type === "error") {
  //           //alert
  //           const alertTemplate = document.querySelector(".alert-template");
  //           const clone = alertTemplate.content.cloneNode(true);
  //           const alert = clone.querySelector(".alert");
  //           const progressBar = alert.querySelector(".progress-bar");
  //           //alert type
  //           alert.classList.add("alert-danger");
  //           //icon
  //           alert
  //             .querySelector(".fad")
  //             .classList.add("fa-exclamation-triangle");
  //           //message
  //           alert.querySelector(".alert-message").innerHTML =
  //             cashReport.message;
  //           //progress bar
  //           progressBar.style.transition = "width 20s linear";
  //           progressBar.style.width = "100%";

  //           //add alert
  //           modalCashReport.querySelector(".modal-body").prepend(alert);

  //           //progress launch animation
  //           setTimeout(() => {
  //             progressBar.style.width = "0%";
  //           }, 10);
  //           //auto close alert
  //           setTimeout(() => {
  //             alert.querySelector(".btn-close").click();
  //           }, 20000);

  //           return;
  //         }
  //         //invalid
  //         else if (cashReport.message_type === "invalid") {
  //           //alert
  //           const alertTemplate = document.querySelector(".alert-template");
  //           const clone = alertTemplate.content.cloneNode(true);
  //           const alert = clone.querySelector(".alert");
  //           const progressBar = alert.querySelector(".progress-bar");
  //           //alert type
  //           alert.classList.add("alert-warning");
  //           //icon
  //           alert.querySelector(".fad").classList.add("fa-exclamation-circle");
  //           //message
  //           alert.querySelector(".alert-message").innerHTML =
  //             cashReport.message;
  //           //progress bar
  //           progressBar.style.transition = "width 10s linear";
  //           progressBar.style.width = "100%";

  //           //add alert
  //           modalCashReport.querySelector(".modal-body").prepend(alert);

  //           //progress launch animation
  //           setTimeout(() => {
  //             progressBar.style.width = "0%";
  //           }, 10);
  //           //auto close alert
  //           setTimeout(() => {
  //             alert.querySelector(".btn-close").click();
  //           }, 10000);

  //           return;
  //         }

  //         //download cash report
  //         const a = document.createElement("a");
  //         a.href = `data:application/pdf;base64,${cashReport.pdf}`;
  //         a.download = cashReport.file_name;
  //         a.click();
  //         //close modal
  //         modalCashReport.querySelector("#btn-close-modal-cash-report").click();

  //         return;
  //       } catch (e) {
  //         console.error(e);
  //       }
  //     });
  // }
});
