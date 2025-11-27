document.addEventListener("DOMContentLoaded", () => {
  //=================== FUNCTIONS ======================

  //function - create article
  async function createArticle(libelle_article) {
    try {
      const response = await apiRequest("/article/create_article", {
        method: "POST",
        body: {
          libelle_article: libelle_article,
        },
      });
      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }
  //function - filter article
  async function filterArticle(status, order_by, arrange, search_article) {
    try {
      const response = await apiRequest(
        `/article/filter_article?status=${status}&order_by=${order_by}&arrange=${arrange}&search_article=${search_article}`
      );
      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }
  //function - list all article
  async function listAllArticle() {
    try {
      const response = await apiRequest("/article/list_all_article");
      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }
  //function - update article
  async function updateArticle(id_article, libelle_article) {
    try {
      const response = await apiRequest("/article/update_article", {
        method: "PUT",
        body: {
          id_article: id_article,
          libelle_article: libelle_article,
        },
      });
      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }
  //function - delete all article
  async function deleteAllArticle(ids_article = []) {
    try {
      const response = await apiRequest("/article/delete_all_article", {
        method: "PUT",
        body: {
          ids_article: ids_article,
        },
      });
      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }
  //function - permanent delete all article
  async function permanentDeleteAllArticle(ids_article = []) {
    try {
      const response = await apiRequest(
        "/article/permanent_delete_all_article",
        {
          method: "DELETE",
          body: {
            ids_article: ids_article,
          },
        }
      );
      console.log(response);
    } catch (e) {
      console.error(e);
    }
  }

  //================== EVENTS===================

  //btn-test
  const btnTest = document.getElementById("btn-test");
  btnTest.addEventListener("click", () => {
    // createArticle("achat de capsule");
    // filterArticle("active", "or", "desc", "correction");
    // listAllArticle();
    // updateArticle("3", "achat de carburant");
    // deleteAllArticle([3, 2]);
    permanentDeleteAllArticle([3, 2]);
  });
});
