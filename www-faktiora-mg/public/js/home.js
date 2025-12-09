document.addEventListener("DOMContentLoaded", () => {
  setTimeout(async () => {
    //template real content
    const templateRealContent = document.getElementById("template-home");
    // container
    const container = document.getElementById("container");
    // load template real
    container.append(templateRealContent.content.cloneNode(true));

    try {
      //================ chart client ========================
      // div chart client
      const divChartClient = document.getElementById("chart-client");
      //get client effective
      const clientEffective = await apiRequest("/client/list_all_client");
      //get user effective
      const userEffective = await apiRequest("/user/list_all_user");
      //chart client title
      const chartClientTitle = document.getElementById("chart-client-title");
      chartClientTitle.innerHTML = `<i class="fad fa-user me-2" id="chart-client-title"></i>${lang.clients_users} (${clientEffective.nb_client}, ${userEffective.nb_user})`;
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
        divChartTransactions.innerHTML = `<div class='alert alert-danger form-text'><i class='fad fa-exclamation-circle me-2'></i>${autreEntreeEfective.message}'</div>`;
      }
      //error facture effective
      else if (factureEffective.message_type !== "success") {
        divChartTransactions.innerHTML = `<div class='alert alert-danger form-text'><i class='fad fa-exclamation-circle me-2'></i>${autreEntreeEfective.message}'</div>`;
      }
      //error sortie effective
      else if (sortieEffective.message_type !== "success") {
        divChartTransactions.innerHTML = `<div class='alert alert-danger form-text'><i class='fad fa-exclamation-circle me-2'></i>${autreEntreeEfective.message}'</div>`;
      }
      //successs
      else {
        //============== chart transactions number

        //get currency units
        const config = await fetch(`${SITE_URL}/config/config.json`);
        if (!config.ok) throw new Error(`HTTP ${config.status}`);
        const currencyUnits = (await config.json()).currency_units;

        //format nb && total
        //initialize variables
        let nbTransactions =
            Number(autreEntreeEffective.nb_ae) +
            Number(factureEffective.nb_facture) +
            Number(sortieEffective.nb_sortie),
          totalTransactions =
            Number(autreEntreeEffective.total_ae) +
            Number(factureEffective.total_facture) +
            Number(sortieEffective.total_sortie),
          formatterNumber,
          formatterTotal;
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
      }
    } catch (e) {
      console.log(e);
    }
  }, 1050);
});
