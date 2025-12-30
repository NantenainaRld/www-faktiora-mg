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

    //========================== UPDATE PRODUIT =======================
    //modal update produit
    const modalUpdateProduit = container.querySelector("#modal-update-produit");
    //===== EVENT modal update produit form submit
    modalUpdateProduit
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
          //FETCH api update produit
          const apiUpdateProduit = await apiRequest("/produit/update_produit", {
            method: "PUT",
            body: {
              id_produit: modalUpdateProduit
                .querySelector("#update-produit-id-produit")
                .textContent.trim(),
              libelle_produit: modalUpdateProduit
                .querySelector("#input-update-produit-libelle-produit")
                .value.trim(),
              prix_produit: modalUpdateProduit
                .querySelector("#input-update-produit-prix-produit")
                .value.replace(/[\u202F\u00A0 ]/g, "")
                .replace(",", "."),
              nb_stock: modalUpdateProduit
                .querySelector("#input-update-produit-nb-stock")
                .value.trim(),
            },
          });

          //invalid
          if (apiUpdateProduit.message_type === "invalid") {
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
              apiUpdateProduit.message;
            //progress bar
            progressBar.style.transition = "width 10s linear";
            progressBar.style.width = "100%";

            //add alert
            modalUpdateProduit.querySelector(".modal-body").prepend(alert);

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
          else if (apiUpdateProduit.message_type === "error") {
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
              apiUpdateProduit.message;
            //progress bar
            progressBar.style.transition = "width 10s linear";
            progressBar.style.width = "100%";

            //add alert
            modalUpdateProduit.querySelector(".modal-body").prepend(alert);

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
            apiUpdateProduit.message;
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
          modalUpdateProduit
            .querySelector("#btn-close-modal-update-produit")
            .click();

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

    //========================= DELETE PRODUIT =================
    //btn delete produit
    const btnDeleteProduit = container.querySelector("#btn-delete-produit");
    //===== EVENT btn delete produit
    if (btnDeleteProduit) {
      btnDeleteProduit.addEventListener("click", () => {
        //modal delete produit
        const modalDeleteProduit = container.querySelector(
          "#modal-delete-produit"
        );
        //selected produit
        const selectedProduit = container.querySelectorAll(
          "#tbody-produit input[type='checkbox']:checked"
        );

        //no selection
        if (selectedProduit.length <= 0) {
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
            lang.produit_ids_produit_empty;
          //progress bar
          progressBar.style.transition = "width 10s linear";
          progressBar.style.width = "100%";

          //add alert
          container
            .querySelector("#tbody-produit")
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
        if (selectedProduit.length === 1) {
          modalDeleteProduit.querySelector(".message").innerHTML =
            lang.question_delete_produit_1.replace(
              ":field",
              selectedProduit[0].closest("tr").dataset.idProduit
            );
        }
        //modal message plur
        else {
          modalDeleteProduit.querySelector(".message").innerHTML =
            lang.question_delete_produit_plur.replace(
              ":field",
              selectedProduit.length
            );
        }

        //show modal delete produit
        new bootstrap.Modal(modalDeleteProduit).show();

        //==== EVENT btn confirm modal delete produit
        modalDeleteProduit
          .querySelector("#btn-confirm-modal-delete-produit")
          .addEventListener("click", async () => {
            try {
              //ids_produit
              let ids_produit = [...selectedProduit];
              ids_produit = ids_produit.map(
                (selected) => selected.closest("tr").dataset.idProduit
              );

              //FETCH api delete produit
              const apiDeleteProduit = await apiRequest(
                "/produit/delete_all_produit",
                {
                  method: "PUT",
                  body: {
                    ids_produit: ids_produit,
                  },
                }
              );

              //error
              if (apiDeleteProduit.message_type === "error") {
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
                  apiDeleteProduit.message;
                //progress bar
                progressBar.style.transition = "width 20s linear";
                progressBar.style.width = "100%";

                //add alert
                modalDeleteProduit.querySelector(".modal-body").prepend(alert);

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
              else if (apiDeleteProduit.message_type === "invalid") {
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
                  apiDeleteProduit.message;
                //progress bar
                progressBar.style.transition = "width 10s linear";
                progressBar.style.width = "100%";

                //add alert
                modalDeleteProduit.querySelector(".modal-body").prepend(alert);

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
                apiDeleteProduit.message;
              //progress bar
              progressBar.style.transition = "width 10s linear";
              progressBar.style.width = "100%";

              //add alert
              container
                .querySelector("#tbody-produit")
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
              modalDeleteProduit
                .querySelector("#btn-close-modal-delete-produit")
                .click();

              //refresh filter produit
              filterProduit(
                selectStatus.value.trim(),
                selectArrangeBy.value.trim(),
                selectOrder.value.trim(),
                inputSearch.value.trim()
              );

              return;
            } catch (e) {
              console.error(e);
            }
          });
      });
    }

    //========================= DELETE PERMANENT PRODUIT =================
    //btn delete permanent produit
    const btnDeletePermanentProduit = container.querySelector(
      "#btn-delete-permanent-produit"
    );
    //===== EVENT btn delete produit
    if (btnDeletePermanentProduit) {
      btnDeletePermanentProduit.addEventListener("click", () => {
        //modal delete produit
        const modalDeleteProduit = container.querySelector(
          "#modal-delete-produit"
        );
        //selected produit
        const selectedProduit = container.querySelectorAll(
          "#tbody-produit input[type='checkbox']:checked"
        );

        //no selection
        if (selectedProduit.length <= 0) {
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
            lang.produit_ids_produit_empty;
          //progress bar
          progressBar.style.transition = "width 10s linear";
          progressBar.style.width = "100%";

          //add alert
          container
            .querySelector("#tbody-produit")
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
        if (selectedProduit.length === 1) {
          modalDeleteProduit.querySelector(".message").innerHTML =
            lang.question_delete_permanent_produit_1.replace(
              ":field",
              selectedProduit[0].closest("tr").dataset.idProduit
            );
        }
        //modal message plur
        else {
          modalDeleteProduit.querySelector(".message").innerHTML =
            lang.question_delete_permanent_produit_plur.replace(
              ":field",
              selectedProduit.length
            );
        }

        //show modal delete produit
        new bootstrap.Modal(modalDeleteProduit).show();

        //==== EVENT btn confirm modal delete produit
        modalDeleteProduit
          .querySelector("#btn-confirm-modal-delete-produit")
          .addEventListener("click", async () => {
            try {
              //ids_produit
              let ids_produit = [...selectedProduit];
              ids_produit = ids_produit.map(
                (selected) => selected.closest("tr").dataset.idProduit
              );

              //FETCH api delete produit
              const apiDeleteProduit = await apiRequest(
                "/produit/delete_permanent_all_produit",
                {
                  method: "DELETE",
                  body: {
                    ids_produit: ids_produit,
                  },
                }
              );

              //error
              if (apiDeleteProduit.message_type === "error") {
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
                  apiDeleteProduit.message;
                //progress bar
                progressBar.style.transition = "width 20s linear";
                progressBar.style.width = "100%";

                //add alert
                modalDeleteProduit.querySelector(".modal-body").prepend(alert);

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
              else if (apiDeleteProduit.message_type === "invalid") {
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
                  apiDeleteProduit.message;
                //progress bar
                progressBar.style.transition = "width 10s linear";
                progressBar.style.width = "100%";

                //add alert
                modalDeleteProduit.querySelector(".modal-body").prepend(alert);

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
                apiDeleteProduit.message;
              //progress bar
              progressBar.style.transition = "width 10s linear";
              progressBar.style.width = "100%";

              //add alert
              container
                .querySelector("#tbody-produit")
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
              modalDeleteProduit
                .querySelector("#btn-close-modal-delete-produit")
                .click();

              //refresh filter produit
              filterProduit(
                selectStatus.value.trim(),
                selectArrangeBy.value.trim(),
                selectOrder.value.trim(),
                inputSearch.value.trim()
              );

              return;
            } catch (e) {
              console.error(e);
            }
          });
      });
    }

    //========================= RESTORE PRODUIT =================
    //btn restore produit
    const btnRestoreProduit = container.querySelector("#btn-restore-produit");
    //===== EVENT btn restore produit
    if (btnRestoreProduit) {
      btnRestoreProduit.addEventListener("click", () => {
        //modal Restore produit
        const modalRestoreProduit = container.querySelector(
          "#modal-restore-produit"
        );
        //selected produit
        const selectedProduit = container.querySelectorAll(
          "#tbody-produit input[type='checkbox']:checked"
        );

        //no selection
        if (selectedProduit.length <= 0) {
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
            lang.produit_ids_produit_empty;
          //progress bar
          progressBar.style.transition = "width 10s linear";
          progressBar.style.width = "100%";

          //add alert
          container
            .querySelector("#tbody-produit")
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
        if (selectedProduit.length === 1) {
          modalRestoreProduit.querySelector(".message").innerHTML =
            lang.question_restore_produit_1.replace(
              ":field",
              selectedProduit[0].closest("tr").dataset.idProduit
            );
        }
        //modal message plur
        else {
          modalRestoreProduit.querySelector(".message").innerHTML =
            lang.question_restore_produit_plur.replace(
              ":field",
              selectedProduit.length
            );
        }

        //show modal restore produit
        new bootstrap.Modal(modalRestoreProduit).show();

        //==== EVENT btn confirm modal restore produit
        modalRestoreProduit
          .querySelector("#btn-confirm-modal-restore-produit")
          .addEventListener("click", async () => {
            try {
              //ids_produit
              let ids_produit = [...selectedProduit];
              ids_produit = ids_produit.map(
                (selected) => selected.closest("tr").dataset.idProduit
              );

              //FETCH api restore produit
              const apiRestoreProduit = await apiRequest(
                "/produit/restore_all_produit",
                {
                  method: "PUT",
                  body: {
                    ids_produit: ids_produit,
                  },
                }
              );

              //error
              if (apiRestoreProduit.message_type === "error") {
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
                  apiRestoreProduit.message;
                //progress bar
                progressBar.style.transition = "width 20s linear";
                progressBar.style.width = "100%";

                //add alert
                modalRestoreProduit.querySelector(".modal-body").prepend(alert);

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
              else if (apiRestoreProduit.message_type === "invalid") {
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
                  apiRestoreProduit.message;
                //progress bar
                progressBar.style.transition = "width 10s linear";
                progressBar.style.width = "100%";

                //add alert
                modalRestoreProduit.querySelector(".modal-body").prepend(alert);

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
                apiRestoreProduit.message;
              //progress bar
              progressBar.style.transition = "width 10s linear";
              progressBar.style.width = "100%";

              //add alert
              container
                .querySelector("#tbody-produit")
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
              modalRestoreProduit
                .querySelector("#btn-close-modal-restore-produit")
                .click();

              //refresh filter produit
              filterProduit(
                selectStatus.value.trim(),
                selectArrangeBy.value.trim(),
                selectOrder.value.trim(),
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

      //foreach all tr
      tbodyProduit.querySelectorAll("tr").forEach((tr) => {
        //========================== UPDATE PRODUIT ===========================
        //==== EVENT btn update produit
        const btnUpdateProduit = tr.querySelector(".btn-update-produit");
        if (btnUpdateProduit) {
          btnUpdateProduit.addEventListener("click", () => {
            //modal update produit
            const modalUpdateProduit = container.querySelector(
              "#modal-update-produit"
            );
            //modal update produit id_produit
            modalUpdateProduit.querySelector(
              "#update-produit-id-produit"
            ).innerHTML = tr.dataset.idProduit;
            //input - update produit libelle_produit
            const inputUpdateProduitLibelleProduit =
              modalUpdateProduit.querySelector(
                "#input-update-produit-libelle-produit"
              );
            inputUpdateProduitLibelleProduit.value = tr.dataset.libelleProduit;
            //input - update produit prix_produit
            const inputUpdatePorduitPrixProduit =
              modalUpdateProduit.querySelector(
                "#input-update-produit-prix-produit"
              );
            inputUpdatePorduitPrixProduit.value = formatterNumber.format(
              Number(tr.dataset.prixProduit)
            );
            inputUpdatePorduitPrixProduit.dataset.val = tr.dataset.prixProduit;
            //input - update produit nb_stock
            const inputUpdateProduitNbStock = modalUpdateProduit.querySelector(
              "#input-update-produit-nb-stock"
            );
            inputUpdateProduitNbStock.value = tr.dataset.nbStock;

            //===== EVENT input update produit libelle_produit
            inputUpdateProduitLibelleProduit.addEventListener("input", (e) => {
              e.target.value = e.target.value.replace("  ", " ");
            });
            //===== EVENT input update produit prix_produit
            inputUpdatePorduitPrixProduit.addEventListener("input", (e) => {
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
            inputUpdatePorduitPrixProduit.addEventListener("blur", (e) => {
              if (e.target.value.endsWith(",")) {
                e.target.value += "0";
              }

              if (e.target.value) {
                e.target.value = formatterNumber.format(
                  e.target.value
                    .replace(/[\u202F\u00A0 ]/g, "")
                    .replace(",", ".")
                );
              } else {
                e.target.value = "1";
                e.target.dataset.val = 1;
              }
            });
            //===== EVENT input update produit nb_stock
            inputUpdateProduitNbStock.addEventListener("input", (e) => {
              e.target.value = e.target.value.replace(/[^0-9]/g, "");
            });

            //show modal update produit
            new bootstrap.Modal(modalUpdateProduit).show();
          });
        }
      });

      //===== EVENT check all
      const inputCheckAll = container.querySelector("#check-all-produit");
      if (inputCheckAll) {
        inputCheckAll.addEventListener("change", (e) => {
          tbodyProduit
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
});
