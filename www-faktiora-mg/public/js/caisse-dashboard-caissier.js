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

    //===== EVENT select per
    selectPer.addEventListener("change", (e) => {
      chartCaisse(
        selectDateBy.value.trim(),
        e.target.value.trim(),
        dateFrom.value.trim(),
        dateTo.value.trim(),
        selectMonth.value.trim(),
        selectYear.value.trim()
      );
      localStorage.setItem(e.target.id, e.target.value);
    });
    //===== EVENT date from
    dateFrom.addEventListener("input", (e) => {
      chartCaisse(
        selectDateBy.value.trim(),
        selectPer.value.trim(),
        e.target.value.trim(),
        dateTo.value.trim(),
        selectMonth.value.trim(),
        selectYear.value.trim()
      );
      localStorage.setItem(e.target.id, e.target.value);
      dateTo.min = e.target.value;
    });
    //===== EVENT date to
    dateTo.addEventListener("input", (e) => {
      chartCaisse(
        selectDateBy.value.trim(),
        selectPer.value.trim(),
        dateFrom.value.trim(),
        e.target.value.trim(),
        selectMonth.value.trim(),
        selectYear.value.trim()
      );
      localStorage.setItem(e.target.id, e.target.value);
      dateFrom.max = e.target.value;
    });
    //===== EVENT select month
    selectMonth.addEventListener("change", (e) => {
      chartCaisse(
        selectDateBy.value.trim(),
        selectPer.value.trim(),
        dateFrom.value.trim(),
        dateTo.value.trim(),
        e.target.value.trim(),
        selectYear.value.trim()
      );
      localStorage.setItem(e.target.id, e.target.value);
    });
    //===== EVENT select year
    selectYear.addEventListener("change", (e) => {
      chartCaisse(
        selectDateBy.value.trim(),
        selectPer.value.trim(),
        dateFrom.value.trim(),
        dateTo.value.trim(),
        selectMonth.value.trim(),
        e.target.value.trim()
      );
      localStorage.setItem(e.target.id, e.target.value);
    });

    //========================= TRANSACTIONS ========================
    chartCaisse(
      selectDateBy.value.trim(),
      selectPer.value.trim(),
      dateFrom.value.trim(),
      dateTo.value.trim(),
      selectMonth.value.trim(),
      selectYear.value.trim()
    );

    //========================= OCCUP CAISSE ======================
    //select occup caisse
    const selectOccupCaisse = container.querySelector("#select-occup-caisse");
    //list free caisse
    listFreeCaisse(selectOccupCaisse);

    //=====  EVENT form occup caisse submit
    container
      .querySelector("#form-occup-caisse")
      .addEventListener("submit", async (e) => {
        //suspend submit
        e.preventDefault();
        //check validity
        if (!e.target.checkValidity()) {
          e.target.reportValidity();
          return;
        }

        try {
          //FETCH api occup caisse
          const apiOccupCaisse = await apiRequest(`/caisse/occup_caisse`, {
            method: "POST",
            body: { num_caisse: selectOccupCaisse.value.trim() },
          });

          //error
          if (apiOccupCaisse.message_type === "error") {
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
              apiOccupCaisse.message;
            //progress bar
            progressBar.style.transition = "width 20s linear";
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
            }, 20000);

            return;
          }
          //invalid
          else if (apiOccupCaisse.message_type === "invalid") {
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
              apiOccupCaisse.message;
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

          //success
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
            apiOccupCaisse.message;
          //progress bar
          progressBar.style.transition = "width 3s linear";
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
            //refresh page
            location.reload();
          }, 3000);

          return;
        } catch (e) {
          console.error(e);
        }
      });

    //========================= QUIT CAISSE =========================
    //btn quit caisse
    const btnQuitCaisse = container.querySelector("#btn-quit-caisse");
    //===== EVENT btn quit caisse
    btnQuitCaisse.addEventListener("click", async () => {
      try {
        //FETCH api quit caisse
        const apiQuitCaisse = await apiRequest(`/caisse/quit_caisse`, {
          method: "PUT",
        });

        //error
        if (apiQuitCaisse.message_type !== "success") {
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
            apiQuitCaisse.message;
          //progress bar
          progressBar.style.transition = "width 20s linear";
          progressBar.style.width = "100%";

          //add alert
          btnQuitCaisse.parentElement.parentElement.prepend(alert);

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

        //reload page
        location.reload();

        return;
      } catch (e) {
        console.error(e);
      }
    });

    //======================== FILTER LIGNE CAISSE ==================
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
      filterLigneCaisse(
        e.target.value.trim(),
        inputSearchLCFrom.value.trim(),
        inputSearchLCTo.value.trim()
      );
    });
    //===== EVENT input search lc from
    inputSearchLCFrom.addEventListener("input", (e) => {
      inputSearchLCTo.min = e.target.value;
      localStorage.setItem(e.target.id, e.target.value);
      filterLigneCaisse(
        inputSearchLCId.value.trim(),
        e.target.value.trim(),
        inputSearchLCTo.value.trim()
      );
    });
    //===== EVENT input search lc to
    inputSearchLCTo.addEventListener("input", (e) => {
      inputSearchLCFrom.max = e.target.value;
      localStorage.setItem(e.target.id, e.target.value);
      filterLigneCaisse(
        inputSearchLCId.value.trim(),
        inputSearchLCFrom.value.trim(),
        e.target.value.trim()
      );
    });
    filterLigneCaisse(
      inputSearchLCId.value.trim(),
      inputSearchLCFrom.value.trim(),
      inputSearchLCTo.value.trim()
    );

    //====================== CASH REPORT ==================
    cashReport();

    //===================== SOLDE SEUIL ==============
    soldeSeuil(
      container.querySelector("#div-balance"),
      container.querySelector("#div-treshold")
    );
  }, 1050);

  //====================== FUNCTIONS ========================

  //function - chart caisse
  async function chartCaisse(date_by, per, from, to, month, year) {
    //div chart nb_transactions
    const divChartNbTransactions = container.querySelector(
      "#chart-nb-transactions"
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

    try {
      //===== FETCH
      //FETCH ae effective
      const autreEntreeEffective = await apiRequest(
        `/entree/list_all_autre_entree?date_by=${date_by}&per=${per}&from=${from}&to=${to}&month=${month}&year=${year}`
      );
      //FETCH facture effective
      const factureEffective = await apiRequest(
        `/entree/list_all_facture?date_by=${date_by}&per=${per}&from=${from}&to=${to}&month=${month}&year=${year}`
      );
      //FETCH sortie effective
      const sortieEffective = await apiRequest(
        `/sortie/list_all_demande_sortie?date_by=${date_by}&per=${per}&from=${from}&to=${to}&month=${month}&year=${year}`
      );

      //error autre entree effective
      if (autreEntreeEffective.message_type !== "success") {
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
          autreEntreeEffective.message;
        //progress bar
        progressBar.style.transition = "width 20s linear";
        progressBar.style.width = "100%";

        //add alert
        container.querySelector(".searchbar .alert-container").prepend(alert);

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
      //error facture effective
      else if (factureEffective.message_type !== "success") {
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
          factureEffective.message;
        //progress bar
        progressBar.style.transition = "width 20s linear";
        progressBar.style.width = "100%";

        //add alert
        container.querySelector(".searchbar .alert-container").prepend(alert);

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
      //error sortie effective
      else if (sortieEffective.message_type !== "success") {
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
          sortieEffective.message;
        //progress bar
        progressBar.style.transition = "width 20s linear";
        progressBar.style.width = "100%";

        //add alert
        container.querySelector(".searchbar .alert-container").prepend(alert);

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

      //success effective transactions
      //all dates
      const allDates = [
        ...new Set([
          ...autreEntreeEffective.data.map((d) => d.date),
          ...factureEffective.data.map((d) => d.date),
          ...sortieEffective.data.map((d) => d.date),
        ]),
      ].sort();

      //===== show chart nb_transactions
      chartNbTransactions(
        divChartNbTransactions,
        autreEntreeEffective.nb_ae,
        factureEffective.nb_facture,
        sortieEffective.nb_sortie
      );
      //===== show chart total_transactions
      chartTotalTransactions(
        divChartTotalTransactions,
        autreEntreeEffective.total_ae,
        factureEffective.total_facture,
        sortieEffective.total_sortie
      );

      //rest
      let rest =
          Number(autreEntreeEffective.total_ae) +
          Number(factureEffective.total_facture) -
          Number(sortieEffective.total_sortie),
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
    } catch (e) {
      console.error(e);
    }
  }
  //function chart nb_transactions
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
  //function - filter ligne_caisse
  async function filterLigneCaisse(id, from, to) {
    //tbody
    const tbodyLC = container.querySelector("#tbody-lc");

    try {
      //FETCH api filter ligne_caisse
      const apiFilterLigneCaisse = await apiRequest(
        `/caisse/filter_ligne_caisse?id=${id}&from=${from}&to=${to}`
      );

      // error
      if (apiFilterLigneCaisse.message_type === "error") {
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
          apiFilterLigneCaisse.message;
        //progress bar
        progressBar.style.transition = "width 20s linear";
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
        }, 20000);

        return;
      }
      //invalid
      else if (apiFilterLigneCaisse.message_type === "invalid") {
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
          apiFilterLigneCaisse.message;
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

      //===== TABLE ligne_caisse
      //lang
      let langDate = "";
      if (cookieLangValue === "en") {
        langDate = "en-US";
      } else if (cookieLangValue === "fr") {
        langDate = "fr-FR";
        langDate = "mg-MG";
      }
      //formatter date
      const fromatterDate = new Intl.DateTimeFormat(langDate, {
        dateStyle: "short",
        timeStyle: "short",
      });
      //set table list ligne_caisse
      tbodyLC.innerHTML = "";
      apiFilterLigneCaisse.data.forEach((line) => {
        const tr = document.createElement("tr");

        //td - id_lc
        const tdIdLC = document.createElement("td");
        tdIdLC.textContent = line.id_lc;
        tdIdLC.classList.add("text-center");

        //td - date_debut
        const tdDateDebut = document.createElement("td");
        tdDateDebut.classList.add("text-center");
        line.date_debut;
        const dateDebut = line.date_debut.replace(" ", "T");
        tdDateDebut.textContent = fromatterDate.format(new Date(dateDebut));

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
        tdUser.classList.add("text-center");

        //append
        tr.append(tdIdLC, tdDateDebut, tdDateFin, tdUser);
        tbodyLC.appendChild(tr);
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
            `/caisse/cash_report?&date_by=${cashReportSelectDateBy.value}&per=${cashReportSelectPer.value}&from=${cashReportDateFrom.value}&to=${cashReportDateTo.value}&month=${cashReportSelectMonth.value}&year=${cashReportSelectYear.value}`
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
  //function - solde seuil
  async function soldeSeuil(divBalance, divTresholde) {
    try {
      //FETCH api solde seuil
      const apiSodleSeuil = await apiRequest("/caisse/solde_seuil");

      //error
      if (apiSodleSeuil.message_type === "error") {
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
        alert.querySelector(".alert-message").innerHTML = apiSodleSeuil.message;
        //progress bar
        progressBar.style.transition = "width 20s linear";
        progressBar.style.width = "100%";

        //add alert
        divBalance.closest("div").prepend(alert);

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

      if (!Array.isArray(apiSodleSeuil.data)) {
        divBalance.innerHTML = formatterTotal.format(
          Number(apiSodleSeuil.data.solde)
        );
        divTresholde.innerHTML = formatterTotal.format(
          Number(apiSodleSeuil.data.seuil)
        );
      }
      return;
    } catch (e) {
      console.error(e);
    }
  }
});
