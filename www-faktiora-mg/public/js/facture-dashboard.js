document.addEventListener("DOMContentLoaded", async () => {
  //template real content
  const templateRealContent = document.getElementById("template-facture");

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
    // select - status
    const selectStatus = searchBar.querySelector("#select-status");
    const savedSelectStatus = localStorage.getItem(selectStatus.id);
    selectStatus.value = !savedSelectStatus ? "all" : savedSelectStatus;
    //select - arrange_by
    const selectArrangeBy = searchBar.querySelector("#select-arrange-by");
    const savedSelectArrangeBy = localStorage.getItem(selectArrangeBy.id);
    selectArrangeBy.value = !savedSelectArrangeBy
      ? "num"
      : savedSelectArrangeBy;
    //select - order
    const selectOrder = searchBar.querySelector("#select-order");
    const savedSelectOrder = localStorage.getItem(selectOrder.id);
    selectOrder.value = !savedSelectOrder ? "asc" : savedSelectOrder;
    //date - from
    const dateFrom = searchBar.querySelector("#date-from");
    const savedDateFrom = localStorage.getItem(dateFrom.id);
    dateFrom.value = !savedDateFrom ? "" : savedDateFrom;
    //date - to
    const dateTo = searchBar.querySelector("#date-to");
    const savedDateTo = localStorage.getItem(dateTo.id);
    dateTo.value = !savedDateTo ? "" : savedDateTo;
    //select - num_caisse
    const selectNumCaisse = searchBar.querySelector("#select-num-caisse");
    if (selectNumCaisse) {
      const savedSelectNumCaisse = localStorage.getItem(selectNumCaisse.id);
      //initialize select2
      $(selectNumCaisse).select2({
        theme: "bootstrap-5",
        placeholder: lang.select.toLowerCase(),
        dropdownParent: $(searchBar),
      });
      //list num_caisse
      await listNumCaisse(selectNumCaisse);

      $(selectNumCaisse)
        .val(!savedSelectNumCaisse ? "all" : savedSelectNumCaisse)
        .trigger("change");
    }
    //select - id_utilisateur
    const selectIdUtilisateur = searchBar.querySelector(
      "#select-id-utilisateur"
    );
    ////initialize select2
    $(selectIdUtilisateur).select2({
      theme: "bootstrap-5",
      placeholder: lang.select.toLowerCase(),
      dropdownParent: $(searchBar),
    });
    //list user
    await listUser(selectIdUtilisateur);
    const savedSelectIdUtilisateur = localStorage.getItem(
      selectIdUtilisateur.id
    );
    $(selectIdUtilisateur)
      .val(!savedSelectIdUtilisateur ? "all" : savedSelectIdUtilisateur)
      .trigger("change");

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
      selectArrangeBy.value = "num";
      localStorage.removeItem(selectArrangeBy.id);
      //reset order
      selectOrder.value = "asc";
      localStorage.removeItem(selectOrder.id);
      //reset date from
      dateFrom.value = "";
      localStorage.removeItem(dateFrom.id);
      //reset date to
      dateTo.value = "";
      localStorage.removeItem(dateTo.id);
      //reset select num_caisse
      selectNumCaisse.value = "all";
      localStorage.removeItem(selectNumCaisse.id);
      //reset select id_utilisateur
      $(selectIdUtilisateur).val("all").trigger("change");
      localStorage.removeItem(selectIdUtilisateur.id);
    });

    //===== EVENT input search
    inputSearch.addEventListener("input", (e) => {
      inputSearchSearchBar.value = e.target.value;
      filterFacture(
        selectStatus.value.trim(),
        selectArrangeBy.value.trim(),
        selectOrder.value.trim(),
        dateFrom.value.trim(),
        dateTo.value.trim(),
        selectNumCaisse ? $(selectNumCaisse).val().trim() : "",
        $(selectIdUtilisateur).val().trim(),
        e.target.value.trim()
      );
      localStorage.setItem(e.target.id, e.target.value);
    });
    //===== EVENT input searchbar
    inputSearchSearchBar.addEventListener("input", (e) => {
      inputSearch.value = e.target.value;
      inputSearch.dispatchEvent(new Event("input"));
    });
    //===== EVENT select status
    selectStatus.addEventListener("change", (e) => {
      filterFacture(
        e.target.value.trim(),
        selectArrangeBy.value.trim(),
        selectOrder.value.trim(),
        dateFrom.value.trim(),
        dateTo.value.trim(),
        selectNumCaisse ? $(selectNumCaisse).val().trim() : "",
        $(selectIdUtilisateur).val().trim(),
        inputSearch.value.trim()
      );
      localStorage.setItem(e.target.id, e.target.value);
    });
    //===== EVENT select arrange_by
    selectArrangeBy.addEventListener("change", (e) => {
      filterFacture(
        selectStatus.value.trim(),
        e.target.value.trim(),
        selectOrder.value.trim(),
        dateFrom.value.trim(),
        dateTo.value.trim(),
        selectNumCaisse ? $(selectNumCaisse).val().trim() : "",
        $(selectIdUtilisateur).val().trim(),
        inputSearch.value.trim()
      );
      localStorage.setItem(e.target.id, e.target.value);
    });
    //===== EVENT select order
    selectOrder.addEventListener("change", (e) => {
      filterFacture(
        selectStatus.value.trim(),
        selectArrangeBy.value.trim(),
        e.target.value.trim(),
        dateFrom.value.trim(),
        dateTo.value.trim(),
        selectNumCaisse ? $(selectNumCaisse).val().trim() : "",
        $(selectIdUtilisateur).val().trim(),
        inputSearch.value.trim()
      );
      localStorage.setItem(e.target.id, e.target.value);
    });
    //===== EVENT date from
    dateFrom.addEventListener("input", (e) => {
      filterFacture(
        selectStatus.value.trim(),
        selectArrangeBy.value.trim(),
        selectOrder.value.trim(),
        e.target.value.trim(),
        dateTo.value.trim(),
        selectNumCaisse ? $(selectNumCaisse).val().trim() : "",
        $(selectIdUtilisateur).val().trim(),
        inputSearch.value.trim()
      );
      localStorage.setItem(e.target.id, e.target.value);
      dateTo.min = e.target.value;
    });
    //===== EVENT date to
    dateTo.addEventListener("input", (e) => {
      filterFacture(
        selectStatus.value.trim(),
        selectArrangeBy.value.trim(),
        selectOrder.value.trim(),
        dateFrom.value.trim(),
        e.target.value.trim(),
        selectNumCaisse ? $(selectNumCaisse).val().trim() : "",
        $(selectIdUtilisateur).val().trim(),
        inputSearch.value.trim()
      );
      localStorage.setItem(e.target.id, e.target.value);
      dateFrom.max = e.target.value;
    });
    //EVENT select num_caisse
    if (selectNumCaisse)
      $(selectNumCaisse).on("change", function (e) {
        filterFacture(
          selectStatus.value.trim(),
          selectArrangeBy.value.trim(),
          selectOrder.value.trim(),
          dateFrom.value.trim(),
          dateTo.value.trim(),
          $(this).val().trim(),
          $(selectIdUtilisateur).val().trim(),
          inputSearch.value.trim()
        );
        localStorage.setItem(e.target.id, $(this).val());
      });
    //EVENT select id_utilisateur
    $(selectIdUtilisateur).on("change", function (e) {
      filterFacture(
        selectStatus.value.trim(),
        selectArrangeBy.value.trim(),
        selectOrder.value.trim(),
        dateFrom.value.trim(),
        dateTo.value.trim(),
        $(selectNumCaisse).val().trim(),
        $(this).val().trim(),
        inputSearch.value.trim()
      );
      localStorage.setItem(e.target.id, $(this).val());
    });
    //======================== FILTER CLIENT ==================
    await filterFacture(
      selectStatus.value.trim(),
      selectArrangeBy.value.trim(),
      selectOrder.value.trim(),
      dateFrom.value.trim(),
      dateTo.value.trim(),
      selectNumCaisse ? $(selectNumCaisse).val().trim() : "",
      $(selectIdUtilisateur).val().trim(),
      inputSearch.value.trim()
    );
    //   //======================== ADD CLIENT ======================
    //   //modal add client
    //   const modalAddClient = container.querySelector("#modal-add-client");
    //   //input - add nom_client
    //   const inputAddNomClient = modalAddClient.querySelector(
    //     "#input-add-nom-client"
    //   );
    //   const savedInputAddNomClient = localStorage.getItem(inputAddNomClient.id);
    //   inputAddNomClient.value = !savedInputAddNomClient
    //     ? ""
    //     : savedInputAddNomClient;
    //   //input - add prenoms_client
    //   const inputAddPrenomsClient = modalAddClient.querySelector(
    //     "#input-add-prenoms-client"
    //   );
    //   const savedInputAddPrenomsClient = localStorage.getItem(
    //     inputAddPrenomsClient.id
    //   );
    //   inputAddPrenomsClient.value = !savedInputAddPrenomsClient
    //     ? ""
    //     : savedInputAddPrenomsClient;
    //   //select - add sexe_client
    //   const selectAddSexeClient = modalAddClient.querySelector(
    //     "#select-add-sexe-client"
    //   );
    //   const savedSelectAddSexeClient = localStorage.getItem(
    //     selectAddSexeClient.id
    //   );
    //   selectAddSexeClient.value = !savedSelectAddSexeClient
    //     ? "masculin"
    //     : savedSelectAddSexeClient;
    //   //input - add telephone
    //   const inputAddTelephone = modalAddClient.querySelector(
    //     "#input-add-telephone"
    //   );
    //   const savedInputAddTelephone = localStorage.getItem(inputAddTelephone.id);
    //   inputAddTelephone.value = !savedInputAddTelephone
    //     ? ""
    //     : savedInputAddTelephone;
    //   //input - add adresse
    //   const inputAddAdresse = modalAddClient.querySelector("#input-add-adresse");
    //   const savedInputAddAdresse = localStorage.getItem(inputAddAdresse.id);
    //   inputAddAdresse.value = !savedInputAddAdresse ? "" : savedInputAddAdresse;
    //   //===== EVENT input add nom_client
    //   inputAddNomClient.addEventListener("input", (e) => {
    //     //remove double space && change into uppercase
    //     e.target.value = e.target.value.replace("  ", " ").toUpperCase();
    //     //save value into localstorage
    //     localStorage.setItem(e.target.id, e.target.value);
    //   });
    //   //===== EVENT input add prenoms_client
    //   inputAddPrenomsClient.addEventListener("input", (e) => {
    //     //remove double space && change into uppercase
    //     e.target.value = e.target.value.replace("  ", " ");
    //     //save value into localstorage
    //     localStorage.setItem(e.target.id, e.target.value);
    //   });
    //   //===== EVENT select add sexe_client
    //   selectAddSexeClient.addEventListener("change", (e) => {
    //     //save value into localstorage
    //     localStorage.setItem(e.target.id, e.target.value);
    //   });
    //   //===== EVENT input add telephone
    //   inputAddTelephone.addEventListener("input", (e) => {
    //     //remove invalid
    //     e.target.value = e.target.value.replace(/[^\d+\s]/g, "");
    //     //remove double space
    //     e.target.value = e.target.value.replace("  ", " ");
    //     //save value into localstorage
    //     localStorage.setItem(e.target.id, e.target.value);
    //   });
    //   //===== EVENT input add adresse
    //   inputAddAdresse.addEventListener("input", (e) => {
    //     //remove double space
    //     e.target.value = e.target.value.replace("  ", " ");
    //     //save value into localstorage
    //     localStorage.setItem(e.target.id, e.target.value);
    //   });
    //   //===== EVENT form add client submit
    //   modalAddClient
    //     .querySelector("form")
    //     .addEventListener("submit", async (e) => {
    //       //suspend submit
    //       e.preventDefault();
    //       //check validity
    //       if (!e.target.checkValidity()) {
    //         e.target.reportValidity();
    //       }
    //       try {
    //         //FETCH api add client
    //         const apiAddClient = await apiRequest("/client/create_client", {
    //           method: "POST",
    //           body: {
    //             nom_client: inputAddNomClient.value.trim(),
    //             prenoms_client: inputAddPrenomsClient.value.trim(),
    //             sexe_client: selectAddSexeClient.value.trim(),
    //             telephone: inputAddTelephone.value.trim(),
    //             adresse: inputAddAdresse.value.trim(),
    //           },
    //         });
    //         //error
    //         if (apiAddClient.message_type === "error") {
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
    //             apiAddClient.message;
    //           //progress bar
    //           progressBar.style.transition = "width 20s linear";
    //           progressBar.style.width = "100%";
    //           //add alert
    //           modalAddClient.querySelector(".modal-body").prepend(alert);
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
    //         else if (apiAddClient.message_type === "invalid") {
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
    //             apiAddClient.message;
    //           //progress bar
    //           progressBar.style.transition = "width 10s linear";
    //           progressBar.style.width = "100%";
    //           //add alert
    //           modalAddClient.querySelector(".modal-body").prepend(alert);
    //           //progress lanch animation
    //           setTimeout(() => {
    //             progressBar.style.width = "0%";
    //           }, 10);
    //           //auto close alert
    //           setTimeout(() => {
    //             alert.querySelector(".btn-close").click();
    //           }, 10000);
    //           return;
    //         }
    //         //success add client
    //         //alert
    //         const alertTemplate = document.querySelector(".alert-template");
    //         const clone = alertTemplate.content.cloneNode(true);
    //         const alert = clone.querySelector(".alert");
    //         const progressBar = alert.querySelector(".progress-bar");
    //         //alert type
    //         alert.classList.add("alert-success");
    //         //icon
    //         alert.querySelector(".fad").classList.add("fa-check-circle");
    //         //message
    //         alert.querySelector(".alert-message").innerHTML =
    //           apiAddClient.message;
    //         //progress bar
    //         progressBar.style.transition = "width 10s linear";
    //         progressBar.style.width = "100%";
    //         //add alert
    //         container
    //           .querySelector("#tbody-client")
    //           .closest("div")
    //           .prepend(alert);
    //         //progress lanch animation
    //         setTimeout(() => {
    //           progressBar.style.width = "0%";
    //         }, 10);
    //         //auto close alert
    //         setTimeout(() => {
    //           alert.querySelector(".btn-close").click();
    //         }, 10000);
    //         //auto close modal
    //         modalAddClient.querySelector("#btn-close-modal-add-client").click();
    //         //refresh filter client
    //         filterClient(
    //           selectStatus.value.trim(),
    //           selectSex.value.trim(),
    //           selectArrangeBy.value.trim(),
    //           selectOrder.value.trim(),
    //           selectDateBy.value.trim(),
    //           selectPer.value.trim(),
    //           dateFrom.value.trim(),
    //           dateTo.value.trim(),
    //           selectMonth.value.trim(),
    //           selectYear.value.trim(),
    //           inputSearch.value.trim()
    //         );
    //         return;
    //       } catch (e) {
    //         console.error(e);
    //       }
    //     });
    //   //========================= UPDATE CLIENT ======================
    //   //modal update client
    //   const modalUpdateClient = container.querySelector("#modal-update-client");
    //   //===== EVENT form update client submit
    //   modalUpdateClient
    //     .querySelector("form")
    //     .addEventListener("submit", async (e) => {
    //       //suspend submit
    //       e.preventDefault();
    //       //check validity
    //       if (!e.target.checkValidity()) {
    //         e.target.reportValidity();
    //       }
    //       try {
    //         //FETCH api update client
    //         const apiUpdateClient = await apiRequest("/client/update_client", {
    //           method: "PUT",
    //           body: {
    //             id_client: modalUpdateClient
    //               .querySelector("#update-id-client")
    //               .textContent.trim(),
    //             nom_client: modalUpdateClient
    //               .querySelector("#input-update-nom-client")
    //               .value.trim(),
    //             prenoms_client: modalUpdateClient
    //               .querySelector("#input-update-prenoms-client")
    //               .value.trim(),
    //             sexe_client: modalUpdateClient
    //               .querySelector("#select-update-sexe-client")
    //               .value.trim(),
    //             telephone: modalUpdateClient
    //               .querySelector("#input-update-telephone")
    //               .value.trim(),
    //             adresse: modalUpdateClient
    //               .querySelector("#input-update-adresse")
    //               .value.trim(),
    //           },
    //         });
    //         //error
    //         if (apiUpdateClient.message_type === "error") {
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
    //             apiUpdateClient.message;
    //           //progress bar
    //           progressBar.style.transition = "width 20s linear";
    //           progressBar.style.width = "100%";
    //           //add alert
    //           modalUpdateClient.querySelector(".modal-body").prepend(alert);
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
    //         else if (apiUpdateClient.message_type === "invalid") {
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
    //             apiUpdateClient.message;
    //           //progress bar
    //           progressBar.style.transition = "width 10s linear";
    //           progressBar.style.width = "100%";
    //           //add alert
    //           modalUpdateClient.querySelector(".modal-body").prepend(alert);
    //           //progress lanch animation
    //           setTimeout(() => {
    //             progressBar.style.width = "0%";
    //           }, 10);
    //           //auto close alert
    //           setTimeout(() => {
    //             alert.querySelector(".btn-close").click();
    //           }, 10000);
    //           return;
    //         }
    //         //success update client
    //         //alert
    //         const alertTemplate = document.querySelector(".alert-template");
    //         const clone = alertTemplate.content.cloneNode(true);
    //         const alert = clone.querySelector(".alert");
    //         const progressBar = alert.querySelector(".progress-bar");
    //         //alert type
    //         alert.classList.add("alert-success");
    //         //icon
    //         alert.querySelector(".fad").classList.add("fa-check-circle");
    //         //message
    //         alert.querySelector(".alert-message").innerHTML =
    //           apiUpdateClient.message;
    //         //progress bar
    //         progressBar.style.transition = "width 10s linear";
    //         progressBar.style.width = "100%";
    //         //add alert
    //         container
    //           .querySelector("#tbody-client")
    //           .closest("div")
    //           .prepend(alert);
    //         //progress lanch animation
    //         setTimeout(() => {
    //           progressBar.style.width = "0%";
    //         }, 10);
    //         //auto close alert
    //         setTimeout(() => {
    //           alert.querySelector(".btn-close").click();
    //         }, 10000);
    //         //auto close modal
    //         modalUpdateClient
    //           .querySelector("#btn-close-modal-update-client")
    //           .click();
    //         //refresh filter client
    //         filterClient(
    //           selectStatus.value.trim(),
    //           selectSex.value.trim(),
    //           selectArrangeBy.value.trim(),
    //           selectOrder.value.trim(),
    //           selectDateBy.value.trim(),
    //           selectPer.value.trim(),
    //           dateFrom.value.trim(),
    //           dateTo.value.trim(),
    //           selectMonth.value.trim(),
    //           selectYear.value.trim(),
    //           inputSearch.value.trim()
    //         );
    //       } catch (e) {
    //         console.error(e);
    //       }
    //     });
    //   //========================== DELETE CLIENT =====================
    //   //btn delete client
    //   const btnDeleteClient = container.querySelector("#btn-delete-client");
    //   //===== EVENT btn delete client
    //   if (btnDeleteClient) {
    //     btnDeleteClient.addEventListener("click", () => {
    //       //modal delete client
    //       const modalDeleteClient = container.querySelector(
    //         "#modal-delete-client"
    //       );
    //       //selected client
    //       const selectedClient = container.querySelectorAll(
    //         "#tbody-client input[type='checkbox']:checked"
    //       );
    //       //no selection
    //       if (selectedClient.length <= 0) {
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
    //           lang.client_ids_client_empty;
    //         //progress bar
    //         progressBar.style.transition = "width 10s linear";
    //         progressBar.style.width = "100%";
    //         //add alert
    //         container
    //           .querySelector("#tbody-client")
    //           .closest("div")
    //           .prepend(alert);
    //         //progress launch animation
    //         setTimeout(() => {
    //           progressBar.style.width = "0%";
    //         }, 10);
    //         //auto close alert
    //         setTimeout(() => {
    //           alert.querySelector(".btn-close").click();
    //         }, 10000);
    //         return;
    //       }
    //       //modal message 1
    //       if (selectedClient.length === 1) {
    //         modalDeleteClient.querySelector(".message").innerHTML =
    //           lang.question_delete_client_1.replace(
    //             ":field",
    //             selectedClient[0].closest("tr").dataset.idClient
    //           );
    //       }
    //       //modal message plur
    //       else {
    //         modalDeleteClient.querySelector(".message").innerHTML =
    //           lang.question_delete_client_plur.replace(
    //             ":field",
    //             selectedClient.length
    //           );
    //       }
    //       //show modal delete client
    //       new bootstrap.Modal(modalDeleteClient).show();
    //       //==== EVENT btn confirm modal delete client
    //       modalDeleteClient
    //         .querySelector("#btn-confirm-modal-delete-client")
    //         .addEventListener("click", async () => {
    //           try {
    //             //ids_client
    //             let ids_client = [...selectedClient];
    //             ids_client = ids_client.map(
    //               (selected) => selected.closest("tr").dataset.idClient
    //             );
    //             //FETCH api delete client
    //             const apiDeleteClient = await apiRequest(
    //               "/client/delete_all_client",
    //               {
    //                 method: "PUT",
    //                 body: {
    //                   ids_client: ids_client,
    //                 },
    //               }
    //             );
    //             //error
    //             if (apiDeleteClient.message_type === "error") {
    //               //alert
    //               const alertTemplate = document.querySelector(".alert-template");
    //               const clone = alertTemplate.content.cloneNode(true);
    //               const alert = clone.querySelector(".alert");
    //               const progressBar = alert.querySelector(".progress-bar");
    //               //alert type
    //               alert.classList.add("alert-danger");
    //               //icon
    //               alert
    //                 .querySelector(".fad")
    //                 .classList.add("fa-exclamation-circle");
    //               //message
    //               alert.querySelector(".alert-message").innerHTML =
    //                 apiDeleteClient.message;
    //               //progress bar
    //               progressBar.style.transition = "width 20s linear";
    //               progressBar.style.width = "100%";
    //               //add alert
    //               modalDeleteClient.querySelector(".modal-body").prepend(alert);
    //               //progress launch animation
    //               setTimeout(() => {
    //                 progressBar.style.width = "0%";
    //               }, 10);
    //               //auto close alert
    //               setTimeout(() => {
    //                 alert.querySelector(".btn-close").click();
    //               }, 20000);
    //               return;
    //             }
    //             //invalid
    //             else if (apiDeleteClient.message_type === "invalid") {
    //               //alert
    //               const alertTemplate = document.querySelector(".alert-template");
    //               const clone = alertTemplate.content.cloneNode(true);
    //               const alert = clone.querySelector(".alert");
    //               const progressBar = alert.querySelector(".progress-bar");
    //               //alert type
    //               alert.classList.add("alert-warning");
    //               //icon
    //               alert
    //                 .querySelector(".fad")
    //                 .classList.add("fa-exclamation-circle");
    //               //message
    //               alert.querySelector(".alert-message").innerHTML =
    //                 apiDeleteClient.message;
    //               //progress bar
    //               progressBar.style.transition = "width 10s linear";
    //               progressBar.style.width = "100%";
    //               //add alert
    //               modalDeleteClient.querySelector(".modal-body").prepend(alert);
    //               //progress launch animation
    //               setTimeout(() => {
    //                 progressBar.style.width = "0%";
    //               }, 10);
    //               //auto close alert
    //               setTimeout(() => {
    //                 alert.querySelector(".btn-close").click();
    //               }, 10000);
    //               return;
    //             }
    //             //success
    //             //alert
    //             const alertTemplate = document.querySelector(".alert-template");
    //             const clone = alertTemplate.content.cloneNode(true);
    //             const alert = clone.querySelector(".alert");
    //             const progressBar = alert.querySelector(".progress-bar");
    //             //alert type
    //             alert.classList.add("alert-success");
    //             //icon
    //             alert.querySelector(".fad").classList.add("fa-check-circle");
    //             //message
    //             alert.querySelector(".alert-message").innerHTML =
    //               apiDeleteClient.message;
    //             //progress bar
    //             progressBar.style.transition = "width 10s linear";
    //             progressBar.style.width = "100%";
    //             //add alert
    //             container
    //               .querySelector("#tbody-client")
    //               .closest("div")
    //               .prepend(alert);
    //             //progress launch animation
    //             setTimeout(() => {
    //               progressBar.style.width = "0%";
    //             }, 10);
    //             //auto close alert
    //             setTimeout(() => {
    //               alert.querySelector(".btn-close").click();
    //             }, 10000);
    //             //auto hide modal
    //             modalDeleteClient
    //               .querySelector("#btn-close-modal-delete-client")
    //               .click();
    //             //refresh filter client
    //             filterClient(
    //               selectStatus.value.trim(),
    //               selectSex.value.trim(),
    //               selectArrangeBy.value.trim(),
    //               selectOrder.value.trim(),
    //               selectDateBy.value.trim(),
    //               selectPer.value.trim(),
    //               dateFrom.value.trim(),
    //               dateTo.value.trim(),
    //               selectMonth.value.trim(),
    //               selectYear.value.trim(),
    //               inputSearch.value.trim()
    //             );
    //             return;
    //           } catch (e) {
    //             console.error(e);
    //           }
    //         });
    //     });
    //   }
    //   //========================= DELETE PERMANENT CLIENT =================
    //   //btn restore client
    //   const btnDeletePermanentClient = container.querySelector(
    //     "#btn-delete-permanent-client"
    //   );
    //   //===== EVENT btn delete permanent client
    //   if (btnDeletePermanentClient) {
    //     btnDeletePermanentClient.addEventListener("click", () => {
    //       //modal delete client
    //       const modalDeleteClient = container.querySelector(
    //         "#modal-delete-client"
    //       );
    //       //selected client
    //       const selectedClient = container.querySelectorAll(
    //         "#tbody-client input[type='checkbox']:checked"
    //       );
    //       //no selection
    //       if (selectedClient.length <= 0) {
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
    //           lang.client_ids_client_empty;
    //         //progress bar
    //         progressBar.style.transition = "width 10s linear";
    //         progressBar.style.width = "100%";
    //         //add alert
    //         container
    //           .querySelector("#tbody-client")
    //           .closest("div")
    //           .prepend(alert);
    //         //progress launch animation
    //         setTimeout(() => {
    //           progressBar.style.width = "0%";
    //         }, 10);
    //         //auto close alert
    //         setTimeout(() => {
    //           alert.querySelector(".btn-close").click();
    //         }, 10000);
    //         return;
    //       }
    //       //modal message 1
    //       if (selectedClient.length === 1) {
    //         modalDeleteClient.querySelector(".message").innerHTML =
    //           lang.question_delete_permanent_client_1.replace(
    //             ":field",
    //             selectedClient[0].closest("tr").dataset.idClient
    //           );
    //       }
    //       //modal message plur
    //       else {
    //         modalDeleteClient.querySelector(".message").innerHTML =
    //           lang.question_delete_permanent_client_plur.replace(
    //             ":field",
    //             selectedClient.length
    //           );
    //       }
    //       //show modal delete client
    //       new bootstrap.Modal(modalDeleteClient).show();
    //       //==== EVENT btn confirm modal delete client
    //       modalDeleteClient
    //         .querySelector("#btn-confirm-modal-delete-client")
    //         .addEventListener("click", async () => {
    //           try {
    //             //ids_client
    //             let ids_client = [...selectedClient];
    //             ids_client = ids_client.map(
    //               (selected) => selected.closest("tr").dataset.idClient
    //             );
    //             //FETCH api delete client
    //             const apiDeleteClient = await apiRequest(
    //               "/client/delete_permanent_all_client",
    //               {
    //                 method: "DELETE",
    //                 body: {
    //                   ids_client: ids_client,
    //                 },
    //               }
    //             );
    //             //error
    //             if (apiDeleteClient.message_type === "error") {
    //               //alert
    //               const alertTemplate = document.querySelector(".alert-template");
    //               const clone = alertTemplate.content.cloneNode(true);
    //               const alert = clone.querySelector(".alert");
    //               const progressBar = alert.querySelector(".progress-bar");
    //               //alert type
    //               alert.classList.add("alert-danger");
    //               //icon
    //               alert
    //                 .querySelector(".fad")
    //                 .classList.add("fa-exclamation-circle");
    //               //message
    //               alert.querySelector(".alert-message").innerHTML =
    //                 apiDeleteClient.message;
    //               //progress bar
    //               progressBar.style.transition = "width 20s linear";
    //               progressBar.style.width = "100%";
    //               //add alert
    //               modalDeleteClient.querySelector(".modal-body").prepend(alert);
    //               //progress launch animation
    //               setTimeout(() => {
    //                 progressBar.style.width = "0%";
    //               }, 10);
    //               //auto close alert
    //               setTimeout(() => {
    //                 alert.querySelector(".btn-close").click();
    //               }, 20000);
    //               return;
    //             }
    //             //invalid
    //             else if (apiDeleteClient.message_type === "invalid") {
    //               //alert
    //               const alertTemplate = document.querySelector(".alert-template");
    //               const clone = alertTemplate.content.cloneNode(true);
    //               const alert = clone.querySelector(".alert");
    //               const progressBar = alert.querySelector(".progress-bar");
    //               //alert type
    //               alert.classList.add("alert-warning");
    //               //icon
    //               alert
    //                 .querySelector(".fad")
    //                 .classList.add("fa-exclamation-circle");
    //               //message
    //               alert.querySelector(".alert-message").innerHTML =
    //                 apiDeleteClient.message;
    //               //progress bar
    //               progressBar.style.transition = "width 10s linear";
    //               progressBar.style.width = "100%";
    //               //add alert
    //               modalDeleteClient.querySelector(".modal-body").prepend(alert);
    //               //progress launch animation
    //               setTimeout(() => {
    //                 progressBar.style.width = "0%";
    //               }, 10);
    //               //auto close alert
    //               setTimeout(() => {
    //                 alert.querySelector(".btn-close").click();
    //               }, 10000);
    //               return;
    //             }
    //             //success
    //             //alert
    //             const alertTemplate = document.querySelector(".alert-template");
    //             const clone = alertTemplate.content.cloneNode(true);
    //             const alert = clone.querySelector(".alert");
    //             const progressBar = alert.querySelector(".progress-bar");
    //             //alert type
    //             alert.classList.add("alert-success");
    //             //icon
    //             alert.querySelector(".fad").classList.add("fa-check-circle");
    //             //message
    //             alert.querySelector(".alert-message").innerHTML =
    //               apiDeleteClient.message;
    //             //progress bar
    //             progressBar.style.transition = "width 10s linear";
    //             progressBar.style.width = "100%";
    //             //add alert
    //             container
    //               .querySelector("#tbody-client")
    //               .closest("div")
    //               .prepend(alert);
    //             //progress launch animation
    //             setTimeout(() => {
    //               progressBar.style.width = "0%";
    //             }, 10);
    //             //auto close alert
    //             setTimeout(() => {
    //               alert.querySelector(".btn-close").click();
    //             }, 10000);
    //             //auto hide modal
    //             modalDeleteClient
    //               .querySelector("#btn-close-modal-delete-client")
    //               .click();
    //             //refresh filter client
    //             filterClient(
    //               selectStatus.value.trim(),
    //               selectSex.value.trim(),
    //               selectArrangeBy.value.trim(),
    //               selectOrder.value.trim(),
    //               selectDateBy.value.trim(),
    //               selectPer.value.trim(),
    //               dateFrom.value.trim(),
    //               dateTo.value.trim(),
    //               selectMonth.value.trim(),
    //               selectYear.value.trim(),
    //               inputSearch.value.trim()
    //             );
    //             return;
    //           } catch (e) {
    //             console.error(e);
    //           }
    //         });
    //     });
    //   }
    //   //========================== RESTORE CLIENT =========================
    //   //btn restore client
    //   const btnRestoreClient = container.querySelector("#btn-restore-client");
    //   //===== EVENT btn restore client
    //   if (btnRestoreClient) {
    //     btnRestoreClient.addEventListener("click", () => {
    //       //modal restore client
    //       const modalRestoreClient = container.querySelector(
    //         "#modal-restore-client"
    //       );
    //       //selected client
    //       const selectedClient = container.querySelectorAll(
    //         "#tbody-client input[type='checkbox']:checked"
    //       );
    //       //no selection
    //       if (selectedClient.length <= 0) {
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
    //           lang.client_ids_client_empty;
    //         //progress bar
    //         progressBar.style.transition = "width 10s linear";
    //         progressBar.style.width = "100%";
    //         //add alert
    //         container
    //           .querySelector("#tbody-client")
    //           .closest("div")
    //           .prepend(alert);
    //         //progress launch animation
    //         setTimeout(() => {
    //           progressBar.style.width = "0%";
    //         }, 10);
    //         //auto close alert
    //         setTimeout(() => {
    //           alert.querySelector(".btn-close").click();
    //         }, 10000);
    //         return;
    //       }
    //       //modal message 1
    //       if (selectedClient.length === 1) {
    //         modalRestoreClient.querySelector(".message").innerHTML =
    //           lang.question_restore_client_1.replace(
    //             ":field",
    //             selectedClient[0].closest("tr").dataset.idClient
    //           );
    //       }
    //       //modal message plur
    //       else {
    //         modalRestoreClient.querySelector(".message").innerHTML =
    //           lang.question_restore_client_plur.replace(
    //             ":field",
    //             selectedClient.length
    //           );
    //       }
    //       //show modal restore client
    //       new bootstrap.Modal(modalRestoreClient).show();
    //       //==== EVENT btn confirm modal restore client
    //       modalRestoreClient
    //         .querySelector("#btn-confirm-modal-restore-client")
    //         .addEventListener("click", async () => {
    //           try {
    //             //ids_client
    //             let ids_client = [...selectedClient];
    //             ids_client = ids_client.map(
    //               (selected) => selected.closest("tr").dataset.idClient
    //             );
    //             //FETCH api restore client
    //             const apiRestoreClient = await apiRequest(
    //               "/client/restore_all_client",
    //               {
    //                 method: "PUT",
    //                 body: {
    //                   ids_client: ids_client,
    //                 },
    //               }
    //             );
    //             //error
    //             if (apiRestoreClient.message_type === "error") {
    //               //alert
    //               const alertTemplate = document.querySelector(".alert-template");
    //               const clone = alertTemplate.content.cloneNode(true);
    //               const alert = clone.querySelector(".alert");
    //               const progressBar = alert.querySelector(".progress-bar");
    //               //alert type
    //               alert.classList.add("alert-danger");
    //               //icon
    //               alert
    //                 .querySelector(".fad")
    //                 .classList.add("fa-exclamation-circle");
    //               //message
    //               alert.querySelector(".alert-message").innerHTML =
    //                 apiRestoreClient.message;
    //               //progress bar
    //               progressBar.style.transition = "width 20s linear";
    //               progressBar.style.width = "100%";
    //               //add alert
    //               modalRestoreClient.querySelector(".modal-body").prepend(alert);
    //               //progress launch animation
    //               setTimeout(() => {
    //                 progressBar.style.width = "0%";
    //               }, 10);
    //               //auto close alert
    //               setTimeout(() => {
    //                 alert.querySelector(".btn-close").click();
    //               }, 20000);
    //               return;
    //             }
    //             //invalid
    //             else if (apiRestoreClient.message_type === "invalid") {
    //               //alert
    //               const alertTemplate = document.querySelector(".alert-template");
    //               const clone = alertTemplate.content.cloneNode(true);
    //               const alert = clone.querySelector(".alert");
    //               const progressBar = alert.querySelector(".progress-bar");
    //               //alert type
    //               alert.classList.add("alert-warning");
    //               //icon
    //               alert
    //                 .querySelector(".fad")
    //                 .classList.add("fa-exclamation-circle");
    //               //message
    //               alert.querySelector(".alert-message").innerHTML =
    //                 apiRestoreClient.message;
    //               //progress bar
    //               progressBar.style.transition = "width 10s linear";
    //               progressBar.style.width = "100%";
    //               //add alert
    //               modalRestoreClient.querySelector(".modal-body").prepend(alert);
    //               //progress launch animation
    //               setTimeout(() => {
    //                 progressBar.style.width = "0%";
    //               }, 10);
    //               //auto close alert
    //               setTimeout(() => {
    //                 alert.querySelector(".btn-close").click();
    //               }, 10000);
    //               return;
    //             }
    //             //success
    //             //alert
    //             const alertTemplate = document.querySelector(".alert-template");
    //             const clone = alertTemplate.content.cloneNode(true);
    //             const alert = clone.querySelector(".alert");
    //             const progressBar = alert.querySelector(".progress-bar");
    //             //alert type
    //             alert.classList.add("alert-success");
    //             //icon
    //             alert.querySelector(".fad").classList.add("fa-check-circle");
    //             //message
    //             alert.querySelector(".alert-message").innerHTML =
    //               apiRestoreClient.message;
    //             //progress bar
    //             progressBar.style.transition = "width 10s linear";
    //             progressBar.style.width = "100%";
    //             //add alert
    //             container
    //               .querySelector("#tbody-client")
    //               .closest("div")
    //               .prepend(alert);
    //             //progress launch animation
    //             setTimeout(() => {
    //               progressBar.style.width = "0%";
    //             }, 10);
    //             //auto close alert
    //             setTimeout(() => {
    //               alert.querySelector(".btn-close").click();
    //             }, 10000);
    //             //auto hide modal
    //             modalRestoreClient
    //               .querySelector("#btn-close-modal-restore-client")
    //               .click();
    //             //refresh filter client
    //             filterClient(
    //               selectStatus.value.trim(),
    //               selectSex.value.trim(),
    //               selectArrangeBy.value.trim(),
    //               selectOrder.value.trim(),
    //               selectDateBy.value.trim(),
    //               selectPer.value.trim(),
    //               dateFrom.value.trim(),
    //               dateTo.value.trim(),
    //               selectMonth.value.trim(),
    //               selectYear.value.trim(),
    //               inputSearch.value.trim()
    //             );
    //             return;
    //           } catch (e) {
    //             console.error(e);
    //           }
    //         });
    //     });
    //   }
  }, 1050);

  //====================== FUNCTIONS ========================
  //function - filter facture
  async function filterFacture(
    status,
    arrange_by,
    order,
    from,
    to,
    num_caisse,
    id_utilisateur,
    search_facture
  ) {
    //tbody
    const tbodyFacture = container.querySelector("#tbody-facture");
    try {
      //FETCH api filter facture
      const apiFilterFacture = await apiRequest(
        `/entree/filter_facture?status=${status}&arrange_by=${arrange_by}&order=${order}&from=${from}&to=${to}&num_caisse=${num_caisse}&id_user=${id_utilisateur}&search_facture=${search_facture}`
      );

      //error
      if (apiFilterFacture.message_type === "error") {
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
          apiFilterFacture.message;
        //progress bar
        progressBar.style.transition = "width 20s linear";
        progressBar.style.width = "100%";

        //add alert
        tbodyFacture.closest("div").prepend(alert);

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
      else if (apiFilterFacture.message_type === "invalid") {
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
          apiFilterFacture.message;
        //progress bar
        progressBar.style.transition = "width 10s linear";
        progressBar.style.width = "100%";

        //add alert
        tbodyFacture.closest("div").prepend(alert);

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

      //===== TABLE facture
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
      //set table list facture
      tbodyFacture.innerHTML = "";
      apiFilterFacture.data.forEach((line) => {
        const tr = document.createElement("tr");

        //td - checkbox
        if (container.querySelector("#check-all-facture")) {
          const tdCheckbox = document.createElement("td");
          tdCheckbox.innerHTML =
            "<input type='checkbox' class='form-check-input'>";
          tdCheckbox.classList.add("text-center");
          tr.append(tdCheckbox);
        }

        //td - num_facture
        const tdNumFacture = document.createElement("td");
        tdNumFacture.textContent = line.num_facture;
        tdNumFacture.classList.add("text-center");

        //td - date_facture
        const tdDateFacture = document.createElement("td");
        tdDateFacture.textContent = fromatterDate.format(
          new Date(line.date_facture)
        );
        tdDateFacture.classList.add("text-center");

        //td - montant_facture
        const tdMontantFacture = document.createElement("td");
        tdMontantFacture.textContent = formatterNumber.format(
          Number(line.montant_facture)
        );
        tdMontantFacture.classList.add("text-center");

        //td - id_utilisateur
        const tdIdUtilisateur = document.createElement("td");
        tdIdUtilisateur.textContent = line.id_utilisateur;
        tdIdUtilisateur.classList.add("text-center");

        //td - id_client
        const tdIdClient = document.createElement("td");
        tdIdClient.textContent = line.id_client;
        tdIdClient.classList.add("text-center");

        //td - num_caisse
        const tdNumCaisse = document.createElement("td");
        tdNumCaisse.textContent = line.num_caisse;
        tdNumCaisse.classList.add("text-center");

        //td - status
        const tdStatus = document.createElement("td");
        if (line.etat_facture === "supprimé") {
          tdStatus.textContent = lang.deleted;
        } else {
          tdStatus.textContent = lang.active.toLowerCase();
        }
        tdStatus.classList.add("text-center");

        //td - actions
        const tdActions = document.createElement("td");
        const divActions = document.createElement("div");
        divActions.classList.add(
          "d-flex",
          "justify-content-center",
          "align-items-center",
          "gap-2"
        );
        //btn update facture
        if (container.querySelector("#check-all-facture")) {
          const btnUpdateFacture = document.createElement("button");
          btnUpdateFacture.type = "button";
          btnUpdateFacture.classList.add(
            "btn-light",
            "btn",
            "btn-sm",
            "text-primary",
            "btn-update-facture"
          );
          btnUpdateFacture.innerHTML = "<i class='fad fa-pen-to-square'></i>";
          divActions.append(btnUpdateFacture);
        }
        //btn correct facture outflow
        const btnCorrectFactureOutflow = document.createElement("button");
        btnCorrectFactureOutflow.type = "button";
        btnCorrectFactureOutflow.classList.add(
          "btn-light",
          "btn",
          "btn-sm",
          "text-danger",
          "btn-correct-facture-outflow"
        );
        btnCorrectFactureOutflow.innerHTML =
          "<i class='fad fa-circle-minus'></i>";
        //btn print facture
        const btnPrintFacture = document.createElement("button");
        btnPrintFacture.type = "button";
        btnPrintFacture.classList.add(
          "btn-light",
          "btn-sm",
          "btn",
          "btn-print-facture"
        );
        btnPrintFacture.innerHTML = "<i class='fad fa-print'></i>";
        //append btn actions
        divActions.append(btnCorrectFactureOutflow, btnPrintFacture);
        tdActions.append(divActions);

        //append
        tr.append(
          tdNumFacture,
          tdDateFacture,
          tdMontantFacture,
          tdIdUtilisateur,
          tdIdClient,
          tdNumCaisse,
          tdStatus,
          tdActions
        );

        tr.dataset.numFacture = line.num_facture;
        tr.dataset.idUtilisateur = line.id_utilisateur;
        tr.dataset.idClient = line.id_client;
        tr.dataset.dateFacture = line.date_facture;
        tr.dataset.numCaisse = line.telephone;
        tbodyFacture.appendChild(tr);
      });

      //foreach all tr
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
  //function - lis num_caisse
  async function listNumCaisse(select) {
    try {
      //FETCH api list all caisse
      const apiListAllCaisse = await apiRequest("/caisse/list_all_caisse");

      //error
      if (apiListAllCaisse.message_type === "error") {
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
          apiListAllCaisse.message;
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

      //success api list num_caisse
      select.innerHTML = `<option></option>
                          <option value='all' selected>${lang.all}</option>`;
      apiListAllCaisse.data.forEach((line) => {
        const option = document.createElement("option");
        option.value = line.num_caisse;
        option.innerText = line.num_caisse;

        select.append(option);
      });
    } catch (e) {
      console.error(e);
    }
  }
  //function - lis user
  async function listUser(select) {
    try {
      //FETCH api list all user
      const apiListAllUser = await apiRequest("/user/list_all_user");

      //error
      if (apiListAllUser.message_type === "error") {
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
          apiListAllUser.message;
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

      //success api list user
      select.innerHTML = `<option></option>
                          <option value='all' selected>${lang.all}</option>`;
      apiListAllUser.data.forEach((line) => {
        const option = document.createElement("option");
        option.value = line.id_utilisateur;
        option.innerText = `${line.id_utilisateur} - ${line.nom_prenoms}`;

        select.append(option);
      });
    } catch (e) {
      console.error(e);
    }
  }
  // //function - chart nb_facture
  // async function chartNbFacture(
  //   divChartNbFacture,
  //   id_client,
  //   date_by,
  //   per,
  //   from,
  //   to,
  //   month,
  //   year
  // ) {
  //   try {
  //     //FETCH api facture effective
  //     const factureEffective = await apiRequest(
  //       `/entree/list_all_facture?date_by=${date_by}&per=${per}&from=${from}&to=${to}&month=${month}&year=${year}&id_client=${id_client}`
  //     );
  //     //error
  //     if (factureEffective.message_type === "error") {
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
  //       divChartNbFacture
  //         .closest(".modal-body")
  //         .querySelector(".alert-container")
  //         .prepend(alert);
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
  //     //invalid
  //     else if (factureEffective.message_type === "invalid") {
  //       //alert
  //       const alertTemplate = document.querySelector(".alert-template");
  //       const clone = alertTemplate.content.cloneNode(true);
  //       const alert = clone.querySelector(".alert");
  //       const progressBar = alert.querySelector(".progress-bar");
  //       //alert type
  //       alert.classList.add("alert-warning");
  //       //icon
  //       alert.querySelector(".fad").classList.add("fa-exclamation-circle");
  //       //message
  //       alert.querySelector(".alert-message").innerHTML =
  //         factureEffective.message;
  //       //progress bar
  //       progressBar.style.transition = "width 10s linear";
  //       progressBar.style.width = "100%";
  //       //add alert
  //       divChartNbFacture
  //         .closest(".modal-body")
  //         .querySelector(".alert-container")
  //         .prepend(alert);
  //       //progress lanch animation
  //       setTimeout(() => {
  //         progressBar.style.width = "0%";
  //       }, 10);
  //       //auto close alert
  //       setTimeout(() => {
  //         alert.querySelector(".btn-close").click();
  //       }, 10000);
  //       return;
  //     }
  //     //success effective transactions
  //     //all dates
  //     const allDates = [
  //       ...new Set([...factureEffective.data.map((d) => d.date)]),
  //     ].sort();
  //     Chart.defaults.locale = cookieLangValue;
  //     //count par date
  //     const countNbTotalFacture = countNbTotal(factureEffective);
  //     //=====show chart histo nb_facture
  //     const canvasHistoNbFacture = document.createElement("canvas");
  //     new Chart(canvasHistoNbFacture, {
  //       type: "line",
  //       data: {
  //         labels: allDates,
  //         datasets: [
  //           {
  //             label: lang.bill,
  //             data: prepareCount(countNbTotalFacture, allDates),
  //             borderColor: "#01a7b9ff",
  //             borderWidth: 1,
  //             backgroundColor: "#2ec4b5a1",
  //             // barThickness: 10,
  //             borderRadius: 5,
  //           },
  //         ],
  //       },
  //       options: {
  //         responsive: true,
  //         plugins: {
  //           title: {
  //             display: true,
  //             text: `${lang.nb_facture} (${formatterNumber.format(
  //               factureEffective.nb_facture
  //             )})`,
  //           },
  //           legend: { display: true, position: "bottom", align: "center" },
  //         },
  //         scales: {
  //           x: {
  //             title: {
  //               display: true,
  //               text: lang.date,
  //             },
  //             type: "time",
  //             time: { unit: "day" },
  //           },
  //           y: {
  //             title: { display: true, text: lang.number },
  //             beginAtZero: true,
  //             ticks: { stepSize: 1 },
  //           },
  //         },
  //         zoom: {
  //           // zoom: {
  //           //   wheel: {
  //           //     enabled: true,
  //           //     minScale: 0.5,
  //           //     maxScale: 10,
  //           //     wheelEvent: "wheel",
  //           //   },
  //           //   pinch: { enabled: true },
  //           //   pan: {
  //           //     enabled: true,
  //           //     mode: "xy",
  //           //     modifierKey: "alt",
  //           //   },
  //           //   drag: {
  //           //     mode: "xy",
  //           //     enabled: true,
  //           //     backgroundColor: "red",
  //           //     animation: 100,
  //           //   },
  //           // },
  //         },
  //       },
  //       plugins: [ChartZoom],
  //     });
  //     divChartNbFacture.innerHTML = "";
  //     divChartNbFacture.append(canvasHistoNbFacture);
  //   } catch (e) {
  //     console.error(e);
  //   }
  // }
  // //function - chart total_facture
  // async function chartTotalFacture(
  //   divChartTotalFacture,
  //   id_client,
  //   date_by,
  //   per,
  //   from,
  //   to,
  //   month,
  //   year
  // ) {
  //   try {
  //     //FETCH api facture effective
  //     const factureEffective = await apiRequest(
  //       `/entree/list_all_facture?date_by=${date_by}&per=${per}&from=${from}&to=${to}&month=${month}&year=${year}&id_client=${id_client}`
  //     );
  //     //error
  //     if (factureEffective.message_type === "error") {
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
  //       divChartTotalFacture
  //         .closest(".modal-body")
  //         .querySelector(".alert-container")
  //         .prepend(alert);
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
  //     //invalid
  //     else if (factureEffective.message_type === "invalid") {
  //       //alert
  //       const alertTemplate = document.querySelector(".alert-template");
  //       const clone = alertTemplate.content.cloneNode(true);
  //       const alert = clone.querySelector(".alert");
  //       const progressBar = alert.querySelector(".progress-bar");
  //       //alert type
  //       alert.classList.add("alert-warning");
  //       //icon
  //       alert.querySelector(".fad").classList.add("fa-exclamation-circle");
  //       //message
  //       alert.querySelector(".alert-message").innerHTML =
  //         factureEffective.message;
  //       //progress bar
  //       progressBar.style.transition = "width 10s linear";
  //       progressBar.style.width = "100%";
  //       //add alert
  //       divChartTotalFacture
  //         .closest(".modal-body")
  //         .querySelector(".alert-container")
  //         .prepend(alert);
  //       //progress lanch animation
  //       setTimeout(() => {
  //         progressBar.style.width = "0%";
  //       }, 10);
  //       //auto close alert
  //       setTimeout(() => {
  //         alert.querySelector(".btn-close").click();
  //       }, 10000);
  //       return;
  //     }
  //     //success effective transactions
  //     //all dates
  //     const allDates = [
  //       ...new Set([...factureEffective.data.map((d) => d.date)]),
  //     ].sort();
  //     Chart.defaults.locale = cookieLangValue;
  //     //count par date
  //     const countNbTotalFacture = countNbTotal(factureEffective);
  //     //=====show chart histo total_facture
  //     const canvasHistoTotalFacture = document.createElement("canvas");
  //     new Chart(canvasHistoTotalFacture, {
  //       type: "line",
  //       data: {
  //         labels: allDates,
  //         datasets: [
  //           {
  //             label: lang.bill,
  //             data: prepareTotal(countNbTotalFacture, allDates),
  //             borderColor: "#01a7b9ff",
  //             borderWidth: 1,
  //             backgroundColor: "#2ec4b5a1",
  //             // barThickness: 10,
  //             borderRadius: 5,
  //           },
  //         ],
  //       },
  //       options: {
  //         responsive: true,
  //         plugins: {
  //           title: {
  //             display: true,
  //             text: `${lang.total_facture} (${formatterTotal.format(
  //               factureEffective.total_facture
  //             )})`,
  //           },
  //           legend: { display: true, position: "bottom", align: "center" },
  //         },
  //         scales: {
  //           x: {
  //             title: {
  //               display: true,
  //               text: lang.date,
  //             },
  //             type: "time",
  //             time: { unit: "day" },
  //           },
  //           y: {
  //             title: {
  //               display: true,
  //               text: `${lang.total} (${currencyUnits})`,
  //             },
  //             beginAtZero: true,
  //             ticks: { stepSize: 1 },
  //           },
  //         },
  //         zoom: {
  //           // zoom: {
  //           //   wheel: {
  //           //     enabled: true,
  //           //     minScale: 0.5,
  //           //     maxScale: 10,
  //           //     wheelEvent: "wheel",
  //           //   },
  //           //   pinch: { enabled: true },
  //           //   pan: {
  //           //     enabled: true,
  //           //     mode: "xy",
  //           //     modifierKey: "alt",
  //           //   },
  //           //   drag: {
  //           //     mode: "xy",
  //           //     enabled: true,
  //           //     backgroundColor: "red",
  //           //     animation: 100,
  //           //   },
  //           // },
  //         },
  //       },
  //       plugins: [ChartZoom],
  //     });
  //     divChartTotalFacture.innerHTML = "";
  //     divChartTotalFacture.append(canvasHistoTotalFacture);
  //   } catch (e) {
  //     console.error(e);
  //   }
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
});
