   <!-- row  -->
   </div>
   <!-- modal update account  -->
   <div class="modal fade" id="modal-update-account" tabindex="-1" aria-labelledby="modalUpdateAccount" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable">
         <div class="modal-content">
            <!-- modal header  -->
            <div class="modal-header bg-green-0 text-light">
               <h6 class="modal-title fw-bold"><i class="fad fa-user-edit me-2"></i><?= __('forms.titles.account_update') ?></h6>
            </div>
            <!-- form update account -->
            <form>
               <!-- modal body  -->
               <div class="modal-body">
                  <!-- template alert  -->
                  <template class="alert-template">
                     <div class="alert alert-dismissible fade show alert-slide w-100" role="alert">
                        <!-- alert progress bar  -->
                        <div class="progress mb-2" style="height: 2px;">
                           <div class="progress-bar bg-secondary"></div>
                        </div>
                        <!-- alert icon  -->
                        <i class="fad me-2"></i>
                        <!-- alert message  -->
                        <span class="alert-message"></span>
                        <!-- alert btn close  -->
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                     </div>
                  </template>
                  <!-- update user name  -->
                  <div class="mb-2">
                     <label for="input-update-user-name" class="form-label"><?= ucfirst(__('forms.labels.name')) ?> <span class="text-danger">*</span></label>
                     <div class="input-group">
                        <span class="input-group-text text-success">
                           <i class="fad fa-address-card"></i>
                        </span>
                        <input type="text" class="form-control form-control-sm " placeholder="RALANDISON" id="input-update-user-name" maxlength="100" required>
                     </div>
                  </div>
                  <!--  update user first name -->
                  <div class="mb-2">
                     <label for="input-update-user-first-name" class="form-label"><?= ucfirst(__('forms.labels.firstname')) ?></label>
                     <div class="input-group">
                        <span class="input-group-text text-success">
                           <i class="fad fa-address-card"></i>
                        </span>
                        <input type="text" class="form-control form-control-sm " placeholder="Nantenaina" id="input-update-user-first-name" maxlength="100">
                     </div>
                  </div>
                  <!-- update user sex -->
                  <div class="mb-2">
                     <label for="select-update-user-sex" class="form-label me-2 mt-1"><?= ucfirst(__('forms.labels.sex')) ?></label>
                     <div class="input-group">
                        <span class="input-group-text text-success">
                           <i class="fad fa-venus"></i>
                        </span>
                        <select name="" id="select-update-user-sex" class="form-select form-select-sm">
                           <option value="masculin"><?= strtolower(__('forms.labels.male')) ?></option>
                           <option value="féminin"><?= strtolower(__('forms.labels.female')) ?></option>
                        </select>
                     </div>
                  </div>
                  <!--  update user email -->
                  <div class="mb-2">
                     <label for="input-update-user-email" class="form-label me-2"><?= ucfirst(__('forms.labels.email')) ?> <span class="text-danger">*</span></label>
                     <div class="input-group">
                        <span class="input-group-text text-success">
                           <i class="fad fa-at"></i>
                        </span>
                        <input type="email" class="form-control form-control-sm " id="input-update-user-email" placeholder="nantenaina@faktiora.mg" maxlength="150" required>
                     </div>
                  </div>
                  <!--  update user mdp -->
                  <div class="mb-2">
                     <label for="input-update-user-mdp" class="form-label me-2 mt-1"><?= ucfirst(__('forms.labels.password')) ?> </label>
                     <div class="input-group">
                        <span class="input-group-text text-success">
                           <i class="fad fa-lock"></i>
                        </span>
                        <input type="password" class="form-control form-control-sm " id="input-update-user-mdp" minlength="6">
                        <button class="input-group-text" type="button"><i class="fad fa-eye-slash"></i></button>
                     </div>
                     <span class="form-text text-secondary"><?= __('forms.labels.keep_empty') ?></span>
                  </div>
               </div>
               <!-- modal footer  -->
               <div class="modal-footer d-flex flex-nowrap justify-content-end">
                  <button class="btn btn-outline-secondary btn-sm fw-bold" data-bs-dismiss="modal" type="button" id="btn-close-modal-update-account"><i class="fad fa-x me-2"></i><?= __('forms.labels.cancel') ?></button>
                  <button class="btn btn-primary btn-sm fw-bold" type="submit"><i class="fad fa-floppy-disk me-2"></i><?= __('forms.labels.save') ?></button>
               </div>
            </form>
         </div>
      </div>
   </div>
   <!-- modal change lang -->
   <div class="modal fade" id="modal-change-lang" tabindex="-1" aria-labelledby="modalChangeLang" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable">
         <div class="modal-content">
            <!-- modal header  -->
            <div class="modal-header bg-green-0 text-light">
               <h6 class="modal-title fw-bold"><i class="fad fa-language me-2"></i><?= __('forms.titles.change_lang') ?></h6>
            </div>
            <!-- form change lang-->
            <form method="get" action="<?= SITE_URL ?>/lang">
               <!-- modal body  -->
               <div class="modal-body">
                  <div class="mb-2">
                     <div class="input-group">
                        <span class="input-group-text text-success"><i class="fad fa-language"></i></span>
                        <select name="lang" id="lang" class="form-select form-select-sm">
                           <option value="fr" <?= $_COOKIE['lang'] === 'fr' ? 'selected' : '' ?>><?= __('forms.lang.fr') ?></option>
                           <option value="mg" <?= $_COOKIE['lang'] === 'mg' ? 'selected' : '' ?>><?= __('forms.lang.mg') ?></option>
                           <option value="en" <?= $_COOKIE['lang'] === 'en' ? 'selected' : '' ?>><?= __('forms.lang.en') ?></option>
                        </select>
                     </div>
                  </div>
               </div>
               <!-- modal footer  -->
               <div class="modal-footer d-flex flex-nowrap justify-content-end">
                  <button class="btn btn-outline-secondary btn-sm fw-bold" data-bs-dismiss="modal" type="button" id="btn-close-modal-change-lang"><i class="fad fa-x me-2"></i><?= __('forms.labels.cancel') ?></button>
                  <button class="btn btn-primary btn-sm fw-bold" type="submit"><i class="fad fa-floppy-disk me-2"></i><?= __('forms.labels.save') ?></button>
               </div>
            </form>
         </div>
      </div>
   </div>
   <!-- modal application setting -->
   <div class="modal fade" id="modal-setting" tabindex="-1" aria-labelledby="modalSetting" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable">
         <div class="modal-content">
            <!-- modal header  -->
            <div class="modal-header bg-dark text-light">
               <h6 class="modal-title fw-bold"><i class="fad fa-gear me-2"></i><?= __('forms.labels.setting') ?></h6>
            </div>
            <!-- form setting -->
            <form>
               <!-- modal body  -->
               <div class="modal-body">
                  <div class="mb-2">
                     <!-- enterprise name  -->
                     <div class="mb-2">
                        <label for="enterprise-name" class="form-label"><?= __('forms.labels.enterprise_name') ?><class="text-danger"> *</span></label>
                        <input type="text" class="form-control form-control-sm" id="enterprise-name" required>
                     </div>
                     <div class="mb-2">
                        <label for="currency" class="form-label"><?= __('forms.labels.currency') ?></label>
                        <div class="input-group">
                           <span class="input-group-text text-success"><i class="fad fa-dollar-circle"></i></span>
                           <select id="currency" class="form-select form-select-sm">
                              <option value="MGA">Ariary</option>
                              <option value="EUR">Euro</option>
                              <option value="USD">Dollar US</option>
                           </select>
                        </div>
                     </div>
                  </div>
               </div>
               <!-- modal footer  -->
               <div class="modal-footer d-flex flex-nowrap justify-content-end">
                  <button class="btn btn-outline-secondary btn-sm fw-bold" data-bs-dismiss="modal" type="button" id="btn-close-modal-change-lang"><i class="fad fa-x me-2"></i><?= __('forms.labels.cancel') ?></button>
                  <button class="btn btn-primary btn-sm fw-bold" type="submit"><i class="fad fa-floppy-disk me-2"></i><?= __('forms.labels.save') ?></button>
               </div>
            </form>
         </div>
      </div>
   </div>
   <!-- container  -->
   </div>

   <!-- bootstrap  -->
   <script src="<?= SITE_URL ?>/bootstrap-5.3.3/js/bootstrap.bundle.min.js"></script>
   <!-- script setting -->
   <script src="<?= SITE_URL ?>/js/setting.js"></script>
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
   <!-- script client  -->
   <?php if ($_SESSION['menu'] === 'client'): ?>
      <script src="<?= SITE_URL ?>/js/client-dashboard.js"></script>
   <?php endif; ?>
   <!-- script facture  -->
   <?php if ($_SESSION['menu'] === 'facture'): ?>
      <script src="<?= SITE_URL ?>/js/facture-dashboard.js"></script>
   <?php endif; ?>
   <!-- script autre entree -->
   <?php if ($_SESSION['menu'] === 'autre_entree'): ?>
      <script src="<?= SITE_URL ?>/js/autre-entree-dashboard.js"></script>
   <?php endif; ?>
   <!-- script sortie -->
   <?php if ($_SESSION['menu'] === 'sortie'): ?>
      <script src="<?= SITE_URL ?>/js/sortie-dashboard.js"></script>
   <?php endif; ?>
   <!-- script produit -->
   <?php if ($_SESSION['menu'] === 'produit'): ?>
      <script src="<?= SITE_URL ?>/js/produit-dashboard.js"></script>
   <?php endif; ?>
   <!-- script article -->
   <?php if ($_SESSION['menu'] === 'article'): ?>
      <script src="<?= SITE_URL ?>/js/article-dashboard.js"></script>
   <?php endif; ?>
   </body>

   </html>