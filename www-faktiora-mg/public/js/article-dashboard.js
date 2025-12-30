document.addEventListener("DOMContentLoaded", async () => {
  //template real content
  const templateRealContent = document.getElementById("template-produit");
  //   //config
  //   let config = "",
  //     currencyUnits = "",
  //     cookieLangValue = "";
  //   const f = async () => {
  //     try {
  //       //cookie lang value
  //       cookieLangValue = document.cookie
  //         .split("; ")
  //         .find((row) => row.startsWith(`lang=`))
  //         ?.split("=")[1];
  //       //get currency units
  //       config = await fetch(`${SITE_URL}/config/config.json`);
  //       if (!config.ok) throw new Error(`HTTP ${config.status}`);
  //       currencyUnits = (await config.json()).currency_units;
  //     } catch (e) {
  //       console.error(e);
  //     }
  //   };
  //   await f();

  //   //formatter
  //   let formatterNumber, formatterTotal;
  //   //en
  //   if (cookieLangValue === "en") {
  //     formatterNumber = new Intl.NumberFormat("en-US", {
  //       style: "decimal",
  //       minimumFractionDigits: 0,
  //       maximumFractionDigits: 2,
  //     });
  //     formatterTotal = new Intl.NumberFormat("en-US", {
  //       style: "currency",
  //       currency: currencyUnits,
  //       minimumFractionDigits: 0,
  //       maximumFractionDigits: 2,
  //     });
  //   }
  //   //fr && mg
  //   else {
  //     formatterNumber = new Intl.NumberFormat("fr-FR", {
  //       style: "decimal",
  //       minimumFractionDigits: 0,
  //       maximumFractionDigits: 2,
  //     });
  //     formatterTotal = new Intl.NumberFormat("fr-FR", {
  //       style: "currency",
  //       currency: currencyUnits,
  //       minimumFractionDigits: 0,
  //       maximumFractionDigits: 2,
  //     });
  //   }

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
      filterArticle(
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
      filterArticle(
        e.target.value.trim(),
        selectArrangeBy.value.trim(),
        selectOrder.value.trim(),
        inputSearch.value.trim()
      );
      localStorage.setItem(e.target.id, e.target.value);
    });
    //===== EVENT select arrange_by
    selectArrangeBy.addEventListener("change", (e) => {
      filterArticle(
        selectStatus.value.trim(),
        e.target.value.trim(),
        selectOrder.value.trim(),
        inputSearch.value.trim()
      );
      localStorage.setItem(e.target.id, e.target.value);
    });
    //===== EVENT select order
    selectOrder.addEventListener("change", (e) => {
      filterArticle(
        selectStatus.value.trim(),
        selectArrangeBy.value.trim(),
        e.target.value.trim(),
        inputSearch.value.trim()
      );
      localStorage.setItem(e.target.id, e.target.value);
    });

    //======================== FILTER ARTICLE ==================
    await filterArticle(
      selectStatus.value.trim(),
      selectArrangeBy.value.trim(),
      selectOrder.value.trim(),
      inputSearch.value.trim()
    );

    //======================== ADD ARTICLE ======================
    //modal add article
    const modalAddArticle = container.querySelector("#modal-add-article");
    //input - add article libelle_article
    const inputAddArticleLibelleArticle = modalAddArticle.querySelector(
      "#input-add-article-libelle-article"
    );
    const savedInputAddArticleLibelleArticle = localStorage.getItem(
      inputAddArticleLibelleArticle.id
    );
    inputAddArticleLibelleArticle.value = !savedInputAddArticleLibelleArticle
      ? ""
      : savedInputAddArticleLibelleArticle;

    //===== EVENT input add article libelle_article
    inputAddArticleLibelleArticle.addEventListener("input", (e) => {
      e.target.value = e.target.value.replace("  ", " ");

      localStorage.setItem(e.target.id, e.target.value);
    });

    //===== EVENT modal add article form submit
    modalAddArticle
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
          //FETCH api add article
          const apiAddArticle = await apiRequest("/article/create_article", {
            method: "POST",
            body: {
              libelle_article: inputAddArticleLibelleArticle.value.trim(),
            },
          });

          //invalid
          if (apiAddArticle.message_type === "invalid") {
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
              apiAddArticle.message;
            //progress bar
            progressBar.style.transition = "width 10s linear";
            progressBar.style.width = "100%";

            //add alert
            modalAddArticle.querySelector(".modal-body").prepend(alert);

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
          else if (apiAddArticle.message_type === "error") {
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
              apiAddArticle.message;
            //progress bar
            progressBar.style.transition = "width 10s linear";
            progressBar.style.width = "100%";

            //add alert
            modalAddArticle.querySelector(".modal-body").prepend(alert);

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
            apiAddArticle.message;
          //progress bar
          progressBar.style.transition = "width 10s linear";
          progressBar.style.width = "100%";

          //add alert
          container
            .querySelector("#tbody-article")
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
          modalAddArticle.querySelector("#btn-close-modal-add-article").click();

          //refresh filter article
          filterArticle(
            selectStatus.value.trim(),
            selectArrangeBy.value.trim(),
            selectOrder.value.trim(),
            inputSearch.value.trim()
          );
        } catch (e) {
          console.error(e);
        }
      });

    //======================== UPDATE ARTICLE ======================
    //modal update article
    const modalUpdateArticle = container.querySelector("#modal-update-article");

    //===== EVENT modal update  article form submit
    modalUpdateArticle
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
          //FETCH api update article
          const apiUpdateArticle = await apiRequest("/article/update_article", {
            method: "PUT",
            body: {
              id_article: modalUpdateArticle
                .querySelector("#update-article-id-article")
                .textContent.trim(),
              libelle_article: modalUpdateArticle
                .querySelector("#input-update-article-libelle-article")
                .value.trim(),
            },
          });

          //invalid
          if (apiUpdateArticle.message_type === "invalid") {
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
              apiUpdateArticle.message;
            //progress bar
            progressBar.style.transition = "width 10s linear";
            progressBar.style.width = "100%";

            //add alert
            modalUpdateArticle.querySelector(".modal-body").prepend(alert);

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
          else if (apiUpdateArticle.message_type === "error") {
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
              apiUpdateArticle.message;
            //progress bar
            progressBar.style.transition = "width 10s linear";
            progressBar.style.width = "100%";

            //add alert
            modalUpdateArticle.querySelector(".modal-body").prepend(alert);

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
            apiUpdateArticle.message;
          //progress bar
          progressBar.style.transition = "width 10s linear";
          progressBar.style.width = "100%";

          //add alert
          container
            .querySelector("#tbody-article")
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

          //hide modal
          modalUpdateArticle
            .querySelector("#btn-close-modal-update-article")
            .click();

          //refresh filter article
          filterArticle(
            selectStatus.value.trim(),
            selectArrangeBy.value.trim(),
            selectOrder.value.trim(),
            inputSearch.value.trim()
          );
        } catch (e) {
          console.error(e);
        }
      });

    //     //========================= DELETE PRODUIT =================
    //     //btn delete produit
    //     const btnDeleteProduit = container.querySelector("#btn-delete-produit");
    //     //===== EVENT btn delete produit
    //     if (btnDeleteProduit) {
    //       btnDeleteProduit.addEventListener("click", () => {
    //         //modal delete produit
    //         const modalDeleteProduit = container.querySelector(
    //           "#modal-delete-produit"
    //         );
    //         //selected produit
    //         const selectedProduit = container.querySelectorAll(
    //           "#tbody-produit input[type='checkbox']:checked"
    //         );

    //         //no selection
    //         if (selectedProduit.length <= 0) {
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
    //             lang.produit_ids_produit_empty;
    //           //progress bar
    //           progressBar.style.transition = "width 10s linear";
    //           progressBar.style.width = "100%";

    //           //add alert
    //           container
    //             .querySelector("#tbody-produit")
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
    //         if (selectedProduit.length === 1) {
    //           modalDeleteProduit.querySelector(".message").innerHTML =
    //             lang.question_delete_produit_1.replace(
    //               ":field",
    //               selectedProduit[0].closest("tr").dataset.idProduit
    //             );
    //         }
    //         //modal message plur
    //         else {
    //           modalDeleteProduit.querySelector(".message").innerHTML =
    //             lang.question_delete_produit_plur.replace(
    //               ":field",
    //               selectedProduit.length
    //             );
    //         }

    //         //show modal delete produit
    //         new bootstrap.Modal(modalDeleteProduit).show();

    //         //==== EVENT btn confirm modal delete produit
    //         modalDeleteProduit
    //           .querySelector("#btn-confirm-modal-delete-produit")
    //           .addEventListener("click", async () => {
    //             try {
    //               //ids_produit
    //               let ids_produit = [...selectedProduit];
    //               ids_produit = ids_produit.map(
    //                 (selected) => selected.closest("tr").dataset.idProduit
    //               );

    //               //FETCH api delete produit
    //               const apiDeleteProduit = await apiRequest(
    //                 "/produit/delete_all_produit",
    //                 {
    //                   method: "PUT",
    //                   body: {
    //                     ids_produit: ids_produit,
    //                   },
    //                 }
    //               );

    //               //error
    //               if (apiDeleteProduit.message_type === "error") {
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
    //                   apiDeleteProduit.message;
    //                 //progress bar
    //                 progressBar.style.transition = "width 20s linear";
    //                 progressBar.style.width = "100%";

    //                 //add alert
    //                 modalDeleteProduit.querySelector(".modal-body").prepend(alert);

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
    //               else if (apiDeleteProduit.message_type === "invalid") {
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
    //                   apiDeleteProduit.message;
    //                 //progress bar
    //                 progressBar.style.transition = "width 10s linear";
    //                 progressBar.style.width = "100%";

    //                 //add alert
    //                 modalDeleteProduit.querySelector(".modal-body").prepend(alert);

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
    //                 apiDeleteProduit.message;
    //               //progress bar
    //               progressBar.style.transition = "width 10s linear";
    //               progressBar.style.width = "100%";

    //               //add alert
    //               container
    //                 .querySelector("#tbody-produit")
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
    //               modalDeleteProduit
    //                 .querySelector("#btn-close-modal-delete-produit")
    //                 .click();

    //               //refresh filter produit
    //               filterProduit(
    //                 selectStatus.value.trim(),
    //                 selectArrangeBy.value.trim(),
    //                 selectOrder.value.trim(),
    //                 inputSearch.value.trim()
    //               );

    //               return;
    //             } catch (e) {
    //               console.error(e);
    //             }
    //           });
    //       });
    //     }

    //     //========================= DELETE PERMANENT PRODUIT =================
    //     //btn delete permanent produit
    //     const btnDeletePermanentProduit = container.querySelector(
    //       "#btn-delete-permanent-produit"
    //     );
    //     //===== EVENT btn delete produit
    //     if (btnDeletePermanentProduit) {
    //       btnDeletePermanentProduit.addEventListener("click", () => {
    //         //modal delete produit
    //         const modalDeleteProduit = container.querySelector(
    //           "#modal-delete-produit"
    //         );
    //         //selected produit
    //         const selectedProduit = container.querySelectorAll(
    //           "#tbody-produit input[type='checkbox']:checked"
    //         );

    //         //no selection
    //         if (selectedProduit.length <= 0) {
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
    //             lang.produit_ids_produit_empty;
    //           //progress bar
    //           progressBar.style.transition = "width 10s linear";
    //           progressBar.style.width = "100%";

    //           //add alert
    //           container
    //             .querySelector("#tbody-produit")
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
    //         if (selectedProduit.length === 1) {
    //           modalDeleteProduit.querySelector(".message").innerHTML =
    //             lang.question_delete_permanent_produit_1.replace(
    //               ":field",
    //               selectedProduit[0].closest("tr").dataset.idProduit
    //             );
    //         }
    //         //modal message plur
    //         else {
    //           modalDeleteProduit.querySelector(".message").innerHTML =
    //             lang.question_delete_permanent_produit_plur.replace(
    //               ":field",
    //               selectedProduit.length
    //             );
    //         }

    //         //show modal delete produit
    //         new bootstrap.Modal(modalDeleteProduit).show();

    //         //==== EVENT btn confirm modal delete produit
    //         modalDeleteProduit
    //           .querySelector("#btn-confirm-modal-delete-produit")
    //           .addEventListener("click", async () => {
    //             try {
    //               //ids_produit
    //               let ids_produit = [...selectedProduit];
    //               ids_produit = ids_produit.map(
    //                 (selected) => selected.closest("tr").dataset.idProduit
    //               );

    //               //FETCH api delete produit
    //               const apiDeleteProduit = await apiRequest(
    //                 "/produit/delete_permanent_all_produit",
    //                 {
    //                   method: "DELETE",
    //                   body: {
    //                     ids_produit: ids_produit,
    //                   },
    //                 }
    //               );

    //               //error
    //               if (apiDeleteProduit.message_type === "error") {
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
    //                   apiDeleteProduit.message;
    //                 //progress bar
    //                 progressBar.style.transition = "width 20s linear";
    //                 progressBar.style.width = "100%";

    //                 //add alert
    //                 modalDeleteProduit.querySelector(".modal-body").prepend(alert);

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
    //               else if (apiDeleteProduit.message_type === "invalid") {
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
    //                   apiDeleteProduit.message;
    //                 //progress bar
    //                 progressBar.style.transition = "width 10s linear";
    //                 progressBar.style.width = "100%";

    //                 //add alert
    //                 modalDeleteProduit.querySelector(".modal-body").prepend(alert);

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
    //                 apiDeleteProduit.message;
    //               //progress bar
    //               progressBar.style.transition = "width 10s linear";
    //               progressBar.style.width = "100%";

    //               //add alert
    //               container
    //                 .querySelector("#tbody-produit")
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
    //               modalDeleteProduit
    //                 .querySelector("#btn-close-modal-delete-produit")
    //                 .click();

    //               //refresh filter produit
    //               filterProduit(
    //                 selectStatus.value.trim(),
    //                 selectArrangeBy.value.trim(),
    //                 selectOrder.value.trim(),
    //                 inputSearch.value.trim()
    //               );

    //               return;
    //             } catch (e) {
    //               console.error(e);
    //             }
    //           });
    //       });
    //     }

    //     //========================= RESTORE PRODUIT =================
    //     //btn restore produit
    //     const btnRestoreProduit = container.querySelector("#btn-restore-produit");
    //     //===== EVENT btn restore produit
    //     if (btnRestoreProduit) {
    //       btnRestoreProduit.addEventListener("click", () => {
    //         //modal Restore produit
    //         const modalRestoreProduit = container.querySelector(
    //           "#modal-restore-produit"
    //         );
    //         //selected produit
    //         const selectedProduit = container.querySelectorAll(
    //           "#tbody-produit input[type='checkbox']:checked"
    //         );

    //         //no selection
    //         if (selectedProduit.length <= 0) {
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
    //             lang.produit_ids_produit_empty;
    //           //progress bar
    //           progressBar.style.transition = "width 10s linear";
    //           progressBar.style.width = "100%";

    //           //add alert
    //           container
    //             .querySelector("#tbody-produit")
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
    //         if (selectedProduit.length === 1) {
    //           modalRestoreProduit.querySelector(".message").innerHTML =
    //             lang.question_restore_produit_1.replace(
    //               ":field",
    //               selectedProduit[0].closest("tr").dataset.idProduit
    //             );
    //         }
    //         //modal message plur
    //         else {
    //           modalRestoreProduit.querySelector(".message").innerHTML =
    //             lang.question_restore_produit_plur.replace(
    //               ":field",
    //               selectedProduit.length
    //             );
    //         }

    //         //show modal restore produit
    //         new bootstrap.Modal(modalRestoreProduit).show();

    //         //==== EVENT btn confirm modal restore produit
    //         modalRestoreProduit
    //           .querySelector("#btn-confirm-modal-restore-produit")
    //           .addEventListener("click", async () => {
    //             try {
    //               //ids_produit
    //               let ids_produit = [...selectedProduit];
    //               ids_produit = ids_produit.map(
    //                 (selected) => selected.closest("tr").dataset.idProduit
    //               );

    //               //FETCH api restore produit
    //               const apiRestoreProduit = await apiRequest(
    //                 "/produit/restore_all_produit",
    //                 {
    //                   method: "PUT",
    //                   body: {
    //                     ids_produit: ids_produit,
    //                   },
    //                 }
    //               );

    //               //error
    //               if (apiRestoreProduit.message_type === "error") {
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
    //                   apiRestoreProduit.message;
    //                 //progress bar
    //                 progressBar.style.transition = "width 20s linear";
    //                 progressBar.style.width = "100%";

    //                 //add alert
    //                 modalRestoreProduit.querySelector(".modal-body").prepend(alert);

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
    //               else if (apiRestoreProduit.message_type === "invalid") {
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
    //                   apiRestoreProduit.message;
    //                 //progress bar
    //                 progressBar.style.transition = "width 10s linear";
    //                 progressBar.style.width = "100%";

    //                 //add alert
    //                 modalRestoreProduit.querySelector(".modal-body").prepend(alert);

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
    //                 apiRestoreProduit.message;
    //               //progress bar
    //               progressBar.style.transition = "width 10s linear";
    //               progressBar.style.width = "100%";

    //               //add alert
    //               container
    //                 .querySelector("#tbody-produit")
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
    //               modalRestoreProduit
    //                 .querySelector("#btn-close-modal-restore-produit")
    //                 .click();

    //               //refresh filter produit
    //               filterProduit(
    //                 selectStatus.value.trim(),
    //                 selectArrangeBy.value.trim(),
    //                 selectOrder.value.trim(),
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

  //function - filter article
  async function filterArticle(status, arrange_by, order, search_article) {
    //tbody
    const tbodyArticle = container.querySelector("#tbody-article");

    try {
      //FETCH api filter article
      const apiFilterArticle = await apiRequest(
        `/article/filter_article?status=${status}&arrange_by=${arrange_by}&order=${order}&search_article=${search_article}`
      );

      //error
      if (apiFilterArticle.message_type === "error") {
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
          apiFilterArticle.message;
        //progress bar
        progressBar.style.transition = "width 20s linear";
        progressBar.style.width = "100%";

        //add alert
        tbodyArticle.closest("div").prepend(alert);

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
      else if (apiFilterArticle.message_type === "invalid") {
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
          apiFilterArticle.message;
        //progress bar
        progressBar.style.transition = "width 10s linear";
        progressBar.style.width = "100%";

        //add alert
        tbodyArticle.closest("div").prepend(alert);

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

      //===== TABLE article
      //set table list article
      tbodyArticle.innerHTML = "";
      apiFilterArticle.data.forEach((line) => {
        const tr = document.createElement("tr");

        //td - checkbox
        if (container.querySelector("#check-all-article")) {
          const tdCheckbox = document.createElement("td");
          tdCheckbox.innerHTML =
            "<input type='checkbox' class='form-check-input'>";
          tdCheckbox.classList.add("text-center");
          tr.append(tdCheckbox);
        }

        //td - id_article
        const tdIdArticle = document.createElement("td");
        tdIdArticle.textContent = line.id_article;
        tdIdArticle.classList.add("text-center");

        //td - libelle_article
        const tdLibelleArticle = document.createElement("td");
        tdLibelleArticle.textContent = line.libelle_article;
        tdLibelleArticle.classList.add("text-center");

        //td - status
        const tdStatus = document.createElement("td");
        if (line.etat_article === "supprimé") {
          tdStatus.textContent = lang.deleted;
        } else {
          tdStatus.textContent = lang.active.toLowerCase();
        }
        tdStatus.classList.add("text-center");

        //append
        tr.append(tdIdArticle, tdLibelleArticle, tdStatus);

        //td - actions
        const tdActions = document.createElement("td");
        const divActions = document.createElement("div");
        divActions.classList.add(
          "d-flex",
          "justify-content-center",
          "align-items-center"
        );
        //btn update article
        const btnUpdateArticle = document.createElement("button");
        btnUpdateArticle.type = "button";
        btnUpdateArticle.classList.add(
          "btn-light",
          "btn",
          "btn-sm",
          "text-primary",
          "btn-update-article"
        );
        btnUpdateArticle.innerHTML = "<i class='fad fa-pen-to-square'></i>";
        divActions.append(btnUpdateArticle);
        tdActions.append(divActions);
        tr.append(tdActions);

        tr.dataset.idArticle = line.id_article;
        tr.dataset.libelleArticle = line.libelle_article;
        tbodyArticle.appendChild(tr);
      });

      //foreach all tr
      tbodyArticle.querySelectorAll("tr").forEach((tr) => {
        //===================== UPDATE ARTICLE =================
        //==== EVENT btn update article
        tr.querySelector(".btn-update-article").addEventListener(
          "click",
          () => {
            //modal update article
            const modalUpdateArticle = container.querySelector(
              "#modal-update-article"
            );
            //modal update article id_article
            modalUpdateArticle.querySelector(
              "#update-article-id-article"
            ).innerHTML = tr.dataset.idArticle;
            //input - update article libelle_article
            const inputUpdateArticleLibelleArticle =
              modalUpdateArticle.querySelector(
                "#input-update-article-libelle-article"
              );
            inputUpdateArticleLibelleArticle.value = tr.dataset.libelleArticle;

            //===== EVENT input update article libelle_article
            inputUpdateArticleLibelleArticle.addEventListener("input", (e) => {
              e.target.value = e.target.value.replace("  ", " ");
            });

            //show modal update article
            new bootstrap.Modal(modalUpdateArticle).show();
          }
        );
      });

      //===== EVENT check all
      const inputCheckAll = container.querySelector("#check-all-article");
      if (inputCheckAll) {
        inputCheckAll.addEventListener("change", (e) => {
          tbodyArticle
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
