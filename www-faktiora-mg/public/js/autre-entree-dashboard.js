document.addEventListener("DOMContentLoaded", async () => {
  //template real content
  const templateRealContent = document.getElementById("template-autre-entree");

  //   //config
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
      filterAE(
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
      filterAE(
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
      filterAE(
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
      filterAE(
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
      filterAE(
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
      filterAE(
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
        filterAE(
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
      filterAE(
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

    //======================== FILTER AE ==================
    await filterAE(
      selectStatus.value.trim(),
      selectArrangeBy.value.trim(),
      selectOrder.value.trim(),
      dateFrom.value.trim(),
      dateTo.value.trim(),
      selectNumCaisse ? $(selectNumCaisse).val().trim() : "",
      $(selectIdUtilisateur).val().trim(),
      inputSearch.value.trim()
    );

    //========================= ADD AE =========================
    //modal add ae
    const modalAddAe = container.querySelector("#modal-add-ae");
    //modal add ae - intialize select 2
    $(modalAddAe.querySelectorAll(".select2")).select2({
      theme: "bootstrap-5",
      placeholder: lang.select.toLowerCase(),
      dropdownParent: $(modalAddAe),
    });
    //input - add ae libelle_ae
    const inputAddAeLibelleAe = modalAddAe.querySelector(
      "#input-add-ae-libelle-ae"
    );
    const savedInputAddAeLibelleAe = localStorage.getItem(
      inputAddAeLibelleAe.id
    );
    inputAddAeLibelleAe.value = !savedInputAddAeLibelleAe
      ? ""
      : savedInputAddAeLibelleAe;
    //input - add ae montant_ae
    const inputAddAeMontantAe = modalAddAe.querySelector(
      "#input-add-ae-montant-ae"
    );
    const savedInputAddAeMontantAe = localStorage.getItem(
      inputAddAeMontantAe.id
    );
    inputAddAeMontantAe.value = !savedInputAddAeMontantAe
      ? "1"
      : savedInputAddAeMontantAe;
    inputAddAeMontantAe.dataset.val = inputAddAeMontantAe.value;
    //input - add ae date_ae
    const inputAddAeDateAe = modalAddAe.querySelector("#input-add-ae-date-ae");
    if (inputAddAeDateAe) {
      const savedInputAddAeDateAe = localStorage.getItem(inputAddAeDateAe.id);
      inputAddAeDateAe.value = !savedInputAddAeDateAe
        ? ""
        : savedInputAddAeDateAe;

      //===== EVENT input add ae date_ae
      inputAddAeDateAe.addEventListener("input", (e) => {
        localStorage.setItem(e.target.id, e.target.value);
      });
    }
    //select - add ae id_utilisateur
    const selectAddAeIdUtilisateur = modalAddAe.querySelector(
      "#select-add-ae-id-utilisateur"
    );
    if (selectAddAeIdUtilisateur) {
      //list all user
      await listUser(selectAddAeIdUtilisateur, true);
      //load value from localstorage
      const savedSelectAddAeIdUtilisateur = localStorage.getItem(
        selectAddAeIdUtilisateur.id
      );
      $(selectAddAeIdUtilisateur)
        .val(
          !savedSelectAddAeIdUtilisateur ? "" : savedSelectAddAeIdUtilisateur
        )
        .trigger("change");

      //===== EVENT btn add ae refresh id_utilisateur
      modalAddAe
        .querySelector("#btn-add-ae-refresh-id-utilisateur")
        .addEventListener("click", () => {
          listUser(selectAddAeIdUtilisateur, true);
        });
      //===== EVENT select add ae id_utilisateur
      $(selectAddAeIdUtilisateur).on("change", function (e) {
        localStorage.setItem(e.target.id, $(this).val());
      });
    }
    //select - add ae num_caisse
    const selectAddAeNumCaisse = modalAddAe.querySelector(
      "#select-add-ae-num-caisse"
    );
    if (selectAddAeNumCaisse) {
      //list num_caisse
      await listNumCaisse(selectAddAeNumCaisse, true);
      //load value from localstorage
      const savedSelectAddAeNumCaisse = localStorage.getItem(
        selectAddAeNumCaisse.id
      );
      $(selectAddAeNumCaisse)
        .val(!savedSelectAddAeNumCaisse ? "" : savedSelectAddAeNumCaisse)
        .trigger("change");
      //===== EVENT btn add ae refresh num_caisse
      modalAddAe
        .querySelector("#btn-add-ae-refresh-num-caisse")
        .addEventListener("click", () => {
          listNumCaisse(selectAddAeNumCaisse, true);
        });

      //===== EVENT select add ae num_caisse
      $(selectAddAeNumCaisse).on("change", function (e) {
        localStorage.setItem(e.target.id, $(this).val());
      });
    }

    //===== EVENT input add ae libelle_ae
    inputAddAeLibelleAe.addEventListener("input", (e) => {
      e.target.value = e.target.value.replace("  ", " ");

      localStorage.setItem(e.target.id, e.target.value);
    });
    //===== EVENT input add ae montant_ae
    inputAddAeMontantAe.addEventListener("input", (e) => {
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
    inputAddAeMontantAe.addEventListener("blur", (e) => {
      if (e.target.value.endsWith(",")) {
        e.target.value += "0";
      }
      e.target.value = formatterNumber.format(
        e.target.value.replace(/[\u202F\u00A0 ]/g, "").replace(",", ".")
      );
      //save to local storage
      localStorage.setItem(e.target.id, e.target.value);
    });

    //===== EVENT btn add ae
    container.querySelector("#btn-add-ae").addEventListener("click", () => {
      //show modal add ae
      new bootstrap.Modal(modalAddAe).show();
    });

    //===== EVENT form add ae submit
    modalAddAe.querySelector("form").addEventListener("submit", async (e) => {
      //suspend submit
      e.preventDefault();
      //check validity
      if (!e.target.checkValidity()) {
        e.target.reportValidity();
        return;
      }

      try {
        //FETH api add ae
        const apiAddAe = await apiRequest("/entree/create_autre_entree", {
          method: "POST",
          body: {
            libelle_ae: inputAddAeLibelleAe.value.trim(),
            montant_ae: inputAddAeMontantAe.value
              .replace(/[\u202F\u00A0 ]/g, "")
              .replace(",", "."),
            date_ae: !inputAddAeDateAe ? "" : inputAddAeDateAe.value.trim(),
            id_utilisateur: !selectAddAeIdUtilisateur
              ? ""
              : $(selectAddAeIdUtilisateur).val().trim(),
            num_caisse: !selectAddAeNumCaisse
              ? ""
              : $(selectAddAeNumCaisse).val().trim(),
          },
        });

        //error
        if (apiAddAe.message_type === "error") {
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
          alert.querySelector(".alert-message").innerHTML = apiAddAe.message;
          //progress bar
          progressBar.style.transition = "width 20s linear";
          progressBar.style.width = "100%";
          //add alert
          modalAddAe.querySelector(".modal-body").prepend(alert);
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
        else if (apiAddAe.message_type === "invalid") {
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
          alert.querySelector(".alert-message").innerHTML = apiAddAe.message;
          //progress bar
          progressBar.style.transition = "width 10s linear";
          progressBar.style.width = "100%";
          //add alert
          modalAddAe.querySelector(".modal-body").prepend(alert);
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
        alert.querySelector(".alert-message").innerHTML = apiAddAe.message;
        //progress bar
        progressBar.style.transition = "width 10s linear";
        progressBar.style.width = "100%";
        //add alert
        container.querySelector("#tbody-ae").closest("div").prepend(alert);
        //progress launch animation
        setTimeout(() => {
          progressBar.style.width = "0%";
        }, 10);
        //auto close alert
        setTimeout(() => {
          alert.querySelector(".btn-close").click();
        }, 10000);

        //close modal
        modalAddAe.querySelector("#btn-close-modal-add-ae").click();

        //refresh filter ae
        filterAE(
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

    //============================ UPDATE AE =========================
    //modal update ae
    const modalUpdateAe = container.querySelector("#modal-update-ae");

    //===== EVENT form update ae submit
    modalUpdateAe
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
          //FETCH api update ae
          const apiUpdateAe = await apiRequest("/entree/update_autre_entree", {
            method: "PUT",
            body: {
              num_ae: modalUpdateAe
                .querySelector("#update-ae-num-ae")
                .textContent.trim(),
              libelle_ae: modalUpdateAe
                .querySelector("#input-update-ae-libelle-ae")
                .value.trim(),
              date_ae: !modalUpdateAe.querySelector("#input-update-ae-date-ae")
                ? ""
                : modalUpdateAe
                    .querySelector("#input-update-ae-date-ae")
                    .value.trim(),
            },
          });

          //error
          if (apiUpdateAe.message_type === "error") {
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
              apiUpdateAe.message;
            //progress bar
            progressBar.style.transition = "width 20s linear";
            progressBar.style.width = "100%";

            //add alert
            modalUpdateAe.querySelector(".modal-body").prepend(alert);

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
          else if (apiUpdateAe.message_type === "invalid") {
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
              apiUpdateAe.message;
            //progress bar
            progressBar.style.transition = "width 10s linear";
            progressBar.style.width = "100%";

            //add alert
            modalUpdateAe.querySelector(".modal-body").prepend(alert);

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
          alert.querySelector(".alert-message").innerHTML = apiUpdateAe.message;
          //progress bar
          progressBar.style.transition = "width 10s linear";
          progressBar.style.width = "100%";

          //add alert
          container.querySelector("#tbody-ae").closest("div").prepend(alert);

          //progress launch animation
          setTimeout(() => {
            progressBar.style.width = "0%";
          }, 10);
          //auto close alert
          setTimeout(() => {
            alert.querySelector(".btn-close").click();
          }, 10000);

          //close modal
          modalUpdateAe.querySelector("#btn-close-modal-update-ae").click();

          //refresh filter ae
          filterAE(
            selectStatus.value.trim(),
            selectArrangeBy.value.trim(),
            selectOrder.value.trim(),
            dateFrom.value.trim(),
            dateTo.value.trim(),
            selectNumCaisse ? $(selectNumCaisse).val().trim() : "",
            $(selectIdUtilisateur).val().trim(),
            inputSearch.value.trim()
          );
        } catch (e) {
          console.error(e);
        }
      });

    //===================== CORRECTION AE INFLOW =================
    //modal correction ae inflow
    const modalCorrectionAeInflow = container.querySelector(
      "#modal-correction-ae-inflow"
    );

    //===== EVENT form correction ae inflow submit
    modalCorrectionAeInflow
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
          //FETH api correction ae inflow
          const apiCorectionAeInflow = await apiRequest(
            "/entree/correction_autre_entree",
            {
              method: "POST",
              body: {
                num_ae: modalCorrectionAeInflow
                  .querySelector("#correction-ae-inflow-num-ae")
                  .textContent.trim(),
                libelle_ae: modalCorrectionAeInflow
                  .querySelector("#input-correction-ae-inflow-libelle-ae")
                  .value.trim(),
                montant_ae: modalCorrectionAeInflow
                  .querySelector("#input-correction-ae-inflow-montant-ae")
                  .value.replace(/[\u202F\u00A0 ]/g, "")
                  .replace(",", "."),
                date_ae: !modalCorrectionAeInflow.querySelector(
                  "#input-correction-ae-inflow-date-ae"
                )
                  ? ""
                  : modalCorrectionAeInflow
                      .querySelector("#input-correction-ae-inflow-date-ae")
                      .value.trim(),
                id_utilisateur: !modalCorrectionAeInflow.querySelector(
                  "#select-correction-ae-inflow-id-utilisateur"
                )
                  ? ""
                  : $(
                      modalCorrectionAeInflow.querySelector(
                        "#select-correction-ae-inflow-id-utilisateur"
                      )
                    )
                      .val()
                      .trim(),
              },
            }
          );

          //error
          if (apiCorectionAeInflow.message_type === "error") {
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
              apiCorectionAeInflow.message;
            //progress bar
            progressBar.style.transition = "width 20s linear";
            progressBar.style.width = "100%";

            //add alert
            modalCorrectionAeInflow.querySelector(".modal-body").prepend(alert);

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
          else if (apiCorectionAeInflow.message_type === "invalid") {
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
              apiCorectionAeInflow.message;
            //progress bar
            progressBar.style.transition = "width 10s linear";
            progressBar.style.width = "100%";

            //add alert
            modalCorrectionAeInflow.querySelector(".modal-body").prepend(alert);

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
            apiCorectionAeInflow.message;
          //progress bar
          progressBar.style.transition = "width 10s linear";
          progressBar.style.width = "100%";

          //add alert
          container.querySelector("#tbody-ae").closest("div").prepend(alert);

          //progress launch animation
          setTimeout(() => {
            progressBar.style.width = "0%";
          }, 10);
          //auto close alert
          setTimeout(() => {
            alert.querySelector(".btn-close").click();
          }, 10000);

          //close modal
          modalCorrectionAeInflow
            .querySelector("#btn-close-modal-correction-ae-inflow")
            .click();

          //refresh filter ae
          filterAE(
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

    //=================== CORRECTION AE OUTFLOW =================
    //modal correction ae outflow
    const modalCorrectionAeOutflow = container.querySelector(
      "#modal-correction-ae-outflow"
    );

    //===== EVENT form correction ae outflow submit
    modalCorrectionAeOutflow
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
          //FETH api correction ae outflow
          const apiCorectionAeOutflow = await apiRequest(
            "/sortie/correction_autre_entree",
            {
              method: "POST",
              body: {
                num_ae: modalCorrectionAeOutflow
                  .querySelector("#correction-ae-outflow-num-ae")
                  .textContent.trim(),
                libelle_article: modalCorrectionAeOutflow
                  .querySelector("#input-correction-ae-outflow-libelle-article")
                  .value.trim(),
                montant: modalCorrectionAeOutflow
                  .querySelector("#input-correction-ae-outflow-prix-article")
                  .value.replace(/[\u202F\u00A0 ]/g, "")
                  .replace(",", "."),
                date_ds: !modalCorrectionAeOutflow.querySelector(
                  "#input-correction-ae-outflow-date-ds"
                )
                  ? ""
                  : modalCorrectionAeOutflow
                      .querySelector("#input-correction-ae-outflow-date-ds")
                      .value.trim(),
                id_utilisateur: !modalCorrectionAeOutflow.querySelector(
                  "#select-correction-ae-outflow-id-utilisateur"
                )
                  ? ""
                  : $(
                      modalCorrectionAeOutflow.querySelector(
                        "#select-correction-ae-outflow-id-utilisateur"
                      )
                    )
                      .val()
                      .trim(),
              },
            }
          );

          //error
          if (apiCorectionAeOutflow.message_type === "error") {
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
              apiCorectionAeOutflow.message;
            //progress bar
            progressBar.style.transition = "width 20s linear";
            progressBar.style.width = "100%";

            //add alert
            modalCorrectionAeOutflow
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
          else if (apiCorectionAeOutflow.message_type === "invalid") {
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
              apiCorectionAeOutflow.message;
            //progress bar
            progressBar.style.transition = "width 10s linear";
            progressBar.style.width = "100%";

            //add alert
            modalCorrectionAeOutflow
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
            apiCorectionAeOutflow.message;
          //progress bar
          progressBar.style.transition = "width 10s linear";
          progressBar.style.width = "100%";

          //add alert
          container.querySelector("#tbody-ae").closest("div").prepend(alert);

          //progress launch animation
          setTimeout(() => {
            progressBar.style.width = "0%";
          }, 10);
          //auto close alert
          setTimeout(() => {
            alert.querySelector(".btn-close").click();
          }, 10000);

          //close modal
          modalCorrectionAeOutflow
            .querySelector("#btn-close-modal-correction-ae-outflow")
            .click();

          return;
        } catch (e) {
          console.error(e);
        }
      });

    //========================== DELETE AE =====================
    //btn delete ae
    const btnDeleteAe = container.querySelector("#btn-delete-ae");
    //===== EVENT btn delete ae
    if (btnDeleteAe) {
      btnDeleteAe.addEventListener("click", () => {
        //modal delete ae
        const modalDeleteAe = container.querySelector("#modal-delete-ae");
        //selected ae
        const selectedAe = container.querySelectorAll(
          "#tbody-ae input[type='checkbox']:checked"
        );

        //no selection
        if (selectedAe.length <= 0) {
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
            lang.entree_nums_ae_empty;
          //progress bar
          progressBar.style.transition = "width 10s linear";
          progressBar.style.width = "100%";

          //add alert
          container.querySelector("#tbody-ae").closest("div").prepend(alert);

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
        if (selectedAe.length === 1) {
          modalDeleteAe.querySelector(".message").innerHTML =
            lang.question_delete_ae_1.replace(
              ":field",
              selectedAe[0].closest("tr").dataset.numAe
            );
        }
        //modal message plur
        else {
          modalDeleteAe.querySelector(".message").innerHTML =
            lang.question_delete_ae_plur.replace(":field", selectedAe.length);
        }

        //show modal delete ae
        new bootstrap.Modal(modalDeleteAe).show();

        //==== EVENT btn confirm modal delete ae
        modalDeleteAe
          .querySelector("#btn-confirm-modal-delete-ae")
          .addEventListener("click", async () => {
            try {
              //nums_ae
              let nums_ae = [...selectedAe];
              nums_ae = nums_ae.map(
                (selected) => selected.closest("tr").dataset.numAe
              );

              //FETCH api delete ae
              const apiDeleteAe = await apiRequest(
                "/entree/delete_all_autre_entree",
                {
                  method: "PUT",
                  body: {
                    nums_ae: nums_ae,
                  },
                }
              );
              //error
              if (apiDeleteAe.message_type === "error") {
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
                  apiDeleteAe.message;
                //progress bar
                progressBar.style.transition = "width 20s linear";
                progressBar.style.width = "100%";

                //add alert
                modalDeleteAe.querySelector(".modal-body").prepend(alert);

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
              else if (apiDeleteAe.message_type === "invalid") {
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
                  apiDeleteAe.message;
                //progress bar
                progressBar.style.transition = "width 10s linear";
                progressBar.style.width = "100%";

                //add alert
                modalDeleteAe.querySelector(".modal-body").prepend(alert);

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
                apiDeleteAe.message;
              //progress bar
              progressBar.style.transition = "width 10s linear";
              progressBar.style.width = "100%";

              //add alert
              container
                .querySelector("#tbody-ae")
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
              modalDeleteAe.querySelector("#btn-close-modal-delete-ae").click();

              //refresh filter ae
              filterAE(
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

    //====================== DELETE PERMANENT AE =====================
    //btn delete permanent ae
    const btnDeletePermanentAe = container.querySelector(
      "#btn-delete-permanent-ae"
    );
    //===== EVENT btn delete permanent ae
    if (btnDeletePermanentAe) {
      btnDeletePermanentAe.addEventListener("click", () => {
        //modal delete ae
        const modalDeleteAe = container.querySelector("#modal-delete-ae");
        //selected ae
        const selectedAe = container.querySelectorAll(
          "#tbody-ae input[type='checkbox']:checked"
        );

        //no selection
        if (selectedAe.length <= 0) {
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
            lang.entree_nums_ae_empty;
          //progress bar
          progressBar.style.transition = "width 10s linear";
          progressBar.style.width = "100%";

          //add alert
          container.querySelector("#tbody-ae").closest("div").prepend(alert);

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
        if (selectedAe.length === 1) {
          modalDeleteAe.querySelector(".message").innerHTML =
            lang.question_delete_permanent_ae_1.replace(
              ":field",
              selectedAe[0].closest("tr").dataset.numAe
            );
        }
        //modal message plur
        else {
          modalDeleteAe.querySelector(".message").innerHTML =
            lang.question_delete_permanent_ae_plur.replace(
              ":field",
              selectedAe.length
            );
        }

        //show modal delete ae
        new bootstrap.Modal(modalDeleteAe).show();

        //==== EVENT btn confirm modal delete permanent ae
        modalDeleteAe
          .querySelector("#btn-confirm-modal-delete-ae")
          .addEventListener("click", async () => {
            try {
              //nums_ae
              let nums_ae = [...selectedAe];
              nums_ae = nums_ae.map(
                (selected) => selected.closest("tr").dataset.numAe
              );

              //FETCH api delete permanent ae
              const apiDeleteAe = await apiRequest(
                "/entree/delete_permanent_all_autre_entree",
                {
                  method: "DELETE",
                  body: {
                    nums_ae: nums_ae,
                  },
                }
              );
              //error
              if (apiDeleteAe.message_type === "error") {
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
                  apiDeleteAe.message;
                //progress bar
                progressBar.style.transition = "width 20s linear";
                progressBar.style.width = "100%";

                //add alert
                modalDeleteAe.querySelector(".modal-body").prepend(alert);

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
              else if (apiDeleteAe.message_type === "invalid") {
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
                  apiDeleteAe.message;
                //progress bar
                progressBar.style.transition = "width 10s linear";
                progressBar.style.width = "100%";

                //add alert
                modalDeleteAe.querySelector(".modal-body").prepend(alert);

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
                apiDeleteAe.message;
              //progress bar
              progressBar.style.transition = "width 10s linear";
              progressBar.style.width = "100%";

              //add alert
              container
                .querySelector("#tbody-ae")
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
              modalDeleteAe.querySelector("#btn-close-modal-delete-ae").click();

              //refresh filter ae
              filterAE(
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

    //========================== RESTORE AE =====================
    //btn restore ae
    const btnRestoreAe = container.querySelector("#btn-restore-ae");
    //===== EVENT btn restore ae
    if (btnRestoreAe) {
      btnRestoreAe.addEventListener("click", () => {
        //modal restore ae
        const modalRestoreAe = container.querySelector("#modal-restore-ae");
        //selected ae
        const selectedAe = container.querySelectorAll(
          "#tbody-ae input[type='checkbox']:checked"
        );

        //no selection
        if (selectedAe.length <= 0) {
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
            lang.entree_nums_ae_empty;
          //progress bar
          progressBar.style.transition = "width 10s linear";
          progressBar.style.width = "100%";

          //add alert
          container.querySelector("#tbody-ae").closest("div").prepend(alert);

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
        if (selectedAe.length === 1) {
          modalRestoreAe.querySelector(".message").innerHTML =
            lang.question_restore_ae_1.replace(
              ":field",
              selectedAe[0].closest("tr").dataset.numAe
            );
        }
        //modal message plur
        else {
          modalRestoreAe.querySelector(".message").innerHTML =
            lang.question_restore_ae_plur.replace(":field", selectedAe.length);
        }

        //show modal delete ae
        new bootstrap.Modal(modalRestoreAe).show();

        //==== EVENT btn confirm modal restore ae
        modalRestoreAe
          .querySelector("#btn-confirm-modal-restore-ae")
          .addEventListener("click", async () => {
            try {
              //nums_ae
              let nums_ae = [...selectedAe];
              nums_ae = nums_ae.map(
                (selected) => selected.closest("tr").dataset.numAe
              );

              //FETCH api restore ae
              const apiRestoreAe = await apiRequest(
                "/entree/restore_all_autre_entree",
                {
                  method: "PUT",
                  body: {
                    nums_ae: nums_ae,
                  },
                }
              );

              //error
              if (apiRestoreAe.message_type === "error") {
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
                  apiRestoreAe.message;
                //progress bar
                progressBar.style.transition = "width 20s linear";
                progressBar.style.width = "100%";

                //add alert
                modalRestoreAe.querySelector(".modal-body").prepend(alert);

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
              else if (apiRestoreAe.message_type === "invalid") {
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
                  apiRestoreAe.message;
                //progress bar
                progressBar.style.transition = "width 10s linear";
                progressBar.style.width = "100%";

                //add alert
                modalRestoreAe.querySelector(".modal-body").prepend(alert);

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
                apiRestoreAe.message;
              //progress bar
              progressBar.style.transition = "width 10s linear";
              progressBar.style.width = "100%";

              //add alert
              container
                .querySelector("#tbody-ae")
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
              modalRestoreAe
                .querySelector("#btn-close-modal-restore-ae")
                .click();

              //refresh filter ae
              filterAE(
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
  //function - filter ae
  async function filterAE(
    status,
    arrange_by,
    order,
    from,
    to,
    num_caisse,
    id_utilisateur,
    search_ae
  ) {
    //tbody
    const tbodyAE = container.querySelector("#tbody-ae");
    try {
      //FETCH api filter ae
      const apiFilterAE = await apiRequest(
        `/entree/filter_ae?status=${status}&arrange_by=${arrange_by}&order=${order}&from=${from}&to=${to}&num_caisse=${num_caisse}&id_user=${id_utilisateur}&search_ae=${search_ae}`
      );

      //error
      if (apiFilterAE.message_type === "error") {
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
        alert.querySelector(".alert-message").innerHTML = apiFilterAE.message;
        //progress bar
        progressBar.style.transition = "width 20s linear";
        progressBar.style.width = "100%";

        //add alert
        tbodyAE.closest("div").prepend(alert);

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
      else if (apiFilterAE.message_type === "invalid") {
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
        alert.querySelector(".alert-message").innerHTML = apiFilterAE.message;
        //progress bar
        progressBar.style.transition = "width 10s linear";
        progressBar.style.width = "100%";

        //add alert
        tbodyAE.closest("div").prepend(alert);

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

      //===== TABLE ae
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

      //set table list ae
      tbodyAE.innerHTML = "";
      apiFilterAE.data.forEach((line) => {
        const tr = document.createElement("tr");

        //td - checkbox
        if (container.querySelector("#check-all-ae")) {
          const tdCheckbox = document.createElement("td");
          tdCheckbox.innerHTML =
            "<input type='checkbox' class='form-check-input'>";
          tdCheckbox.classList.add("text-center");
          tr.append(tdCheckbox);
        }

        //td - num_ae
        const tdNumAe = document.createElement("td");
        tdNumAe.textContent = line.num_ae;
        tdNumAe.classList.add("text-center");

        //td - libelle_ae
        const tdLibelleAe = document.createElement("td");
        tdLibelleAe.textContent = line.libelle_ae;
        tdLibelleAe.classList.add("text-center");

        //td - date_ae
        const tdDateAe = document.createElement("td");
        tdDateAe.textContent = fromatterDate.format(new Date(line.date_ae));
        tdDateAe.classList.add("text-center");

        //td - montant_ae
        const tdMontantAe = document.createElement("td");
        tdMontantAe.textContent = formatterNumber.format(
          Number(line.montant_ae)
        );
        tdMontantAe.classList.add("text-center");

        //td - id_utilisateur
        const tdIdUtilisateur = document.createElement("td");
        tdIdUtilisateur.textContent = line.id_utilisateur;
        tdIdUtilisateur.classList.add("text-center");

        //td - num_caisse
        const tdNumCaisse = document.createElement("td");
        tdNumCaisse.textContent = line.num_caisse;
        tdNumCaisse.classList.add("text-center");

        //td - status
        const tdStatus = document.createElement("td");
        if (line.etat_ae === "supprimé") {
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
        //btn update ae
        const btnUpdateAe = document.createElement("button");
        btnUpdateAe.type = "button";
        btnUpdateAe.classList.add(
          "btn-light",
          "text-primary",
          "btn",
          "btn-sm",
          "btn-update-ae"
        );
        btnUpdateAe.innerHTML = "<i class='fad fa-pen-to-square'></i>";
        //btn correct ae inflow
        const btnCorrectAeInflow = document.createElement("button");
        btnCorrectAeInflow.type = "button";
        btnCorrectAeInflow.classList.add(
          "btn-light",
          "text-success",
          "btn",
          "btn-sm",
          "btn-correct-ae-inflow"
        );
        btnCorrectAeInflow.innerHTML = "<i class='fad fa-circle-plus'></i>";
        //btn correct ae outflow
        const btnCorrectAeOutflow = document.createElement("button");
        btnCorrectAeOutflow.type = "button";
        btnCorrectAeOutflow.classList.add(
          "btn-ligh",
          "text-danger",
          "btn",
          "btn-sm",
          "btn-correct-ae-outflow"
        );
        btnCorrectAeOutflow.innerHTML = "<i class='fad fa-circle-minus'></i>";
        //append btn actions
        divActions.append(btnUpdateAe, btnCorrectAeInflow, btnCorrectAeOutflow);
        tdActions.append(divActions);

        //append
        tr.append(
          tdNumAe,
          tdLibelleAe,
          tdDateAe,
          tdMontantAe,
          tdIdUtilisateur,
          tdNumCaisse,
          tdStatus,
          tdActions
        );

        tr.dataset.numAe = line.num_ae;
        tr.dataset.libelleAe = line.libelle_ae;
        tr.dataset.dateAe = line.date_ae;
        tr.dataset.montantAe = line.montant_ae;
        tbodyAE.appendChild(tr);
      });

      //foreach tr
      const allTr = tbodyAE.querySelectorAll("tr");
      allTr.forEach((tr) => {
        //===================== UPDATE AE ==================
        //====== EVENT btn update ae
        tr.querySelector(".btn-update-ae").addEventListener("click", () => {
          //modal update ae
          const modalUpdateAe = container.querySelector("#modal-update-ae");
          //num_ae
          modalUpdateAe.querySelector("#update-ae-num-ae").innerHTML =
            tr.dataset.numAe;
          //input - update ae libelle_ae
          const inputUpdateAeLibelleAe = modalUpdateAe.querySelector(
            "#input-update-ae-libelle-ae"
          );
          inputUpdateAeLibelleAe.value = tr.dataset.libelleAe;
          //input - update ae date_ae
          const inputUpdateAeDateAe = modalUpdateAe.querySelector(
            "#input-update-ae-date-ae"
          );
          if (inputUpdateAeDateAe)
            inputUpdateAeDateAe.value = tr.dataset.dateAe.replace(" ", "T");

          //====== EVENT input update ae libelle_ae
          inputUpdateAeLibelleAe.addEventListener("input", (e) => {
            e.target.value = e.target.value.replace("  ", " ");
          });

          //show modal update ae
          new bootstrap.Modal(modalUpdateAe).show();
        });

        //=================== CORRECTION AE INFLOW =================
        //====== EVENT btn correction ae inflow
        tr.querySelector(".btn-correct-ae-inflow").addEventListener(
          "click",
          async () => {
            //modal correction ae inflow
            const modalCorrectionAeInflow = container.querySelector(
              "#modal-correction-ae-inflow"
            );
            //num_ae
            modalCorrectionAeInflow.querySelector(
              "#correction-ae-inflow-num-ae"
            ).innerHTML = tr.dataset.numAe;
            //montant_total
            const montantTotal =
              modalCorrectionAeInflow.querySelector(".montant-total");
            montantTotal.innerHTML = formatterTotal.format(
              Number(tr.dataset.montantAe) + 1
            );
            montantTotal.dataset.value = tr.dataset.montantAe;
            //modal correction ae inflow - intialize select 2
            $(modalCorrectionAeInflow.querySelectorAll(".select2")).select2({
              theme: "bootstrap-5",
              placeholder: lang.select.toLowerCase(),
              dropdownParent: $(modalCorrectionAeInflow),
            });
            //input - correction ae inflow libelle_ae
            const inputCorrectionAeInflowLibelleAe =
              modalCorrectionAeInflow.querySelector(
                "#input-correction-ae-inflow-libelle-ae"
              );
            //input - correction ae inflow montant_ae
            const inputCorrectionAeInflowMontantAe =
              modalCorrectionAeInflow.querySelector(
                "#input-correction-ae-inflow-montant-ae"
              );
            inputCorrectionAeInflowMontantAe.value = 1;
            inputCorrectionAeInflowMontantAe.dataset.val = 1;
            //input - correction ae inflow date_ae
            const inputCorrectionAeInflowDateAe =
              modalCorrectionAeInflow.querySelector(
                "#input-correction-ae-inflow-date-ae"
              );
            //select - correction ae inflow id_utilisateur
            const selectCorrectionAeInflowIdUtilisateur =
              modalCorrectionAeInflow.querySelector(
                "#select-correction-ae-inflow-id-utilisateur"
              );
            if (selectCorrectionAeInflowIdUtilisateur) {
              //list all user
              await listUser(selectCorrectionAeInflowIdUtilisateur, true);

              //===== EVENT btn correction ae inflow refresh id_utilisateur
              modalCorrectionAeInflow
                .querySelector(
                  "#btn-correction-ae-inflow-refresh-id-utilisateur"
                )
                .addEventListener("click", () => {
                  listUser(selectCorrectionAeInflowIdUtilisateur, true);
                });
            }

            //===== EVENT input ucorrection ae inflow libelle_ae
            inputCorrectionAeInflowLibelleAe.addEventListener("input", (e) => {
              e.target.value = e.target.value.replace("  ", " ");
            });
            //===== EVENT input correction ae inflow montant_ae
            inputCorrectionAeInflowMontantAe.addEventListener("input", (e) => {
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

              //correction ae montant_total
              montantTotal.innerHTML = formatterTotal.format(
                Number(montantTotal.dataset.value) +
                  Number(e.target.dataset.val)
              );
            });
            inputCorrectionAeInflowMontantAe.addEventListener("blur", (e) => {
              if (e.target.value.endsWith(",")) {
                e.target.value += "0";
              }
              e.target.value = formatterNumber.format(
                e.target.value.replace(/[\u202F\u00A0 ]/g, "").replace(",", ".")
              );
            });

            //show modal correction ae inflow
            new bootstrap.Modal(modalCorrectionAeInflow).show();
          }
        );

        //=================== CORRECTION AE OUTFLOW ===============

        //=================== CORRECTION AE INFLOW =================
        //====== EVENT btn correction ae outflow
        tr.querySelector(".btn-correct-ae-outflow").addEventListener(
          "click",
          async () => {
            //modal correction ae outflow
            const modalCorrectionAeOutflow = container.querySelector(
              "#modal-correction-ae-outflow"
            );
            //num_ae
            modalCorrectionAeOutflow.querySelector(
              "#correction-ae-outflow-num-ae"
            ).innerHTML = tr.dataset.numAe;
            //montant_total
            const montantTotal =
              modalCorrectionAeOutflow.querySelector(".montant-total");
            montantTotal.innerHTML = formatterTotal.format(
              Number(tr.dataset.montantAe) - 1
            );
            montantTotal.dataset.value = tr.dataset.montantAe;
            montantTotal.dataset.prixArticle = tr.dataset.montantAe;
            //modal correction ae outflow - intialize select 2
            $(modalCorrectionAeOutflow.querySelectorAll(".select2")).select2({
              theme: "bootstrap-5",
              placeholder: lang.select.toLowerCase(),
              dropdownParent: $(modalCorrectionAeOutflow),
            });
            //input - correction ae outflow libelle_article
            const inputCorrectionAeOutflowLibelleArticle =
              modalCorrectionAeOutflow.querySelector(
                "#input-correction-ae-inflow-libelle-aarticle"
              );
            //input - correction ae outflow prix_article
            const inputCorrectionAeOutflowPrixArticle =
              modalCorrectionAeOutflow.querySelector(
                "#input-correction-ae-outflow-prix-article"
              );
            inputCorrectionAeOutflowPrixArticle.value = 1;
            inputCorrectionAeOutflowPrixArticle.dataset.val = 1;
            //input - correction ae outflow date_ds
            const inputCorrectionAeOutflowDateDs =
              modalCorrectionAeOutflow.querySelector(
                "#input-correction-ae-outflow-date-ds"
              );
            //select - correction ae outflow id_utilisateur
            const selectCorrectionAeOutflowIdUtilisateur =
              modalCorrectionAeOutflow.querySelector(
                "#select-correction-ae-outflow-id-utilisateur"
              );
            if (selectCorrectionAeOutflowIdUtilisateur) {
              //list all user
              await listUser(selectCorrectionAeOutflowIdUtilisateur, true);

              //===== EVENT btn correction ae outflow refresh id_utilisateur
              modalCorrectionAeOutflow
                .querySelector(
                  "#btn-correction-ae-outflow-refresh-id-utilisateur"
                )
                .addEventListener("click", () => {
                  listUser(selectCorrectionAeOutflowIdUtilisateur, true);
                });
            }

            //===== EVENT input ucorrection ae outflow libelle_article
            inputCorrectionAeOutflowPrixArticle.addEventListener(
              "input",
              (e) => {
                e.target.value = e.target.value.replace("  ", " ");
              }
            );
            //===== EVENT input correction ae outflow prix_article
            inputCorrectionAeOutflowPrixArticle.addEventListener(
              "input",
              (e) => {
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

                //correction ae montant_total
                if (
                  Number(montantTotal.dataset.prixArticle) -
                    Number(e.target.dataset.val) <
                  0
                ) {
                  e.target.value = Number(montantTotal.dataset.prixArticle);
                  montantTotal.innerHTML = formatterTotal.format(0);
                  montantTotal.dataset.value = 0;
                } else {
                  montantTotal.innerHTML = formatterTotal.format(
                    Number(montantTotal.dataset.prixArticle) -
                      Number(e.target.dataset.val)
                  );
                }
              }
            );
            inputCorrectionAeOutflowPrixArticle.addEventListener(
              "blur",
              (e) => {
                if (e.target.value.endsWith(",")) {
                  e.target.value += "0";
                }
                e.target.value = formatterNumber.format(
                  e.target.value
                    .replace(/[\u202F\u00A0 ]/g, "")
                    .replace(",", ".")
                );
              }
            );

            //show modal correction ae outflow
            new bootstrap.Modal(modalCorrectionAeOutflow).show();
          }
        );
      });

      //===== EVENT check all
      const inputCheckAll = container.querySelector("#check-all-ae");
      if (inputCheckAll) {
        inputCheckAll.addEventListener("change", (e) => {
          tbodyAE
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
  //   //function - list connection facture
  //   async function listConnectionFacture(numFacture) {
  //     //tbody lf
  //     const tbodyLF = container.querySelector("#tbody-lf");
  //     try {
  //       //FETCH api list connection facture
  //       const apiListConnectionFacture = await apiRequest(
  //         `/entree/list_connection_facture?num_facture=${numFacture}`
  //       );

  //       //error
  //       if (apiListConnectionFacture.message_type === "error") {
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
  //           apiListConnectionFacture.message;
  //         //progress bar
  //         progressBar.style.transition = "width 20s linear";
  //         progressBar.style.width = "100%";

  //         //add alert
  //         tbodyLF.closest("div").prepend(alert);

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
  //       else if (apiListConnectionFacture.message_type === "invalid") {
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
  //           apiListConnectionFacture.message;
  //         //progress bar
  //         progressBar.style.transition = "width 10s linear";
  //         progressBar.style.width = "100%";

  //         //add alert
  //         tbodyLF.closest("div").prepend(alert);

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

  //       //===== TABLE ligne_facture

  //       //set table list connection facture
  //       tbodyLF.innerHTML = "";
  //       //lf
  //       apiListConnectionFacture.lf.forEach((line) => {
  //         const tr = document.createElement("tr");

  //         //td - id_lf
  //         const tdIdLF = document.createElement("td");
  //         tdIdLF.textContent = line.id_lf;
  //         tdIdLF.classList.add("text-center");

  //         //td - libelle_produit
  //         const tdLibelle = document.createElement("td");
  //         tdLibelle.textContent = line.libelle_produit;
  //         tdLibelle.classList.add("text-center");

  //         //td - quantite_produit
  //         const tdQuantite = document.createElement("td");
  //         tdQuantite.textContent = formatterNumber.format(
  //           Number(line.quantite_produit)
  //         );
  //         tdQuantite.classList.add("text-center");

  //         //td - prix
  //         const tdPrix = document.createElement("td");
  //         tdPrix.textContent = formatterNumber.format(Number(line.prix));
  //         tdPrix.classList.add("text-center");

  //         //td - montant
  //         const tdMontant = document.createElement("td");
  //         tdMontant.textContent = formatterNumber.format(Number(line.prix_total));
  //         tdMontant.classList.add("text-center");

  //         tr.append(tdIdLF, tdLibelle, tdQuantite, tdPrix, tdMontant);
  //         tbodyLF.appendChild(tr);
  //       });
  //       //ae
  //       if (apiListConnectionFacture.autre_entree.length > 0) {
  //         const trCorrectionAe = document.createElement("tr");
  //         trCorrectionAe.innerHTML = `<td colspan='5' class='text-center text-light p-2 bg-secondary'><b>${
  //           lang.correction
  //         }</b> (${lang.inflow.toLowerCase()})</td>`;

  //         tbodyLF.append(trCorrectionAe);

  //         //list correction ae
  //         apiListConnectionFacture.autre_entree.forEach((line) => {
  //           const tr = document.createElement("tr");

  //           //td - id_ae
  //           const tdIdAe = document.createElement("td");
  //           tdIdAe.textContent = line.id_ae;
  //           tdIdAe.classList.add("text-center");

  //           //td - libelle_ae
  //           const tdLibelle = document.createElement("td");
  //           tdLibelle.textContent = line.libelle_ae;
  //           tdLibelle.classList.add("text-center");

  //           //td - quantite_ae
  //           const tdQuantite = document.createElement("td");
  //           tdQuantite.textContent = 1;
  //           tdQuantite.classList.add("text-center");

  //           //td - prix
  //           const tdPrix = document.createElement("td");
  //           tdPrix.textContent = formatterNumber.format(Number(line.montant_ae));
  //           tdPrix.classList.add("text-center");

  //           //td - montant
  //           const tdMontant = document.createElement("td");
  //           tdMontant.textContent = formatterNumber.format(
  //             Number(line.montant_ae)
  //           );
  //           tdMontant.classList.add("text-center");

  //           tr.append(tdIdAe, tdLibelle, tdQuantite, tdPrix, tdMontant);
  //           tbodyLF.appendChild(tr);
  //         });
  //       }
  //       //ae
  //       if (apiListConnectionFacture.sortie.length > 0) {
  //         const trCorrectionDs = document.createElement("tr");
  //         trCorrectionDs.innerHTML = `<td colspan='5' class='text-center text-light p-2 bg-secondary'><b>${
  //           lang.correction
  //         }</b> (${lang.outflow.toLowerCase()})</td>`;

  //         tbodyLF.append(trCorrectionDs);

  //         //list correction ds
  //         apiListConnectionFacture.sortie.forEach((line) => {
  //           const tr = document.createElement("tr");

  //           //td - id_ds
  //           const tdIdDs = document.createElement("td");
  //           tdIdDs.textContent = line.num_ds;
  //           tdIdDs.classList.add("text-center");

  //           //td - libelle_ds
  //           const tdLibelle = document.createElement("td");
  //           tdLibelle.textContent = line.libelle_article;
  //           tdLibelle.classList.add("text-center");

  //           //td - quantite_ds
  //           const tdQuantite = document.createElement("td");
  //           tdQuantite.textContent = 1;
  //           tdQuantite.classList.add("text-center");

  //           //td - prix
  //           const tdPrix = document.createElement("td");
  //           tdPrix.textContent = formatterNumber.format(
  //             Number(line.prix_article)
  //           );
  //           tdPrix.classList.add("text-center");

  //           //td - montant
  //           const tdMontant = document.createElement("td");
  //           tdMontant.textContent = formatterNumber.format(
  //             Number(line.prix_article)
  //           );
  //           tdMontant.classList.add("text-center");

  //           tr.append(tdIdDs, tdLibelle, tdQuantite, tdPrix, tdMontant);
  //           tbodyLF.appendChild(tr);
  //         });
  //       }
  //     } catch (e) {
  //       console.error(e);
  //     }
  //   }
});
