document.addEventListener("DOMContentLoaded", async () => {
  //template real content
  const templateRealContent = document.getElementById("template-cash");

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
  let formatterNumber, formatterTotal, formatterInput;
  //en
  if (cookieLangValue === "en") {
    formatterNumber = new Intl.NumberFormat("en-US", {
      style: "decimal",
      minimumFractionDigits: 0,
      maximumFractionDigits: 2,
    });
    formatterTotal = new Intl.NumberFormat("en-US", {
      style: "currency",
      currency: currencyUnits,
      minimumFractionDigits: 0,
      maximumFractionDigits: 2,
    });
    formatterInput = new Intl.NumberFormat("en-US", {
      style: "decimal",
      minimumFractionDigits: 0,
      maximumFractionDigits: 2,
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
    formatterInput = new Intl.NumberFormat("fr-FR", {
      style: "decimal",
      minimumFractionDigits: 0,
      maximumFractionDigits: 2,
    });
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

    //tbody caisse
    const tbodyCaisse = container.querySelector("#tbody-caisse");
    const tbodyLC = container.querySelector("#tbody-lc");

    //===================== SEARCHBAR =====================
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

    //======= EVENT date_by change
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
    const inputSearch = container.querySelector("#input-search");
    const savedInputSearch = localStorage.getItem(inputSearch.id);
    inputSearch.value = !savedInputSearch ? "" : savedInputSearch;
    //input - search searchbar
    const inputSearchSearchBar = searchBar.querySelector(
      "#input-search-searchbar"
    );
    inputSearchSearchBar.value = inputSearch.value;
    //select - status
    const selectStatus = searchBar.querySelector("#select-status");
    const savedSelectStatus = localStorage.getItem(selectStatus.id);
    selectStatus.value = !savedSelectStatus ? "all" : savedSelectStatus;
    //select - arrange_by
    const selectArrangeBy = searchBar.querySelector("#select-arrange-by");
    const savedSelectArrangeBy = localStorage.getItem(selectArrangeBy.id);
    selectArrangeBy.value = !savedSelectArrangeBy
      ? "name"
      : savedSelectArrangeBy;
    //select - order
    const selectOrder = searchBar.querySelector("#select-order");
    const savedSelectOrder = localStorage.getItem(selectOrder.id);
    selectOrder.value = !savedSelectOrder ? "asc" : savedSelectOrder;
    //select - per
    const selectPer = searchBar.querySelector("#select-per");
    const savedSelectPer = localStorage.getItem(selectPer.id);
    selectPer.value = !savedSelectPer ? "day" : savedSelectPer;
    //date - from
    const dateFrom = searchBar.querySelector("#date-from");
    const savedDateFrom = localStorage.getItem(dateFrom.id);
    dateFrom.value = !savedDateFrom ? "" : savedDateFrom;
    //date - to
    const dateTo = searchBar.querySelector("#date-to");
    const savedDateTo = localStorage.getItem(dateTo.id);
    dateTo.value = !savedDateTo ? "" : savedDateTo;
    //select - month
    const selectMonth = searchBar.querySelector("#select-month");
    const savedSelectMonth = localStorage.getItem(selectMonth.id);
    selectMonth.value = !savedSelectMonth ? "all" : savedSelectMonth;
    //select - year
    const selectYear = searchBar.querySelector("#select-year");
    const savedSelectYear = localStorage.getItem(selectYear.id);
    selectYear.value = !savedSelectYear ? selectYear.value : savedSelectYear;
    //====== EVENT btn reset
    const btnReset = searchBar.querySelector("#btn-reset");
    btnReset.addEventListener("click", () => {
      //reset input search
      inputSearch.value = "";
      inputSearch.dispatchEvent(new Event("input"));
      //reset select status
      selectStatus.value = "all";
      localStorage.removeItem(selectStatus.id);
      //reset select arrange_by
      selectArrangeBy.value = "num";
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

    //list all caisse
    await filterCaisse(
      tbodyCaisse,
      container.querySelector("#chart-cash-number"),
      selectStatus.value.trim(),
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

    //===== EVENT input search
    inputSearch.addEventListener("input", (e) => {
      e.target.value = e.target.value.replace(/[^0-9]/g, "");
      filterCaisse(
        tbodyCaisse,
        container.querySelector("#chart-cash-number"),
        selectStatus.value.trim(),
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
    //===== EVENT input search searchbar
    inputSearchSearchBar.addEventListener("input", (e) => {
      e.target.value = e.target.value.replace(/[^0-9]/g, "");
      inputSearch.value = e.target.value;
      inputSearch.dispatchEvent(new Event("input"));
    });
    //===== EVENT select status
    selectStatus.addEventListener("change", (e) => {
      filterCaisse(
        tbodyCaisse,
        container.querySelector("#chart-cash-number"),
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
      filterCaisse(
        tbodyCaisse,
        container.querySelector("#chart-cash-number"),
        selectStatus.value.trim(),
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
      filterCaisse(
        tbodyCaisse,
        container.querySelector("#chart-cash-number"),
        selectStatus.value.trim(),
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
      filterCaisse(
        tbodyCaisse,
        container.querySelector("#chart-cash-number"),
        selectStatus.value.trim(),
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
      filterCaisse(
        tbodyCaisse,
        container.querySelector("#chart-cash-number"),
        selectStatus.value.trim(),
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
      filterCaisse(
        tbodyCaisse,
        container.querySelector("#chart-cash-number"),
        selectStatus.value.trim(),
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
      filterCaisse(
        tbodyCaisse,
        container.querySelector("#chart-cash-number"),
        selectStatus.value.trim(),
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
      filterCaisse(
        tbodyCaisse,
        container.querySelector("#chart-cash-number"),
        selectStatus.value.trim(),
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

    //========================= SEARCH LIGNE CAISSE ====================
    //input - search lc id
    const inputSearchLCId = container.querySelector("#input-search-lc-id");
    const savedInputSearchLCId = localStorage.getItem(inputSearchLCId.id);
    inputSearchLCId.value = !savedInputSearchLCId ? "" : savedInputSearchLCId;
    //input - search lc from
    const inputSearchLCFrom = container.querySelector("#input-search-lc-from");
    const savedInputSeachLCFrom = localStorage.getItem(inputSearchLCFrom.id);
    inputSearchLCFrom.value = !savedInputSeachLCFrom
      ? ""
      : savedInputSeachLCFrom;
    //input - searc lc to
    const inputSearchLCTo = container.querySelector("#input-search-lc-to");
    const savedInputSearchLCTo = localStorage.getItem(inputSearchLCTo.id);
    inputSearchLCTo.value = !savedInputSearchLCTo ? "" : savedInputSearchLCTo;

    //===== EVENT input search lc id
    inputSearchLCId.addEventListener("input", (e) => {
      e.target.value = e.target.value.replace(/[^0-9]/g, "");
      localStorage.setItem(e.target.id, e.target.value);
      filterLigneCaisse(tbodyLC);
    });
    //===== EVENT input search lc from
    inputSearchLCFrom.addEventListener("input", (e) => {
      inputSearchLCTo.min = e.target.value;
      localStorage.setItem(e.target.id, e.target.value);
      filterLigneCaisse(tbodyLC);
    });
    //===== EVENT input search lc to
    inputSearchLCTo.addEventListener("input", (e) => {
      inputSearchLCFrom.max = e.target.value;
      localStorage.setItem(e.target.id, e.target.value);
      filterLigneCaisse(tbodyLC);
    });

    //========================== ADD CAISSE =======================
    //======elements add caisse
    //modal - add caisse
    const modalAddCaisse = document.getElementById("modal-add-caisse");
    //form - add caisse
    const formAddCaisse = modalAddCaisse.querySelector("form");
    //input - add num_caisse
    const inputAddNumCaisse = modalAddCaisse.querySelector(
      "#input-add-num-caisse"
    );
    const savedInputAddNumCaisse = localStorage.getItem(inputAddNumCaisse.id);
    inputAddNumCaisse.value = !savedInputAddNumCaisse
      ? "0"
      : savedInputAddNumCaisse;
    //input - add solde
    const inputAddSolde = modalAddCaisse.querySelector("#input-add-solde");
    const savedInputAddSolde = localStorage.getItem(inputAddSolde.id);
    inputAddSolde.value = !savedInputAddSolde ? "0" : savedInputAddSolde;
    inputAddSolde.dataset.val = inputAddSolde.value;
    //input - add seuil
    const inputAddSeuil = modalAddCaisse.querySelector("#input-add-seuil");
    const savedInputAddSeuil = localStorage.getItem(inputAddSeuil.id);
    inputAddSeuil.value = !savedInputAddSeuil ? "0" : savedInputAddSeuil;
    inputAddSeuil.dataset.val = inputAddSeuil.value;

    //===== EVENT input - add num_caisse
    inputAddNumCaisse.addEventListener("input", (e) => {
      e.target.value = e.target.value.replace(/[^0-9]/g, "");
      localStorage.setItem(e.target.id, e.target.value);
    });
    //===== EVENT input - solde
    inputAddSolde.addEventListener("input", (e) => {
      if (cookieLangValue === "en") {
        e.target.value = e.target.value.replace(/[^0-9.]/g, "");
        if (!/^\d*\.?\d*$/.test(e.target.value)) {
          e.target.value = e.target.value.slice(0, -1);
        }

        // add 0 in the start if ,
        if (e.target.value.startsWith(".")) {
          e.target.value = "0" + e.target.value;
        }

        //real value for calcul
        e.target.dataset.val = e.target.value.replace(/[\u202F\u00A0 ]/g, "");
      } else {
        //number and , only
        e.target.value = e.target.value.replace(/[^0-9,]/g, "");
        if (!/^\d*\,?\d*$/.test(e.target.value)) {
          e.target.value = e.target.value.slice(0, -1);
        }
        // add 0 in the start if ,
        if (e.target.value.startsWith(",")) {
          e.target.value = "0" + e.target.value;
        }

        //real value for calcul
        e.target.dataset.val = e.target.value
          .replace(",", ".")
          .replace(/[\u202F\u00A0 ]/g, "");
      }
    });
    inputAddSolde.addEventListener("blur", (e) => {
      if (e.target.value.endsWith(",")) {
        e.target.value += "0";
      }
      e.target.value = formatterInput.format(
        e.target.value.replace(/[\u202F\u00A0 ]/g, "").replace(",", ".")
      );
      //save to local storage
      localStorage.setItem(e.target.id, e.target.value);

      inputAddSeuil.dispatchEvent(new Event("input"));
      inputAddSeuil.dispatchEvent(new Event("blur"));
    });
    //====== EVENT input - seuil
    inputAddSeuil.addEventListener("input", (e) => {
      if (cookieLangValue === "en") {
        e.target.value = e.target.value.replace(/[^0-9.]/g, "");
        if (!/^\d*\.?\d*$/.test(e.target.value)) {
          e.target.value = e.target.value.slice(0, -1);
        }

        // add 0 in the start if ,
        if (e.target.value.startsWith(".")) {
          e.target.value = "0" + e.target.value;
        }

        //real value for calcul
        e.target.dataset.val = e.target.value.replace(/[\u202F\u00A0 ]/g, "");

        //seuil > solde
        const rest =
          Number(e.target.dataset.val) -
          Number(
            inputAddSolde.value
              .replace(/[\u202F\u00A0 ]/g, "")
              .replace(",", ".")
          );
        if (rest > 0) {
          e.target.dataset.val = inputAddSolde.dataset.val;
          e.target.value = e.target.dataset.val;
        }
      } else {
        //number and , only
        e.target.value = e.target.value.replace(/[^0-9,]/g, "");
        if (!/^\d*\,?\d*$/.test(e.target.value)) {
          e.target.value = e.target.value.slice(0, -1);
        }
        // add 0 in the start if ,
        if (e.target.value.startsWith(",")) {
          e.target.value = "0" + e.target.value;
        }

        //real value for calcul
        e.target.dataset.val = e.target.value
          .replace(",", ".")
          .replace(/[\u202F\u00A0 ]/g, "");

        //seuil > solde
        const rest =
          Number(e.target.dataset.val) -
          Number(
            inputAddSolde.value
              .replace(/[\u202F\u00A0 ]/g, "")
              .replace(",", ".")
          );
        if (rest > 0) {
          e.target.dataset.val = inputAddSolde.dataset.val;
          e.target.value = e.target.dataset.val;
        }
      }
    });
    inputAddSeuil.addEventListener("blur", (e) => {
      if (e.target.value.endsWith(",")) {
        e.target.value += "0";
      }
      e.target.value = formatterInput.format(
        e.target.value.replace(/[\u202F\u00A0 ]/g, "").replace(",", ".")
      );
      //save to local storage
      localStorage.setItem(e.target.id, e.target.value);
    });
    //===== EVENT form add caisse submit
    formAddCaisse.addEventListener("submit", async (e) => {
      //suspend submit
      e.preventDefault();

      //inputs - not valid
      if (!e.target.checkValidity()) {
        e.target.reportValidity();
        return;
      } else {
        try {
          //FETCH api add caisse
          const response = await apiRequest("/caisse/create_caisse", {
            method: "POST",
            body: {
              num_caisse: inputAddNumCaisse.value.trim(),
              solde: inputAddSolde.value
                .replace(/[\u202F\u00A0 ]/g, "")
                .replace(",", "."),
              seuil: inputAddSeuil.value
                .replace(/[\u202F\u00A0 ]/g, "")
                .replace(",", "."),
            },
          });
          //invalid
          if (response.message_type === "invalid") {
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
            alert.querySelector(".alert-message").innerHTML = response.message;
            //progress bar
            progressBar.style.transition = "width 10s linear";
            progressBar.style.width = "100%";

            //add alert
            formAddCaisse.querySelector(".modal-body").prepend(alert);

            //progress launch animation
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
            alert.querySelector(".alert-message").innerHTML = response.message;
            //progress bar
            progressBar.style.transition = "width 10s linear";
            progressBar.style.width = "100%";

            //add alert
            formAddCaisse.querySelector(".modal-body").prepend(alert);

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
            const alertTemplate = document.querySelector(".alert-template");
            const clone = alertTemplate.content.cloneNode(true);
            const alert = clone.querySelector(".alert");
            const progressBar = alert.querySelector(".progress-bar");
            //alert type
            alert.classList.add("alert-success");
            //icon
            alert.querySelector(".fad").classList.add("fa-check-circle");
            //message
            alert.querySelector(".alert-message").innerHTML = response.message;
            //progress bar
            progressBar.style.transition = "width 10s linear";
            progressBar.style.width = "100%";

            //add alert
            tbodyCaisse.closest("div").prepend(alert);

            //progress lanch animation
            setTimeout(() => {
              progressBar.style.width = "0%";
            }, 10);
            //auto close alert
            setTimeout(() => {
              alert.querySelector(".btn-close").click();
            }, 10000);

            //hide modal
            modalAddCaisse.querySelector("#btn-close-modal-add-caisse").click();
            //remove all input value from local storage
            modalAddCaisse.querySelectorAll("input").forEach((element) => {
              const saved = localStorage.getItem(element.id);
              if (saved) {
                localStorage.removeItem(element.id);
              }
            });

            //refesh filter caisse
            filterCaisse(
              tbodyCaisse,
              container.querySelector("#chart-cash-number"),
              selectStatus.value.trim(),
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
          }
        } catch (e) {
          console.error(e);
        }
      }
    });

    //==================== UPDATE CAISSE ===================
    //modal update caisse
    const modalUpdateCaisse = container.querySelector("#modal-update-caisse");
    //===== EVENT form update caisse submit
    modalUpdateCaisse
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
            //FETCH api update caisse
            const response = await apiRequest("/caisse/update_caisse", {
              method: "PUT",
              body: {
                num_caisse: modalUpdateCaisse
                  .querySelector("#num-caisse")
                  .textContent.trim(),
                num_caisse_update: modalUpdateCaisse
                  .querySelector("#input-update-num-caisse-update")
                  .value.trim(),
                solde: modalUpdateCaisse
                  .querySelector("#input-update-solde")
                  .value.replace(/[\u202F\u00A0 ]/g, "")
                  .replace(",", ".")
                  .trim(),
                seuil: modalUpdateCaisse
                  .querySelector("#input-update-seuil")
                  .value.replace(/[\u202F\u00A0 ]/g, "")
                  .replace(",", ".")
                  .trim(),
              },
            });
            //invalid
            if (response.message_type === "invalid") {
              //alert
              const alertTemplate = document.querySelector(".alert-template");
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
              modalUpdateCaisse.querySelector(".modal-body").prepend(alert);

              //progress launch animation
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
                response.message;
              //progress bar
              progressBar.style.transition = "width 10s linear";
              progressBar.style.width = "100%";

              //add alert
              modalUpdateCaisse.querySelector(".modal-body").prepend(alert);

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
                response.message;
              //progress bar
              progressBar.style.transition = "width 10s linear";
              progressBar.style.width = "100%";

              //add alert
              container
                .querySelector("#tbody-caisse")
                .closest("div")
                .prepend(alert);

              //progress launch animation
              setTimeout(() => {
                progressBar.style.width = "0%";
              }, 10);
              //auto close alert
              setTimeout(() => {
                alert.querySelector(".btn-close").click();
              }, 10000);

              //hide modal
              modalUpdateCaisse
                .querySelector("#btn-close-modal-update-caisse")
                .click();

              //refesh filter caisse
              filterCaisse(
                tbodyCaisse,
                container.querySelector("#chart-cash-number"),
                selectStatus.value.trim(),
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
            }
          } catch (e) {
            console.error(e);
          }
        }
      });

    //=======================  DELETE CAISSE ==========================
    //modal delete caisse
    const modalDeleteCaisse = document.getElementById("modal-delete-caisse");
    //btn delete caisse
    const btnDeleteCaisse = tbodyCaisse
      .closest("table")
      .parentElement.querySelector("#btn-delete-caisse");

    //===== EVENT btn delete caisse
    btnDeleteCaisse.addEventListener("click", () => {
      //selected caisse
      const selectedCaisse = tbodyCaisse.querySelectorAll(
        "input[type='checkbox']:checked"
      );

      //no selection
      if (selectedCaisse.length <= 0) {
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
          lang.caisse_nums_caisse_empty;
        //progress bar
        progressBar.style.transition = "width 10s linear";
        progressBar.style.width = "100%";

        //add alert
        tbodyCaisse.closest("div").prepend(alert);

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

      //modal delete client
      const modalDeleteClient = container.querySelector("#modal-delete-client");
    });

    //=======================  DELETE PERMANENT CAISSE =========================
    //btn delete permanent caisse
    const btnDeletePermanentCaisse = tbodyCaisse
      .closest("table")
      .parentElement.querySelector("#btn-delete-permanent-caisse");

    //===== EVENT btn delete permanent caisse
    btnDeletePermanentCaisse.addEventListener("click", () => {
      //selected caisse
      const selectedCaisse = tbodyCaisse.querySelectorAll(
        "input[type='checkbox']:checked"
      );

      //no selection
      if (selectedCaisse.length <= 0) {
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
          lang.caisse_nums_caisse_empty;
        //progress bar
        progressBar.style.transition = "width 10s linear";
        progressBar.style.width = "100%";

        //add alert
        tbodyCaisse.closest("div").prepend(alert);

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
        if (selectedCaisse.length === 1) {
          modalDeleteCaisse.querySelector(".message").innerHTML =
            lang.question_delete_permanent_caisse_1.replace(
              ":field",
              selectedCaisse[0].closest("tr").dataset.numCaisse
            );
        }
        //modal message plur
        else {
          modalDeleteCaisse.querySelector(".message").innerHTML =
            lang.question_delete_permanent_caisse_plur.replace(
              ":field",
              selectedCaisse.length
            );
        }

        //show modal delete caisse
        new bootstrap.Modal(modalDeleteCaisse).show();

        //===== EVENT btn confirm delete caisse
        modalDeleteCaisse
          .querySelector("#btn-confirm-delete-caisse")
          .addEventListener("click", async () => {
            try {
              //nums_caisse
              let nums_caisse = [...selectedCaisse];
              nums_caisse = nums_caisse.map(
                (selected) => selected.closest("tr").dataset.numCaisse
              );
              //FETCH api delete permanent all caisse
              const response = await apiRequest(
                "/caisse/delete_permanent_all_caisse",
                {
                  method: "DELETE",
                  body: { nums_caisse: nums_caisse },
                }
              );
              //error
              if (response.message_type === "error") {
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
                  response.message;
                //progress bar
                progressBar.style.transition = "width 20s linear";
                progressBar.style.width = "100%";

                //add alert
                modalDeleteCaisse.querySelector(".modal-body").prepend(alert);

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
              else if (response.message_type === "invalid") {
                //alert
                const alertTemplate = document.querySelector(".alert-template");
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
                modalDeleteCaisse.querySelector(".modal-body").prepend(alert);

                //progress launch animation
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
                  response.message;
                //progress bar
                progressBar.style.transition = "width 10s linear";
                progressBar.style.width = "100%";

                //add alert
                tbodyCaisse.closest("div").prepend(alert);

                //progress lanch animation
                setTimeout(() => {
                  progressBar.style.width = "0%";
                }, 10);
                //auto close alert
                setTimeout(() => {
                  alert.querySelector(".btn-close").click();
                }, 10000);
                //close modal
                modalDeleteCaisse
                  .querySelector("#btn-close-modal-delete-caisse")
                  .click();

                //refesh filter user
                filterCaisse(
                  tbodyCaisse,
                  container.querySelector("#chart-cash-number"),
                  selectStatus.value.trim(),
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
              }
            } catch (e) {
              console.error(e);
            }
          });
      }
    });

    //=======================  RESTORE CAISSE =========================
    //modal restore caisse
    const modalRestoreCaisse = document.getElementById("modal-restore-caisse");
    //btn restore caisse
    const btnRestoreCaisse = tbodyCaisse
      .closest("table")
      .parentElement.querySelector("#btn-restore-caisse");

    //===== EVENT btn restore caisse
    btnRestoreCaisse.addEventListener("click", () => {
      //selected caisse
      const selectedCaisse = tbodyCaisse.querySelectorAll(
        "input[type='checkbox']:checked"
      );

      //no selection
      if (selectedCaisse.length <= 0) {
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
          lang.caisse_nums_caisse_empty;
        //progress bar
        progressBar.style.transition = "width 10s linear";
        progressBar.style.width = "100%";

        //add alert
        tbodyCaisse.closest("div").prepend(alert);

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
        if (selectedCaisse.length === 1) {
          modalRestoreCaisse.querySelector(".message").innerHTML =
            lang.question_restore_caisse_1.replace(
              ":field",
              selectedCaisse[0].closest("tr").dataset.numCaisse
            );
        }
        //modal message plur
        else {
          modalRestoreCaisse.querySelector(".message").innerHTML =
            lang.question_restore_caisse_plur.replace(
              ":field",
              selectedCaisse.length
            );
        }

        //show modal restore caisse
        new bootstrap.Modal(modalRestoreCaisse).show();

        //===== EVENT btn confirm restore caisse
        modalRestoreCaisse
          .querySelector("#btn-confirm-restore-caisse")
          .addEventListener("click", async () => {
            try {
              //nums_caisse
              let nums_caisse = [...selectedCaisse];
              nums_caisse = nums_caisse.map(
                (selected) => selected.closest("tr").dataset.numCaisse
              );
              //FETCH api restore all caisse
              const response = await apiRequest("/caisse/restore_all_caisse", {
                method: "PUT",
                body: { nums_caisse: nums_caisse },
              });
              //error
              if (response.message_type === "error") {
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
                  response.message;
                //progress bar
                progressBar.style.transition = "width 20s linear";
                progressBar.style.width = "100%";

                //add alert
                modalRestoreCaisse.querySelector(".modal-body").prepend(alert);

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
              else if (response.message_type === "invalid") {
                //alert
                const alertTemplate = document.querySelector(".alert-template");
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
                modalRestoreCaisse.querySelector(".modal-body").prepend(alert);

                //progress launch animation
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
                  response.message;
                //progress bar
                progressBar.style.transition = "width 10s linear";
                progressBar.style.width = "100%";

                //add alert
                tbodyCaisse.closest("div").prepend(alert);

                //progress lanch animation
                setTimeout(() => {
                  progressBar.style.width = "0%";
                }, 10);
                //auto close alert
                setTimeout(() => {
                  alert.querySelector(".btn-close").click();
                }, 10000);
                //close modal
                modalRestoreCaisse
                  .querySelector("#btn-close-modal-restore-caisse")
                  .click();

                //refesh filter user
                filterCaisse(
                  tbodyCaisse,
                  container.querySelector("#chart-cash-number"),
                  selectStatus.value.trim(),
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
              }
            } catch (e) {
              console.error(e);
            }
          });
      }
    });

    //=======================  FREE CAISSE =========================
    // modal free caisse
    const modalFreeCaisse = document.getElementById("modal-free-caisse");
    //btn free caisse
    const btnFreeCaisse = tbodyCaisse
      .closest("table")
      .parentElement.querySelector("#btn-free-caisse");

    //===== EVENT btn free caisse
    btnFreeCaisse.addEventListener("click", () => {
      //selected caisse
      const selectedCaisse = tbodyCaisse.querySelectorAll(
        "input[type='checkbox']:checked"
      );

      //no selection
      if (selectedCaisse.length <= 0) {
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
          lang.caisse_nums_caisse_empty;
        //progress bar
        progressBar.style.transition = "width 10s linear";
        progressBar.style.width = "100%";

        //add alert
        tbodyCaisse.closest("div").prepend(alert);

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
        if (selectedCaisse.length === 1) {
          modalFreeCaisse.querySelector(".message").innerHTML =
            lang.question_free_caisse_1.replace(
              ":field",
              selectedCaisse[0].closest("tr").dataset.numCaisse
            );
        }
        //modal message plur
        else {
          modalFreeCaisse.querySelector(".message").innerHTML =
            lang.question_free_caisse_plur.replace(
              ":field",
              selectedCaisse.length
            );
        }

        //show modal free caisse
        new bootstrap.Modal(modalFreeCaisse).show();

        //===== EVENT btn confirm free caisse
        modalFreeCaisse
          .querySelector("#btn-confirm-free-caisse")
          .addEventListener("click", async () => {
            try {
              //nums_caisse
              let nums_caisse = [...selectedCaisse];
              nums_caisse = nums_caisse.map(
                (selected) => selected.closest("tr").dataset.numCaisse
              );
              //FETCH api restore all caisse
              const response = await apiRequest("/caisse/free_all_caisse", {
                method: "PUT",
                body: { nums_caisse: nums_caisse },
              });
              //error
              if (response.message_type === "error") {
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
                  response.message;
                //progress bar
                progressBar.style.transition = "width 20s linear";
                progressBar.style.width = "100%";

                //add alert
                modalFreeCaisse.querySelector(".modal-body").prepend(alert);

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
              else if (response.message_type === "invalid") {
                //alert
                const alertTemplate = document.querySelector(".alert-template");
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
                modalFreeCaisse.querySelector(".modal-body").prepend(alert);

                //progress launch animation
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
                  response.message;
                //progress bar
                progressBar.style.transition = "width 10s linear";
                progressBar.style.width = "100%";

                //add alert
                tbodyCaisse.closest("div").prepend(alert);

                //progress lanch animation
                setTimeout(() => {
                  progressBar.style.width = "0%";
                }, 10);
                //auto close alert
                setTimeout(() => {
                  alert.querySelector(".btn-close").click();
                }, 10000);
                //close modal
                modalFreeCaisse
                  .querySelector("#btn-close-modal-free-caisse")
                  .click();

                //refesh filter user
                filterCaisse(
                  tbodyCaisse,
                  container.querySelector("#chart-cash-number"),
                  selectStatus.value.trim(),
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
                //refres list free caisse
                listFreeCaisse(container.querySelector("#select-occup-caisse"));

                return;
              }
            } catch (e) {
              console.error(e);
            }
          });
      }
    });

    //====================== ADD LIGNE CAISSE ========================
    addLigneCaisse();

    //======================= UPDATE LIGNE CAISSE ======================
    //modal update ligne caisse
    const modalUpdateLigneCaisse = container.querySelector(
      "#modal-update-ligne-caisse"
    );
    //===== EVENT form update ligne_caisse submit
    modalUpdateLigneCaisse
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
            //FETCH api update ligne_caisse
            const response = await apiRequest("/caisse/update_ligne_caisse", {
              method: "PUT",
              body: {
                id_lc: modalUpdateLigneCaisse
                  .querySelector("#update-ligne-caisse-id-lc")
                  .textContent.trim(),
                date_debut: modalUpdateLigneCaisse
                  .querySelector("#input-update-date-debut")
                  .value.trim(),
                date_fin: modalUpdateLigneCaisse
                  .querySelector("#input-update-date-fin")
                  .value.trim(),
                id_utilisateur: $(
                  modalUpdateLigneCaisse.querySelector(
                    "#select-update-id-utilisateur"
                  )
                )
                  .val()
                  .trim(),
              },
            });
            //invalid
            if (response.message_type === "invalid") {
              //alert
              const alertTemplate = document.querySelector(".alert-template");
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
              modalUpdateLigneCaisse
                .querySelector(".modal-body")
                .prepend(alert);

              //progress launch animation
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
                response.message;
              //progress bar
              progressBar.style.transition = "width 10s linear";
              progressBar.style.width = "100%";

              //add alert
              modalUpdateLigneCaisse
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
                response.message;
              //progress bar
              progressBar.style.transition = "width 10s linear";
              progressBar.style.width = "100%";

              //add alert
              container
                .querySelector("#tbody-lc")
                .closest("div")
                .prepend(alert);

              //progress launch animation
              setTimeout(() => {
                progressBar.style.width = "0%";
              }, 10);
              //auto close alert
              setTimeout(() => {
                alert.querySelector(".btn-close").click();
              }, 10000);

              //hide modal
              modalUpdateLigneCaisse
                .querySelector("#btn-close-modal-update-ligne-caisse")
                .click();
            }
          } catch (e) {
            console.error(e);
          }
        }
      });
    //======================= DELETE LIGNE CAISSE =========================
    //btn delete delete ligne caisse
    const btnDeleteLigneCaisse = tbodyLC
      .closest("table")
      .parentElement.querySelector("#btn-delete-ligne-caisse");
    //modal delete line caisse
    const modalDeleteLigneCaisse = container.querySelector(
      "#modal-delete-ligne-caisse"
    );

    //===== EVENT btn delete  ligne caisse
    btnDeleteLigneCaisse.addEventListener("click", () => {
      //selected ligne caisse
      const selectedLigneCaisse = tbodyLC.querySelectorAll(
        "input[type='checkbox']:checked"
      );

      //no selection
      if (selectedLigneCaisse.length <= 0) {
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
          lang.caisse_ids_lc_empty;
        //progress bar
        progressBar.style.transition = "width 10s linear";
        progressBar.style.width = "100%";

        //add alert
        tbodyLC.closest("div").prepend(alert);

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
        // modal message 1
        if (selectedLigneCaisse.length === 1) {
          modalDeleteLigneCaisse.querySelector(".message").innerHTML =
            lang.question_delete_ligne_caisse_1.replace(
              ":field",
              selectedLigneCaisse[0].closest("tr").dataset.idLC
            );
        }
        //modal message plur
        else {
          modalDeleteLigneCaisse.querySelector(".message").innerHTML =
            lang.question_delete_ligne_caisse_plur.replace(
              ":field",
              selectedLigneCaisse.length
            );
        }
        //show modal delete ligne caisse
        new bootstrap.Modal(modalDeleteLigneCaisse).show();
        //===== EVENT btn confirm delete caisse
        modalDeleteLigneCaisse
          .querySelector("#btn-confirm-delete-ligne-caisse")
          .addEventListener("click", async () => {
            try {
              //ids_lc
              let ids_lc = [...selectedLigneCaisse];
              ids_lc = ids_lc.map(
                (selected) => selected.closest("tr").dataset.idLC
              );
              //FETCH api delete all caisse
              const response = await apiRequest(
                "/caisse/delete_all_ligne_caisse",
                {
                  method: "DELETE",
                  body: { ids_lc: ids_lc },
                }
              );
              //error
              if (response.message_type === "error") {
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
                  response.message;
                //progress bar
                progressBar.style.transition = "width 20s linear";
                progressBar.style.width = "100%";

                //add alert
                modalDeleteLigneCaisse
                  .querySelector(".modal-body")
                  .prepend(alert);

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
              else if (response.message_type === "invalid") {
                //alert
                const alertTemplate = document.querySelector(".alert-template");
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
                modalDeleteLigneCaisse
                  .querySelector(".modal-body")
                  .prepend(alert);

                //progress launch animation
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
                  response.message;
                //progress bar
                progressBar.style.transition = "width 10s linear";
                progressBar.style.width = "100%";

                //add alert
                tbodyLC.closest("div").prepend(alert);

                //progress lanch animation
                setTimeout(() => {
                  progressBar.style.width = "0%";
                }, 10);
                //auto close alert
                setTimeout(() => {
                  alert.querySelector(".btn-close").click();
                }, 10000);

                //close modal
                modalDeleteLigneCaisse
                  .querySelector("#btn-close-modal-delete-ligne-caisse")
                  .click();

                //refresh filter ligne caisse
                filterLigneCaisse(tbodyLC);

                return;
              }
            } catch (e) {
              console.error(e);
            }
          });
      }
    });

    //============================= OCCUP CAISSE =============================
    //select - occup caisse
    const selectOccupCaisse = container.querySelector("#select-occup-caisse");
    //btn occup caisse
    const btnOccupCaisse = container.querySelector("#btn-occup-caisse");
    //modal occup caisse
    const modalOccupCaisse = container.querySelector("#modal-occup-caisse");
    //list free caisse
    listFreeCaisse(selectOccupCaisse);

    //====== EVENT btn occup caisse
    btnOccupCaisse.addEventListener("click", () => {
      //num_caisse
      const num_caisse = $(selectOccupCaisse).val().trim();
      modalOccupCaisse.querySelector("#occup-caisse-cash-num").innerHTML =
        num_caisse;
      //select - caissier
      const selectCaissier = modalOccupCaisse.querySelector(
        "#select-occup-caisse-id-utilisateur"
      );

      //caisse not selected
      if (num_caisse === "") {
        //alert
        const alertTemplate = document.querySelector(".alert-template");
        const clone = alertTemplate.content.cloneNode(true);
        const alert = clone.querySelector(".alert");
        const progressBar = alert.querySelector(".progress-bar");
        //alert type
        alert.classList.add("alert-warning");
        //icon
        alert.querySelector(".fad").classList.add("fa-exclamation-triangle");
        //message
        alert.querySelector(".alert-message").innerHTML =
          lang.caisse_not_selected;
        //progress bar
        progressBar.style.transition = "width 10s linear";
        progressBar.style.width = "100%";

        //add alert
        selectOccupCaisse.closest("div").prepend(alert);

        //progress launch animation
        setTimeout(() => {
          progressBar.style.width = "0%";
        }, 10);
        //auto close alert
        setTimeout(() => {
          alert.querySelector(".btn-close").click();
        }, 10000);

        return;
      }

      //initialize select2 list caissier
      const $modalOccupCaisse = $(modalOccupCaisse);
      $(selectCaissier).select2({
        theme: "bootstrap-5",
        placeholder: lang.select.toLowerCase(),
        dropdownParent: $modalOccupCaisse,
      });

      //list caissier
      listCaissier(selectCaissier);

      //show modal occup caisse
      new bootstrap.Modal(modalOccupCaisse).show();

      //===== EVENT btn refresh list caissier
      modalOccupCaisse
        .querySelector("#btn-refresh-list-caissier")
        .addEventListener("click", () => {
          //refresh list caissier
          listCaissier(selectCaissier);
        });

      //===== EVENT form occup caisse submit
      modalOccupCaisse
        .querySelector("form")
        .addEventListener("submit", async (e) => {
          //suspend submit
          e.preventDefault();

          //check validty
          if (!e.target.checkValidity()) {
            e.target.reportValidity();
            return;
          }

          //FETCH api occup caisse
          const occupCaisse = await apiRequest("/caisse/occup_caisse", {
            method: "POST",
            body: {
              num_caisse: num_caisse.trim(),
              id_utilisateur: $(selectCaissier).val().trim(),
            },
          });

          //error
          if (occupCaisse.message_type === "error") {
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
              occupCaisse.message;
            //progress bar
            progressBar.style.transition = "width 20s linear";
            progressBar.style.width = "100%";

            //add alert
            modalOccupCaisse.querySelector(".modal-body").prepend(alert);

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
          else if (occupCaisse.message_type === "invalid") {
            //alert
            const alertTemplate = document.querySelector(".alert-template");
            const clone = alertTemplate.content.cloneNode(true);
            const alert = clone.querySelector(".alert");
            const progressBar = alert.querySelector(".progress-bar");
            //alert type
            alert.classList.add("alert-warning");
            //icon
            alert
              .querySelector(".fad")
              .classList.add("fa-exclamation-triangle");
            //message
            alert.querySelector(".alert-message").innerHTML =
              occupCaisse.message;
            //progress bar
            progressBar.style.transition = "width 10s linear";
            progressBar.style.width = "100%";

            //add alert
            modalOccupCaisse.querySelector(".modal-body").prepend(alert);

            //progress launch animation
            setTimeout(() => {
              progressBar.style.width = "0%";
            }, 10);
            //auto close alert
            setTimeout(() => {
              alert.querySelector(".btn-close").click();
            }, 10000);

            return;
          }

          //close modal
          modalOccupCaisse
            .querySelector("#btn-close-modal-occup-caisse")
            .click();

          //refresh list free caisse
          listFreeCaisse(selectOccupCaisse);
          //refresh list caissier
          listCaissier(selectCaissier);
          //refresh filter caisse
          filterCaisse(
            tbodyCaisse,
            container.querySelector("#chart-cash-number"),
            selectStatus.value.trim(),
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
          alert.querySelector(".alert-message").innerHTML = occupCaisse.message;
          //progress bar
          progressBar.style.transition = "width 10s linear";
          progressBar.style.width = "100%";

          //add alert
          selectOccupCaisse.closest("div").prepend(alert);

          //progress launch animation
          setTimeout(() => {
            progressBar.style.width = "0%";
          }, 10);
          //auto close alert
          setTimeout(() => {
            alert.querySelector(".btn-close").click();
          }, 10000);

          return;
        });
    });

    //============================= CASH REPORT ====================
    cashReport();
  }, 1050);

  //================ FUNCTIONS ================

  //function - filter caisse
  async function filterCaisse(
    tbody,
    divChartCashNumber,
    status,
    arrange_by,
    order,
    date_by,
    per,
    from,
    to,
    month,
    year,
    search_user
  ) {
    try {
      //FETCH api filter caisse
      const filterCaisse = await apiRequest(
        `/caisse/filter_caisse?status=${status}&arrange_by=${arrange_by}&order=${order}&date_by=${date_by}&per=${per}&from=${from}&to=${to}&month=${month}&year=${year}&search_caisse=${search_user}`
      );

      //error
      if (filterCaisse.message_type === "error") {
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
        alert.querySelector(".alert-message").innerHTML = filterCaisse.message;
        //progress bar
        progressBar.style.transition = "width 20s linear";
        progressBar.style.width = "100%";

        //add alert
        tbody.closest("div").prepend(alert);

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
      else if (filterCaisse.message_type === "invalid") {
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
        alert.querySelector(".alert-message").innerHTML = filterCaisse.message;
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

      //===== CHART cash number
      //chart cash number title
      const chartCaisseNumberTitle = document.getElementById(
        "chart-caisse-number-title"
      );
      chartCaisseNumberTitle.innerHTML = ` (${formatterNumber.format(
        Number(filterCaisse.nb_caisse)
      )})`;
      //chart cash number canvas
      const canvasChartCaisseNumber = document.createElement("canvas");
      new Chart(canvasChartCaisseNumber, {
        type: "doughnut",
        data: {
          labels: [lang.free, lang.occuped, lang.deleted],
          datasets: [
            {
              label: lang.caisse_number,
              data: [
                filterCaisse.nb_free,
                filterCaisse.nb_occuped,
                filterCaisse.nb_deleted,
              ],
              backgroundColor: ["#1da033ff", "#0a50d1ff", "tomato"],
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
              text: lang.distribution_status,
            },
            legend: { position: "bottom", align: "center" },
          },
        },
      });
      divChartCashNumber.innerHTML = "";
      divChartCashNumber.append(canvasChartCaisseNumber);

      //===== TABLE list caisse
      tbody.innerHTML = "";
      filterCaisse.data.forEach((line) => {
        const tr = document.createElement("tr");

        //td - checkbox
        const tdCheckbox = document.createElement("td");
        const inputCheckbox = document.createElement("input");
        inputCheckbox.type = "checkbox";
        inputCheckbox.classList.add("form-check-input", "text-center");
        tdCheckbox.appendChild(inputCheckbox);

        //td - num_caisse
        const tdNumeroCaisse = document.createElement("td");
        tdNumeroCaisse.textContent = line.num_caisse;
        tdNumeroCaisse.classList.add("text-center");

        //td - solde
        const tdSolde = document.createElement("td");
        tdSolde.textContent = formatterNumber.format(Number(line.solde));
        tdSolde.classList.add("text-center");

        //td - seuil
        const tdSeuil = document.createElement("td");
        tdSeuil.textContent = formatterNumber.format(Number(line.seuil));
        tdSeuil.classList.add("text-center");

        //status
        const tdStatus = document.createElement("td");
        if (line.etat_caisse === "libre") {
          tdStatus.classList.add("text-success");
          tdStatus.textContent = lang.free.toLowerCase();
        } else if (line.etat_caisse === "occupé") {
          tdStatus.classList.add("text-secondary");
          tdStatus.textContent = lang.occuped.toLowerCase();
        } else {
          tdStatus.classList.add("text-dark");
          tdStatus.textContent = lang.deleted.toLowerCase();
        }
        tdStatus.classList.add("form-text", "text-center");

        //td - id_utilisateur
        const tdUserId = document.createElement("td");
        tdUserId.classList.add("text-center");
        tdUserId.textContent = line.id_utilisateur;

        //action
        const tdAction = document.createElement("td");
        tdAction.innerHTML = `<button class='btn btn-sm btn-light text-primary'><i class='fad fa-pen-to-square'></i></button>`;
        tdAction.classList.add("text-center");

        //append
        tr.append(
          tdCheckbox,
          tdNumeroCaisse,
          tdSolde,
          tdSeuil,
          tdStatus,
          tdUserId,
          tdAction
        );
        tr.dataset.numCaisse = line.num_caisse;
        tr.dataset.solde = line.solde;
        tr.dataset.seuil = line.seuil;
        tr.dataset.nbAe = line.nb_ae;
        tr.dataset.nbFacture = line.nb_facture;
        tr.dataset.nbSortie = line.nb_sortie;
        tr.dataset.totalAe = line.total_ae;
        tr.dataset.totalFacture = line.total_facture;
        tr.dataset.totalSortie = line.total_sortie;
        tbody.appendChild(tr);
      });

      //===== EVENT tr selection
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
        //===== EVENT tr selection
        tr.addEventListener("click", async () => {
          //tbody ligne_caisse
          const tbodyLC = container.querySelector("#tbody-lc");
          //table lc cash num
          const tableLcCashNum = container.querySelector("#table-lc-cash-num");
          //rest
          let rest =
              Number(tr.dataset.totalAe) +
              Number(tr.dataset.totalFacture) -
              Number(tr.dataset.totalSortie),
            status;
          //loss
          if (rest < 0) {
            status = `(<span class='text-danger'>${
              lang.loss
            } ${formatterTotal.format(rest)}</span>)`;
          }
          //neutral
          else if (rest === 0) {
            status = `(<span class='text-secondary'>${lang.neutral}</span>)`;
          }
          //benefice
          else {
            status = `(<span class='text-success'>${
              lang.benefice
            } +${formatterTotal.format(rest)}</span>)`;
          }

          //chart histo title
          const chartHistoTransactionsTitle = document.getElementById(
            "chart-histo-transactions-title"
          );
          chartHistoTransactionsTitle.innerHTML = `<i class="fad fa-chart-mixed-up-circle-dollar me-2"></i>${lang.curves_transactions} ${status}`;

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
            //remove tbody ligne_caisse
            tbodyLC.innerHTML = `<tr>
                                                        <td colspan="9">
                                                                <span class="bg-second placeholder w-100 rounded-1" style="height: 2vh !important;"></span>
                                                            </td>
                                                        </tr>`;
            //remove table lc cash num
            tableLcCashNum.innerHTML = "";
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

            //===== show chart nb_transactions
            chartNbTransactions(
              divChartNbTransactions,
              tr.dataset.nbAe,
              tr.dataset.nbFacture,
              tr.dataset.nbSortie
            );
            //===== show chart total transactions
            chartTotalTransactions(
              divChartTotalTransactions,
              tr.dataset.totalAe,
              tr.dataset.totalFacture,
              tr.dataset.totalSortie
            );

            //===== FETCH list transactions
            //get list autre entree
            const autreEntreeEffective = await apiRequest(
              `/entree/list_all_autre_entree?num_caisse=${tr.dataset.numCaisse}&date_by=${date_by}&per=${per}&from=${from}&to=${to}&month=${month}&year=${year}`
            );
            //get list facture
            const factureEffective = await apiRequest(
              `/entree/list_all_facture?num_caisse=${tr.dataset.numCaisse}&&date_by=${date_by}&per=${per}&from=${from}&to=${to}&month=${month}&year=${year}`
            );
            //get list sortie
            const sortieEffective = await apiRequest(
              `/sortie/list_all_demande_sortie?num_caisse=${tr.dataset.numCaisse}&date_by=${date_by}&per=${per}&from=${from}&to=${to}&month=${month}&year=${year}`
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

              //===== show chart histo nb_transactions
              chartHistoNbTransactions(
                divChartHistoNbTransactions,
                allDates,
                autreEntreeEffective,
                factureEffective,
                sortieEffective
              );
              //===== show chart histo total_transactions
              chartHistoTotalTransactions(
                divChartHistoTotalTransactions,
                allDates,
                autreEntreeEffective,
                factureEffective,
                sortieEffective
              );

              //table lc cash num
              tableLcCashNum.innerHTML = tr.dataset.numCaisse;
              //===== table ligne_caisse
              filterLigneCaisse(tbodyLC);
            }
          }
        });

        //========================== UPDATE CAISSE ====================
        tr.querySelector("button").addEventListener("click", async () => {
          //modal update caisse
          const modalUpdateCaisse = document.getElementById(
            "modal-update-caisse"
          );
          //form update caisse
          const formUpdateCaisse = modalUpdateCaisse.querySelector("form");
          //num_caisse
          modalUpdateCaisse.querySelector("#num-caisse").textContent =
            tr.dataset.numCaisse;
          //input - update num_caisse
          const inputUpdateNumCaisse = modalUpdateCaisse.querySelector(
            "#input-update-num-caisse-update"
          );
          inputUpdateNumCaisse.value = tr.dataset.numCaisse;
          //input - update solde
          const inputUpdateSolde = modalUpdateCaisse.querySelector(
            "#input-update-solde"
          );
          inputUpdateSolde.value = formatterInput.format(
            Number(tr.dataset.solde)
          );
          //input - update seuil
          const inputUpdateSeuil = modalUpdateCaisse.querySelector(
            "#input-update-seuil"
          );
          inputUpdateSeuil.value = formatterInput.format(
            Number(tr.dataset.seuil)
          );

          //===== EVENT input - update num_caisse
          inputUpdateNumCaisse.addEventListener("input", (e) => {
            e.target.value = e.target.value.replace(/[^0-9]/g, "");
          });
          //===== EVENT input - update solde
          inputUpdateSolde.addEventListener("input", (e) => {
            if (cookieLangValue === "en") {
              e.target.value = e.target.value.replace(/[^0-9.]/g, "");
              if (!/^\d*\.?\d*$/.test(e.target.value)) {
                e.target.value = e.target.value.slice(0, -1);
              }

              // add 0 in the start if ,
              if (e.target.value.startsWith(".")) {
                e.target.value = "0" + e.target.value;
              }

              //real value for calcul
              e.target.dataset.val = e.target.value.replace(
                /[\u202F\u00A0 ]/g,
                ""
              );
            } else {
              //number and , only
              e.target.value = e.target.value.replace(/[^0-9,]/g, "");
              if (!/^\d*\,?\d*$/.test(e.target.value)) {
                e.target.value = e.target.value.slice(0, -1);
              }
              // add 0 in the start if ,
              if (e.target.value.startsWith(",")) {
                e.target.value = "0" + e.target.value;
              }

              //real value for calcul
              e.target.dataset.val = e.target.value
                .replace(",", ".")
                .replace(/[\u202F\u00A0 ]/g, "");
            }
          });
          inputUpdateSolde.addEventListener("blur", (e) => {
            if (e.target.value.endsWith(",")) {
              e.target.value += "0";
            }
            e.target.value = formatterInput.format(
              e.target.value.replace(/[\u202F\u00A0 ]/g, "").replace(",", ".")
            );

            inputUpdateSeuil.dispatchEvent(new Event("input"));
            inputUpdateSeuil.dispatchEvent(new Event("blur"));
          });
          //====== EVENT input - update seuil
          inputUpdateSeuil.addEventListener("input", (e) => {
            if (cookieLangValue === "en") {
              e.target.value = e.target.value.replace(/[^0-9.]/g, "");
              if (!/^\d*\.?\d*$/.test(e.target.value)) {
                e.target.value = e.target.value.slice(0, -1);
              }

              // add 0 in the start if ,
              if (e.target.value.startsWith(".")) {
                e.target.value = "0" + e.target.value;
              }

              //real value for calcul
              e.target.dataset.val = e.target.value.replace(
                /[\u202F\u00A0 ]/g,
                ""
              );

              //seuil > solde
              const rest =
                Number(e.target.dataset.val) -
                Number(
                  inputUpdateSolde.value
                    .replace(/[\u202F\u00A0 ]/g, "")
                    .replace(",", ".")
                );
              if (rest > 0) {
                e.target.dataset.val = inputUpdateSolde.dataset.val;
                e.target.value = e.target.dataset.val;
              }
            } else {
              //number and , only
              e.target.value = e.target.value.replace(/[^0-9,]/g, "");
              if (!/^\d*\,?\d*$/.test(e.target.value)) {
                e.target.value = e.target.value.slice(0, -1);
              }
              // add 0 in the start if ,
              if (e.target.value.startsWith(",")) {
                e.target.value = "0" + e.target.value;
              }

              //real value for calcul
              e.target.dataset.val = e.target.value
                .replace(",", ".")
                .replace(/[\u202F\u00A0 ]/g, "");

              //seuil > solde
              const rest =
                Number(e.target.dataset.val) -
                Number(
                  inputUpdateSolde.value
                    .replace(/[\u202F\u00A0 ]/g, "")
                    .replace(",", ".")
                );
              if (rest > 0) {
                e.target.dataset.val = inputUpdateSolde.dataset.val;
                e.target.value = e.target.dataset.val;
              }
            }
          });
          inputUpdateSeuil.addEventListener("blur", (e) => {
            if (e.target.value.endsWith(",")) {
              e.target.value += "0";
            }
            e.target.value = formatterInput.format(
              e.target.value.replace(/[\u202F\u00A0 ]/g, "").replace(",", ".")
            );
          });

          //show modal update caisse
          new bootstrap.Modal(modalUpdateCaisse).show();
        });
      });

      //===== EVENT check all caisse
      const checkAllCaisse = document.getElementById("check-all-caisse");
      checkAllCaisse.addEventListener("change", (e) =>
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
  //function - filter ligne_caisse
  async function filterLigneCaisse(tbody) {
    try {
      //FETCH api filter ligne_caisse
      const filterLigneCaisse = await apiRequest(
        `/caisse/filter_ligne_caisse?num_caisse=${
          container.querySelector("#table-lc-cash-num").textContent
        }&id=${container
          .querySelector("#input-search-lc-id")
          .value.trim()}&from=${container
          .querySelector("#input-search-lc-from")
          .value.trim()}&to=${container
          .querySelector("#input-search-lc-to")
          .value.trim()}`
      );

      //error
      if (filterLigneCaisse.message_type === "error") {
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
          filterLigneCaisse.message;
        //progress bar
        progressBar.style.transition = "width 20s linear";
        progressBar.style.width = "100%";

        //add alert
        tbody.closest("div").prepend(alert);

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
      else if (filterLigneCaisse.message_type === "invalid") {
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
          filterLigneCaisse.message;
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

      //===== TABLE ligne_caisse
      //lang
      let langDate = "";
      if (cookieLangValue === "en") {
        langDate = "en-US";
      } else if (cookieLangValue === "fr") {
        langDate = "fr-FR";
      } else {
        langDate = "mg-MG";
      }
      //formatter date
      const fromatterDate = new Intl.DateTimeFormat(langDate, {
        dateStyle: "short",
        timeStyle: "short",
      });
      //set table list - ligne_caisse
      tbody.innerHTML = "";
      filterLigneCaisse.data.forEach((line) => {
        const tr = document.createElement("tr");

        //td - checkbox
        const tdCheckbox = document.createElement("td");
        const inputCheckbox = document.createElement("input");
        inputCheckbox.type = "checkbox";
        inputCheckbox.classList.add("form-check-input", "text-center");
        tdCheckbox.appendChild(inputCheckbox);

        //td - id_lc
        const tdIdLC = document.createElement("td");
        tdIdLC.textContent = line.id_lc;
        tdIdLC.classList.add("text-center");

        //td - date_debut
        const tdDateDebut = document.createElement("td");
        tdDateDebut.classList.add("text-center");
        if (line.date_debut) {
          const dateDebut = line.date_debut.replace(" ", "T");
          tdDateDebut.textContent = fromatterDate.format(new Date(dateDebut));
        } else {
          tdDateDebut.textContent = "-";
        }

        //td - date_fin
        const tdDateFin = document.createElement("td");
        tdDateFin.classList.add("text-center");
        if (line.date_fin) {
          const dateFin = line.date_fin.replace(" ", "T");
          tdDateFin.textContent = fromatterDate.format(new Date(dateFin));
        } else {
          tdDateFin.textContent = "-";
        }

        //td - utilisateur
        const tdUser = document.createElement("td");
        tdUser.classList.add("text-center");
        tdUser.textContent = line.id_utilisateur;

        //action
        const tdAction = document.createElement("td");
        tdAction.innerHTML = `<button class='btn btn-sm btn-light text-primary'><i class='fad fa-pen-to-square'></i></button>`;
        tdAction.classList.add("text-center");

        //append
        tr.append(tdCheckbox, tdIdLC, tdDateDebut, tdDateFin, tdUser, tdAction);
        tr.dataset.idLC = line.id_lc;
        tr.dataset.dateDebut = !line.date_debut
          ? ""
          : line.date_debut.slice(0, 16);
        tr.dataset.dateFin = !line.date_fin ? "" : line.date_fin.slice(0, 16);
        tr.dataset.idUtilisateur = line.id_utilisateur;
        tbody.appendChild(tr);
      });

      //foreach tr
      tbody.querySelectorAll("tr").forEach(async (tr) => {
        //========================= UPDATE LIGNE CAISSE ===================
        //===== EVENT btn update ligne_caisse
        tr.querySelector("button").addEventListener("click", async () => {
          //modal update ligne_caisse
          const modalUpdateLigneCaisse = container.querySelector(
            "#modal-update-ligne-caisse"
          );
          //modal id_lc
          modalUpdateLigneCaisse.querySelector(
            "#update-ligne-caisse-id-lc"
          ).innerHTML = tr.dataset.idLC;
          //input - update date_debut
          const inputUpdateDateDebut = modalUpdateLigneCaisse.querySelector(
            "#input-update-date-debut"
          );
          inputUpdateDateDebut.value = tr.dataset.dateDebut.replace(" ", "T");
          //input - update date_fin
          const inputUpdateDateFin = modalUpdateLigneCaisse.querySelector(
            "#input-update-date-fin"
          );
          inputUpdateDateFin.value = tr.dataset.dateFin.replace(" ", "T");

          //select - add id_utilisateur
          const selectUpdateIdUtilisateur =
            modalUpdateLigneCaisse.querySelector(
              "#select-update-id-utilisateur"
            );

          //initialize select2
          const $modalUpdateligneCaisse = $(modalUpdateLigneCaisse);
          $(selectUpdateIdUtilisateur).select2({
            theme: "bootstrap-5",
            placeholder: lang.select.toLowerCase(),
            dropdownParent: $modalUpdateligneCaisse,
          });

          //list user
          await listUser(selectUpdateIdUtilisateur);
          $(selectUpdateIdUtilisateur)
            .val(tr.dataset.idUtilisateur)
            .trigger("change");

          //===== EVENT - input update date_date_debut
          inputUpdateDateDebut.addEventListener("input", (e) => {
            //set min date_fin
            inputUpdateDateFin.min = e.target.value;
          });
          //===== EVENT - input add date_fin
          inputUpdateDateFin.addEventListener("input", (e) => {
            //set max date_debut
            inputUpdateDateDebut.max = e.target.value;
          });

          //show modal update ligne_caisse
          new bootstrap.Modal(modalUpdateLigneCaisse).show();
        });
      });
    } catch (e) {
      console.error(e);
    }
  }
  //function - add ligne_caisse
  function addLigneCaisse(tbodyLC) {
    //btn add ligne_caisse
    const btnAddLigneCaisse = container.querySelector("#btn-add-ligne-caisse");
    //modal add ligne_caisse
    const modalAddLigneCaisse = document.getElementById(
      "modal-add-ligne-caisse"
    );

    //===== EVENT btn add ligne_caisse
    btnAddLigneCaisse.addEventListener("click", () => {
      //num_caisse
      const numCaisse = container
        .querySelector("#table-lc-cash-num")
        .textContent.trim();
      //num_caisse - empty
      if (numCaisse === "") {
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
          "Veuiller séléctionner une caisse";
        //progress bar
        progressBar.style.transition = "width 10s linear";
        progressBar.style.width = "100%";

        //add alert
        tbodyLC.closest("div").prepend(alert);

        //progress launch animation
        setTimeout(() => {
          progressBar.style.width = "0%";
        }, 10);
        //auto close alert
        setTimeout(() => {
          alert.querySelector(".btn-close").click();
        }, 10000);

        return;
      }
      //modal - num_caisse
      modalAddLigneCaisse.querySelector(
        "#add-ligne-caisse-cash-num"
      ).innerHTML = numCaisse;

      //input - add date_debut
      const inputAddDateDebut = modalAddLigneCaisse.querySelector(
        "#input-add-date-debut"
      );
      //input - add date_fin
      const inputAddDateFin = modalAddLigneCaisse.querySelector(
        "#input-add-date-fin"
      );
      //select - add id_utilisateur
      const selectAddIdUtilisateur = modalAddLigneCaisse.querySelector(
        "#select-add-id-utilisateur"
      );

      //initialize select2
      const $modalAddligneCaisse = $(modalAddLigneCaisse);
      $(selectAddIdUtilisateur).select2({
        theme: "bootstrap-5",
        placeholder: lang.select.toLowerCase(),
        dropdownParent: $modalAddligneCaisse,
      });

      //list user
      listUser(selectAddIdUtilisateur);

      //===== EVENT - input add date_date_debut
      inputAddDateDebut.addEventListener("input", (e) => {
        //set min date_fin
        inputAddDateFin.min = e.target.value;
      });
      //===== EVENT - input add date_fin
      inputAddDateFin.addEventListener("input", (e) => {
        //set max date_debut
        inputAddDateDebut.max = e.target.value;
      });

      //show modal add ligne_caisse
      new bootstrap.Modal(modalAddLigneCaisse).show();

      //===== EVENT - btn refresh list user
      modalAddLigneCaisse
        .querySelector("#btn-refresh-list-user")
        .addEventListener("click", () => {
          listUser(selectAddIdUtilisateur);
        });

      //===== EVENT - form add ligne_caisse submit
      modalAddLigneCaisse
        .querySelector("form")
        .addEventListener("submit", async (e) => {
          //suspend submit
          e.preventDefault();

          //check validity
          if (!e.target.checkValidity()) {
            e.target.reportValidity();
            return;
          }

          try {
            //FETCH api add ligne_caisse
            const apiAddLigneCaisse = await apiRequest(
              "/caisse/add_ligne_caisse",
              {
                method: "POST",
                body: {
                  num_caisse: numCaisse,
                  date_debut: inputAddDateDebut.value.trim(),
                  date_fin: inputAddDateFin.value.trim(),
                  id_utilisateur: $(selectAddIdUtilisateur).val().trim(),
                },
              }
            );
            //error
            if (apiAddLigneCaisse.message_type === "error") {
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
                apiAddLigneCaisse.message;
              //progress bar
              progressBar.style.transition = "width 20s linear";
              progressBar.style.width = "100%";

              //add alert
              modalAddLigneCaisse.querySelector(".modal-body").prepend(alert);

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
            else if (apiAddLigneCaisse.message_type === "invalid") {
              //alert
              const alertTemplate = document.querySelector(".alert-template");
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
                apiAddLigneCaisse.message;
              //progress bar
              progressBar.style.transition = "width 10s linear";
              progressBar.style.width = "100%";

              //add alert
              modalAddLigneCaisse.querySelector(".modal-body").prepend(alert);

              //progress launch animation
              setTimeout(() => {
                progressBar.style.width = "0%";
              }, 10);
              //auto close alert
              setTimeout(() => {
                alert.querySelector(".btn-close").click();
              }, 10000);

              return;
            }
            //sucess
            else {
              //alert
              const alertTemplate = container.querySelector(".alert-template");
              const clone = alertTemplate.content.cloneNode(true);
              const alert = clone.querySelector(".alert");
              const progressBar = alert.querySelector(".progress-bar");
              //alert type
              alert.classList.add("alert-success");
              //icon
              alert.querySelector(".fad").classList.add("fa-check-circle");
              //message
              alert.querySelector(".alert-message").innerHTML =
                apiAddLigneCaisse.message;
              //progress bar
              progressBar.style.transition = "width 10s linear";
              progressBar.style.width = "100%";

              //add alert
              tbodyLC.closest("div").prepend(alert);

              //progress lanch animation
              setTimeout(() => {
                progressBar.style.width = "0%";
              }, 10);

              //auto close alert
              setTimeout(() => {
                alert.querySelector(".btn-close").click();
              }, 10000);

              //hide modal
              modalAddLigneCaisse
                .querySelector("#btn-close-modal-add-ligne-caisse")
                .click();

              //refresh filter caisse
              filterCaisse(
                tbodyCaisse,
                container.querySelector("#chart-cash-number"),
                selectStatus.value.trim(),
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
              //refresh filter ligne_caisse
              filterLigneCaisse(tbodyLC);
            }
          } catch (e) {
            console.error(e);
          }
        });
    });
  }
  //function - list user
  async function listUser(select) {
    try {
      //FETCH api list all user
      const response = await apiRequest("/user/list_all_user");

      //error
      if (response.message_type === "error") {
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
        alert.querySelector(".alert-message").innerHTML = response.message;
        //progress bar
        progressBar.style.transition = "width 20s linear";
        progressBar.style.width = "100%";

        //add alert
        select.closest("div").prepend(alert);

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

      //set options
      select.innerHTML = "<option></option>";
      response.data.forEach((line) => {
        const option = document.createElement("option");
        option.value = line.id_utilisateur;
        option.innerText = `${line.id_utilisateur} - ${line.nom_prenoms}`;

        //append option to select
        select.append(option);
      });
    } catch (e) {
      console.error(e);
    }
  }
  //function - list free caisse
  async function listFreeCaisse(select) {
    //initialize select2
    $(select).select2({
      theme: "bootstrap-5",
      placeholder: lang.select.toLowerCase(),
      dropdownParent: $(select.closest(".card")),
    });
    try {
      //FETCH api list free caisse
      const response = await apiRequest("/caisse/list_free_caisse");

      //error
      if (response.message_type === "error") {
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
        alert.querySelector(".alert-message").innerHTML = response.message;
        //progress bar
        progressBar.style.transition = "width 20s linear";
        progressBar.style.width = "100%";

        //add alert
        select.closest("div").prepend(alert);

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

      //set options
      select.innerHTML = "<option></option>";
      response.data.forEach((line) => {
        const option = document.createElement("option");
        option.value = line.num_caisse;
        option.innerText = line.num_caisse;

        //append option to select
        select.append(option);
      });
    } catch (e) {
      console.error(e);
    }
  }
  //function - list caissier
  async function listCaissier(select) {
    try {
      //FETCH api list caissier
      const response = await apiRequest("/user/list_caissier");

      //error
      if (response.message_type === "error") {
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
        alert.querySelector(".alert-message").innerHTML = response.message;
        //progress bar
        progressBar.style.transition = "width 20s linear";
        progressBar.style.width = "100%";

        //add alert
        select.closest("div").prepend(alert);

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

      //set options
      select.innerHTML = "<option></option>";
      response.data.forEach((line) => {
        const option = document.createElement("option");
        option.value = line.id_utilisateur;
        option.innerText = `${line.id_utilisateur} - ${line.fullname}`;

        //append option to select
        select.append(option);
      });
    } catch (e) {
      console.error(e);
    }
  }
  //function - cash report
  function cashReport() {
    //a - cash report
    const aCashReport = container.querySelector("#a-cash-report");
    //modal cash report
    const modalCashReport = container.querySelector("#modal-cash-report");
    //select - num_caisse
    const cashReportSelectNumCaisse = modalCashReport.querySelector(
      "#cash-report-select-num-caisse"
    );
    //select - date_by
    const cashReportSelectDateBy = modalCashReport.querySelector(
      "#cash-report-select-date-by"
    );
    //select per
    const cashReportSelectPer = modalCashReport.querySelector(
      "#cash-report-select-per"
    );
    //date - from
    const cashReportDateFrom = modalCashReport.querySelector(
      "#cash-report-date-from"
    );
    //date - to
    const cashReportDateTo = modalCashReport.querySelector(
      "#cash-report-date-to"
    );
    //select - month
    const cashReportSelectMonth = modalCashReport.querySelector(
      "#cash-report-select-month"
    );
    //select - year
    const cashReportSelectYear = modalCashReport.querySelector(
      "#cash-report-select-year"
    );

    //load select date_by value from local storage for div
    const savedCashReportSelectDateBy = localStorage.getItem(
      cashReportSelectDateBy.id
    );
    cashReportSelectDateBy.value = !savedCashReportSelectDateBy
      ? "all"
      : savedCashReportSelectDateBy;
    switch (cashReportSelectDateBy.value) {
      //per
      case "per":
        modalCashReport
          .querySelector("#cash-report-div-per")
          .classList.add("active");
        break;
      //between
      case "between":
        modalCashReport
          .querySelector("#cash-report-div-between")
          .classList.add("active");
        break;
      //month_year
      case "month_year":
        modalCashReport
          .querySelector("#cash-report-div-month_year")
          .classList.add("active");
        break;
    }

    //list all caisse
    listAllCaisse(cashReportSelectNumCaisse);

    //===== EVENT select date_by
    cashReportSelectDateBy.addEventListener("change", (e) => {
      //hide all
      modalCashReport.querySelectorAll(".date-by").forEach((dateBy) => {
        if (dateBy.classList.contains("active")) {
          dateBy.classList.remove("active");
        }
      });
      //active
      switch (e.target.value) {
        //per
        case "per":
          modalCashReport
            .querySelector("#cash-report-div-per")
            .classList.add("active");
          break;
        //between
        case "between":
          modalCashReport
            .querySelector("#cash-report-div-between")
            .classList.add("active");
          break;
        //month_year
        case "month_year":
          modalCashReport
            .querySelector("#cash-report-div-month_year")
            .classList.add("active");
          break;
      }

      localStorage.setItem(e.target.id, e.target.value);
    });
    //===== EVENT date from
    cashReportDateFrom.addEventListener("input", (e) => {
      cashReportDateTo.min = e.target.value;
    });
    //===== EVENT date to
    cashReportDateTo.addEventListener("input", (e) => {
      cashReportDateFrom.max = e.target.value;
    });

    ///===== EVENT a cash report
    aCashReport.addEventListener("click", () => {
      //show modal cash report
      new bootstrap.Modal(modalCashReport).show();
    });

    //===== EVENT form submit
    modalCashReport
      .querySelector("form")
      .addEventListener("submit", async (e) => {
        //suspend submit
        e.preventDefault();
        //check validity
        if (!e.target.checkValidity()) {
          e.target.reportValidity();
          return;
        }

        try {
          //FETCH api cash report
          const cashReport = await apiRequest(
            `/caisse/cash_report?num_caisse=${cashReportSelectNumCaisse.value}&date_by=${cashReportSelectDateBy.value}&per=${cashReportSelectPer.value}&from=${cashReportDateFrom.value}&to=${cashReportDateTo.value}&month=${cashReportSelectMonth.value}&year=${cashReportSelectYear.value}`
          );

          //error
          if (cashReport.message_type === "error") {
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
              cashReport.message;
            //progress bar
            progressBar.style.transition = "width 20s linear";
            progressBar.style.width = "100%";

            //add alert
            modalCashReport.querySelector(".modal-body").prepend(alert);

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
          else if (cashReport.message_type === "invalid") {
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
              cashReport.message;
            //progress bar
            progressBar.style.transition = "width 10s linear";
            progressBar.style.width = "100%";

            //add alert
            modalCashReport.querySelector(".modal-body").prepend(alert);

            //progress launch animation
            setTimeout(() => {
              progressBar.style.width = "0%";
            }, 10);
            //auto close alert
            setTimeout(() => {
              alert.querySelector(".btn-close").click();
            }, 10000);

            return;
          }

          //download cash report
          const a = document.createElement("a");
          a.href = `data:application/pdf;base64,${cashReport.pdf}`;
          a.download = cashReport.file_name;
          a.click();
          //close modal
          modalCashReport.querySelector("#btn-close-modal-cash-report").click();

          return;
        } catch (e) {
          console.error(e);
        }
      });
  }
  //function - list all caisse
  async function listAllCaisse(select) {
    //initialize select2
    $(select).select2({
      theme: "bootstrap-5",
      placeholder: lang.select.toLowerCase(),
      dropdownParent: $(select.closest(".modal")),
    });

    try {
      //FETCH api list all caisse
      const response = await apiRequest("/caisse/list_all_caisse");

      //error
      if (response.message_type === "error") {
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
        alert.querySelector(".alert-message").innerHTML = response.message;
        //progress bar
        progressBar.style.transition = "width 20s linear";
        progressBar.style.width = "100%";

        //add alert
        select.closest(".modal-body").prepend(alert);

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

      //set options
      select.innerHTML = "<option></option>";
      response.data.forEach((line) => {
        const option = document.createElement("option");
        option.value = line.num_caisse;
        option.innerText = line.num_caisse;

        //append option to select
        select.append(option);
      });
    } catch (e) {
      console.error(e);
    }
  }
});
