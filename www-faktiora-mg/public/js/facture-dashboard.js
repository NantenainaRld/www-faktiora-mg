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
    //===== EVENT select num_caisse
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
    //===== EVENT select id_utilisateur
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
    //======================== FILTER FACTURE ==================
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

    //========================= ADD FACTURE =========================
    //modal add facture
    const modalAddFacture = container.querySelector("#modal-add-facture");
    //modal add facture - intialize select 2
    $(modalAddFacture.querySelectorAll(".select2")).select2({
      theme: "bootstrap-5",
      placeholder: lang.select.toLowerCase(),
      dropdownParent: $(modalAddFacture),
    });
    //input - add facture date facture
    const inputAddFactureDateFacture = modalAddFacture.querySelector(
      "#input-add-facture-date-facture"
    );
    if (inputAddFactureDateFacture) {
      const savedInputAddFactureDateFacture = localStorage.getItem(
        inputAddFactureDateFacture.id
      );
      inputAddFactureDateFacture.value = !savedInputAddFactureDateFacture
        ? ""
        : savedInputAddFactureDateFacture;

      //===== EVENT input add facture date facture
      inputAddFactureDateFacture.addEventListener("input", (e) => {
        localStorage.setItem(e.target.id, e.target.value);
      });
    }
    //select - add facture id_utilisateur
    const selectAddFactureIdUtilisateur = modalAddFacture.querySelector(
      "#select-add-facture-id-utilisateur"
    );
    if (selectAddFactureIdUtilisateur) {
      //list all user
      await listUser(selectAddFactureIdUtilisateur, true);
      //load value from localstorage
      const savedSelectAddFactureIdUtilisateur = localStorage.getItem(
        selectAddFactureIdUtilisateur.id
      );
      $(selectAddFactureIdUtilisateur)
        .val(
          !savedSelectAddFactureIdUtilisateur
            ? ""
            : savedSelectAddFactureIdUtilisateur
        )
        .trigger("change");

      //===== EVENT btn add facture refresh id_utilisateur
      modalAddFacture
        .querySelector("#btn-add-facture-refresh-id-utilisateur")
        .addEventListener("click", () => {
          listUser(selectAddFactureIdUtilisateur, true);
        });

      //===== EVENT select add facture id_utilisateur
      $(selectAddFactureIdUtilisateur).on("change", function (e) {
        localStorage.setItem(e.target.id, $(this).val());
      });
    }
    //select - add facture num_caisse
    const selectAddFactureNumCaisse = modalAddFacture.querySelector(
      "#select-add-facture-num-caisse"
    );
    if (selectAddFactureNumCaisse) {
      //list num_caisse
      await listNumCaisse(selectAddFactureNumCaisse, true);
      //load value from localstorage
      const savedSelectAddFactureNumCaisse = localStorage.getItem(
        selectAddFactureNumCaisse.id
      );
      $(selectAddFactureNumCaisse)
        .val(
          !savedSelectAddFactureNumCaisse ? "" : savedSelectAddFactureNumCaisse
        )
        .trigger("change");

      //===== EVENT btn add facture refresh num_caisse
      modalAddFacture
        .querySelector("#btn-add-facture-refresh-num-caisse")
        .addEventListener("click", () => {
          listNumCaisse(selectAddFactureNumCaisse, true);
        });

      //===== EVENT select add facture num_caisse
      $(selectAddFactureNumCaisse).on("change", function (e) {
        localStorage.setItem(e.target.id, $(this).val());
      });
    }
    //select - add facture id_client
    const selectAddFactureIdClient = modalAddFacture.querySelector(
      "#select-add-facture-id-client"
    );
    if (selectAddFactureIdClient) {
      //list all client
      await listClient(selectAddFactureIdClient);
      //load value from localstorage
      const savedSelectAddFactureIdClient = localStorage.getItem(
        selectAddFactureIdClient.id
      );
      $(selectAddFactureIdClient)
        .val(
          !savedSelectAddFactureIdClient ? "" : savedSelectAddFactureIdClient
        )
        .trigger("change");

      //===== EVENT btn add facture refresh id_client
      modalAddFacture
        .querySelector("#btn-add-facture-refresh-id-client")
        .addEventListener("click", () => {
          listClient(selectAddFactureIdClient);
        });

      //===== EVENT select add facture id_client
      $(selectAddFactureIdClient).on("change", function (e) {
        localStorage.setItem(e.target.id, $(this).val());
      });
    }
    //select - add facture id_produit
    const selectAddFactureIdProduit = modalAddFacture.querySelector(
      "#select-add-facture-id-produit"
    );
    if (selectAddFactureIdProduit) {
      //list all produit
      listProduit(selectAddFactureIdProduit);
      //===== EVENT btn add facture refresh id_produit
      modalAddFacture
        .querySelector("#btn-add-facture-refresh-id-produit")
        .addEventListener("click", () => {
          listProduit(selectAddFactureIdProduit);
        });
    }
    //input - add facture quantite_produit
    const inputAddFactureQauntiteProduit = modalAddFacture.querySelector(
      "#input-add-facture-quantite-produit"
    );

    //===== EVENT input add facture quantite_produit
    inputAddFactureQauntiteProduit.addEventListener("input", (e) => {
      e.target.value = e.target.value.replace(/[^0-9]/g, "");
    });
    //===== EVENT btn add facture add produit
    modalAddFacture
      .querySelector("#btn-add-facture-add-produit")
      .addEventListener("click", () => {
        //no produit selected
        if (!$(selectAddFactureIdProduit).val()) {
          modalAddFacture.querySelector("#message-produit-select").innerHTML =
            lang.produit_not_selected;
          setTimeout(() => {
            modalAddFacture.querySelector("#message-produit-select").innerHTML =
              "";
          }, 3000);
          return;
        }
        //quantite invalid
        else if (!inputAddFactureQauntiteProduit.value) {
          modalAddFacture.querySelector("#message-produit-select").innerHTML =
            lang.invalids_quantite;
          setTimeout(() => {
            modalAddFacture.querySelector("#message-produit-select").innerHTML =
              "";
          }, 3000);
          return;
        }

        //add produit into the card
        const spanProduit = document.createElement("span");
        spanProduit.dataset.idProduit = $(selectAddFactureIdProduit).val();
        spanProduit.dataset.quantiteProduit =
          inputAddFactureQauntiteProduit.value.trim();
        spanProduit.classList.add(
          "rounded-1",
          "bg-second",
          "p-1",
          "text-secondary",
          "form-text",
          "produit"
        );
        //span prix total
        spanProduit.dataset.prixTotal =
          Number(inputAddFactureQauntiteProduit.value) *
          Number(
            $(selectAddFactureIdProduit).select2("data")[0].element.dataset.prix
          );
        //span html
        spanProduit.innerHTML = `${
          $(selectAddFactureIdProduit).select2("data")[0].text
        }<span class="badge bg-light text-success">${inputAddFactureQauntiteProduit.value.trim()}</span><button type="button" class="btn btn-light btn-sm ms-1 btn-close-produit"><i class="fad fa-x"></i></button>`;
        //append to card
        modalAddFacture.querySelector("#card-produit").append(spanProduit);

        //add facture total
        const addFactureTotal =
          modalAddFacture.querySelector("#add-facture-total");
        addFactureTotal.dataset.value =
          Number(addFactureTotal.dataset.value) +
          Number(
            $(selectAddFactureIdProduit).select2("data")[0].element.dataset.prix
          ) *
            Number(inputAddFactureQauntiteProduit.value);
        addFactureTotal.innerHTML = formatterTotal.format(
          Number(addFactureTotal.dataset.value)
        );
        //===== EVENT btn close produit
        const btn = spanProduit.querySelector(".btn-close-produit");
        btn.addEventListener("click", () => {
          addFactureTotal.dataset.value =
            Number(addFactureTotal.dataset.value) -
            Number(btn.closest("span").dataset.prixTotal);
          addFactureTotal.innerHTML = formatterTotal.format(
            Number(addFactureTotal.dataset.value)
          );
          //remove span produit
          btn.closest("span").remove();
        });
      });
    //===== EVENT btn empty produit
    modalAddFacture
      .querySelector("#btn-add-facture-empty-produit")
      .addEventListener("click", () => {
        modalAddFacture.querySelector("#card-produit").innerHTML = "";
        modalAddFacture.querySelector("#add-facture-total").dataset.value = 0;
        modalAddFacture.querySelector("#add-facture-total").innerHTML =
          formatterNumber.format(0);
      });

    //===== EVENT btn add facture
    container
      .querySelector("#btn-add-facture")
      .addEventListener("click", () => {
        //show modal add facture
        new bootstrap.Modal(modalAddFacture).show();
      });

    //===== EVENT form add facture submit
    modalAddFacture
      .querySelector("form")
      .addEventListener("submit", async (e) => {
        //suspend submit
        e.preventDefault();
        //check validity
        if (!e.target.checkValidity()) {
          e.target.reportValidity();
          return;
        }

        //produits
        let produits = [];
        modalAddFacture
          .querySelectorAll("#card-produit .produit")
          .forEach((span) => {
            const produit = {
              id_produit: span.dataset.idProduit.trim(),
              quantite_produit: span.dataset.quantiteProduit.trim(),
            };

            produits.push(produit);
          });

        try {
          //FETH api add facture
          const apiAddFacture = await apiRequest("/entree/create_facture", {
            method: "POST",
            body: {
              date_facture: !inputAddFactureDateFacture
                ? ""
                : inputAddFactureDateFacture.value.trim(),
              id_utilisateur: !selectAddFactureIdUtilisateur
                ? ""
                : $(selectAddFactureIdUtilisateur).val().trim(),
              num_caisse: !selectAddFactureNumCaisse
                ? ""
                : $(selectAddFactureNumCaisse).val().trim(),
              id_client: $(selectAddFactureIdClient).val().trim(),
              produits: produits,
            },
          });

          //error
          if (apiAddFacture.message_type === "error") {
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
              apiAddFacture.message;
            //progress bar
            progressBar.style.transition = "width 20s linear";
            progressBar.style.width = "100%";

            //add alert
            modalAddFacture.querySelector(".modal-body").prepend(alert);

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
          else if (apiAddFacture.message_type === "invalid") {
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
              apiAddFacture.message;
            //progress bar
            progressBar.style.transition = "width 10s linear";
            progressBar.style.width = "100%";

            //add alert
            modalAddFacture.querySelector(".modal-body").prepend(alert);

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
            apiAddFacture.message;
          //progress bar
          progressBar.style.transition = "width 10s linear";
          progressBar.style.width = "100%";

          //add alert
          container
            .querySelector("#tbody-facture")
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

          //close modal
          modalAddFacture.querySelector("#btn-close-modal-add-facture").click();

          //refresh filter facture
          filterFacture(
            selectStatus.value.trim(),
            selectArrangeBy.value.trim(),
            selectOrder.value.trim(),
            dateFrom.value.trim(),
            dateTo.value.trim(),
            selectNumCaisse ? $(selectNumCaisse).val().trim() : "",
            $(selectIdUtilisateur).val().trim(),
            inputSearch.value.trim()
          );

          return;
        } catch (e) {
          console.error(e);
        }
      });

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
          modalAddFacture.querySelector(".modal-body").prepend(alert);

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

          //refresh list client
          await listClient(selectAddFactureIdClient);
          $(selectAddFactureIdClient)
            .val(apiAddClient.last_inserted)
            .trigger("change");

          return;
        } catch (e) {
          console.error(e);
        }
      });

    //======================= CORRECION FACTURE ===================
    //modal correction facture
    const modalCorrectionFacture = container.querySelector(
      "#modal-correction-facture"
    );
    //====== EVENT form correction facture submit
    modalCorrectionFacture
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
          //ligne facture
          let lf = [];
          modalCorrectionFacture
            .querySelectorAll(".div-lf")
            .forEach((divLF) => {
              const lfObject = {
                id_lf: divLF.dataset.idLf.trim(),
                id_produit: divLF.dataset.idProduit.trim(),
                quantite_produit: divLF
                  .querySelector("input[type='number']")
                  .value.trim(),
              };

              lf.push(lfObject);
            });

          //FETCH api correction facture
          const apiCorrectionFacture = await apiRequest(
            "/sortie/correction_facture",
            {
              method: "POST",
              body: {
                num_facture: modalCorrectionFacture
                  .querySelector("#correction-facture-num-facture")
                  .textContent.trim(),
                date_ds: !modalCorrectionFacture.querySelector(
                  "#input-correction-facture-date-ds"
                )
                  ? ""
                  : modalCorrectionFacture
                      .querySelector("#input-correction-facture-date-ds")
                      .value.trim(),
                id_utilisateur: !modalCorrectionFacture.querySelector(
                  "#select-correction-facture-id-utilisateur"
                )
                  ? ""
                  : $(
                      modalCorrectionFacture.querySelector(
                        "#select-correction-facture-id-utilisateur"
                      )
                    )
                      .val()
                      .trim(),
                lf: lf,
              },
            }
          );

          //error
          if (apiCorrectionFacture.message_type === "error") {
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
              apiCorrectionFacture.message;
            //progress bar
            progressBar.style.transition = "width 20s linear";
            progressBar.style.width = "100%";

            //add alert
            modalCorrectionFacture.querySelector(".modal-body").prepend(alert);

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
          else if (apiCorrectionFacture.message_type === "invalid") {
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
              apiCorrectionFacture.message;
            //progress bar
            progressBar.style.transition = "width 10s linear";
            progressBar.style.width = "100%";

            //add alert
            modalCorrectionFacture.querySelector(".modal-body").prepend(alert);

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
            apiCorrectionFacture.message;
          //progress bar
          progressBar.style.transition = "width 10s linear";
          progressBar.style.width = "100%";

          //add alert
          container
            .querySelector("#tbody-facture")
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

          //close modal
          modalCorrectionFacture
            .querySelector("#btn-close-modal-correction-facture")
            .click();

          return;
        } catch (e) {
          console.error(e);
        }
      });

    //========================== DELETE FACTURE =====================
    //btn delete facture
    const btnDeleteFacture = container.querySelector("#btn-delete-facture");
    //===== EVENT btn delete facture
    if (btnDeleteFacture) {
      btnDeleteFacture.addEventListener("click", () => {
        //modal delete facture
        const modalDeleteFacture = container.querySelector(
          "#modal-delete-facture"
        );

        //selected facture
        const selectedFacture = container.querySelectorAll(
          "#tbody-facture input[type='checkbox']:checked"
        );

        //no selection
        if (selectedFacture.length <= 0) {
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
            lang.entree_nums_facture_empty;
          //progress bar
          progressBar.style.transition = "width 10s linear";
          progressBar.style.width = "100%";

          //add alert
          container
            .querySelector("#tbody-facture")
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
          return;
        }

        //modal message 1
        if (selectedFacture.length === 1) {
          modalDeleteFacture.querySelector(".message").innerHTML =
            lang.question_delete_facture_1.replace(
              ":field",
              selectedFacture[0].closest("tr").dataset.numFacture
            );
        }
        //modal message plur
        else {
          modalDeleteFacture.querySelector(".message").innerHTML =
            lang.question_delete_facture_plur.replace(
              ":field",
              selectedFacture.length
            );
        }

        //show modal delete facture
        new bootstrap.Modal(modalDeleteFacture).show();

        //==== EVENT btn confirm modal delete facture
        modalDeleteFacture
          .querySelector("#btn-confirm-modal-delete-facture")
          .addEventListener("click", async () => {
            try {
              //nums_facture
              let nums_facture = [...selectedFacture];
              nums_facture = nums_facture.map(
                (selected) => selected.closest("tr").dataset.numFacture
              );

              //FETCH api delete facture
              const apiDeleteFacture = await apiRequest(
                "/entree/delete_all_facture",
                {
                  method: "PUT",
                  body: {
                    nums_facture: nums_facture,
                  },
                }
              );

              //error
              if (apiDeleteFacture.message_type === "error") {
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
                  .classList.add("fa-exclamation-circle");
                //message
                alert.querySelector(".alert-message").innerHTML =
                  apiDeleteFacture.message;
                //progress bar
                progressBar.style.transition = "width 20s linear";
                progressBar.style.width = "100%";

                //add alert
                modalDeleteFacture.querySelector(".modal-body").prepend(alert);

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
              else if (apiDeleteFacture.message_type === "invalid") {
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
                  apiDeleteFacture.message;
                //progress bar
                progressBar.style.transition = "width 10s linear";
                progressBar.style.width = "100%";
                //add alert
                modalDeleteFacture.querySelector(".modal-body").prepend(alert);
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
                apiDeleteFacture.message;
              //progress bar
              progressBar.style.transition = "width 10s linear";
              progressBar.style.width = "100%";

              //add alert
              container
                .querySelector("#tbody-facture")
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

              //auto hide modal
              modalDeleteFacture
                .querySelector("#btn-close-modal-delete-facture")
                .click();

              //refresh filter facture
              filterFacture(
                selectStatus.value.trim(),
                selectArrangeBy.value.trim(),
                selectOrder.value.trim(),
                dateFrom.value.trim(),
                dateTo.value.trim(),
                selectNumCaisse ? $(selectNumCaisse).val().trim() : "",
                $(selectIdUtilisateur).val().trim(),
                inputSearch.value.trim()
              );

              return;
            } catch (e) {
              console.error(e);
            }
          });
      });
    }

    //========================== DELETE PERMANENT FACTURE =====================
    //btn delete permanent facture
    const btnDeletePermanentFacture = container.querySelector(
      "#btn-delete-permanent-facture"
    );
    //===== EVENT btn delete permanent facture
    if (btnDeletePermanentFacture) {
      btnDeletePermanentFacture.addEventListener("click", () => {
        //modal delete facture
        const modalDeleteFacture = container.querySelector(
          "#modal-delete-facture"
        );

        //selected facture
        const selectedFacture = container.querySelectorAll(
          "#tbody-facture input[type='checkbox']:checked"
        );

        //no selection
        if (selectedFacture.length <= 0) {
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
            lang.entree_nums_facture_empty;
          //progress bar
          progressBar.style.transition = "width 10s linear";
          progressBar.style.width = "100%";

          //add alert
          container
            .querySelector("#tbody-facture")
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
          return;
        }

        //modal message 1
        if (selectedFacture.length === 1) {
          modalDeleteFacture.querySelector(".message").innerHTML =
            lang.question_delete_permanent_facture_1.replace(
              ":field",
              selectedFacture[0].closest("tr").dataset.numFacture
            );
        }
        //modal message plur
        else {
          modalDeleteFacture.querySelector(".message").innerHTML =
            lang.question_delete_permanent_facture_plur.replace(
              ":field",
              selectedFacture.length
            );
        }

        //show modal delete facture
        new bootstrap.Modal(modalDeleteFacture).show();

        //==== EVENT btn confirm modal delete facture
        modalDeleteFacture
          .querySelector("#btn-confirm-modal-delete-facture")
          .addEventListener("click", async () => {
            try {
              //nums_facture
              let nums_facture = [...selectedFacture];
              nums_facture = nums_facture.map(
                (selected) => selected.closest("tr").dataset.numFacture
              );

              //FETCH api delete facture
              const apiDeleteFacture = await apiRequest(
                "/entree/delete_permanent_all_facture",
                {
                  method: "DELETE",
                  body: {
                    nums_facture: nums_facture,
                  },
                }
              );

              //error
              if (apiDeleteFacture.message_type === "error") {
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
                  .classList.add("fa-exclamation-circle");
                //message
                alert.querySelector(".alert-message").innerHTML =
                  apiDeleteFacture.message;
                //progress bar
                progressBar.style.transition = "width 20s linear";
                progressBar.style.width = "100%";

                //add alert
                modalDeleteFacture.querySelector(".modal-body").prepend(alert);

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
              else if (apiDeleteFacture.message_type === "invalid") {
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
                  apiDeleteFacture.message;
                //progress bar
                progressBar.style.transition = "width 10s linear";
                progressBar.style.width = "100%";
                //add alert
                modalDeleteFacture.querySelector(".modal-body").prepend(alert);
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
                apiDeleteFacture.message;
              //progress bar
              progressBar.style.transition = "width 10s linear";
              progressBar.style.width = "100%";

              //add alert
              container
                .querySelector("#tbody-facture")
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

              //auto hide modal
              modalDeleteFacture
                .querySelector("#btn-close-modal-delete-facture")
                .click();

              //refresh filter facture
              filterFacture(
                selectStatus.value.trim(),
                selectArrangeBy.value.trim(),
                selectOrder.value.trim(),
                dateFrom.value.trim(),
                dateTo.value.trim(),
                selectNumCaisse ? $(selectNumCaisse).val().trim() : "",
                $(selectIdUtilisateur).val().trim(),
                inputSearch.value.trim()
              );

              return;
            } catch (e) {
              console.error(e);
            }
          });
      });
    }

    //========================== RESTORE FACTURE =========================
    //btn restore facture
    const btnRestoreFacture = container.querySelector("#btn-restore-facture");
    //===== EVENT btn restore facture
    if (btnRestoreFacture) {
      btnRestoreFacture.addEventListener("click", () => {
        //modal restore facture
        const modalRestoreFacture = container.querySelector(
          "#modal-restore-facture"
        );

        //selected facture
        const selectedFacture = container.querySelectorAll(
          "#tbody-facture input[type='checkbox']:checked"
        );

        //no selection
        if (selectedFacture.length <= 0) {
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
            lang.entree_nums_facture_empty;
          //progress bar
          progressBar.style.transition = "width 10s linear";
          progressBar.style.width = "100%";

          //add alert
          container
            .querySelector("#tbody-facture")
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
          return;
        }

        //modal message 1
        if (selectedFacture.length === 1) {
          modalRestoreFacture.querySelector(".message").innerHTML =
            lang.question_restore_facture_1.replace(
              ":field",
              selectedFacture[0].closest("tr").dataset.numFacture
            );
        }
        //modal message plur
        else {
          modalRestoreFacture.querySelector(".message").innerHTML =
            lang.question_restore_facture_plur.replace(
              ":field",
              selectedFacture.length
            );
        }

        //show modal restore facture
        new bootstrap.Modal(modalRestoreFacture).show();

        //==== EVENT btn confirm modal restore facture
        modalRestoreFacture
          .querySelector("#btn-confirm-modal-restore-facture")
          .addEventListener("click", async () => {
            try {
              //nums_facture
              let nums_facture = [...selectedFacture];
              nums_facture = nums_facture.map(
                (selected) => selected.closest("tr").dataset.numFacture
              );

              //FETCH api restore facture
              const apiRestoreFacture = await apiRequest(
                "/entree/restore_all_facture",
                {
                  method: "PUT",
                  body: {
                    nums_facture: nums_facture,
                  },
                }
              );

              //error
              if (apiRestoreFacture.message_type === "error") {
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
                  .classList.add("fa-exclamation-circle");
                //message
                alert.querySelector(".alert-message").innerHTML =
                  apiRestoreFacture.message;
                //progress bar
                progressBar.style.transition = "width 20s linear";
                progressBar.style.width = "100%";

                //add alert
                modalRestoreFacture.querySelector(".modal-body").prepend(alert);

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
              else if (apiRestoreFacture.message_type === "invalid") {
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
                  apiRestoreFacture.message;
                //progress bar
                progressBar.style.transition = "width 10s linear";
                progressBar.style.width = "100%";
                s;

                //add alert
                modalRestoreFacture.querySelector(".modal-body").prepend(alert);

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
                apiRestoreFacture.message;
              //progress bar
              progressBar.style.transition = "width 10s linear";
              progressBar.style.width = "100%";

              //add alert
              container
                .querySelector("#tbody-facture")
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

              //auto hide modal
              modalRestoreFacture
                .querySelector("#btn-close-modal-restore-facture")
                .click();

              //refresh filter facture
              filterFacture(
                selectStatus.value.trim(),
                selectArrangeBy.value.trim(),
                selectOrder.value.trim(),
                dateFrom.value.trim(),
                dateTo.value.trim(),
                selectNumCaisse ? $(selectNumCaisse).val().trim() : "",
                $(selectIdUtilisateur).val().trim(),
                inputSearch.value.trim()
              );

              return;
            } catch (e) {
              console.error(e);
            }
          });
      });
    }
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
          tdStatus.textContent = lang.deleted.toLowerCase();
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
        //btn correct facture
        const btnCorrectFacture = document.createElement("button");
        btnCorrectFacture.type = "button";
        btnCorrectFacture.classList.add(
          "btn-light",
          "btn",
          "btn-sm",
          "text-danger",
          "btn-correct-facture"
        );
        btnCorrectFacture.innerHTML = "<i class='fad fa-circle-minus'></i>";
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
        divActions.append(btnCorrectFacture, btnPrintFacture);
        tdActions.append(divActions);

        //append
        tr.append(
          tdNumFacture,
          tdDateFacture,
          tdMontantFacture,
          tdIdClient,
          tdIdUtilisateur,
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

      //foreach tr
      let selectedRow = null;
      const allTr = tbodyFacture.querySelectorAll("tr");
      allTr.forEach((tr) => {
        //===================== CORRECTION FACTURE ==================
        //modal correction facture
        const modalCorrectionFacture = container.querySelector(
          "#modal-correction-facture"
        );

        //===== EVENT btn correct facture
        tr.querySelector(".btn-correct-facture").addEventListener(
          "click",
          async () => {
            //num_facture
            modalCorrectionFacture.querySelector(
              "#correction-facture-num-facture"
            ).innerHTML = tr.dataset.numFacture;
            //select - correction facture id_utilisateur
            const selectCorrectionFactureIdUtilisateur =
              modalCorrectionFacture.querySelector(
                "#select-correction-facture-id-utilisateur"
              );
            if (selectCorrectionFactureIdUtilisateur) {
              $(selectCorrectionFactureIdUtilisateur).select2({
                theme: "bootstrap-5",
                placeholder: lang.select.toLowerCase(),
                dropdownParent: $(modalCorrectionFacture),
              });
              listUser(selectCorrectionFactureIdUtilisateur, true);

              //===== EVENT refresh list user
              modalCorrectionFacture
                .querySelector("#btn-correction-facture-refresh-id-utilisateur")
                .addEventListener("click", () => {
                  listUser(selectCorrectionFactureIdUtilisateur, true);
                });
            }

            //list ligne_facture
            await listLigneFacture(
              tr.dataset.numFacture,
              modalCorrectionFacture.querySelector("#div-ligne-facture")
            );
            //===== EVENT btn refresh list ligne_caisse
            modalCorrectionFacture
              .querySelector("#btn-correction-facture-refresh-ligne-facture")
              .addEventListener("click", () => {
                listLigneFacture(
                  tr.dataset.numFacture,
                  modalCorrectionFacture.querySelector("#div-ligne-facture")
                );
              });

            //show modal correction facture
            new bootstrap.Modal(modalCorrectionFacture).show();
          }
        );

        //===== EVENT btn print facture
        tr.querySelector(".btn-print-facture").addEventListener(
          "click",
          async () => {
            try {
              //FETCH api print facture
              const apiPrintFacture = await apiRequest(
                `/entree/print_facture?num_facture=${tr.dataset.numFacture}`
              );

              //error
              if (apiPrintFacture.message_type === "error") {
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
                container
                  .querySelector("#tbody-facture")
                  .closest("div")
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
              else if (apiPrintFacture.message_type === "invalid") {
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
                  apiPrintFacture.message;
                //progress bar
                progressBar.style.transition = "width 10s linear";
                progressBar.style.width = "100%";

                //add alert
                container
                  .querySelector("#tbody-facture")
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

                return;
              }

              //download facture
              const a = document.createElement("a");
              a.href = `data:application/pdf;base64,${apiPrintFacture.pdf}`;
              a.download = apiPrintFacture.file_name;
              a.click();

              return;
            } catch (e) {
              console.error(e);
            }
          }
        );

        //===== EVENT tr selection
        tr.addEventListener("click", () => {
          //remove selection
          if (selectedRow && selectedRow === tr) {
            tr.classList.remove("active");
            selectedRow = null;

            //remove table lf facture num
            container.querySelector("#table-lf-facture-num").innerHTML = "";

            //remove tbody lf
            container.querySelector("#tbody-lf").innerHTML = `  <tr>
                                                            <td colspan="9">
                                                                <span class="bg-second placeholder w-100 rounded-1" style="height: 2vh !important;"></span>
                                                            </td>
                                                        </tr>`;
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

            //add table lf facture num
            container.querySelector("#table-lf-facture-num").innerHTML =
              tr.dataset.numFacture;

            //list facture connection
            listConnectionFacture(tr.dataset.numFacture.trim());
          }
        });
      });

      //===== EVENT check all
      const inputCheckAll = container.querySelector("#check-all-facture");
      if (inputCheckAll) {
        inputCheckAll.addEventListener("change", (e) => {
          tbodyFacture
            .querySelectorAll("input[type='checkbox']")
            .forEach((checkbox) => {
              checkbox.checked = e.target.checked;
            });
        });
      }
    } catch (e) {
      console.error(e);
    }
  }
  //function - list num_caisse
  async function listNumCaisse(select, add = false) {
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
      if (!add)
        select.innerHTML = `<option></option>
                          <option value='all' selected>${lang.all}</option>`;
      else select.innerHTML = `<option></option>`;

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
  //function - list user
  async function listUser(select, add = false) {
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
      if (!add)
        select.innerHTML = `<option></option>
                          <option value='all' selected>${lang.all}</option>`;
      else select.innerHTML = `<option></option>`;

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
  //function - list client
  async function listClient(select) {
    try {
      //FETCH api list all client
      const apiListAllClient = await apiRequest("/client/list_all_client");

      //error
      if (apiListAllClient.message_type === "error") {
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
          apiListAllClient.message;
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

      //success api list client
      select.innerHTML = `<option></option>`;

      apiListAllClient.data.forEach((line) => {
        const option = document.createElement("option");
        option.value = line.id_client;
        option.innerText = `${line.id_client} - ${line.fullname}`;

        select.append(option);
      });
    } catch (e) {
      console.error(e);
    }
  }
  //function - list produit
  async function listProduit(select) {
    try {
      //FETCH api list all produit
      const apiListAllProduit = await apiRequest("/produit/list_all_produit");

      //error
      if (apiListAllProduit.message_type === "error") {
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
          apiListAllProduit.message;
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

      //success api list produit
      select.innerHTML = `<option></option>`;

      apiListAllProduit.data.forEach((line) => {
        const option = document.createElement("option");
        option.value = line.id_produit;
        option.innerText = `${line.id_produit} - ${
          line.libelle_produit
        } (${formatterTotal.format(Number(line.prix_produit))})`;
        option.dataset.prix = line.prix_produit;

        select.append(option);
      });
    } catch (e) {
      console.error(e);
    }
  }
  //function - list ligne_facture
  async function listLigneFacture(numFacture, div) {
    try {
      //FETCH api list ligne_facture
      const apiListLigneFacture = await apiRequest(
        `/entree/list_ligne_facture?num_facture=${numFacture}`
      );

      //error
      if (apiListLigneFacture.message_type === "error") {
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
          apiListLigneFacture.message;
        //progress bar
        progressBar.style.transition = "width 20s linear";
        progressBar.style.width = "100%";

        //add alert
        div.prepend(alert);

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
      else if (apiListLigneFacture.message_type === "invalid") {
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
          apiListLigneFacture.message;
        //progress bar
        progressBar.style.transition = "width 10s linear";
        progressBar.style.width = "100%";

        //add alert
        div.prepend(alert);

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

      //success api list ligne_facture
      div.innerHTML = "";
      apiListLigneFacture.data.forEach((line) => {
        const divLF = document.createElement("div");
        divLF.classList.add(
          "p-2",
          "d-flex",
          "gap-2",
          "bg-second",
          "rounded-1",
          "text-secondary",
          "div-lf",
          "flex-wrap"
        );
        divLF.dataset.prixTotal = line.prix_total;
        divLF.dataset.idLf = line.id_lf;
        divLF.dataset.prix = line.prix;
        divLF.dataset.idProduit = line.id_produit;
        divLF.innerHTML = `${line.id_lf} - ${
          line.libelle_produit
        } <span class='total-prix me-2'>(${formatterTotal.format(
          Number(divLF.dataset.prixTotal)
        )})</span><input class='form-control form-control-sm' type='number' min='0' max='${
          line.quantite_produit
        }' value='${line.quantite_produit}'>`;

        //append div lf
        div.append(divLF);
      });

      //correction facture total
      const correctionFactureTotal = container.querySelector(
        "#correction-facture-total"
      );
      let prixTotal = 0;
      //foreach divLF
      div.querySelectorAll(".div-lf").forEach((divLF) => {
        //prix total
        prixTotal += Number(divLF.dataset.prixTotal);

        //===== EVENT input quantite_produit
        divLF
          .querySelector("input[type='number']")
          .addEventListener("input", (e) => {
            e.target.value = e.target.value.replace(/[^0-9]/g, "");

            //div prix total
            divLF.dataset.prixTotal =
              Number(divLF.dataset.prix) * Number(e.target.value);
            divLF.querySelector(
              ".total-prix"
            ).innerHTML = `(${formatterTotal.format(
              Number(divLF.dataset.prixTotal)
            )})`;

            //prix total
            let prixTotal0 = 0;
            div.querySelectorAll(".div-lf").forEach((divLF0) => {
              //prix total 0
              prixTotal0 += Number(divLF0.dataset.prixTotal);
            });
            correctionFactureTotal.innerHTML =
              formatterTotal.format(prixTotal0);
          });
      });
      correctionFactureTotal.innerHTML = formatterTotal.format(prixTotal);
    } catch (e) {
      console.error(e);
    }
  }
  //function - list connection facture
  async function listConnectionFacture(numFacture) {
    //tbody lf
    const tbodyLF = container.querySelector("#tbody-lf");
    try {
      //FETCH api list connection facture
      const apiListConnectionFacture = await apiRequest(
        `/entree/list_connection_facture?num_facture=${numFacture}`
      );

      //error
      if (apiListConnectionFacture.message_type === "error") {
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
          apiListConnectionFacture.message;
        //progress bar
        progressBar.style.transition = "width 20s linear";
        progressBar.style.width = "100%";

        //add alert
        tbodyLF.closest("div").prepend(alert);

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
      else if (apiListConnectionFacture.message_type === "invalid") {
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
          apiListConnectionFacture.message;
        //progress bar
        progressBar.style.transition = "width 10s linear";
        progressBar.style.width = "100%";

        //add alert
        tbodyLF.closest("div").prepend(alert);

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

      //===== TABLE ligne_facture

      //set table list connection facture
      tbodyLF.innerHTML = "";
      //lf
      apiListConnectionFacture.lf.forEach((line) => {
        const tr = document.createElement("tr");

        //td - id_lf
        const tdIdLF = document.createElement("td");
        tdIdLF.textContent = line.id_lf;
        tdIdLF.classList.add("text-center");

        //td - libelle_produit
        const tdLibelle = document.createElement("td");
        tdLibelle.textContent = line.libelle_produit;
        tdLibelle.classList.add("text-center");

        //td - quantite_produit
        const tdQuantite = document.createElement("td");
        tdQuantite.textContent = formatterNumber.format(
          Number(line.quantite_produit)
        );
        tdQuantite.classList.add("text-center");

        //td - prix
        const tdPrix = document.createElement("td");
        tdPrix.textContent = formatterNumber.format(Number(line.prix));
        tdPrix.classList.add("text-center");

        //td - montant
        const tdMontant = document.createElement("td");
        tdMontant.textContent = formatterNumber.format(Number(line.prix_total));
        tdMontant.classList.add("text-center");

        tr.append(tdIdLF, tdLibelle, tdQuantite, tdPrix, tdMontant);
        tbodyLF.appendChild(tr);
      });
      //ae
      if (apiListConnectionFacture.autre_entree.length > 0) {
        const trCorrectionAe = document.createElement("tr");
        trCorrectionAe.innerHTML = `<td colspan='5' class='text-center text-light p-2 bg-secondary'><b>${
          lang.correction
        }</b> (${lang.inflow.toLowerCase()})</td>`;

        tbodyLF.append(trCorrectionAe);

        //list correction ae
        apiListConnectionFacture.autre_entree.forEach((line) => {
          const tr = document.createElement("tr");

          //td - id_ae
          const tdIdAe = document.createElement("td");
          tdIdAe.textContent = line.num_ae;
          tdIdAe.classList.add("text-center");

          //td - libelle_ae
          const tdLibelle = document.createElement("td");
          tdLibelle.textContent = line.libelle_ae;
          tdLibelle.classList.add("text-center");

          //td - quantite_ae
          const tdQuantite = document.createElement("td");
          tdQuantite.textContent = 1;
          tdQuantite.classList.add("text-center");

          //td - prix
          const tdPrix = document.createElement("td");
          tdPrix.textContent = formatterNumber.format(Number(line.montant_ae));
          tdPrix.classList.add("text-center");

          //td - montant
          const tdMontant = document.createElement("td");
          tdMontant.textContent = formatterNumber.format(
            Number(line.montant_ae)
          );
          tdMontant.classList.add("text-center");

          tr.append(tdIdAe, tdLibelle, tdQuantite, tdPrix, tdMontant);
          tbodyLF.appendChild(tr);
        });
      }
      //sortie
      if (apiListConnectionFacture.sortie.length > 0) {
        const trCorrectionDs = document.createElement("tr");
        trCorrectionDs.innerHTML = `<td colspan='5' class='text-center text-light p-2 bg-secondary'><b>${
          lang.correction
        }</b> (${lang.outflow.toLowerCase()})</td>`;

        tbodyLF.append(trCorrectionDs);

        //list correction ds
        apiListConnectionFacture.sortie.forEach((line) => {
          const tr = document.createElement("tr");

          //td - id_ds
          const tdIdDs = document.createElement("td");
          tdIdDs.textContent = line.num_ds;
          tdIdDs.classList.add("text-center");

          //td - libelle_ds
          const tdLibelle = document.createElement("td");
          tdLibelle.textContent = line.libelle_article;
          tdLibelle.classList.add("text-center");

          //td - quantite_ds
          const tdQuantite = document.createElement("td");
          tdQuantite.textContent = 1;
          tdQuantite.classList.add("text-center");

          //td - prix
          const tdPrix = document.createElement("td");
          tdPrix.textContent = formatterNumber.format(
            Number(line.prix_article)
          );
          tdPrix.classList.add("text-center");

          //td - montant
          const tdMontant = document.createElement("td");
          tdMontant.textContent = formatterNumber.format(
            Number(line.prix_article)
          );
          tdMontant.classList.add("text-center");

          tr.append(tdIdDs, tdLibelle, tdQuantite, tdPrix, tdMontant);
          tbodyLF.appendChild(tr);
        });
      }
    } catch (e) {
      console.error(e);
    }
  }
});
