document.addEventListener("DOMContentLoaded", async () => {
  //template real content
  const templateRealContent = document.getElementById("template-produit");
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
    //select - arrange_by
    const selectArrangeBy = document.getElementById("select-arrange-by");
    const savedSelectArrangeBy = localStorage.getItem(selectArrangeBy.id);
    selectArrangeBy.value = !savedSelectArrangeBy ? "id" : savedSelectArrangeBy;
    //select - order
    const selectOrder = document.getElementById("select-order");
    const savedSelectOrder = localStorage.getItem(selectOrder.id);
    selectOrder.value = !savedSelectOrder ? "asc" : savedSelectOrder;

    //EVENT btn reset
    const btnReset = document.getElementById("btn-reset");
    btnReset.addEventListener("click", () => {
      //reset input search
      inputSearch.value = "";
      inputSearch.dispatchEvent(new Event("input"));
      //reset select status
      selectStatus.value = "all";
      localStorage.removeItem(selectStatus.id);
      //reset select arrange_by
      selectArrangeBy.value = "id";
      localStorage.removeItem(selectArrangeBy.id);
      //reset order
      selectOrder.value = "asc";
      localStorage.removeItem(selectOrder.id);
    });

    //===== EVENT input search
    inputSearch.addEventListener("input", (e) => {
      filterProduit(
        selectStatus.value.trim(),
        selectArrangeBy.value.trim(),
        selectOrder.value.trim(),
        e.target.value.trim()
      );
      inputSearchSearchBar.value = e.target.value;
      localStorage.setItem(e.target.id, e.target.value);
    });
    //===== EVENT input searchbar
    inputSearchSearchBar.addEventListener("input", (e) => {
      inputSearch.value = e.target.value;
      inputSearch.dispatchEvent(new Event("input"));
    });
    //===== EVENT select status
    selectStatus.addEventListener("change", (e) => {
      filterProduit(
        e.target.value.trim(),
        selectArrangeBy.value.trim(),
        selectOrder.value.trim(),
        inputSearch.value.trim()
      );
      localStorage.setItem(e.target.id, e.target.value);
    });
    //===== EVENT select arrange_by
    selectArrangeBy.addEventListener("change", (e) => {
      filterProduit(
        selectStatus.value.trim(),
        e.target.value.trim(),
        selectOrder.value.trim(),
        inputSearch.value.trim()
      );
      localStorage.setItem(e.target.id, e.target.value);
    });
    //===== EVENT select order
    selectOrder.addEventListener("change", (e) => {
      filterProduit(
        selectStatus.value.trim(),
        selectArrangeBy.value.trim(),
        e.target.value.trim(),
        inputSearch.value.trim()
      );
      localStorage.setItem(e.target.id, e.target.value);
    });

    //======================== FILTER PRODUIT ==================
    await filterProduit(
      selectStatus.value.trim(),
      selectArrangeBy.value.trim(),
      selectOrder.value.trim(),
      inputSearch.value.trim()
    );

    //======================== ADD PRODUIT ======================
    //modal add produit
    const modalAddProduit = container.querySelector("#modal-add-produit");
    //input - add produit libelle_produit
    const inputAddProduitLibelleProduit = modalAddProduit.querySelector(
      "#input-add-produit-libelle-produit"
    );
    const savedInputAddProduiLibelleProduit = localStorage.getItem(
      inputAddProduitLibelleProduit.id
    );
    inputAddProduitLibelleProduit.value = !savedInputAddProduiLibelleProduit
      ? ""
      : savedInputAddProduiLibelleProduit;
    //input - add produit prix_produit
    const inputAddPorduitPrixProduit = modalAddProduit.querySelector(
      "#input-add-produit-prix-produit"
    );
    const savedInputAddProduitPrixProduit = localStorage.getItem(
      inputAddPorduitPrixProduit.id
    );
    inputAddPorduitPrixProduit.value = !savedInputAddProduitPrixProduit
      ? "1"
      : savedInputAddProduitPrixProduit;
    inputAddPorduitPrixProduit.dataset.val = inputAddPorduitPrixProduit.value;
    //input - add produit nb_stock
    const inputAddProduitNbStock = modalAddProduit.querySelector(
      "#input-add-produit-nb-stock"
    );
    const savedInputAddProduitNbStock = localStorage.getItem(
      inputAddProduitNbStock.id
    );
    inputAddProduitNbStock.value = !savedInputAddProduitNbStock
      ? 0
      : savedInputAddProduitNbStock;

    //===== EVENT input add produit libelle_produit
    inputAddProduitLibelleProduit.addEventListener("input", (e) => {
      e.target.value = e.target.value.replace("  ", " ");

      localStorage.setItem(e.target.id, e.target.value);
    });
    //===== EVENT input add produit prix_produit
    inputAddPorduitPrixProduit.addEventListener("input", (e) => {
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
    inputAddPorduitPrixProduit.addEventListener("blur", (e) => {
      if (e.target.value.endsWith(",")) {
        e.target.value += "0";
      }

      if (e.target.value) {
        e.target.value = formatterNumber.format(
          e.target.value.replace(/[\u202F\u00A0 ]/g, "").replace(",", ".")
        );
      } else {
        e.target.value = "1";
        e.target.dataset.val = 1;
      }
      //save to local storage
      localStorage.setItem(e.target.id, e.target.value);
    });
    //===== EVENT input add produit nb_stock
    inputAddProduitNbStock.addEventListener("input", (e) => {
      e.target.value = e.target.value.replace(/[^0-9]/g, "");

      localStorage.setItem(e.target.id, e.target.value);
    });

    //===== EVENT modal add produit form submit
    modalAddProduit
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
          //FETCH api add produit
          const apiAddProduit = await apiRequest("/produit/create_produit", {
            method: "POST",
            body: {
              libelle_produit: inputAddProduitLibelleProduit.value.trim(),
              prix_produit: inputAddPorduitPrixProduit.value
                .replace(/[\u202F\u00A0 ]/g, "")
                .replace(",", "."),
              nb_stock: inputAddProduitNbStock.value.trim(),
            },
          });
          //invalid
          if (apiAddProduit.message_type === "invalid") {
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
              apiAddProduit.message;
            //progress bar
            progressBar.style.transition = "width 10s linear";
            progressBar.style.width = "100%";

            //add alert
            modalAddProduit.querySelector(".modal-body").prepend(alert);

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
          else if (apiAddProduit.message_type === "error") {
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
              apiAddProduit.message;
            //progress bar
            progressBar.style.transition = "width 10s linear";
            progressBar.style.width = "100%";

            //add alert
            modalAddProduit.querySelector(".modal-body").prepend(alert);

            //progress lanch animation
            setTimeout(() => {
              progressBar.style.width = "0%";
            }, 10);
            //auto close alert
            setTimeout(() => {
              alert.querySelector(".btn-close").click();
            }, 10000);
          }

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
            apiAddProduit.message;
          //progress bar
          progressBar.style.transition = "width 10s linear";
          progressBar.style.width = "100%";

          //add alert
          container
            .querySelector("#tbody-produit")
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

          //hide modal
          modalAddProduit.querySelector("#btn-close-modal-add-produit").click();

          //refresh filter produit
          filterProduit(
            selectStatus.value.trim(),
            selectArrangeBy.value.trim(),
            selectOrder.value.trim(),
            inputSearch.value.trim()
          );
        } catch (e) {
          console.error(e);
        }
      });
    //     //========================= DELETE PERMANENT CLIENT =================
    //     //btn restore client
    //     const btnDeletePermanentClient = container.querySelector(
    //       "#btn-delete-permanent-client"
    //     );
    //     //===== EVENT btn delete permanent client
    //     if (btnDeletePermanentClient) {
    //       btnDeletePermanentClient.addEventListener("click", () => {
    //         //modal delete client
    //         const modalDeleteClient = container.querySelector(
    //           "#modal-delete-client"
    //         );
    //         //selected client
    //         const selectedClient = container.querySelectorAll(
    //           "#tbody-client input[type='checkbox']:checked"
    //         );

    //         //no selection
    //         if (selectedClient.length <= 0) {
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
    //             lang.client_ids_client_empty;
    //           //progress bar
    //           progressBar.style.transition = "width 10s linear";
    //           progressBar.style.width = "100%";

    //           //add alert
    //           container
    //             .querySelector("#tbody-client")
    //             .closest("div")
    //             .prepend(alert);

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

    //         //modal message 1
    //         if (selectedClient.length === 1) {
    //           modalDeleteClient.querySelector(".message").innerHTML =
    //             lang.question_delete_permanent_client_1.replace(
    //               ":field",
    //               selectedClient[0].closest("tr").dataset.idClient
    //             );
    //         }
    //         //modal message plur
    //         else {
    //           modalDeleteClient.querySelector(".message").innerHTML =
    //             lang.question_delete_permanent_client_plur.replace(
    //               ":field",
    //               selectedClient.length
    //             );
    //         }

    //         //show modal delete client
    //         new bootstrap.Modal(modalDeleteClient).show();

    //         //==== EVENT btn confirm modal delete client
    //         modalDeleteClient
    //           .querySelector("#btn-confirm-modal-delete-client")
    //           .addEventListener("click", async () => {
    //             try {
    //               //ids_client
    //               let ids_client = [...selectedClient];
    //               ids_client = ids_client.map(
    //                 (selected) => selected.closest("tr").dataset.idClient
    //               );

    //               //FETCH api delete client
    //               const apiDeleteClient = await apiRequest(
    //                 "/client/delete_permanent_all_client",
    //                 {
    //                   method: "DELETE",
    //                   body: {
    //                     ids_client: ids_client,
    //                   },
    //                 }
    //               );

    //               //error
    //               if (apiDeleteClient.message_type === "error") {
    //                 //alert
    //                 const alertTemplate = document.querySelector(".alert-template");
    //                 const clone = alertTemplate.content.cloneNode(true);
    //                 const alert = clone.querySelector(".alert");
    //                 const progressBar = alert.querySelector(".progress-bar");
    //                 //alert type
    //                 alert.classList.add("alert-danger");
    //                 //icon
    //                 alert
    //                   .querySelector(".fad")
    //                   .classList.add("fa-exclamation-circle");
    //                 //message
    //                 alert.querySelector(".alert-message").innerHTML =
    //                   apiDeleteClient.message;
    //                 //progress bar
    //                 progressBar.style.transition = "width 20s linear";
    //                 progressBar.style.width = "100%";

    //                 //add alert
    //                 modalDeleteClient.querySelector(".modal-body").prepend(alert);

    //                 //progress launch animation
    //                 setTimeout(() => {
    //                   progressBar.style.width = "0%";
    //                 }, 10);
    //                 //auto close alert
    //                 setTimeout(() => {
    //                   alert.querySelector(".btn-close").click();
    //                 }, 20000);
    //                 return;
    //               }
    //               //invalid
    //               else if (apiDeleteClient.message_type === "invalid") {
    //                 //alert
    //                 const alertTemplate = document.querySelector(".alert-template");
    //                 const clone = alertTemplate.content.cloneNode(true);
    //                 const alert = clone.querySelector(".alert");
    //                 const progressBar = alert.querySelector(".progress-bar");
    //                 //alert type
    //                 alert.classList.add("alert-warning");
    //                 //icon
    //                 alert
    //                   .querySelector(".fad")
    //                   .classList.add("fa-exclamation-circle");
    //                 //message
    //                 alert.querySelector(".alert-message").innerHTML =
    //                   apiDeleteClient.message;
    //                 //progress bar
    //                 progressBar.style.transition = "width 10s linear";
    //                 progressBar.style.width = "100%";

    //                 //add alert
    //                 modalDeleteClient.querySelector(".modal-body").prepend(alert);

    //                 //progress launch animation
    //                 setTimeout(() => {
    //                   progressBar.style.width = "0%";
    //                 }, 10);
    //                 //auto close alert
    //                 setTimeout(() => {
    //                   alert.querySelector(".btn-close").click();
    //                 }, 10000);
    //                 return;
    //               }

    //               //success
    //               //alert
    //               const alertTemplate = document.querySelector(".alert-template");
    //               const clone = alertTemplate.content.cloneNode(true);
    //               const alert = clone.querySelector(".alert");
    //               const progressBar = alert.querySelector(".progress-bar");
    //               //alert type
    //               alert.classList.add("alert-success");
    //               //icon
    //               alert.querySelector(".fad").classList.add("fa-check-circle");
    //               //message
    //               alert.querySelector(".alert-message").innerHTML =
    //                 apiDeleteClient.message;
    //               //progress bar
    //               progressBar.style.transition = "width 10s linear";
    //               progressBar.style.width = "100%";

    //               //add alert
    //               container
    //                 .querySelector("#tbody-client")
    //                 .closest("div")
    //                 .prepend(alert);

    //               //progress launch animation
    //               setTimeout(() => {
    //                 progressBar.style.width = "0%";
    //               }, 10);
    //               //auto close alert
    //               setTimeout(() => {
    //                 alert.querySelector(".btn-close").click();
    //               }, 10000);

    //               //auto hide modal
    //               modalDeleteClient
    //                 .querySelector("#btn-close-modal-delete-client")
    //                 .click();

    //               //refresh filter client
    //               filterClient(
    //                 selectStatus.value.trim(),
    //                 selectSex.value.trim(),
    //                 selectArrangeBy.value.trim(),
    //                 selectOrder.value.trim(),
    //                 selectDateBy.value.trim(),
    //                 selectPer.value.trim(),
    //                 dateFrom.value.trim(),
    //                 dateTo.value.trim(),
    //                 selectMonth.value.trim(),
    //                 selectYear.value.trim(),
    //                 inputSearch.value.trim()
    //               );

    //               return;
    //             } catch (e) {
    //               console.error(e);
    //             }
    //           });
    //       });
    //     }

    //     //========================== RESTORE CLIENT =========================
    //     //btn restore client
    //     const btnRestoreClient = container.querySelector("#btn-restore-client");
    //     //===== EVENT btn restore client
    //     if (btnRestoreClient) {
    //       btnRestoreClient.addEventListener("click", () => {
    //         //modal restore client
    //         const modalRestoreClient = container.querySelector(
    //           "#modal-restore-client"
    //         );
    //         //selected client
    //         const selectedClient = container.querySelectorAll(
    //           "#tbody-client input[type='checkbox']:checked"
    //         );

    //         //no selection
    //         if (selectedClient.length <= 0) {
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
    //             lang.client_ids_client_empty;
    //           //progress bar
    //           progressBar.style.transition = "width 10s linear";
    //           progressBar.style.width = "100%";

    //           //add alert
    //           container
    //             .querySelector("#tbody-client")
    //             .closest("div")
    //             .prepend(alert);

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

    //         //modal message 1
    //         if (selectedClient.length === 1) {
    //           modalRestoreClient.querySelector(".message").innerHTML =
    //             lang.question_restore_client_1.replace(
    //               ":field",
    //               selectedClient[0].closest("tr").dataset.idClient
    //             );
    //         }
    //         //modal message plur
    //         else {
    //           modalRestoreClient.querySelector(".message").innerHTML =
    //             lang.question_restore_client_plur.replace(
    //               ":field",
    //               selectedClient.length
    //             );
    //         }

    //         //show modal restore client
    //         new bootstrap.Modal(modalRestoreClient).show();

    //         //==== EVENT btn confirm modal restore client
    //         modalRestoreClient
    //           .querySelector("#btn-confirm-modal-restore-client")
    //           .addEventListener("click", async () => {
    //             try {
    //               //ids_client
    //               let ids_client = [...selectedClient];
    //               ids_client = ids_client.map(
    //                 (selected) => selected.closest("tr").dataset.idClient
    //               );

    //               //FETCH api restore client
    //               const apiRestoreClient = await apiRequest(
    //                 "/client/restore_all_client",
    //                 {
    //                   method: "PUT",
    //                   body: {
    //                     ids_client: ids_client,
    //                   },
    //                 }
    //               );

    //               //error
    //               if (apiRestoreClient.message_type === "error") {
    //                 //alert
    //                 const alertTemplate = document.querySelector(".alert-template");
    //                 const clone = alertTemplate.content.cloneNode(true);
    //                 const alert = clone.querySelector(".alert");
    //                 const progressBar = alert.querySelector(".progress-bar");
    //                 //alert type
    //                 alert.classList.add("alert-danger");
    //                 //icon
    //                 alert
    //                   .querySelector(".fad")
    //                   .classList.add("fa-exclamation-circle");
    //                 //message
    //                 alert.querySelector(".alert-message").innerHTML =
    //                   apiRestoreClient.message;
    //                 //progress bar
    //                 progressBar.style.transition = "width 20s linear";
    //                 progressBar.style.width = "100%";

    //                 //add alert
    //                 modalRestoreClient.querySelector(".modal-body").prepend(alert);

    //                 //progress launch animation
    //                 setTimeout(() => {
    //                   progressBar.style.width = "0%";
    //                 }, 10);
    //                 //auto close alert
    //                 setTimeout(() => {
    //                   alert.querySelector(".btn-close").click();
    //                 }, 20000);
    //                 return;
    //               }
    //               //invalid
    //               else if (apiRestoreClient.message_type === "invalid") {
    //                 //alert
    //                 const alertTemplate = document.querySelector(".alert-template");
    //                 const clone = alertTemplate.content.cloneNode(true);
    //                 const alert = clone.querySelector(".alert");
    //                 const progressBar = alert.querySelector(".progress-bar");
    //                 //alert type
    //                 alert.classList.add("alert-warning");
    //                 //icon
    //                 alert
    //                   .querySelector(".fad")
    //                   .classList.add("fa-exclamation-circle");
    //                 //message
    //                 alert.querySelector(".alert-message").innerHTML =
    //                   apiRestoreClient.message;
    //                 //progress bar
    //                 progressBar.style.transition = "width 10s linear";
    //                 progressBar.style.width = "100%";

    //                 //add alert
    //                 modalRestoreClient.querySelector(".modal-body").prepend(alert);

    //                 //progress launch animation
    //                 setTimeout(() => {
    //                   progressBar.style.width = "0%";
    //                 }, 10);
    //                 //auto close alert
    //                 setTimeout(() => {
    //                   alert.querySelector(".btn-close").click();
    //                 }, 10000);
    //                 return;
    //               }

    //               //success
    //               //alert
    //               const alertTemplate = document.querySelector(".alert-template");
    //               const clone = alertTemplate.content.cloneNode(true);
    //               const alert = clone.querySelector(".alert");
    //               const progressBar = alert.querySelector(".progress-bar");
    //               //alert type
    //               alert.classList.add("alert-success");
    //               //icon
    //               alert.querySelector(".fad").classList.add("fa-check-circle");
    //               //message
    //               alert.querySelector(".alert-message").innerHTML =
    //                 apiRestoreClient.message;
    //               //progress bar
    //               progressBar.style.transition = "width 10s linear";
    //               progressBar.style.width = "100%";

    //               //add alert
    //               container
    //                 .querySelector("#tbody-client")
    //                 .closest("div")
    //                 .prepend(alert);

    //               //progress launch animation
    //               setTimeout(() => {
    //                 progressBar.style.width = "0%";
    //               }, 10);
    //               //auto close alert
    //               setTimeout(() => {
    //                 alert.querySelector(".btn-close").click();
    //               }, 10000);

    //               //auto hide modal
    //               modalRestoreClient
    //                 .querySelector("#btn-close-modal-restore-client")
    //                 .click();

    //               //refresh filter client
    //               filterClient(
    //                 selectStatus.value.trim(),
    //                 selectSex.value.trim(),
    //                 selectArrangeBy.value.trim(),
    //                 selectOrder.value.trim(),
    //                 selectDateBy.value.trim(),
    //                 selectPer.value.trim(),
    //                 dateFrom.value.trim(),
    //                 dateTo.value.trim(),
    //                 selectMonth.value.trim(),
    //                 selectYear.value.trim(),
    //                 inputSearch.value.trim()
    //               );

    //               return;
    //             } catch (e) {
    //               console.error(e);
    //             }
    //           });
    //       });
    //     }
  }, 1050);

  //====================== FUNCTIONS ========================

  //function - filter produit
  async function filterProduit(status, arrange_by, order, search_produit) {
    //tbody
    const tbodyProduit = container.querySelector("#tbody-produit");

    try {
      //FETCH api filter produit
      const apiFilterProduit = await apiRequest(
        `/produit/filter_produit?status=${status}&arrange_by=${arrange_by}&order=${order}&search_produit=${search_produit}`
      );

      //error
      if (apiFilterProduit.message_type === "error") {
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
          apiFilterProduit.message;
        //progress bar
        progressBar.style.transition = "width 20s linear";
        progressBar.style.width = "100%";

        //add alert
        tbodyProduit.closest("div").prepend(alert);

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
      else if (apiFilterProduit.message_type === "invalid") {
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
          apiFilterProduit.message;
        //progress bar
        progressBar.style.transition = "width 10s linear";
        progressBar.style.width = "100%";

        //add alert
        tbodyProduit.closest("div").prepend(alert);

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

      //===== TABLE produit
      //set table list produit
      tbodyProduit.innerHTML = "";
      apiFilterProduit.data.forEach((line) => {
        const tr = document.createElement("tr");

        //td - checkbox
        if (container.querySelector("#check-all-produit")) {
          const tdCheckbox = document.createElement("td");
          tdCheckbox.innerHTML =
            "<input type='checkbox' class='form-check-input'>";
          tdCheckbox.classList.add("text-center");
          tr.append(tdCheckbox);
        }

        //td - id_produit
        const tdIdProduit = document.createElement("td");
        tdIdProduit.textContent = line.id_produit;
        tdIdProduit.classList.add("text-center");

        //td - libelle_produit
        const tdLibelleProduit = document.createElement("td");
        tdLibelleProduit.textContent = line.libelle_produit;
        tdLibelleProduit.classList.add("text-center");

        //td - prix_unitaire
        const tdPrixUnitaire = document.createElement("td");
        tdPrixUnitaire.textContent = formatterNumber.format(
          Number(line.prix_produit)
        );
        tdPrixUnitaire.classList.add("text-center");

        //td - nb_stock
        const tdNbStock = document.createElement("td");
        tdNbStock.textContent = formatterNumber.format(Number(line.nb_stock));
        tdNbStock.classList.add("text-center");

        //td - status
        const tdStatus = document.createElement("td");
        if (line.etat_produit === "supprimé") {
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

        //append
        tr.append(
          tdIdProduit,
          tdLibelleProduit,
          tdPrixUnitaire,
          tdNbStock,
          tdStatus
        );

        //td - actions
        if (container.querySelector("#check-all-produit")) {
          const tdActions = document.createElement("td");
          const divActions = document.createElement("div");
          divActions.classList.add(
            "d-flex",
            "justify-content-center",
            "align-items-center"
          );
          //btn update produit
          const btnUpdateProduit = document.createElement("button");
          btnUpdateProduit.type = "button";
          btnUpdateProduit.classList.add(
            "btn-light",
            "btn",
            "btn-sm",
            "text-primary",
            "btn-update-produit"
          );
          btnUpdateProduit.innerHTML = "<i class='fad fa-pen-to-square'></i>";
          divActions.append(btnUpdateProduit);
          tdActions.append(divActions);
          tr.append(tdActions);
        }

        tr.dataset.idProduit = line.id_produit;
        tr.dataset.libelleProduit = line.libelle_produit;
        tr.dataset.prixProduit = line.prix_produit;
        tr.dataset.nbStock = line.nb_stock;
        tbodyProduit.appendChild(tr);
      });

      // //foreach all tr
      // tbodyClient.querySelectorAll("tr").forEach((tr) => {
      //   //========================== UPDATE CLIENT ===========================
      //   //modal update client
      //   const modalUpdateClient = container.querySelector(
      //     "#modal-update-client"
      //   );
      //   //===== EVENT btn update client
      //   tr.querySelector(".btn-update-client").addEventListener("click", () => {
      //     //modal id_client
      //     modalUpdateClient.querySelector("#update-id-client").innerHTML =
      //       tr.dataset.idClient;
      //     //input -  update nom_client
      //     const inputUpdateNomClient = modalUpdateClient.querySelector(
      //       "#input-update-nom-client"
      //     );
      //     inputUpdateNomClient.value = tr.dataset.nomClient;
      //     //input - update prenoms_client
      //     const inputUpdatePrenomsClient = modalUpdateClient.querySelector(
      //       "#input-update-prenoms-client"
      //     );
      //     inputUpdatePrenomsClient.value = tr.dataset.prenomsClient;
      //     //select - update sexe_client
      //     const selectUpdateSexeClient = modalUpdateClient.querySelector(
      //       "#select-update-sexe-client"
      //     );
      //     selectUpdateSexeClient.value = tr.dataset.sexeClient;
      //     //input - update telephone
      //     const inputUpdateTelephone = modalUpdateClient.querySelector(
      //       "#input-update-telephone"
      //     );
      //     inputUpdateTelephone.value = tr.dataset.telephone;
      //     //input - update adresse
      //     const inputUpdateAdresse = modalUpdateClient.querySelector(
      //       "#input-update-adresse"
      //     );
      //     inputUpdateAdresse.value = tr.dataset.adresse;

      //     //show modal update client
      //     new bootstrap.Modal(modalUpdateClient).show();

      //     //===== EVENT input update nom_client
      //     inputUpdateNomClient.addEventListener("input", (e) => {
      //       //remove double space && change into uppercase
      //       e.target.value = e.target.value.replace("  ", " ").toUpperCase();
      //     });
      //     //===== EVENT input update prenoms_client
      //     inputUpdatePrenomsClient.addEventListener("input", (e) => {
      //       //remove double space && change into uppercase
      //       e.target.value = e.target.value.replace("  ", " ");
      //     });
      //     //===== EVENT input update telephone
      //     inputUpdateTelephone.addEventListener("input", (e) => {
      //       //remove invalid
      //       e.target.value = e.target.value.replace(/[^\d+\s]/g, "");
      //       //remove double space
      //       e.target.value = e.target.value.replace("  ", " ");
      //     });
      //     //===== EVENT input update adresse
      //     inputUpdateAdresse.addEventListener("input", (e) => {
      //       //remove double space
      //       e.target.value = e.target.value.replace("  ", " ");
      //       localStorage.setItem(e.target.id, e.target.value);
      //     });
      //   });

      //   //======================== HISTO FACTURE ===========================
      //   //modal histo facture
      //   const modalHistoFacture = container.querySelector(
      //     "#modal-histo-facture"
      //   );

      //   //===== EVENT btn histo facture
      //   tr.querySelector(".btn-histo-facture").addEventListener("click", () => {
      //     //modal id_client
      //     modalHistoFacture.querySelector(
      //       "#histo-facture-id-client"
      //     ).innerHTML = tr.dataset.idClient;
      //     //div chart nb_facture
      //     const divChartNbFacture = modalHistoFacture.querySelector(
      //       "#div-histo-facture-nb"
      //     );
      //     //div chart total_facture
      //     const divChartTotalFacture = modalHistoFacture.querySelector(
      //       "#div-histo-facture-total"
      //     );

      //     //chart nb_facture
      //     chartNbFacture(
      //       divChartNbFacture,
      //       tr.dataset.idClient,
      //       date_by,
      //       per,
      //       from,
      //       to,
      //       month,
      //       year
      //     );
      //     //chart total_facture
      //     chartTotalFacture(
      //       divChartTotalFacture,
      //       tr.dataset.idClient,
      //       date_by,
      //       per,
      //       from,
      //       to,
      //       month,
      //       year
      //     );

      //     //show modal histo facture
      //     new bootstrap.Modal(modalHistoFacture).show();
      //   });
      // });

      // //===== EVENT check all
      // const inputCheckAll = container.querySelector("#check-all-client");
      // if (inputCheckAll) {
      //   inputCheckAll.addEventListener("change", (e) => {
      //     tbodyClient
      //       .querySelectorAll("input[type='checkbox']")
      //       .forEach((checkbox) => {
      //         checkbox.checked = e.target.checked;
      //       });
      //   });
      // }
    } catch (e) {
      console.error(e);
    }
  }
  //   //function - chart nb_facture
  //   async function chartNbFacture(
  //     divChartNbFacture,
  //     id_client,
  //     date_by,
  //     per,
  //     from,
  //     to,
  //     month,
  //     year
  //   ) {
  //     try {
  //       //FETCH api facture effective
  //       const factureEffective = await apiRequest(
  //         `/entree/list_all_facture?date_by=${date_by}&per=${per}&from=${from}&to=${to}&month=${month}&year=${year}&id_client=${id_client}`
  //       );

  //       //error
  //       if (factureEffective.message_type === "error") {
  //         //alert
  //         const alertTemplate = document.querySelector(".alert-template");
  //         const clone = alertTemplate.content.cloneNode(true);
  //         const alert = clone.querySelector(".alert");
  //         const progressBar = alert.querySelector(".progress-bar");
  //         //alert type
  //         alert.classList.add("alert-danger");
  //         //icon
  //         alert.querySelector(".fad").classList.add("fa-exclamation-triangle");
  //         //message
  //         alert.querySelector(".alert-message").innerHTML =
  //           factureEffective.message;
  //         //progress bar
  //         progressBar.style.transition = "width 20s linear";
  //         progressBar.style.width = "100%";

  //         //add alert
  //         divChartNbFacture
  //           .closest(".modal-body")
  //           .querySelector(".alert-container")
  //           .prepend(alert);

  //         //progress launch animation
  //         setTimeout(() => {
  //           progressBar.style.width = "0%";
  //         }, 10);
  //         //auto close alert
  //         setTimeout(() => {
  //           alert.querySelector(".btn-close").click();
  //         }, 20000);

  //         return;
  //       }
  //       //invalid
  //       else if (factureEffective.message_type === "invalid") {
  //         //alert
  //         const alertTemplate = document.querySelector(".alert-template");
  //         const clone = alertTemplate.content.cloneNode(true);
  //         const alert = clone.querySelector(".alert");
  //         const progressBar = alert.querySelector(".progress-bar");
  //         //alert type
  //         alert.classList.add("alert-warning");
  //         //icon
  //         alert.querySelector(".fad").classList.add("fa-exclamation-circle");
  //         //message
  //         alert.querySelector(".alert-message").innerHTML =
  //           factureEffective.message;
  //         //progress bar
  //         progressBar.style.transition = "width 10s linear";
  //         progressBar.style.width = "100%";

  //         //add alert
  //         divChartNbFacture
  //           .closest(".modal-body")
  //           .querySelector(".alert-container")
  //           .prepend(alert);

  //         //progress lanch animation
  //         setTimeout(() => {
  //           progressBar.style.width = "0%";
  //         }, 10);
  //         //auto close alert
  //         setTimeout(() => {
  //           alert.querySelector(".btn-close").click();
  //         }, 10000);

  //         return;
  //       }

  //       //success effective transactions
  //       //all dates
  //       const allDates = [
  //         ...new Set([...factureEffective.data.map((d) => d.date)]),
  //       ].sort();

  //       Chart.defaults.locale = cookieLangValue;
  //       //count par date
  //       const countNbTotalFacture = countNbTotal(factureEffective);
  //       //=====show chart histo nb_facture
  //       const canvasHistoNbFacture = document.createElement("canvas");
  //       new Chart(canvasHistoNbFacture, {
  //         type: "line",
  //         data: {
  //           labels: allDates,
  //           datasets: [
  //             {
  //               label: lang.bill,
  //               data: prepareCount(countNbTotalFacture, allDates),
  //               borderColor: "#01a7b9ff",
  //               borderWidth: 1,
  //               backgroundColor: "#2ec4b5a1",
  //               // barThickness: 10,
  //               borderRadius: 5,
  //             },
  //           ],
  //         },
  //         options: {
  //           responsive: true,
  //           plugins: {
  //             title: {
  //               display: true,
  //               text: `${lang.nb_facture} (${formatterNumber.format(
  //                 factureEffective.nb_facture
  //               )})`,
  //             },
  //             legend: { display: true, position: "bottom", align: "center" },
  //           },
  //           scales: {
  //             x: {
  //               title: {
  //                 display: true,
  //                 text: lang.date,
  //               },
  //               type: "time",
  //               time: { unit: "day" },
  //             },
  //             y: {
  //               title: { display: true, text: lang.number },
  //               beginAtZero: true,
  //               ticks: { stepSize: 1 },
  //             },
  //           },
  //           zoom: {
  //             // zoom: {
  //             //   wheel: {
  //             //     enabled: true,
  //             //     minScale: 0.5,
  //             //     maxScale: 10,
  //             //     wheelEvent: "wheel",
  //             //   },
  //             //   pinch: { enabled: true },
  //             //   pan: {
  //             //     enabled: true,
  //             //     mode: "xy",
  //             //     modifierKey: "alt",
  //             //   },
  //             //   drag: {
  //             //     mode: "xy",
  //             //     enabled: true,
  //             //     backgroundColor: "red",
  //             //     animation: 100,
  //             //   },
  //             // },
  //           },
  //         },
  //         plugins: [ChartZoom],
  //       });
  //       divChartNbFacture.innerHTML = "";
  //       divChartNbFacture.append(canvasHistoNbFacture);
  //     } catch (e) {
  //       console.error(e);
  //     }
  //   }
  //   //function - chart total_facture
  //   async function chartTotalFacture(
  //     divChartTotalFacture,
  //     id_client,
  //     date_by,
  //     per,
  //     from,
  //     to,
  //     month,
  //     year
  //   ) {
  //     try {
  //       //FETCH api facture effective
  //       const factureEffective = await apiRequest(
  //         `/entree/list_all_facture?date_by=${date_by}&per=${per}&from=${from}&to=${to}&month=${month}&year=${year}&id_client=${id_client}`
  //       );

  //       //error
  //       if (factureEffective.message_type === "error") {
  //         //alert
  //         const alertTemplate = document.querySelector(".alert-template");
  //         const clone = alertTemplate.content.cloneNode(true);
  //         const alert = clone.querySelector(".alert");
  //         const progressBar = alert.querySelector(".progress-bar");
  //         //alert type
  //         alert.classList.add("alert-danger");
  //         //icon
  //         alert.querySelector(".fad").classList.add("fa-exclamation-triangle");
  //         //message
  //         alert.querySelector(".alert-message").innerHTML =
  //           factureEffective.message;
  //         //progress bar
  //         progressBar.style.transition = "width 20s linear";
  //         progressBar.style.width = "100%";

  //         //add alert
  //         divChartTotalFacture
  //           .closest(".modal-body")
  //           .querySelector(".alert-container")
  //           .prepend(alert);

  //         //progress launch animation
  //         setTimeout(() => {
  //           progressBar.style.width = "0%";
  //         }, 10);
  //         //auto close alert
  //         setTimeout(() => {
  //           alert.querySelector(".btn-close").click();
  //         }, 20000);

  //         return;
  //       }
  //       //invalid
  //       else if (factureEffective.message_type === "invalid") {
  //         //alert
  //         const alertTemplate = document.querySelector(".alert-template");
  //         const clone = alertTemplate.content.cloneNode(true);
  //         const alert = clone.querySelector(".alert");
  //         const progressBar = alert.querySelector(".progress-bar");
  //         //alert type
  //         alert.classList.add("alert-warning");
  //         //icon
  //         alert.querySelector(".fad").classList.add("fa-exclamation-circle");
  //         //message
  //         alert.querySelector(".alert-message").innerHTML =
  //           factureEffective.message;
  //         //progress bar
  //         progressBar.style.transition = "width 10s linear";
  //         progressBar.style.width = "100%";

  //         //add alert
  //         divChartTotalFacture
  //           .closest(".modal-body")
  //           .querySelector(".alert-container")
  //           .prepend(alert);

  //         //progress lanch animation
  //         setTimeout(() => {
  //           progressBar.style.width = "0%";
  //         }, 10);
  //         //auto close alert
  //         setTimeout(() => {
  //           alert.querySelector(".btn-close").click();
  //         }, 10000);

  //         return;
  //       }

  //       //success effective transactions
  //       //all dates
  //       const allDates = [
  //         ...new Set([...factureEffective.data.map((d) => d.date)]),
  //       ].sort();

  //       Chart.defaults.locale = cookieLangValue;
  //       //count par date
  //       const countNbTotalFacture = countNbTotal(factureEffective);
  //       //=====show chart histo total_facture
  //       const canvasHistoTotalFacture = document.createElement("canvas");
  //       new Chart(canvasHistoTotalFacture, {
  //         type: "line",
  //         data: {
  //           labels: allDates,
  //           datasets: [
  //             {
  //               label: lang.bill,
  //               data: prepareTotal(countNbTotalFacture, allDates),
  //               borderColor: "#01a7b9ff",
  //               borderWidth: 1,
  //               backgroundColor: "#2ec4b5a1",
  //               // barThickness: 10,
  //               borderRadius: 5,
  //             },
  //           ],
  //         },
  //         options: {
  //           responsive: true,
  //           plugins: {
  //             title: {
  //               display: true,
  //               text: `${lang.total_facture} (${formatterTotal.format(
  //                 factureEffective.total_facture
  //               )})`,
  //             },
  //             legend: { display: true, position: "bottom", align: "center" },
  //           },
  //           scales: {
  //             x: {
  //               title: {
  //                 display: true,
  //                 text: lang.date,
  //               },
  //               type: "time",
  //               time: { unit: "day" },
  //             },
  //             y: {
  //               title: {
  //                 display: true,
  //                 text: `${lang.total} (${currencyUnits})`,
  //               },
  //               beginAtZero: true,
  //               ticks: { stepSize: 1 },
  //             },
  //           },
  //           zoom: {
  //             // zoom: {
  //             //   wheel: {
  //             //     enabled: true,
  //             //     minScale: 0.5,
  //             //     maxScale: 10,
  //             //     wheelEvent: "wheel",
  //             //   },
  //             //   pinch: { enabled: true },
  //             //   pan: {
  //             //     enabled: true,
  //             //     mode: "xy",
  //             //     modifierKey: "alt",
  //             //   },
  //             //   drag: {
  //             //     mode: "xy",
  //             //     enabled: true,
  //             //     backgroundColor: "red",
  //             //     animation: 100,
  //             //   },
  //             // },
  //           },
  //         },
  //         plugins: [ChartZoom],
  //       });
  //       divChartTotalFacture.innerHTML = "";
  //       divChartTotalFacture.append(canvasHistoTotalFacture);
  //     } catch (e) {
  //       console.error(e);
  //     }
  //   }
  //   //function - count nb && total per date
  //   const countNbTotal = (effective) => {
  //     const result = {};
  //     effective.data.forEach((item) => {
  //       if (!result[item.date]) {
  //         //object date {} not exist
  //         result[item.date] = {
  //           count: 0,
  //           total: 0,
  //         };
  //       }
  //       //increment
  //       result[item.date].count++;
  //       result[item.date].total += Number(item.montant);
  //     });
  //     return result;
  //   };
  //   //function - prepare count
  //   const prepareCount = (countNbTotal, allDates) =>
  //     allDates.map((date) => countNbTotal[date]?.count || 0);
  //   //function - prepare total
  //   const prepareTotal = (countNbTotal, allDates) =>
  //     allDates.map((date) => countNbTotal[date]?.total || 0);
});
