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
    } catch (e) {
      console.log(e);
    }
  }, 1050);
});
