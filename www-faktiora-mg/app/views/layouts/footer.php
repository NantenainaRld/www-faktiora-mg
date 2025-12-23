   <!-- row  -->
   </div>
   <!-- container  -->
   </div>

   <!-- bootstrap  -->
   <script src="<?= SITE_URL ?>/bootstrap-5.3.3/js/bootstrap.bundle.min.js"></script>
   <!-- script header  -->
   <script>
      document.addEventListener('DOMContentLoaded', () => {
         //template - placeholder
         const templatePlaceholder = document.getElementById("template-placeholder");
         // template real sidebar 
         const templateRealSidebar = document.getElementById('template-sidebar');
         //container
         const container = document.getElementById("container");
         //load placeholder
         container.prepend(templatePlaceholder.content.cloneNode(true));
         // load real content 
         setTimeout(async () => {
            container.innerHTML = '';
            //load real side bar
            container.append(templateRealSidebar.content.cloneNode(true));

            try {
               //get account info
               const accountInfo = await apiRequest('/user/account_info');
               //error 
               if (accountInfo.message_type === 'error') {
                  //div accountinfo
                  const divAccountInfo = document.getElementById('account-info');
                  divAccountInfo.innerHTML = `<div class='alert alert-danger'/><i class='fad fa-warning'></i>${accountInfo.message}</div>`;
               }
               //success
               else {
                  //a- user name
                  const aUserName = document.getElementById('a-user_name');
                  aUserName.className = 'mb-1 text-light text-decoration-none text-center fs-6';
                  aUserName.innerHTML = `<h4 class='fw-bold'>${accountInfo.data.nom_utilisateur}</h4> <span class='fs-6 green-first'>${accountInfo.data.prenoms_utilisateur}</span>`;
                  //a- user email
                  const aUserEmail = document.getElementById('a-user_email');
                  aUserEmail.className = 'mb-1 green-second text-decoration-none text-center form-text bg-green-third rounded-1 px-2';
                  aUserEmail.innerHTML = `${accountInfo.data.email_utilisateur}`;
                  //a- user role id user
                  const aRoleIdUser = document.getElementById('a-user_role_id_user');
                  aRoleIdUser.className = 'mb-1 green-second text-decoration-none text-center form-text bg-success rounded-1 px-2 fw-bold bg-opacity-25';
                  aRoleIdUser.innerHTML = `${accountInfo.data.role_t} - ${accountInfo.data.id_utilisateur}`;
                  //role - caissier
                  if (accountInfo.data.role === 'caissier') {
                     // a- user num_caisse 
                     const aUserNumCaisse = document.getElementById('a-user_num_caisse');
                     aUserNumCaisse.className = 'fw-bold';
                     aUserNumCaisse.innerHTML = accountInfo.data.num_caisse;
                  }
               }

            } catch (e) {
               console.log(e);
            }
         }, 1000);
      });
   </script>
   <!-- script fontawesome  -->
   <script src="<?= SITE_URL ?>/fontawesome-pro-7.1.0-web/js/fontawesome.js"></script>
   <script src="<?= SITE_URL ?>/fontawesome-pro-7.1.0-web/js/duotone.js"></script>
   <!-- script chart js  -->
   <script src="<?= SITE_URL ?>/chart-js/luxon.min.js"></script>
   <script src="<?= SITE_URL ?>/chart-js/chart.umd.min.js"></script>
   <script src="<?= SITE_URL ?>/chart-js/chartjs-adapter-luxon.js"></script>
   <script src="<?= SITE_URL ?>/chart-js/chartjs-plugin-zoom.min.js"></script>
   <!-- script select2  -->
   <script src="<?= SITE_URL ?>/select2/js/jquery.min.js"></script>
   <script src="<?= SITE_URL ?>/select2/js/select2.min.js"></script>
   <!-- script home -->
   <?php if ($_SESSION['menu'] === 'home'): ?>
      <script src="<?= SITE_URL ?>/js/home.js"></script>
   <?php endif; ?>
   <!-- script user -->
   <?php if ($_SESSION['menu'] === 'user'): ?>
      <script src="<?= SITE_URL ?>/js/users/user-dashboard.js"></script>
   <?php endif; ?>
   <!-- script caisse -->
   <?php if ($_SESSION['menu'] === 'cash_admin'): ?>
      <script src="<?= SITE_URL ?>/js/caisse-dashboard-admin.js"></script>
   <?php endif; ?>
   <?php if ($_SESSION['menu'] === 'cash_caissier'): ?>
      <script src="<?= SITE_URL ?>/js/caisse-dashboard-caissier.js"></script>
   <?php endif; ?>
   </body>

   </html>