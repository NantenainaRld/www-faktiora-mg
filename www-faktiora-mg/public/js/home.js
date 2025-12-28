document.addEventListener("DOMContentLoaded", () => {
  setTimeout(async () => {
    //template real content
    const templateRealContent = document.getElementById("template-home");
    // load template real
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

    try {
      //================ chart client ========================
      // div chart client
      const divChartClient = document.getElementById("chart-client");
      //get client effective
      const clientEffective = await apiRequest("/client/list_all_client");
      //get user effective
      const userEffective = await apiRequest("/user/list_all_user");

      //formatter
      let formatterNumber, formatterTotal;
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

      //not success
      if (clientEffective.message_type !== "success") {
        divChartClient.innerHTML = `<div class='alert alert-danger form-text'><i class='fad fa-exclamation-circle me-2'></i>${clientEffective.message}'</div>`;
      }
      //success
      else {
        //error user effective
        if (userEffective.message_type !== "success") {
          divChartClient.innerHTML = `<div class='alert alert-danger form-text'><i class='fad fa-exclamation-circle me-2'></i>${clientEffective.message}'</div>`;
        }
        //success
        else {
          //chart client title
          const chartClientTitle =
            document.getElementById("chart-client-title");
          chartClientTitle.innerHTML = `<i class="fad fa-user me-2" id="chart-client-title"></i>${
            lang.clients_users
          } (${formatterNumber.format(
            Number(clientEffective.nb_client)
          )}, ${formatterNumber.format(Number(userEffective.nb_user))})`;

          //show chart
          divChartClient.innerHTL = "";
          //chart client
          divChartClient.innerHTML = "";
          const canvasClient = document.createElement("canvas");
          const chartClient = new Chart(canvasClient, {
            type: "doughnut",
            data: {
              labels: [lang.male, lang.female],
              datasets: [
                {
                  label: lang.effective_client,
                  data: [clientEffective.nb_male, clientEffective.nb_female],
                  backgroundColor: ["#0EA5E9", "#EC4899"],
                  borderColor: "white",
                  borderRadius: 5,
                },
                {
                  label: lang.effective_user,
                  data: [userEffective.nb_male, userEffective.nb_female],
                  backgroundColor: ["#0EA5E9", "#EC4899"],
                  borderColor: "white",
                  borderRadius: 5,
                },
              ],
            },
            options: {
              responsive: true,
              cutout: "60%",
              plugins: {
                title: {
                  display: true,
                  position: "top",
                  text: lang.effective_client_user,
                },
                legend: { position: "bottom", align: "center" },
              },
            },
          });
          divChartClient.append(canvasClient);
        }
      }
      //============== chart user ===================================
      //div chart user
      const divChartUser = document.getElementById("chart-user");
      //not success
      if (userEffective.message_type !== "success") {
        divChartUser.innerHTML = `<div class='alert alert-danger form-text'><i class='fad fa-exclamation-circle me-2'></i>${userEffective.message}'</div>`;
      }
      //success
      else {
        //show chart
        divChartUser.innerHTML = "";
        const canvasUser = document.createElement("canvas");
        const chartUser = new Chart(canvasUser, {
          type: "doughnut",
          data: {
            labels: [lang.admin, lang.cashier],
            datasets: [
              {
                label: lang.effective_user,
                data: [userEffective.nb_admin, userEffective.nb_caissier],
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
                text: lang.effective_user_role,
              },
              legend: { position: "bottom", align: "center" },
            },
          },
        });
        divChartUser.append(canvasUser);
      }

      //============== chart transactions
      //div chart transactions
      const divChartTransactions =
        document.getElementById("chart-transactions");
      //div chart transactions curves
      const divChartTransactionsCurves = document.getElementById(
        "chart-transactions-curves"
      );
      //get list autre entree
      const autreEntreeEffective = await apiRequest(
        "/entree/list_all_autre_entree"
      );
      //get list facture
      const factureEffective = await apiRequest("/entree/list_all_facture");
      //get list sortie
      const sortieEffective = await apiRequest(
        "/sortie/list_all_demande_sortie"
      );
      //error autre entree effective
      if (autreEntreeEffective.message_type !== "success") {
        divChartTransactions.innerHTML = `<div class='alert alert-danger form-text'><i class='fad fa-exclamation-circle me-2'></i>${autreEntreeEffective.message}'</div>`;
        divChartTransactionsCurves.innerHTML = `<div class='alert alert-danger form-text'><i class='fad fa-exclamation-circle me-2'></i>${autreEntreeEffective.message}'</div>`;
      }
      //error facture effective
      else if (factureEffective.message_type !== "success") {
        divChartTransactions.innerHTML = `<div class='alert alert-danger form-text'><i class='fad fa-exclamation-circle me-2'></i>${autreEntreeEffective.message}'</div>`;
        divChartTransactionsCurves.innerHTML = `<div class='alert alert-danger form-text'><i class='fad fa-exclamation-circle me-2'></i>${autreEntreeEffective.message}'</div>`;
      }
      //error sortie effective
      else if (sortieEffective.message_type !== "success") {
        divChartTransactions.innerHTML = `<div class='alert alert-danger form-text'><i class='fad fa-exclamation-circle me-2'></i>${autreEntreeEffective.message}'</div>`;
        divChartTransactionsCurves.innerHTML = `<div class='alert alert-danger form-text'><i class='fad fa-exclamation-circle me-2'></i>${autreEntreeEffective.message}'</div>`;
      }
      //successs
      else {
        //============== chart transactions number

        //initialize variables
        let nbTransactions =
            Number(autreEntreeEffective.nb_ae) +
            Number(factureEffective.nb_facture) +
            Number(sortieEffective.nb_sortie),
          totalTransactions =
            Number(autreEntreeEffective.total_ae) +
            Number(factureEffective.total_facture) +
            Number(sortieEffective.total_sortie);

        nbTransactions = formatterNumber.format(nbTransactions);
        totalTransactions = formatterTotal.format(totalTransactions);
        //div chart transactions number
        const divChartTransactonsNumber = document.getElementById(
          "chart-transactions-number"
        );
        //show chart
        divChartTransactonsNumber.innerHTML = "";
        const canvasTransactionsNumber = document.createElement("canvas");
        const chartTransactionsNumber = new Chart(canvasTransactionsNumber, {
          type: "doughnut",
          data: {
            labels: [lang.autre_entree, lang.bill, lang.outflow],
            datasets: [
              {
                label: lang.nb_transactions,
                data: [
                  autreEntreeEffective.nb_ae,
                  factureEffective.nb_facture,
                  sortieEffective.nb_sortie,
                ],
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
                text: `${lang.nb_transactions} (${nbTransactions})`,
              },
              legend: { position: "bottom", align: "center" },
            },
          },
        });
        divChartTransactonsNumber.append(canvasTransactionsNumber);
        //============== chart transactions total
        //div chart transactions total
        const divChartTransactonsTotal = document.getElementById(
          "chart-transactions-total"
        );
        //show chart
        divChartTransactonsTotal.innerHTML = "";
        const canvasTransactionsTotal = document.createElement("canvas");
        const chartTransactionsTotal = new Chart(canvasTransactionsTotal, {
          type: "doughnut",
          data: {
            labels: [lang.autre_entree, lang.bill, lang.outflow],
            datasets: [
              {
                label: lang.total_transactions,
                data: [
                  autreEntreeEffective.total_ae,
                  factureEffective.total_facture,
                  sortieEffective.total_sortie,
                ],
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
                text: `${lang.total_transactions} (${totalTransactions})`,
              },
              legend: { position: "bottom", align: "center" },
            },
          },
        });
        divChartTransactonsTotal.append(canvasTransactionsTotal);

        //=============== chart transactions histo
        //title
        const chartTransactionsCurvesTitle = document.getElementById(
          "chart-transactions-curves-title"
        );
        //rest
        let rest =
            Number(autreEntreeEffective.total_ae) +
            Number(factureEffective.total_facture) -
            Number(sortieEffective.total_sortie),
          status;
        //loss
        if (rest < 0) {
          if (cookieLangValue === "en") {
            const formatter = new Intl.NumberFormat("en-US", {
              style: "decimal",
              maximumFractionDigits: 2,
              minimumFractionDigits: 0,
            });
            rest = formatter.format(rest);
          } else {
            const formatter = new Intl.NumberFormat("en-FR", {
              style: "decimal",
              maximumFractionDigits: 2,
              minimumFractionDigits: 0,
            });
            rest = formatter.format(rest);
          }
          status = `(<span class='text-danger'>${lang.loss} ${rest} ${currencyUnits}</span>)`;
        }
        //neutral
        else if (rest === 0) {
          status = `(<span class='text-secondary'>${lang.neutral}</span>)`;
        }
        //benefice
        else {
          if (cookieLangValue === "en") {
            const formatter = new Intl.NumberFormat("en-US", {
              style: "decimal",
              maximumFractionDigits: 2,
              minimumFractionDigits: 0,
            });
            rest = formatter.format(rest);
          } else {
            const formatter = new Intl.NumberFormat("en-FR", {
              style: "decimal",
              maximumFractionDigits: 2,
              minimumFractionDigits: 0,
            });
            rest = formatter.format(rest);
          }
          status = `(<span class='text-success'>${lang.benefice} +${rest} ${currencyUnits}</span>)`;
        }
        chartTransactionsCurvesTitle.innerHTML = `<i class="fad fa-chart-mixed-up-circle-dollar me-2"></i>${lang.curves_transactions} ${status}`;

        Chart.register(ChartZoom);
        Chart.defaults.locale = cookieLangValue;

        //all dates
        const allDates = [
          ...new Set([
            ...autreEntreeEffective.data.map((d) => d.date),
            ...factureEffective.data.map((d) => d.date),
            ...sortieEffective.data.map((d) => d.date),
          ]),
        ].sort();

        //count par date
        const countNbTotalAutreEntree = countNbTotal(autreEntreeEffective);
        const countNbTotalFacture = countNbTotal(factureEffective);
        const countNbTotalSortie = countNbTotal(sortieEffective);

        //=====show chart transactions curves nb
        const chartTransactionsCurvesNumber = document.getElementById(
          "chart-transactions-curves-nb"
        );
        const canvasTransactionsCurvesNumber = document.createElement("canvas");
        new Chart(canvasTransactionsCurvesNumber, {
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
                barThickness: 20,
                borderRadius: 5,
              },
              {
                label: lang.bill,
                data: prepareCount(countNbTotalFacture, allDates),
                borderColor: "#01a7b9ff",
                borderWidth: 1,
                backgroundColor: "#2ec4b5a1",
                barThickness: 20,
                borderRadius: 5,
              },
              {
                label: lang.outflow,
                data: prepareCount(countNbTotalSortie, allDates),
                borderColor: "#e10618b9",
                borderWidth: 1,
                backgroundColor: "#e63947b0",
                barThickness: 20,
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
        chartTransactionsCurvesNumber.innerHTML = "";
        chartTransactionsCurvesNumber.append(canvasTransactionsCurvesNumber);

        //=====show chart transactions curves total
        const chartTransactionsCurvesTotal = document.getElementById(
          "chart-transactions-curves-total"
        );
        const canvasTransactionsCurvesTotal = document.createElement("canvas");
        new Chart(canvasTransactionsCurvesTotal, {
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
                // barThickness: 20,
                borderRadius: 5,
              },
              {
                label: lang.bill,
                data: prepareTotal(countNbTotalFacture, allDates),
                borderColor: "#01a7b9ff",
                borderWidth: 1,
                backgroundColor: "#2ec4b5a1",
                // barThickness: 20,
                borderRadius: 5,
              },
              {
                label: lang.outflow,
                data: prepareTotal(countNbTotalSortie, allDates),
                borderColor: "#e10618b9",
                borderWidth: 1,
                backgroundColor: "#e63947b0",
                // barThickness: 20,
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
        chartTransactionsCurvesTotal.innerHTML = "";
        chartTransactionsCurvesTotal.append(canvasTransactionsCurvesTotal);
      }
    } catch (e) {
      console.log(e);
    }
  }, 1050);

  //=========================== FUNCTIONS
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
});
