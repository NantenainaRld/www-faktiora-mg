<!-- header  -->
<?php require_once APP_PATH . '/views/layouts/header.php'; ?>
<!-- template  -->
<template id="template-produit">
    <!-- main-content  -->
    <div class="col-12 col-lg-10 col-md-8 placeholder-glow d-flex flex-column justify-content-between h-100">
        <!-- header  -->
        <header class="row justify-content-center flex-grow-0">
            <div class="col-12">
                <!-- header  -->
                <div class="row justify-content-center g-3">
                    <!-- btn menu mobile-->
                    <div class="col-2 d-flex justify-content-start py-2 d-md-none">
                        <button class="btn btn-sm btn-second" type="button" id="btn-sidebar"><i class="fad fa-bars"></i></button>
                    </div>
                    <!-- search filter  -->
                    <div class="col-8 d-flex justify-content-center py-2 col-md-11">
                        <div class="row">
                            <div class="input-group col-12 justify-content-center">
                                <input type="text" class="form-control bg-second rounded-start-4 form-control-sm text-secondary " placeholder="<?= __('forms.placeholders.search_produit') ?>" id="input-search">
                                <span class="input-group-text rounded-end-4 text-secondary"><i class="fad fa-magnifying-glass"></i></span>
                            </div>
                        </div>
                    </div>
                    <!-- btn searchbar-->
                    <div class="col-2 col-md-1 d-flex justify-content-end py-2 ">
                        <button class="btn btn-sm btn-second" type="button" id="btn-searchbar"><i class="fad fa-bars-filter"></i></button>
                    </div>
                </div>
            </div>
        </header>
        <!-- main -->
        <main class="row mt-4 mb-2 flex-grow-1 overflow-y-auto">
            <div class="col-12 d-flex flex-column justify-content-between overflow-y-auto h-100">
                <!-- table list client -->
                <div class="row py-2 flex-nowrap h-100">
                    <div class="col-12 h-100">
                        <div class="row overflow-x-auto w-100 flex-nowrap g-0 h-100">
                            <!-- table produit-->
                            <div class="col-12 h-100">
                                <div class="card h-100">
                                    <div class="card-body overflow-auto d-flex flex-column justify-content-between h-100">
                                        <!-- title table -->
                                        <div class="card-title text-secondary flex-grow-0" id="chart-transactions-curves-title">
                                            <i class="fad fa-list-dropdown me-2"></i><?= __('forms.titles.produit_list') ?>
                                        </div>
                                        <div class="row justify-content-center  overflow-auto align-items-top flex-grow-1">
                                            <!-- table  -->
                                            <div class="col-12">
                                                <!-- btns  -->
                                                <div class="d-flex gap-2 justify-content-start w-100 my-2">
                                                    <?php if ($role === 'admin'): ?>
                                                        <!-- btn add produit -->
                                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                                            data-bs-target="#modal-add-produit" id="btn-add-produit"><i class="fad fa-user-circle-plus me-2">
                                                            </i><?= __('forms.labels.add') ?>
                                                        </button>
                                                        <!-- btn restore client  -->
                                                        <button class="btn btn-sm btn-outline-warning" id="btn-restore-client"><i class="fad fa-arrow-rotate-left me-2">
                                                            </i><?= __('forms.labels.restore') ?>
                                                        </button>
                                                        <!-- btn delete produit -->
                                                        <button class="btn btn-sm btn-outline-danger" id="btn-delete-produit"><i class="fad fa-user-circle-minus me-2">
                                                            </i><?= __('forms.labels.delete') ?>
                                                        </button>
                                                        <!-- btn delete permanent produit  -->
                                                        <button class="btn btn-sm btn-danger" id="btn-delete-permanent-produit"><i class="fad fa-user-circle-minus me-2">
                                                            </i><?= __('forms.labels.delete_permanent') ?>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                                <table class=" w-100 table-striped">
                                                    <thead class="bg-success text-light align-items-center form-text">
                                                        <tr>
                                                            <?php if ($role === 'admin'): ?>
                                                                <th class="text-center"><input type="checkbox" id="check-all-produit" class="form-check-input"></th>
                                                            <?php endif; ?>
                                                            <th class="text-center"><i class="fad fa-hashtag me-2"></i>ID</th>
                                                            <th class="text-center"><i class="fad fa-tag me-2"></i><?= __('forms.labels.label') ?></th>
                                                            <th class="text-center"><i class="fad fa-coins me-2"></i><?= ucfirst(__('forms.labels.unit_price')) . " ({$currency_units})" ?></th>
                                                            <th class="text-center"><i class="fad fa-circle-bookmark me-2"></i><?= __('forms.labels.nb_stock') ?></th>
                                                            <th class="text-center"><i class="fad fa-dot-circle me-2"></i><?= __('forms.labels.status') ?></th>
                                                            <?php if ($role === 'admin'): ?>
                                                                <th class="text-center"><i class="fad fa-gears me-2"></i><?= __('forms.labels.action') ?></th>
                                                            <?php endif; ?>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tbody-produit">
                                                        <tr>
                                                            <td colspan="9">
                                                                <span class="bg-second placeholder w-100 rounded-1" style="height: 2vh !important;"></span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <!-- search bar -->
        <nav class="position-fixed overflow-y-auto h-100 searchbar col-6 col-lg-2 col-md-4 top-0 end-0 bg-light d-flex flex-column align-items-center placeholder-glow py-4 px-2">
            <!-- alert -->
            <div class="alert-container"></div>
            <!-- search input  -->
            <div class="input-group justify-content-center mb-4">
                <input type="text" class="form-control bg-second rounded-start-4 form-control-sm text-secondary " placeholder="<?= __('forms.placeholders.search_client') ?>" id="input-search-searchbar">
                <span class="input-group-text rounded-end-4 text-secondary"><i class="fad fa-magnifying-glass"></i></span>
            </div>
            <!-- status select -->
            <div class="text-secondary w-100 row justify-content-between mb-2">
                <div class="flex-grow-0">
                    <label for="select-status" class="w-auto form-label "><i class="fad fa-circle-dot me-2"></i><?= __('forms.labels.status') ?></label>
                </div>
                <div class="flex-grow-1">
                    <div class="input-group">
                        <select class="form-select form-select-sm" id="select-status">
                            <option value="all" selected><?= __('forms.labels.all') ?></option>
                            <option value="active"><?= strtolower(__('forms.labels.active')) ?></option>
                            <option value="deleted"><?= __('forms.labels.deleted') ?></option>
                        </select>
                    </div>
                </div>
            </div>
            <!-- arrange by select select  -->
            <div class="text-secondary w-100 row justify-content-between mb-2">
                <div class="flex-grow-0">
                    <label for="select-arrange-by" class="w-auto form-label
                    "><i class="fad fa-arrow-down-short-wide me-2"></i><?= __('forms.labels.arrange_by') ?></label>
                </div>
                <div class="flex-grow-1">
                    <div class="input-group">
                        <select name="" class="form-select form-select-sm" id="select-arrange-by">
                            <option value="id">ID</option>
                            <option value="libelle"><?= strtolower(__('forms.labels.label')) ?></option>
                            <option value="nb_stock"><?= strtolower(__('forms.labels.nb_stock')) ?></option>
                            <option value="quantite"><?= strtolower(__('forms.labels.quantite_vendu')) ?></option>
                            <option value="total"><?= strtolower(__('forms.labels.total_vendu')) ?></option>
                        </select>
                    </div>
                </div>
            </div>
            <!-- order by select  -->
            <div class="text-secondary w-100 row justify-content-between mb-2">
                <div class="flex-grow-0">
                    <label for="select-order" class="w-auto form-label
                    "><i class="fad fa-arrow-down-arrow-up me-2"></i><?= __('forms.labels.order') ?></label>
                </div>
                <div class="flex-grow-1">
                    <div class="input-group">
                        <select name="" class="form-select form-select-sm" id="select-order">
                            <option value="asc" selected><?= __('forms.labels.ascendant') ?></option>
                            <option value="desc"><?= __('forms.labels.descendant') ?></option>
                        </select>
                    </div>
                </div>
            </div>
            <!-- btn reset  -->
            <div class="text-secondary w-100 row justify-content-center mt-4">
                <button class="btn btn-second border-light border btn-sm w-auto" id="btn-reset"><i class="fad fa-arrow-rotate-left me-2"></i><?= __('forms.labels.reset') ?></button>
            </div>
        </nav>
    </div>
    <!-- overlay searchbar  -->
    <div class="overlay-searchbar d-none min-vh-100 bg-dark bg-opacity-50 top-0 col-12 position-fixed "></div>
    <!-- modal add produit -->
    <div class="modal fade" id="modal-add-produit" tabindex="-1" aria-labelledby="modalAddProduit" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <!-- modal header  -->
                <div class="modal-header bg-green-0 text-light">
                    <h6 class="modal-title fw-bold"><i class="fad fa-circle-plus me-2"></i><?= __('forms.titles.produit_add') ?></h6>
                </div>
                <form>
                    <!-- modal body  -->
                    <div class="modal-body">
                        <!-- libelle_produit -->
                        <div class="mb-2">
                            <label for="input-add-produit-libelle-produit" class="form-label"><?= __('forms.labels.label') ?><span class="text-danger"> *</span></label>
                            <div class="input-group">
                                <span class="input-group-text text-success"><i class="fad fa-tag"></i></span>
                                <input type="text" class="form-control form-control-sm" maxlength="100" placeholder="<?= __('forms.placeholders.libelle_produit') ?>" id="input-add-produit-libelle-produit" required>
                            </div>
                        </div>
                        <!--  input add produit prix_produit -->
                        <div class="mb-2">
                            <label for="input-add-produit-prix-produit" class="form-label"><?= __('forms.labels.unit_price') ?> <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text text-success">
                                    <i class="fad fa-coins"></i>
                                </span>
                                <input type="text" class="form-control form-control-sm " id="input-add-produit-prix-produit" required>
                            </div>
                        </div>
                        <!--  input add produit nb_stock -->
                        <div class="mb-2">
                            <label for="input-add-produit-nb-stock" class="form-label"><?= __('forms.labels.nb_stock') ?> <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text text-success">
                                    <i class="fad fa-circle-bookmark"></i>
                                </span>
                                <input type="number" min="0" value="0" class="form-control form-control-sm " id="input-add-produit-nb-stock" required>
                            </div>
                        </div>
                    </div>
                    <!-- modal footer  -->
                    <div class="modal-footer d-flex flex-nowrap justify-content-end">
                        <button class="btn btn-outline-secondary btn-sm fw-bold" data-bs-dismiss="modal" type="button" id="btn-close-modal-add-produit"><i class="fad fa-x me-2"></i><?= __('forms.labels.cancel') ?></button>
                        <button class="btn btn-primary btn-sm fw-bold" type="submit" id="btn-confirm-add-produit"><i class="fad fa-circle-plus me-2"></i><?= __('forms.labels.add') ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- modal update produit -->
    <div class="modal fade" id="modal-update-produit" tabindex="-1" aria-labelledby="modalUpdateProduit" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <!-- modal header  -->
                <div class="modal-header bg-green-0 text-light">
                    <h6 class="modal-title fw-bold"><i class="fad fa-pen-to-square me-2"></i><?= __('forms.titles.produit_update') ?></h6>
                </div>
                <form>
                    <!-- modal body  -->
                    <div class="modal-body">
                        <!-- update produit id_produit -->
                        <div class="mb-2 text-center text-secondary fw-bold" id="update-produit-id-produit"></div>
                        <!-- libelle_produit -->
                        <div class="mb-2">
                            <label for="input-update-produit-libelle-produit" class="form-label"><?= __('forms.labels.label') ?><span class="text-danger"> *</span></label>
                            <div class="input-group">
                                <span class="input-group-text text-success"><i class="fad fa-tag"></i></span>
                                <input type="text" class="form-control form-control-sm" maxlength="100" placeholder="<?= __('forms.placeholders.libelle_produit') ?>" id="input-update-produit-libelle-produit" required>
                            </div>
                        </div>
                        <!--  input update produit prix_produit -->
                        <div class="mb-2">
                            <label for="input-update-produit-prix-produit" class="form-label"><?= __('forms.labels.unit_price') ?> <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text text-success">
                                    <i class="fad fa-coins"></i>
                                </span>
                                <input type="text" class="form-control form-control-sm " id="input-update-produit-prix-produit" required>
                            </div>
                        </div>
                        <!--  input update produit nb_stock -->
                        <div class="mb-2">
                            <label for="input-update-produit-nb-stock" class="form-label"><?= __('forms.labels.nb_stock') ?> <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text text-success">
                                    <i class="fad fa-circle-bookmark"></i>
                                </span>
                                <input type="number" min="0" value="0" class="form-control form-control-sm " id="input-update-produit-nb-stock" required>
                            </div>
                        </div>
                    </div>
                    <!-- modal footer  -->
                    <div class="modal-footer d-flex flex-nowrap justify-content-end">
                        <button class="btn btn-outline-secondary btn-sm fw-bold" data-bs-dismiss="modal" type="button" id="btn-close-modal-update-produit"><i class="fad fa-x me-2"></i><?= __('forms.labels.cancel') ?></button>
                        <button class="btn btn-primary btn-sm fw-bold" type="submit" id="btn-confirm-upate-produit"><i class="fad fa-floppy-disk me-2"></i><?= __('forms.labels.save') ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- modal delete produit-->
    <div class="modal fade" id="modal-delete-produit" tabindex="-1" aria-labelledby="modalDeleteProduit" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <!-- modal header  -->
                <div class="modal-header bg-danger text-light">
                    <h6 class="modal-title fw-bold"><i class="fad fa-circle-minus me-2"></i><?= __('forms.titles.produit_delete') ?></h6>
                </div>
                <!-- modal body  -->
                <div class="modal-body">
                    <div class="message">Voulez-vous vraiment supprimer ces ... produits ?</div>
                </div>
                <!-- modal footer  -->
                <div class="modal-footer d-flex flex-nowrap justify-content-end">
                    <button class="btn btn-outline-secondary btn-sm fw-bold" data-bs-dismiss="modal" type="button" id="btn-close-modal-delete-produit"><i class="fad fa-x me-2"></i><?= __('forms.labels.no_cancel') ?></button>
                    <button class="btn btn-primary btn-sm fw-bold" id="btn-confirm-modal-delete-produit"><i class="fad fa-check me-2"></i><?= __('forms.labels.yes_delete') ?></button>
                </div>
            </div>
        </div>
    </div>
    <!-- modal restore client -->
    <div class="modal fade" id="modal-restore-client" tabindex="-1" aria-labelledby="modalRestoreClient" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <!-- modal header  -->
                <div class="modal-header bg-warning text-light">
                    <h6 class="modal-title fw-bold"><i class="fad fa-arrow-rotate-left me-2"></i><?= __('forms.titles.restore_client') ?></h6>
                </div>
                <!-- modal body  -->
                <div class="modal-body">
                    <div class="message">Voulez-vous vraiment récupérer ces ... clients ?</div>
                </div>
                <!-- modal footer  -->
                <div class="modal-footer d-flex flex-nowrap justify-content-end">
                    <button class="btn btn-outline-secondary btn-sm fw-bold" data-bs-dismiss="modal" type="button" id="btn-close-modal-restore-client"><i class="fad fa-x me-2"></i><?= __('forms.labels.no_cancel') ?></button>
                    <button class="btn btn-primary btn-sm fw-bold" id="btn-confirm-modal-restore-client"><i class="fad fa-check me-2"></i><?= __('forms.labels.yes_restore') ?></button>
                </div>
            </div>
        </div>
    </div>
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
</template>
<!-- footer  -->
<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>