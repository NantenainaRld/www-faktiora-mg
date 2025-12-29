document.addEventListener("DOMContentLoaded", async () => {
  //template real content
  const templateRealContent = document.getElementById("template-sortie");

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
      filterSortie(
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
      filterSortie(
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
      filterSortie(
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
      filterSortie(
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
      filterSortie(
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
      filterSortie(
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
        filterSortie(
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
      filterSortie(
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

    //======================== FILTER SORTIE ==================
    await filterSortie(
      selectStatus.value.trim(),
      selectArrangeBy.value.trim(),
      selectOrder.value.trim(),
      dateFrom.value.trim(),
      dateTo.value.trim(),
      selectNumCaisse ? $(selectNumCaisse).val().trim() : "",
      $(selectIdUtilisateur).val().trim(),
      inputSearch.value.trim()
    );

    //========================= ADD SORTIE =========================
    //modal add sortie
    const modalAddSortie = container.querySelector("#modal-add-sortie");
    //modal add sortie - intialize select 2
    $(modalAddSortie.querySelectorAll(".select2")).select2({
      theme: "bootstrap-5",
      placeholder: lang.select.toLowerCase(),
      dropdownParent: $(modalAddSortie),
    });

    //input - add sortie date_sortie
    const inputAddSortieDateSortie = modalAddSortie.querySelector(
      "#input-add-sortie-date-sortie"
    );
    if (inputAddSortieDateSortie) {
      const savedInputAddSortieDateSortie = localStorage.getItem(
        inputAddSortieDateSortie.id
      );
      inputAddSortieDateSortie.value = !savedInputAddSortieDateSortie
        ? ""
        : savedInputAddSortieDateSortie;

      //===== EVENT input add sortie date_sortie
      inputAddSortieDateSortie.addEventListener("input", (e) => {
        localStorage.setItem(e.target.id, e.target.value);
      });
    }
    //select - add sortie id_utilisateur
    const selectAddSortieIdUtilisateur = modalAddSortie.querySelector(
      "#select-add-sortie-id-utilisateur"
    );
    if (selectAddSortieIdUtilisateur) {
      //list all user
      await listUser(selectAddSortieIdUtilisateur, true);
      //load value from localstorage
      const savedSelectAddSortieIdUtilisateur = localStorage.getItem(
        selectAddSortieIdUtilisateur.id
      );
      $(selectAddSortieIdUtilisateur)
        .val(
          !savedSelectAddSortieIdUtilisateur
            ? ""
            : savedSelectAddSortieIdUtilisateur
        )
        .trigger("change");

      //===== EVENT btn add sortie refresh id_utilisateur
      modalAddSortie
        .querySelector("#btn-add-sortie-refresh-id-utilisateur")
        .addEventListener("click", () => {
          listUser(selectAddSortieIdUtilisateur, true);
        });

      //===== EVENT select add sortie id_utilisateur
      $(selectAddSortieIdUtilisateur).on("change", function (e) {
        localStorage.setItem(e.target.id, $(this).val());
      });
    }
    //select - add sortie num_caisse
    const selectAddSortieNumCaisse = modalAddSortie.querySelector(
      "#select-add-sortie-num-caisse"
    );
    if (selectAddSortieNumCaisse) {
      //list num_caisse
      await listNumCaisse(selectAddSortieNumCaisse, true);
      //load value from localstorage
      const savedSelectAddSortieNumCaisse = localStorage.getItem(
        selectAddSortieNumCaisse.id
      );
      $(selectAddSortieNumCaisse)
        .val(
          !savedSelectAddSortieNumCaisse ? "" : savedSelectAddSortieNumCaisse
        )
        .trigger("change");

      //===== EVENT btn add sortie refresh num_caisse
      modalAddSortie
        .querySelector("#btn-add-sortie-refresh-num-caisse")
        .addEventListener("click", () => {
          listNumCaisse(selectAddSortieNumCaisse, true);
        });

      //===== EVENT select add sortie num_caisse
      $(selectAddSortieNumCaisse).on("change", function (e) {
        localStorage.setItem(e.target.id, $(this).val());
      });
    }
    //select - add sortie id_article
    const selectAddSortieIdArticle = modalAddSortie.querySelector(
      "#select-add-sortie-id-article"
    );
    if (selectAddSortieIdArticle) {
      //list all article
      listArticle(selectAddSortieIdArticle);
      //===== EVENT btn add sortie refresh id_article
      modalAddSortie
        .querySelector("#btn-add-sortie-refresh-id-article")
        .addEventListener("click", () => {
          listArticle(selectAddSortieIdArticle);
        });

      //===== EVENT select add sortie id_article
      $(selectAddSortieIdArticle).on("change", function () {
        inputAddSortiePrixArticle.value = formatterNumber.format(
          Number($(this).select2("data")[0].element.dataset.prixArticle)
        );
        inputAddSortiePrixArticle.dataset.val = Number(
          $(this).select2("data")[0].element.dataset.prixArticle
        );
        inputAddSortieQuantiteArticle.value =
          $(this).select2("data")[0].element.dataset.quantiteArticle;
      });
    }
    //input - add sortie prix_article
    const inputAddSortiePrixArticle = modalAddSortie.querySelector(
      "#input-add-sortie-prix-article"
    );
    const savedInputAddSortiePrixArticle = localStorage.getItem(
      inputAddSortiePrixArticle.id
    );
    inputAddSortiePrixArticle.value = !savedInputAddSortiePrixArticle
      ? "1"
      : savedInputAddSortiePrixArticle;
    inputAddSortiePrixArticle.dataset.val = inputAddSortiePrixArticle.value;
    // input - add sortie quantite_article
    const inputAddSortieQuantiteArticle = modalAddSortie.querySelector(
      "#input-add-sortie-quantite-article"
    );

    //===== EVENT input add sortie prix_article
    inputAddSortiePrixArticle.addEventListener("input", (e) => {
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
    inputAddSortiePrixArticle.addEventListener("blur", (e) => {
      if (e.target.value.endsWith(",")) {
        e.target.value += "0";
      }
      e.target.value = formatterNumber.format(
        e.target.value.replace(/[\u202F\u00A0 ]/g, "").replace(",", ".")
      );
      //save to local storage
      localStorage.setItem(e.target.id, e.target.value);
    });
    //===== EVENT input add sortie quantite_article
    inputAddSortieQuantiteArticle.addEventListener("input", (e) => {
      e.target.value = e.target.value.replace(/[^0-9]/g, "");
    });
    inputAddSortieQuantiteArticle.addEventListener("blur", (e) => {
      if (!e.target.value || e.target.value == "0") {
        e.target.value = 1;
      }
    });
    //===== EVENT btn add sortie add article
    modalAddSortie
      .querySelector("#btn-add-sortie-add-article")
      .addEventListener("click", () => {
        //no article selected
        if (!$(selectAddSortieIdArticle).val()) {
          modalAddSortie.querySelector("#message-article-select").innerHTML =
            lang.article_not_selected;
          setTimeout(() => {
            modalAddSortie.querySelector("#message-article-select").innerHTML =
              "";
          }, 3000);
          return;
        }

        //add article to the card
        const spanArticle = document.createElement("span");
        spanArticle.dataset.idArticle = $(selectAddSortieIdArticle).val();
        spanArticle.dataset.quantiteArticle =
          inputAddSortieQuantiteArticle.value.trim();
        spanArticle.dataset.prixArticle =
          inputAddSortiePrixArticle.dataset.val.trim();
        spanArticle.classList.add(
          "rounded-1",
          "bg-second",
          "p-1",
          "text-secondary",
          "form-text",
          "article"
        );
        //span prix total
        spanArticle.dataset.prixTotal =
          Number(inputAddSortiePrixArticle.dataset.val.trim()) *
          Number(inputAddSortieQuantiteArticle.value.trim());
        //span html
        spanArticle.innerHTML = `${
          $(selectAddSortieIdArticle).select2("data")[0].text
        } (${formatterTotal.format(
          Number(spanArticle.dataset.prixTotal)
        )})<span class="badge bg-light text-success">${inputAddSortieQuantiteArticle.value.trim()}</span><button type="button" class="btn btn-light btn-sm ms-1 btn-close-article"><i class="fad fa-x"></i></button>`;
        //append to card
        modalAddSortie.querySelector("#card-article").append(spanArticle);

        //add sortie total
        const addSortieTotal =
          modalAddSortie.querySelector("#add-sortie-total");
        addSortieTotal.dataset.value =
          Number(addSortieTotal.dataset.value) +
          Number(inputAddSortiePrixArticle.dataset.val.trim()) *
            Number(inputAddSortieQuantiteArticle.value);
        addSortieTotal.innerHTML = formatterTotal.format(
          Number(addSortieTotal.dataset.value)
        );
        //===== EVENT btn close article
        const btn = spanArticle.querySelector(".btn-close-article");
        btn.addEventListener("click", () => {
          addSortieTotal.dataset.value =
            Number(addSortieTotal.dataset.value) -
            Number(btn.closest("span").dataset.prixTotal);
          addSortieTotal.innerHTML = formatterTotal.format(
            Number(addSortieTotal.dataset.value)
          );
          //remove span article
          btn.closest("span").remove();
        });
      });
    //===== EVENT btn empty article
    modalAddSortie
      .querySelector("#btn-add-sortie-empty-article")
      .addEventListener("click", () => {
        modalAddSortie.querySelector("#card-article").innerHTML = "";
        modalAddSortie.querySelector("#add-sortie-total").dataset.value = 0;
        modalAddSortie.querySelector("#add-sortie-total").innerHTML =
          formatterNumber.format(0);
      });

    //===== EVENT btn add sortie
    container
      .querySelector("#btn-add-facture")
      .addEventListener("click", () => {
        //show modal add sortie
        new bootstrap.Modal(modalAddSortie).show();
      });

    //===== EVENT form add sortie submit
    modalAddSortie
      .querySelector("form")
      .addEventListener("submit", async (e) => {
        //suspend submit
        e.preventDefault();
        //check validity
        if (!e.target.checkValidity()) {
          e.target.reportValidity();
          return;
        }

        //articles
        let articles = [];
        modalAddSortie
          .querySelectorAll("#card-article .article")
          .forEach((span) => {
            const article = {
              id_article: span.dataset.idArticle.trim(),
              quantite_article: span.dataset.quantiteArticle.trim(),
              prix_article: span.dataset.prixArticle.trim(),
            };

            articles.push(article);
          });
        try {
          //FETH api add sortie
          const apiAddSortie = await apiRequest("/sortie/create_sortie", {
            method: "POST",
            body: {
              date_ds: !inputAddSortieDateSortie
                ? ""
                : inputAddSortieDateSortie.value.trim(),
              id_utilisateur: !selectAddSortieIdUtilisateur
                ? ""
                : $(selectAddSortieIdUtilisateur).val().trim(),
              num_caisse: !selectAddSortieNumCaisse
                ? ""
                : $(selectAddSortieNumCaisse).val().trim(),
              articles: articles,
            },
          });

          //error
          if (apiAddSortie.message_type === "error") {
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
              apiAddSortie.message;
            //progress bar
            progressBar.style.transition = "width 20s linear";
            progressBar.style.width = "100%";

            //add alert
            modalAddSortie.querySelector(".modal-body").prepend(alert);

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
          else if (apiAddSortie.message_type === "invalid") {
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
              apiAddSortie.message;
            //progress bar
            progressBar.style.transition = "width 10s linear";
            progressBar.style.width = "100%";

            //add alert
            modalAddSortie.querySelector(".modal-body").prepend(alert);

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
          console.log("ee");
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
            apiAddSortie.message;
          //progress bar
          progressBar.style.transition = "width 10s linear";
          progressBar.style.width = "100%";

          //add alert
          container
            .querySelector("#tbody-sortie")
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
          modalAddSortie.querySelector("#btn-close-modal-add-sortie").click();

          //refresh filter sortie
          filterSortie(
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

    //======================== ADD ARTICLE ======================
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

    //     //======================= CORRECION FACTURE ===================
    //     //modal correction facture
    //     const modalCorrectionFacture = container.querySelector(
    //       "#modal-correction-facture"
    //     );
    //     //====== EVENT form correction facture submit
    //     modalCorrectionFacture
    //       .querySelector("form")
    //       .addEventListener("submit", async (e) => {
    //         //suspend submit
    //         e.preventDefault();
    //         //check validity
    //         if (!e.target.checkValidity()) {
    //           e.target.reportValidity();
    //           return;
    //         }

    //         try {
    //           //ligne facture
    //           let lf = [];
    //           modalCorrectionFacture
    //             .querySelectorAll(".div-lf")
    //             .forEach((divLF) => {
    //               const lfObject = {
    //                 id_lf: divLF.dataset.idLf.trim(),
    //                 id_produit: divLF.dataset.idProduit.trim(),
    //                 quantite_produit: divLF
    //                   .querySelector("input[type='number']")
    //                   .value.trim(),
    //               };

    //               lf.push(lfObject);
    //             });

    //           //FETCH api correction facture
    //           const apiCorrectionFacture = await apiRequest(
    //             "/sortie/correction_facture",
    //             {
    //               method: "POST",
    //               body: {
    //                 num_facture: modalCorrectionFacture
    //                   .querySelector("#correction-facture-num-facture")
    //                   .textContent.trim(),
    //                 date_ds: !modalCorrectionFacture.querySelector(
    //                   "#input-correction-facture-date-ds"
    //                 )
    //                   ? ""
    //                   : modalCorrectionFacture
    //                       .querySelector("#input-correction-facture-date-ds")
    //                       .value.trim(),
    //                 id_utilisateur: !modalCorrectionFacture.querySelector(
    //                   "#select-correction-facture-id-utilisateur"
    //                 )
    //                   ? ""
    //                   : $(
    //                       modalCorrectionFacture.querySelector(
    //                         "#select-correction-facture-id-utilisateur"
    //                       )
    //                     )
    //                       .val()
    //                       .trim(),
    //                 lf: lf,
    //               },
    //             }
    //           );

    //           //error
    //           if (apiCorrectionFacture.message_type === "error") {
    //             //alert
    //             const alertTemplate = document.querySelector(".alert-template");
    //             const clone = alertTemplate.content.cloneNode(true);
    //             const alert = clone.querySelector(".alert");
    //             const progressBar = alert.querySelector(".progress-bar");
    //             //alert type
    //             alert.classList.add("alert-danger");
    //             //icon
    //             alert
    //               .querySelector(".fad")
    //               .classList.add("fa-exclamation-triangle");
    //             //message
    //             alert.querySelector(".alert-message").innerHTML =
    //               apiCorrectionFacture.message;
    //             //progress bar
    //             progressBar.style.transition = "width 20s linear";
    //             progressBar.style.width = "100%";

    //             //add alert
    //             modalCorrectionFacture.querySelector(".modal-body").prepend(alert);

    //             //progress launch animation
    //             setTimeout(() => {
    //               progressBar.style.width = "0%";
    //             }, 10);
    //             //auto close alert
    //             setTimeout(() => {
    //               alert.querySelector(".btn-close").click();
    //             }, 20000);
    //             return;
    //           }
    //           //invalid
    //           else if (apiCorrectionFacture.message_type === "invalid") {
    //             //alert
    //             const alertTemplate = document.querySelector(".alert-template");
    //             const clone = alertTemplate.content.cloneNode(true);
    //             const alert = clone.querySelector(".alert");
    //             const progressBar = alert.querySelector(".progress-bar");
    //             //alert type
    //             alert.classList.add("alert-warning");
    //             //icon
    //             alert.querySelector(".fad").classList.add("fa-exclamation-circle");
    //             //message
    //             alert.querySelector(".alert-message").innerHTML =
    //               apiCorrectionFacture.message;
    //             //progress bar
    //             progressBar.style.transition = "width 10s linear";
    //             progressBar.style.width = "100%";

    //             //add alert
    //             modalCorrectionFacture.querySelector(".modal-body").prepend(alert);

    //             //progress launch animation
    //             setTimeout(() => {
    //               progressBar.style.width = "0%";
    //             }, 10);
    //             //auto close alert
    //             setTimeout(() => {
    //               alert.querySelector(".btn-close").click();
    //             }, 10000);
    //             return;
    //           }

    //           //success
    //           //alert
    //           const alertTemplate = document.querySelector(".alert-template");
    //           const clone = alertTemplate.content.cloneNode(true);
    //           const alert = clone.querySelector(".alert");
    //           const progressBar = alert.querySelector(".progress-bar");
    //           //alert type
    //           alert.classList.add("alert-success");
    //           //icon
    //           alert.querySelector(".fad").classList.add("fa-check-circle");
    //           //message
    //           alert.querySelector(".alert-message").innerHTML =
    //             apiCorrectionFacture.message;
    //           //progress bar
    //           progressBar.style.transition = "width 10s linear";
    //           progressBar.style.width = "100%";

    //           //add alert
    //           container
    //             .querySelector("#tbody-facture")
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

    //           //close modal
    //           modalCorrectionFacture
    //             .querySelector("#btn-close-modal-correction-facture")
    //             .click();

    //           return;
    //         } catch (e) {
    //           console.error(e);
    //         }
    //       });

    //     //========================== DELETE FACTURE =====================
    //     //btn delete facture
    //     const btnDeleteFacture = container.querySelector("#btn-delete-facture");
    //     //===== EVENT btn delete facture
    //     if (btnDeleteFacture) {
    //       btnDeleteFacture.addEventListener("click", () => {
    //         //modal delete facture
    //         const modalDeleteFacture = container.querySelector(
    //           "#modal-delete-facture"
    //         );

    //         //selected facture
    //         const selectedFacture = container.querySelectorAll(
    //           "#tbody-facture input[type='checkbox']:checked"
    //         );

    //         //no selection
    //         if (selectedFacture.length <= 0) {
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
    //             lang.entree_nums_facture_empty;
    //           //progress bar
    //           progressBar.style.transition = "width 10s linear";
    //           progressBar.style.width = "100%";

    //           //add alert
    //           container
    //             .querySelector("#tbody-facture")
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
    //         if (selectedFacture.length === 1) {
    //           modalDeleteFacture.querySelector(".message").innerHTML =
    //             lang.question_delete_facture_1.replace(
    //               ":field",
    //               selectedFacture[0].closest("tr").dataset.numFacture
    //             );
    //         }
    //         //modal message plur
    //         else {
    //           modalDeleteFacture.querySelector(".message").innerHTML =
    //             lang.question_delete_facture_plur.replace(
    //               ":field",
    //               selectedFacture.length
    //             );
    //         }

    //         //show modal delete facture
    //         new bootstrap.Modal(modalDeleteFacture).show();

    //         //==== EVENT btn confirm modal delete facture
    //         modalDeleteFacture
    //           .querySelector("#btn-confirm-modal-delete-facture")
    //           .addEventListener("click", async () => {
    //             try {
    //               //nums_facture
    //               let nums_facture = [...selectedFacture];
    //               nums_facture = nums_facture.map(
    //                 (selected) => selected.closest("tr").dataset.numFacture
    //               );

    //               //FETCH api delete facture
    //               const apiDeleteFacture = await apiRequest(
    //                 "/entree/delete_all_facture",
    //                 {
    //                   method: "PUT",
    //                   body: {
    //                     nums_facture: nums_facture,
    //                   },
    //                 }
    //               );

    //               //error
    //               if (apiDeleteFacture.message_type === "error") {
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
    //                   apiDeleteFacture.message;
    //                 //progress bar
    //                 progressBar.style.transition = "width 20s linear";
    //                 progressBar.style.width = "100%";

    //                 //add alert
    //                 modalDeleteFacture.querySelector(".modal-body").prepend(alert);

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
    //               else if (apiDeleteFacture.message_type === "invalid") {
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
    //                   apiDeleteFacture.message;
    //                 //progress bar
    //                 progressBar.style.transition = "width 10s linear";
    //                 progressBar.style.width = "100%";
    //                 //add alert
    //                 modalDeleteFacture.querySelector(".modal-body").prepend(alert);
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
    //                 apiDeleteFacture.message;
    //               //progress bar
    //               progressBar.style.transition = "width 10s linear";
    //               progressBar.style.width = "100%";

    //               //add alert
    //               container
    //                 .querySelector("#tbody-facture")
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
    //               modalDeleteFacture
    //                 .querySelector("#btn-close-modal-delete-facture")
    //                 .click();

    //               //refresh filter facture
    //               filterFacture(
    //                 selectStatus.value.trim(),
    //                 selectArrangeBy.value.trim(),
    //                 selectOrder.value.trim(),
    //                 dateFrom.value.trim(),
    //                 dateTo.value.trim(),
    //                 selectNumCaisse ? $(selectNumCaisse).val().trim() : "",
    //                 $(selectIdUtilisateur).val().trim(),
    //                 inputSearch.value.trim()
    //               );

    //               return;
    //             } catch (e) {
    //               console.error(e);
    //             }
    //           });
    //       });
    //     }

    //     //========================== DELETE PERMANENT FACTURE =====================
    //     //btn delete permanent facture
    //     const btnDeletePermanentFacture = container.querySelector(
    //       "#btn-delete-permanent-facture"
    //     );
    //     //===== EVENT btn delete permanent facture
    //     if (btnDeletePermanentFacture) {
    //       btnDeletePermanentFacture.addEventListener("click", () => {
    //         //modal delete facture
    //         const modalDeleteFacture = container.querySelector(
    //           "#modal-delete-facture"
    //         );

    //         //selected facture
    //         const selectedFacture = container.querySelectorAll(
    //           "#tbody-facture input[type='checkbox']:checked"
    //         );

    //         //no selection
    //         if (selectedFacture.length <= 0) {
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
    //             lang.entree_nums_facture_empty;
    //           //progress bar
    //           progressBar.style.transition = "width 10s linear";
    //           progressBar.style.width = "100%";

    //           //add alert
    //           container
    //             .querySelector("#tbody-facture")
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
    //         if (selectedFacture.length === 1) {
    //           modalDeleteFacture.querySelector(".message").innerHTML =
    //             lang.question_delete_permanent_facture_1.replace(
    //               ":field",
    //               selectedFacture[0].closest("tr").dataset.numFacture
    //             );
    //         }
    //         //modal message plur
    //         else {
    //           modalDeleteFacture.querySelector(".message").innerHTML =
    //             lang.question_delete_permanent_facture_plur.replace(
    //               ":field",
    //               selectedFacture.length
    //             );
    //         }

    //         //show modal delete facture
    //         new bootstrap.Modal(modalDeleteFacture).show();

    //         //==== EVENT btn confirm modal delete facture
    //         modalDeleteFacture
    //           .querySelector("#btn-confirm-modal-delete-facture")
    //           .addEventListener("click", async () => {
    //             try {
    //               //nums_facture
    //               let nums_facture = [...selectedFacture];
    //               nums_facture = nums_facture.map(
    //                 (selected) => selected.closest("tr").dataset.numFacture
    //               );

    //               //FETCH api delete facture
    //               const apiDeleteFacture = await apiRequest(
    //                 "/entree/delete_permanent_all_facture",
    //                 {
    //                   method: "DELETE",
    //                   body: {
    //                     nums_facture: nums_facture,
    //                   },
    //                 }
    //               );

    //               //error
    //               if (apiDeleteFacture.message_type === "error") {
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
    //                   apiDeleteFacture.message;
    //                 //progress bar
    //                 progressBar.style.transition = "width 20s linear";
    //                 progressBar.style.width = "100%";

    //                 //add alert
    //                 modalDeleteFacture.querySelector(".modal-body").prepend(alert);

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
    //               else if (apiDeleteFacture.message_type === "invalid") {
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
    //                   apiDeleteFacture.message;
    //                 //progress bar
    //                 progressBar.style.transition = "width 10s linear";
    //                 progressBar.style.width = "100%";
    //                 //add alert
    //                 modalDeleteFacture.querySelector(".modal-body").prepend(alert);
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
    //                 apiDeleteFacture.message;
    //               //progress bar
    //               progressBar.style.transition = "width 10s linear";
    //               progressBar.style.width = "100%";

    //               //add alert
    //               container
    //                 .querySelector("#tbody-facture")
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
    //               modalDeleteFacture
    //                 .querySelector("#btn-close-modal-delete-facture")
    //                 .click();

    //               //refresh filter facture
    //               filterFacture(
    //                 selectStatus.value.trim(),
    //                 selectArrangeBy.value.trim(),
    //                 selectOrder.value.trim(),
    //                 dateFrom.value.trim(),
    //                 dateTo.value.trim(),
    //                 selectNumCaisse ? $(selectNumCaisse).val().trim() : "",
    //                 $(selectIdUtilisateur).val().trim(),
    //                 inputSearch.value.trim()
    //               );

    //               return;
    //             } catch (e) {
    //               console.error(e);
    //             }
    //           });
    //       });
    //     }

    //     //========================== RESTORE FACTURE =========================
    //     //btn restore facture
    //     const btnRestoreFacture = container.querySelector("#btn-restore-facture");
    //     //===== EVENT btn restore facture
    //     if (btnRestoreFacture) {
    //       btnRestoreFacture.addEventListener("click", () => {
    //         //modal restore facture
    //         const modalRestoreFacture = container.querySelector(
    //           "#modal-restore-facture"
    //         );

    //         //selected facture
    //         const selectedFacture = container.querySelectorAll(
    //           "#tbody-facture input[type='checkbox']:checked"
    //         );

    //         //no selection
    //         if (selectedFacture.length <= 0) {
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
    //             lang.entree_nums_facture_empty;
    //           //progress bar
    //           progressBar.style.transition = "width 10s linear";
    //           progressBar.style.width = "100%";

    //           //add alert
    //           container
    //             .querySelector("#tbody-facture")
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
    //         if (selectedFacture.length === 1) {
    //           modalRestoreFacture.querySelector(".message").innerHTML =
    //             lang.question_restore_facture_1.replace(
    //               ":field",
    //               selectedFacture[0].closest("tr").dataset.numFacture
    //             );
    //         }
    //         //modal message plur
    //         else {
    //           modalRestoreFacture.querySelector(".message").innerHTML =
    //             lang.question_restore_facture_plur.replace(
    //               ":field",
    //               selectedFacture.length
    //             );
    //         }

    //         //show modal restore facture
    //         new bootstrap.Modal(modalRestoreFacture).show();

    //         //==== EVENT btn confirm modal restore facture
    //         modalRestoreFacture
    //           .querySelector("#btn-confirm-modal-restore-facture")
    //           .addEventListener("click", async () => {
    //             try {
    //               //nums_facture
    //               let nums_facture = [...selectedFacture];
    //               nums_facture = nums_facture.map(
    //                 (selected) => selected.closest("tr").dataset.numFacture
    //               );

    //               //FETCH api restore facture
    //               const apiRestoreFacture = await apiRequest(
    //                 "/entree/restore_all_facture",
    //                 {
    //                   method: "PUT",
    //                   body: {
    //                     nums_facture: nums_facture,
    //                   },
    //                 }
    //               );

    //               //error
    //               if (apiRestoreFacture.message_type === "error") {
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
    //                   apiRestoreFacture.message;
    //                 //progress bar
    //                 progressBar.style.transition = "width 20s linear";
    //                 progressBar.style.width = "100%";

    //                 //add alert
    //                 modalRestoreFacture.querySelector(".modal-body").prepend(alert);

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
    //               else if (apiRestoreFacture.message_type === "invalid") {
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
    //                   apiRestoreFacture.message;
    //                 //progress bar
    //                 progressBar.style.transition = "width 10s linear";
    //                 progressBar.style.width = "100%";
    //                 s;

    //                 //add alert
    //                 modalRestoreFacture.querySelector(".modal-body").prepend(alert);

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
    //                 apiRestoreFacture.message;
    //               //progress bar
    //               progressBar.style.transition = "width 10s linear";
    //               progressBar.style.width = "100%";

    //               //add alert
    //               container
    //                 .querySelector("#tbody-facture")
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
    //               modalRestoreFacture
    //                 .querySelector("#btn-close-modal-restore-facture")
    //                 .click();

    //               //refresh filter facture
    //               filterFacture(
    //                 selectStatus.value.trim(),
    //                 selectArrangeBy.value.trim(),
    //                 selectOrder.value.trim(),
    //                 dateFrom.value.trim(),
    //                 dateTo.value.trim(),
    //                 selectNumCaisse ? $(selectNumCaisse).val().trim() : "",
    //                 $(selectIdUtilisateur).val().trim(),
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

  //   //====================== FUNCTIONS ========================
  //function - filter sortie
  async function filterSortie(
    status,
    arrange_by,
    order,
    from,
    to,
    num_caisse,
    id_utilisateur,
    search_sortie
  ) {
    //tbody
    const tbodySortie = container.querySelector("#tbody-sortie");
    try {
      //FETCH api filter sortie
      const apiFilterSortie = await apiRequest(
        `/sortie/filter_demande_sortie?status=${status}&arrange_by=${arrange_by}&order=${order}&from=${from}&to=${to}&num_caisse=${num_caisse}&id_user=${id_utilisateur}&search_ds=${search_sortie}`
      );

      //error
      if (apiFilterSortie.message_type === "error") {
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
          apiFilterSortie.message;
        //progress bar
        progressBar.style.transition = "width 20s linear";
        progressBar.style.width = "100%";

        //add alert
        tbodySortie.closest("div").prepend(alert);

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
      else if (apiFilterSortie.message_type === "invalid") {
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
          apiFilterSortie.message;
        //progress bar
        progressBar.style.transition = "width 10s linear";
        progressBar.style.width = "100%";

        //add alert
        tbodySortie.closest("div").prepend(alert);

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

      //===== TABLE sortie
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
      //set table list sortie
      tbodySortie.innerHTML = "";
      apiFilterSortie.data.forEach((line) => {
        const tr = document.createElement("tr");

        //td - checkbox
        if (container.querySelector("#check-all-sortie")) {
          const tdCheckbox = document.createElement("td");
          tdCheckbox.innerHTML =
            "<input type='checkbox' class='form-check-input'>";
          tdCheckbox.classList.add("text-center");
          tr.append(tdCheckbox);
        }

        //td - num_ds
        const tdNumDs = document.createElement("td");
        tdNumDs.textContent = line.num_ds;
        tdNumDs.classList.add("text-center");

        //td - date_ds
        const tdDateDs = document.createElement("td");
        tdDateDs.textContent = fromatterDate.format(new Date(line.date_ds));
        tdDateDs.classList.add("text-center");

        //td - montant_ds
        const tdMontantDs = document.createElement("td");
        tdMontantDs.textContent = formatterNumber.format(
          Number(line.montant_ds)
        );
        tdMontantDs.classList.add("text-center");

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
        if (line.etat_ds === "supprimé") {
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
        //btn update ds
        const btnUpdateDs = document.createElement("button");
        btnUpdateDs.type = "button";
        btnUpdateDs.classList.add(
          "btn-light",
          "btn",
          "btn-sm",
          "text-primary",
          "btn-update-ds"
        );
        btnUpdateDs.innerHTML = "<i class='fad fa-pen-to-square'></i>";
        //btn correction ds inflow
        const btnCorrectionDsInflow = document.createElement("button");
        btnCorrectionDsInflow.type = "button";
        btnCorrectionDsInflow.classList.add(
          "btn-light",
          "btn",
          "btn-sm",
          "text-success",
          "btn-correction-ds-inflow"
        );
        btnCorrectionDsInflow.innerHTML = "<i class='fad fa-circle-minus'></i>";
        //btn correction ds outflow
        const btnCorrectionDsOutflow = document.createElement("button");
        btnCorrectionDsOutflow.type = "button";
        btnCorrectionDsOutflow.classList.add(
          "btn-light",
          "btn",
          "btn-sm",
          "text-danger",
          "btn-correction-ds-outflow"
        );
        btnCorrectionDsOutflow.innerHTML = "<i class='fad fa-circle-plus'></i>";
        //btn print ds
        const btnPrintDs = document.createElement("button");
        btnPrintDs.type = "button";
        btnPrintDs.classList.add("btn-light", "btn-sm", "btn", "btn-print-ds");
        btnPrintDs.innerHTML = "<i class='fad fa-print'></i>";
        //append btn actions
        divActions.append(
          btnUpdateDs,
          btnCorrectionDsInflow,
          btnCorrectionDsOutflow,
          btnPrintDs
        );
        tdActions.append(divActions);

        //append
        tr.append(
          tdNumDs,
          tdDateDs,
          tdMontantDs,
          tdIdUtilisateur,
          tdNumCaisse,
          tdStatus,
          tdActions
        );

        tr.dataset.numDs = line.num_ds;
        tr.dataset.dateDs = line.date_ds;
        tbodySortie.appendChild(tr);
      });

      // //foreach tr
      // let selectedRow = null;
      // const allTr = tbodyFacture.querySelectorAll("tr");
      // allTr.forEach((tr) => {
      //   //===================== CORRECTION FACTURE ==================
      //   //modal correction facture
      //   const modalCorrectionFacture = container.querySelector(
      //     "#modal-correction-facture"
      //   );

      //   //===== EVENT btn correct facture
      //   tr.querySelector(".btn-correct-facture").addEventListener(
      //     "click",
      //     async () => {
      //       //num_facture
      //       modalCorrectionFacture.querySelector(
      //         "#correction-facture-num-facture"
      //       ).innerHTML = tr.dataset.numFacture;
      //       //select - correction facture id_utilisateur
      //       const selectCorrectionFactureIdUtilisateur =
      //         modalCorrectionFacture.querySelector(
      //           "#select-correction-facture-id-utilisateur"
      //         );
      //       if (selectCorrectionFactureIdUtilisateur) {
      //         $(selectCorrectionFactureIdUtilisateur).select2({
      //           theme: "bootstrap-5",
      //           placeholder: lang.select.toLowerCase(),
      //           dropdownParent: $(modalCorrectionFacture),
      //         });
      //         listUser(selectCorrectionFactureIdUtilisateur, true);

      //         //===== EVENT refresh list user
      //         modalCorrectionFacture
      //           .querySelector("#btn-correction-facture-refresh-id-utilisateur")
      //           .addEventListener("click", () => {
      //             listUser(selectCorrectionFactureIdUtilisateur, true);
      //           });
      //       }

      //       //list ligne_facture
      //       await listLigneFacture(
      //         tr.dataset.numFacture,
      //         modalCorrectionFacture.querySelector("#div-ligne-facture")
      //       );
      //       //===== EVENT btn refresh list ligne_caisse
      //       modalCorrectionFacture
      //         .querySelector("#btn-correction-facture-refresh-ligne-facture")
      //         .addEventListener("click", () => {
      //           listLigneFacture(
      //             tr.dataset.numFacture,
      //             modalCorrectionFacture.querySelector("#div-ligne-facture")
      //           );
      //         });

      //       //show modal correction facture
      //       new bootstrap.Modal(modalCorrectionFacture).show();
      //     }
      //   );

      //   //===== EVENT btn print facture
      //   tr.querySelector(".btn-print-facture").addEventListener(
      //     "click",
      //     async () => {
      //       try {
      //         //FETCH api print facture
      //         const apiPrintFacture = await apiRequest(
      //           `/entree/print_facture?num_facture=${tr.dataset.numFacture}`
      //         );

      //         //error
      //         if (apiPrintFacture.message_type === "error") {
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
      //           container
      //             .querySelector("#tbody-facture")
      //             .closest("div")
      //             .prepend(alert);

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
      //         else if (apiPrintFacture.message_type === "invalid") {
      //           //alert
      //           const alertTemplate = document.querySelector(".alert-template");
      //           const clone = alertTemplate.content.cloneNode(true);
      //           const alert = clone.querySelector(".alert");
      //           const progressBar = alert.querySelector(".progress-bar");
      //           //alert type
      //           alert.classList.add("alert-warning");
      //           //icon
      //           alert
      //             .querySelector(".fad")
      //             .classList.add("fa-exclamation-circle");
      //           //message
      //           alert.querySelector(".alert-message").innerHTML =
      //             apiPrintFacture.message;
      //           //progress bar
      //           progressBar.style.transition = "width 10s linear";
      //           progressBar.style.width = "100%";

      //           //add alert
      //           container
      //             .querySelector("#tbody-facture")
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

      //         //download facture
      //         const a = document.createElement("a");
      //         a.href = `data:application/pdf;base64,${apiPrintFacture.pdf}`;
      //         a.download = apiPrintFacture.file_name;
      //         a.click();

      //         return;
      //       } catch (e) {
      //         console.error(e);
      //       }
      //     }
      //   );

      //   //===== EVENT tr selection
      //   tr.addEventListener("click", () => {
      //     //remove selection
      //     if (selectedRow && selectedRow === tr) {
      //       tr.classList.remove("active");
      //       selectedRow = null;

      //       //remove table lf facture num
      //       container.querySelector("#table-lf-facture-num").innerHTML = "";

      //       //remove tbody lf
      //       container.querySelector("#tbody-lf").innerHTML = `  <tr>
      //                                                         <td colspan="9">
      //                                                             <span class="bg-second placeholder w-100 rounded-1" style="height: 2vh !important;"></span>
      //                                                         </td>
      //                                                     </tr>`;
      //     }
      //     //add selection
      //     else {
      //       //deselect all
      //       allTr.forEach((tr0) => {
      //         tr0.classList.remove("active");
      //       });

      //       //add selection
      //       tr.classList.add("active");
      //       selectedRow = tr;

      //       //add table lf facture num
      //       container.querySelector("#table-lf-facture-num").innerHTML =
      //         tr.dataset.numFacture;

      //       //list facture connection
      //       listConnectionFacture(tr.dataset.numFacture.trim());
      //     }
      //   });
      // });

      // //===== EVENT check all
      // const inputCheckAll = container.querySelector("#check-all-facture");
      // if (inputCheckAll) {
      //   inputCheckAll.addEventListener("change", (e) => {
      //     tbodyFacture
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
  //function - list article
  async function listArticle(select) {
    try {
      //FETCH api list all article
      const apiListAllArticle = await apiRequest("/article/list_all_article");

      //error
      if (apiListAllArticle.message_type === "error") {
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
          apiListAllArticle.message;
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

      //success api list article
      select.innerHTML = `<option></option>`;

      apiListAllArticle.data.forEach((line) => {
        const option = document.createElement("option");
        option.value = line.id_article;
        option.innerText = `${line.id_article} - ${line.libelle_article}`;
        option.dataset.prixArticle = line.prix_article;
        option.dataset.quantiteArticle = line.quantite_article;

        select.append(option);
      });
    } catch (e) {
      console.error(e);
    }
  }
  //   //function - list ligne_facture
  //   async function listLigneFacture(numFacture, div) {
  //     try {
  //       //FETCH api list ligne_facture
  //       const apiListLigneFacture = await apiRequest(
  //         `/entree/list_ligne_facture?num_facture=${numFacture}`
  //       );

  //       //error
  //       if (apiListLigneFacture.message_type === "error") {
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
  //           apiListLigneFacture.message;
  //         //progress bar
  //         progressBar.style.transition = "width 20s linear";
  //         progressBar.style.width = "100%";

  //         //add alert
  //         div.prepend(alert);

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
  //       else if (apiListLigneFacture.message_type === "invalid") {
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
  //           apiListLigneFacture.message;
  //         //progress bar
  //         progressBar.style.transition = "width 10s linear";
  //         progressBar.style.width = "100%";

  //         //add alert
  //         div.prepend(alert);

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

  //       //success api list ligne_facture
  //       div.innerHTML = "";
  //       apiListLigneFacture.data.forEach((line) => {
  //         const divLF = document.createElement("div");
  //         divLF.classList.add(
  //           "p-2",
  //           "d-flex",
  //           "gap-2",
  //           "bg-second",
  //           "rounded-1",
  //           "text-secondary",
  //           "div-lf",
  //           "flex-wrap"
  //         );
  //         divLF.dataset.prixTotal = line.prix_total;
  //         divLF.dataset.idLf = line.id_lf;
  //         divLF.dataset.prix = line.prix;
  //         divLF.dataset.idProduit = line.id_produit;
  //         divLF.innerHTML = `${line.id_lf} - ${
  //           line.libelle_produit
  //         } <span class='total-prix me-2'>(${formatterTotal.format(
  //           Number(divLF.dataset.prixTotal)
  //         )})</span><input class='form-control form-control-sm' type='number' min='0' max='${
  //           line.quantite_produit
  //         }' value='${line.quantite_produit}'>`;

  //         //append div lf
  //         div.append(divLF);
  //       });

  //       //correction facture total
  //       const correctionFactureTotal = container.querySelector(
  //         "#correction-facture-total"
  //       );
  //       let prixTotal = 0;
  //       //foreach divLF
  //       div.querySelectorAll(".div-lf").forEach((divLF) => {
  //         //prix total
  //         prixTotal += Number(divLF.dataset.prixTotal);

  //         //===== EVENT input quantite_produit
  //         divLF
  //           .querySelector("input[type='number']")
  //           .addEventListener("input", (e) => {
  //             e.target.value = e.target.value.replace(/[^0-9]/g, "");

  //             //div prix total
  //             divLF.dataset.prixTotal =
  //               Number(divLF.dataset.prix) * Number(e.target.value);
  //             divLF.querySelector(
  //               ".total-prix"
  //             ).innerHTML = `(${formatterTotal.format(
  //               Number(divLF.dataset.prixTotal)
  //             )})`;

  //             //prix total
  //             let prixTotal0 = 0;
  //             div.querySelectorAll(".div-lf").forEach((divLF0) => {
  //               //prix total 0
  //               prixTotal0 += Number(divLF0.dataset.prixTotal);
  //             });
  //             correctionFactureTotal.innerHTML =
  //               formatterTotal.format(prixTotal0);
  //           });
  //       });
  //       correctionFactureTotal.innerHTML = formatterTotal.format(prixTotal);
  //     } catch (e) {
  //       console.error(e);
  //     }
  //   }
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
  //           tdIdAe.textContent = line.num_ae;
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
  //       //sortie
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
