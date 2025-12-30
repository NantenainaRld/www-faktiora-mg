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

    //========================= DELETE ARTICLE =================
    //btn delete article
    const btnDeleteArticle = container.querySelector("#btn-delete-article");
    //===== EVENT btn delete article
    if (btnDeleteArticle) {
      btnDeleteArticle.addEventListener("click", () => {
        //modal delete article
        const modalDeleteArticle = container.querySelector(
          "#modal-delete-article"
        );
        //selected article
        const selectedArticle = container.querySelectorAll(
          "#tbody-article input[type='checkbox']:checked"
        );

        //no selection
        if (selectedArticle.length <= 0) {
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
            lang.article_ids_article_empty;
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
          return;
        }

        //modal message 1
        if (selectedArticle.length === 1) {
          modalDeleteArticle.querySelector(".message").innerHTML =
            lang.question_delete_article_1.replace(
              ":field",
              selectedArticle[0].closest("tr").dataset.idArticle
            );
        }
        //modal message plur
        else {
          modalDeleteArticle.querySelector(".message").innerHTML =
            lang.question_delete_article_plur.replace(
              ":field",
              selectedArticle.length
            );
        }

        //show modal delete artilce
        new bootstrap.Modal(modalDeleteArticle).show();

        //==== EVENT btn confirm modal delete article
        modalDeleteArticle
          .querySelector("#btn-confirm-modal-delete-article")
          .addEventListener("click", async () => {
            try {
              //ids_article
              let ids_article = [...selectedArticle];
              ids_article = ids_article.map(
                (selected) => selected.closest("tr").dataset.idArticle
              );

              //FETCH api delete article
              const apiDeleteArticle = await apiRequest(
                "/article/delete_all_article",
                {
                  method: "PUT",
                  body: {
                    ids_article: ids_article,
                  },
                }
              );

              //error
              if (apiDeleteArticle.message_type === "error") {
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
                  apiDeleteArticle.message;
                //progress bar
                progressBar.style.transition = "width 20s linear";
                progressBar.style.width = "100%";

                //add alert
                modalDeleteArticle.querySelector(".modal-body").prepend(alert);

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
              else if (apiDeleteArticle.message_type === "invalid") {
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
                  apiDeleteArticle.message;
                //progress bar
                progressBar.style.transition = "width 10s linear";
                progressBar.style.width = "100%";

                //add alert
                modalDeleteArticle.querySelector(".modal-body").prepend(alert);

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
                apiDeleteArticle.message;
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

              //auto hide modal
              modalDeleteArticle
                .querySelector("#btn-close-modal-delete-article")
                .click();

              //refresh filter article
              filterArticle(
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

    //========================= DELETE PERMANENT ARTICLE =================
    //btn delete permanent article
    const btnDeletePermanentArticle = container.querySelector(
      "#btn-delete-permanent-article"
    );
    //===== EVENT btn delete permanent article
    if (btnDeletePermanentArticle) {
      btnDeletePermanentArticle.addEventListener("click", () => {
        //modal delete article
        const modalDeleteArticle = container.querySelector(
          "#modal-delete-article"
        );
        //selected article
        const selectedArticle = container.querySelectorAll(
          "#tbody-article input[type='checkbox']:checked"
        );

        //no selection
        if (selectedArticle.length <= 0) {
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
            lang.article_ids_article_empty;
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
          return;
        }

        //modal message 1
        if (selectedArticle.length === 1) {
          modalDeleteArticle.querySelector(".message").innerHTML =
            lang.question_delete_permanent_article_1.replace(
              ":field",
              selectedArticle[0].closest("tr").dataset.idArticle
            );
        }
        //modal message plur
        else {
          modalDeleteArticle.querySelector(".message").innerHTML =
            lang.question_delete_permanent_article_plur.replace(
              ":field",
              selectedArticle.length
            );
        }

        //show modal delete artilce
        new bootstrap.Modal(modalDeleteArticle).show();

        //==== EVENT btn confirm modal delete article
        modalDeleteArticle
          .querySelector("#btn-confirm-modal-delete-article")
          .addEventListener("click", async () => {
            try {
              //ids_article
              let ids_article = [...selectedArticle];
              ids_article = ids_article.map(
                (selected) => selected.closest("tr").dataset.idArticle
              );

              //FETCH api delete article
              const apiDeleteArticle = await apiRequest(
                "/article/delete_permanent_all_article",
                {
                  method: "DELETE",
                  body: {
                    ids_article: ids_article,
                  },
                }
              );

              //error
              if (apiDeleteArticle.message_type === "error") {
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
                  apiDeleteArticle.message;
                //progress bar
                progressBar.style.transition = "width 20s linear";
                progressBar.style.width = "100%";

                //add alert
                modalDeleteArticle.querySelector(".modal-body").prepend(alert);

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
              else if (apiDeleteArticle.message_type === "invalid") {
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
                  apiDeleteArticle.message;
                //progress bar
                progressBar.style.transition = "width 10s linear";
                progressBar.style.width = "100%";

                //add alert
                modalDeleteArticle.querySelector(".modal-body").prepend(alert);

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
                apiDeleteArticle.message;
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

              //auto hide modal
              modalDeleteArticle
                .querySelector("#btn-close-modal-delete-article")
                .click();

              //refresh filter article
              filterArticle(
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

    //btn restore article
    const btnRestoreArticle = container.querySelector("#btn-restore-article");
    //===== EVENT btn restore article
    if (btnRestoreArticle) {
      btnRestoreArticle.addEventListener("click", () => {
        //modal restore article
        const modalRestoreArticle = container.querySelector(
          "#modal-restore-article"
        );
        //selected article
        const selectedArticle = container.querySelectorAll(
          "#tbody-article input[type='checkbox']:checked"
        );

        //no selection
        if (selectedArticle.length <= 0) {
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
            lang.article_ids_article_empty;
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
          return;
        }

        //modal message 1
        if (selectedArticle.length === 1) {
          modalRestoreArticle.querySelector(".message").innerHTML =
            lang.question_restore_article_1.replace(
              ":field",
              selectedArticle[0].closest("tr").dataset.idArticle
            );
        }
        //modal message plur
        else {
          modalRestoreArticle.querySelector(".message").innerHTML =
            lang.question_restore_article_plur.replace(
              ":field",
              selectedArticle.length
            );
        }

        //show modal restore artilce
        new bootstrap.Modal(modalRestoreArticle).show();

        //==== EVENT btn confirm modal restore article
        modalRestoreArticle
          .querySelector("#btn-confirm-modal-restore-article")
          .addEventListener("click", async () => {
            try {
              //ids_article
              let ids_article = [...selectedArticle];
              ids_article = ids_article.map(
                (selected) => selected.closest("tr").dataset.idArticle
              );

              //FETCH api restore article
              const apiRestoreArticle = await apiRequest(
                "/article/restore_all_article",
                {
                  method: "PUT",
                  body: {
                    ids_article: ids_article,
                  },
                }
              );

              //error
              if (apiRestoreArticle.message_type === "error") {
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
                  apiRestoreArticle.message;
                //progress bar
                progressBar.style.transition = "width 20s linear";
                progressBar.style.width = "100%";

                //add alert
                modalRestoreArticle.querySelector(".modal-body").prepend(alert);

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
              else if (apiRestoreArticle.message_type === "invalid") {
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
                  apiRestoreArticle.message;
                //progress bar
                progressBar.style.transition = "width 10s linear";
                progressBar.style.width = "100%";

                //add alert
                modalRestoreArticle.querySelector(".modal-body").prepend(alert);

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
                apiRestoreArticle.message;
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

              //auto hide modal
              modalRestoreArticle
                .querySelector("#btn-close-modal-restore-article")
                .click();

              //refresh filter article
              filterArticle(
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
