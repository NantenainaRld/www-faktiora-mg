<!-- header  -->
<?php require_once APP_PATH . '/views/layouts/header.php'; ?>
<!-- template  -->
<template id="template-facture">
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
                    <!-- search filter -->
                    <div class="col-8 d-flex justify-content-center py-2 col-md-11">
                        <div class="row">
                            <div class="input-group col-12 justify-content-center">
                                <input type="text" class="form-control bg-second rounded-start-4 form-control-sm text-secondary " placeholder="<?= strtolower(__('forms.placeholders.search_facture')) ?>" id="input-search">
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
            <div class="col-12 d-flex flex-column justify-content-between overflow-y-auto h-100 w-100">
                <!-- table facture and ligne_facture -->
                <div class="row py-2 flex-nowrap h-100">
                    <div class="col-12 w-100 h-100">
                        <div class="row overflow-x-auto w-100 flex-nowrap g-0 h-100">
                            <!-- table facture  -->
                            <div class="col-12 h-100 col-lg-7">
                                <div class="card h-100 w-100">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <!-- title facture-->
                                        <div class="card-title text-secondary flex-grow-0">
                                            <i class="fad fa-list-dropdown me-2"></i><?= __('forms.titles.facture_list') ?>
                                        </div>
                                        <div class="row justify-content-center  overflow-auto align-items-top flex-grow-1">
                                            <div class="col-12">
                                                <!-- btns  -->
                                                <div class="d-flex justify-content-start my-2 gap-2">
                                                    <!-- btn add facture  -->
                                                    <button class="btn btn-sm btn-outline-primary fw-bold" id="btn-add-facture"><i class="fad fa-circle-plus me-2"></i><?= __('forms.labels.add') ?></button>
                                                    <?php if ($role === 'admin'): ?>
                                                        <!-- btn delete facture -->
                                                        <button class="btn btn-sm btn-outline-danger" id="btn-delete-facture"><i class="fad fa-trash me-2">
                                                            </i><?= __('forms.labels.delete') ?>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                                <!-- table  -->
                                                <table class="w-100 table-striped">
                                                    <thead class="bg-success text-light align-items-center form-text">
                                                        <tr>
                                                            <?php if ($role === 'admin'): ?>
                                                                <th class="text-center">
                                                                    <input type="checkbox" class="form-input-check" id="check-all-facture">
                                                                </th>
                                                            <?php endif; ?>
                                                            <th class="text-center"><i class="fad fa-hashtag me-2"></i><?= __('forms.labels.num') ?></th>
                                                            <th class="text-center"><i class="fad fa-calendar me-2"></i><?= __('forms.labels.date') ?></th>
                                                            <th class="text-center"><i class="fad fa-coins me-2"></i><?= __('forms.labels.amount') . ' (' . $currency_units . ')' ?></th>
                                                            <th class="text-center"><i class="fad fa-user-check me-2"></i><?= __('forms.labels.client') ?></th>
                                                            <th class="text-center"><i class="fad fa-user me-2"></i><?= __('forms.labels.user') ?></th>
                                                            <th class="text-center"><i class="fad fa-cash-register me-2"></i><?= __('forms.labels.cash') ?></th>
                                                            <th class="text-center"><i class="fad fa-circle-dot me-2"></i><?= __('forms.labels.status') ?></th>
                                                            <th class="text-center"><i class="fad fa-gears me-2"></i><?= __('forms.labels.actions') ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tbody-facture">
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
                            <!-- table ligne_caisse -->
                            <div class="col-12 h-100 ms-2 col-lg-5">
                                <div class="card h-100">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <!-- title table -->
                                        <div class="card-title text-secondary flex-grow-0" id="chart-transactions-curves-title">
                                            <i class="fad fa-list-dropdown me-2"></i><?= __('forms.titles.cash_line') . ' (' . strtolower(__('forms.labels.cash_num')) ?>: <span id="table-lc-cash-num"></span>)
                                        </div>
                                        <div class="row justify-content-center  overflow-auto align-items-top flex-grow-1">
                                            <!-- search ligne_caisse  -->
                                            <dvi class="col-12 bg-second rounded-1 py-2 align-items-center d-flex flex-column">
                                                <!-- input - search ligne_caisse  -->
                                                <div class="row w-100 mb-2">
                                                    <div class="me-2 input-group">
                                                        <input type="text" class="form-control form-control-sm text-secondary" placeholder="<?= __('forms.placeholders.search_lc') ?>" id="input-search-lc-id">
                                                        <span class="input-group-text"><i class="fad fa-magnifying-glass"></i></span>
                                                    </div>
                                                </div>
                                                <div class="row w-100 flex-nowrap overflow-x-auto">
                                                    <!-- input - search date_debut  -->
                                                    <div class="me-2 col-6 
                                                    d-flex gap-1">
                                                        <label for="input-search-lc-from" class="form-label text-secondary mt-1"><?= __('forms.labels.on') ?></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fad fa-calendar"></i></span>
                                                            <input type="datetime-local" class="form-control form-control-sm text-secondary" id="input-search-lc-from" max="<?= date('Y-m-d\TH:i') ?>">
                                                        </div>
                                                    </div>
                                                    <!-- input - search date_fin  -->
                                                    <div class="me-2 col-6 d-flex gap-1">
                                                        <label for="input-search-lc-to" class="form-label text-secondary mt-1"><?= ucfirst(__('forms.labels.to')) ?></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fad fa-calendar"></i></span>
                                                            <input type="datetime-local" class="form-control form-control-sm text-secondary" id="input-search-lc-to" max="<?= date('Y-m-d\TH:i') ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </dvi>
                                            <!-- table  -->
                                            <div class="col-12">
                                                <!-- btn  -->
                                                <div class="row g-0 justify-content-start mb-2">
                                                    <!-- btn add ligne_caisse  -->
                                                    <button class="btn btn-outline-primary fw-bold w-auto btn-sm me-2 my-2" id="btn-add-ligne-caisse">
                                                        <i class="fad fa-circle-plus me-2"></i><?= __('forms.labels.add') ?>
                                                    </button>
                                                    <!-- btn delete ligne_caisse  -->
                                                    <button class="btn btn-danger fw-bold w-auto btn-sm me-2 my-2" id="btn-delete-ligne-caisse">
                                                        <i class="fad fa-trash  me-2"></i><?= __('forms.labels.delete') ?>
                                                    </button>
                                                </div>
                                                <table class="w-100 table-striped">
                                                    <thead class="bg-success text-light align-items-center form-text">
                                                        <tr>
                                                            <th class="text-center"><input type="checkbox" class="form-check-input" id="check-all"></th>
                                                            <th class="text-center"><i class="fad fa-hashtag me-2"></i>ID</th>
                                                            <th class="text-center"><i class="fad fa-calendar me-2"></i><?= __('forms.labels.date_start') ?></th>
                                                            <th class="text-center"><i class="fad fa-calendar me-2"></i><?= __('forms.labels.date_end') ?></th>
                                                            <th class="text-center"><i class="fad fa-user me-2"></i><?= __('forms.labels.user') ?></th>
                                                            <th class="text-center"><i class="fad fa-gears me-2"></i><?= __('forms.labels.action') ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tbody-lc">
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
        <!-- search bar  -->
        <nav class="position-fixed overflow-y-auto h-100 searchbar col-6 col-lg-2 col-md-4 top-0 end-0 bg-light d-flex flex-column align-items-center placeholder-glow py-4 px-2">
            <!-- search input  -->
            <div class="input-group justify-content-center mb-4">
                <input type="text" class="form-control bg-second rounded-start-4 form-control-sm text-secondary " placeholder="<?= strtolower(__('forms.placeholders.search_facture')) ?>" id="input-search-searchbar">
                <span class="input-group-text rounded-end-4 text-secondary"><i class="fad fa-magnifying-glass"></i></span>
            </div>
            <!-- select status  -->
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
                            <option value="num" selected><?= strtolower(__('forms.labels.facture_number')) ?></option>
                            <option value="date"><?= strtolower(__('forms.labels.facture_date')) ?></option>
                            <option value="montant"><?= strtolower(__('forms.labels.facture_montant')) ?></option>
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
            <!-- date_by  -->
            <div class="card mb-2">
                <!-- between date_by  -->
                <div class="card-body">
                    <div class="card-title text-secondary mb-2"><i class="fad fa-calendar me-2"></i><?= __('forms.labels.date') ?></div>
                    <div class="text-secondary w-100 row justify-content-between mb-2 date-by" id="div-between">
                        <!-- from  -->
                        <div class="input-group mb-1">
                            <label for="date-from" class="form-label me-2
                    "><?= __('forms.labels.on') ?></label>
                            <input type="date" name="" id="date-from" class="form-control form-control-sm" max="<?= date('Y-m-d') ?>" min="1700-01-01">
                        </div>
                        <!-- to  -->
                        <div class="input-group">
                            <label for="date-to" class="form-label me-2
                    "><?= ucfirst(__('forms.labels.to')) ?></label>
                            <input type="date" name="" id="date-to" class="form-control form-control-sm" max="<?= date('Y-m-d') ?>">
                        </div>
                    </div>
                </div>
            </div>
            <!-- select num_caisse -->
            <?php if ($role === 'admin'): ?>
                <div class="text-secondary w-100 row justify-content-between mb-2">
                    <div class="flex-grow-0">
                        <label for="select-num-caisse" class="w-auto form-label "><i class="fad fa-cash-register me-2"></i><?= __('forms.labels.cash') ?></label>
                    </div>
                    <div class="flex-grow-1">
                        <div class="input-group">
                            <select class="form-select form-select-sm select2" id="select-num-caisse">
                                <option></option>
                                <option value="" disabled><?= strtolower(__('forms.labels.loading')) ?>...</option>
                            </select>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <!-- select id_utilisateur -->
            <div class="text-secondary w-100 row justify-content-between mb-2">
                <div class="flex-grow-0">
                    <label for="select-id-utilisateur" class="w-auto form-label "><i class="fad fa-user me-2"></i><?= __('forms.labels.user') ?></label>
                </div>
                <div class="flex-grow-1">
                    <div class="input-group">
                        <select class="form-select form-select-sm select2" id="select-id-utilisateur">
                            <option></option>
                            <option value="loading" disabled><?= strtolower(__('forms.labels.loading')) ?>...</option>
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
    <!-- modal add facture -->
    <div class="modal fade" id="modal-add-facture" tabindex="-1" aria-labelledby="modalAddFacture" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content d-flex flex-column justify-content-between">
                <!-- modal header  -->
                <div class="modal-header bg-green-0 text-light flex-frow-0">
                    <h6 class="modal-title fw-bold"><i class="fad fa-circle-plus me-2"></i><?= __('forms.titles.facture_add') ?></h6>
                </div>
                <!-- form add facture -->
                <form class="flex-grow-1 overflow-auto">
                    <!-- modal body  -->
                    <div class="modal-body">
                        <?php if ($role === 'admin'): ?>
                            <!--input add facture date facture -->
                            <div class="mb-2">
                                <label for="input-add-facture-date-facture" class="form-label"><?= __('forms.labels.date') ?> <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text text-success"><i class="fad fa-calendar"></i></span>
                                    <input type="datetime-local" max="<?= date('Y-m-d\TH:i') ?>" class="form-control form-control-sm" id="input-add-facture-date-facture" required>
                                </div>
                            </div>
                            <!-- select add facture id_utilisateur -->
                            <div class="mb-2">
                                <label for="select-add-facture-id-utilisateur" class="form-label"><?= __('forms.labels.user') ?> <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text text-success"><i class="fad fa-user"></i></span>
                                    <select name="" id="select-add-facture-id-utilisateur" class="form-select form-select-sm select2" required>
                                        <option></option>
                                        <option value="loading" disabled><?= __('forms.labels.loading') ?>...</option>
                                    </select>
                                    <button type="button" class="input-group-text" id="btn-add-facture-refresh-id-utilisateur"><i class="fad fa-arrow-rotate-left"></i></button>
                                </div>
                            </div>
                            <!-- select add facture num_caisse -->
                            <div class="mb-2">
                                <label for="select-add-facture-num-caisse" class="form-label"><?= ucfirst(__('forms.labels.cash')) ?> <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text text-success"><i class="fad fa-cash-register"></i></span>
                                    <select name="" id="select-add-facture-num-caisse" class="form-select form-select-sm select2" required>
                                        <option></option>
                                        <option value="loading" disabled><?= __('forms.labels.loading') ?>...</option>
                                    </select>
                                    <button type="button" class="input-group-text" id="btn-add-facture-refresh-num-caisse"><i class="fad fa-arrow-rotate-left"></i></button>
                                </div>
                            </div>
                        <?php endif; ?>
                        <!-- select add facture id_client  -->
                        <div class="mb-2">
                            <label for="select-add-facture-id-client" class="form-label"><?= __('forms.labels.client') ?> <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text text-success"><i class="fad fa-user-check"></i></span>
                                <select name="" id="select-add-facture-id-client" class="form-select form-select-sm select2" required>
                                    <option></option>
                                    <option value="loading" disabled><?= __('forms.labels.loading') ?>...</option>
                                </select>
                                <button type="button" class="input-group-text" id="btn-add-facture-refresh-id-client"><i class="fad fa-arrow-rotate-left"></i></button>
                                <!-- btn add client -->
                                <span class="mx-2 text-secondary"> <?= __('forms.labels.or') ?> </span><button type="button" class="btn btn-sm btn-light text-primary" data-bs-toggle="modal" data-bs-target="#modal-add-client"><i class="fad fa-user-circle-plus"></i></button>
                            </div>
                        </div>
                        <!-- select add facture id_produit -->
                        <div class="mb-2">
                            <label for="select-add-facture-id-produit" class="form-label"><?= __('forms.labels.product') ?></label>
                            <div class="d-flex gap-2 flex-wrap">
                                <div class="input-group">
                                    <span class="input-group-text text-success"><i class="fad fa-bin-bottles"></i></span>
                                    <select name="" id="select-add-facture-id-produit" class="form-select form-select-sm select2">
                                        <option></option>
                                        <option value="loading" disabled><?= __('forms.labels.loading') ?>...</option>
                                    </select>
                                    <button type="button" class="input-group-text" id="btn-add-facture-refresh-id-produit"><i class="fad fa-arrow-rotate-left"></i></button>
                                </div>
                                <!-- input produit quantity and btn add produit-->
                                <div class="input-group w-50">
                                    <input type="number" min="1" class="form-control form-control-sm" id="input-add-facture-quantite-produit" value="1">
                                    <button type="button" class="input-group-text text-primary"><i class="fad fa-plus" id="btn-add-facture-add-produit"></i></button>
                                </div>
                                <!-- message produit select  -->
                                <span class="form-text text-danger" id="message-produit-select"></span>
                            </div>
                            <!-- card added produit  -->
                            <div class="card mt-2 w-auto">
                                <div class="card-body">
                                    <div class="d-flex flex-wrap gap-2" id="card-produit"></div>
                                </div>
                                <div class="card-footer d-flex justify-content-center flex-column align-items-center">
                                    <span class="text-secondary"><b><?= __('forms.labels.total') ?> : </b><span id="add-facture-total" data-value="0">0 <?= $currency_units ?></span></span>
                                    <button type="button" class="btn btn-second btn-sm" id="btn-add-facture-empty-produit"><?= __('forms.labels.to_empty') ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- modal footer  -->
                    <div class="modal-footer d-flex flex-nowrap justify-content-end">
                        <button class="btn btn-outline-secondary btn-sm fw-bold" data-bs-dismiss="modal" type="button" id="btn-close-modal-add-facture"><i class="fad fa-x me-2"></i><?= __('forms.labels.cancel') ?></button>
                        <button class="btn btn-primary btn-sm fw-bold" type="submit"><i class="fad fa-circle-plus me-2"></i><?= __('forms.labels.add') ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- modal add client -->
    <div class="modal fade" id="modal-add-client" tabindex="-1" aria-labelledby="modalAddClient" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <!-- modal header  -->
                <div class="modal-header bg-green-0 text-light">
                    <h6 class="modal-title fw-bold"><i class="fad fa-user-circle-plus me-2"></i><?= __('forms.titles.client_add') ?></h6>
                </div>
                <form>
                    <!-- modal body  -->
                    <div class="modal-body">
                        <!-- input - nom_client  -->
                        <div class="mb-2">
                            <label for="input-add-nom-client" class="form-label"><?= __('forms.labels.name') ?> <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text text-success"><i class="fad fa-address-card"></i></span>
                                <input type="text" maxlength="100" class="form-control form-control-sm" id="input-add-nom-client" placeholder="RALANDISON" required>
                            </div>
                        </div>
                        <!-- input - prenoms_client  -->
                        <div class="mb-2">
                            <label for="input-add-prenoms-client" class="form-label"><?= __('forms.labels.firstname') ?></label>
                            <div class="input-group">
                                <span class="input-group-text text-success"><i class="fad fa-address-card"></i></span>
                                <input type="text" maxlength="100" class="form-control form-control-sm" id="input-add-prenoms-client" placeholder="Nantenaina">
                            </div>
                        </div>
                        <!-- select - sexe_client  -->
                        <div class="mb-2">
                            <label for="select-add-sexe-client" class="form-label"><?= __('forms.labels.sex') ?></label>
                            <div class="input-group">
                                <span class="input-group-text text-success"><i class="fad fa-venus"></i></span>
                                <select name="" id="select-add-sexe-client" class="form-select form-select-sm">
                                    <option value="masculin"><?= __('forms.labels.male') ?></option>
                                    <option value="féminin"><?= __('forms.labels.female') ?></option>
                                </select>
                            </div>
                        </div>
                        <!-- input - telephone -->
                        <div class="mb-2">
                            <label for="input-add-telephone" class="form-label"><?= __('forms.labels.phone') ?> </label>
                            <div class="input-group">
                                <span class="input-group-text text-success"><i class="fad fa-phone"></i></span>
                                <input type="text" maxlength="20" minlength="10" class="form-control form-control-sm" id="input-add-telephone" placeholder="032 00 000 00">
                            </div>
                        </div>
                        <!-- input - adresse -->
                        <div class="mb-2">
                            <label for="input-add-adresse" class="form-label"><?= __('forms.labels.address') ?> </label>
                            <div class="input-group">
                                <span class="input-group-text text-success"><i class="fad fa-location-dot"></i></span>
                                <input type="text" maxlength="20" class="form-control form-control-sm" id="input-add-adresse" placeholder="Andrainjato Fianarantsoa">
                            </div>
                        </div>
                    </div>
                    <!-- modal footer  -->
                    <div class="modal-footer d-flex flex-nowrap justify-content-end">
                        <button class="btn btn-outline-secondary btn-sm fw-bold" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#modal-add-facture" type="button" id="btn-close-modal-add-client"><i class="fad fa-x me-2"></i><?= __('forms.labels.cancel') ?></button>
                        <button class="btn btn-primary btn-sm fw-bold" type="submit" id="btn-confirm-add-client"><i class="fad fa-user-circle-plus me-2"></i><?= __('forms.labels.add') ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- modal correction facture -->
    <div class="modal fade" id="modal-correction-facture" tabindex="-1" aria-labelledby="modalCorrectionFacture" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content d-flex flex-column justify-content-between">
                <!-- modal header  -->
                <div class="modal-header bg-green-0 text-light flex-frow-0">
                    <h6 class="modal-title fw-bold"><i class="fad fa-circle-minus me-2"></i><?= __('forms.titles.facture_correction') ?></h6>
                </div>
                <!-- form correction facture facture -->
                <form class="flex-grow-1 overflow-auto">
                    <!-- modal body -->
                    <div class="modal-body">
                        <!-- correction facture num_facture -->
                        <div class="text-secondary fw-bold mb-2 text-center" id="correction-facture-num-facture"></div>
                        <!-- message -->
                        <div class="mb-2 text-secondary text-center"><?= __('forms.labels.facture_correction_message') ?></div>
                        <?php if ($role === 'admin'): ?>
                            <!--input correction facture date_ds -->
                            <div class="mb-2">
                                <label for="input-correction-facture-date-ds" class="form-label"><?= __('forms.labels.date') ?> <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text text-success"><i class="fad fa-calendar"></i></span>
                                    <input type="datetime-local" max="<?= date('Y-m-d\TH:i') ?>" class="form-control form-control-sm" id="input-correction-facture-date-ds" required>
                                </div>
                            </div>
                            <!-- select correction facture id_utilisateur -->
                            <div class="mb-2">
                                <label for="select-correction-facture-id-utilisateur" class="form-label"><?= __('forms.labels.user') ?> <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text text-success"><i class="fad fa-user"></i></span>
                                    <select name="" id="select-correction-facture-id-utilisateur" class="form-select form-select-sm select2" required>
                                        <option></option>
                                        <option value="loading" disabled><?= __('forms.labels.loading') ?>...</option>
                                    </select>
                                    <button type="button" class="input-group-text" id="btn-correction-facture-refresh-id-utilisateur"><i class="fad fa-arrow-rotate-left"></i></button>
                                </div>
                            </div>
                        <?php endif; ?>
                        <!-- ligne_facture -->
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title text-secondary"><?= __('forms.labels.ligne_facture') ?></div>
                                <div class="d-flex flex-column gap-2" id="div-ligne-facture">
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-center flex-column align-items-center">
                                <span class="text-secondary"><b><?= __('forms.labels.total') ?> : </b><span id="correction-facture-total" data-value="0">0 <?= $currency_units ?></span></span>
                                <button type="button" class="btn btn-second btn-sm" id="btn-correction-facture-refresh-ligne-facture"><?= __('forms.labels.refresh') ?></button>
                            </div>
                        </div>
                    </div>
                    <!-- modal footer  -->
                    <div class="modal-footer d-flex flex-nowrap justify-content-end">
                        <button class="btn btn-outline-secondary btn-sm fw-bold" data-bs-dismiss="modal" type="button" id="btn-close-modal-correction-facture"><i class="fad fa-x me-2"></i><?= __('forms.labels.cancel') ?></button>
                        <button class="btn btn-primary btn-sm fw-bold" type="submit"><i class="fad fa-floppy-disk me-2"></i><?= __('forms.labels.save') ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- modal delete facture -->
    <div class="modal fade" id="modal-delete-facture" tabindex="-1" aria-labelledby="modalDeleteFacture" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <!-- modal header  -->
                <div class="modal-header bg-danger text-light">
                    <h6 class="modal-title fw-bold"><i class="fad fa-trash me-2"></i><?= __('forms.titles.facture_delete') ?></h6>
                </div>
                <!-- modal body  -->
                <div class="modal-body">
                    <div class="message">Voulez-vous vraiment supprimer ces ... factures ?</div>
                </div>
                <!-- modal footer  -->
                <div class="modal-footer d-flex flex-nowrap justify-content-end">
                    <button class="btn btn-outline-secondary btn-sm fw-bold" data-bs-dismiss="modal" type="button" id="btn-close-modal-delete-facture"><i class="fad fa-x me-2"></i><?= __('forms.labels.no_cancel') ?></button>
                    <button class="btn btn-primary btn-sm fw-bold" type="button" id="btn-confirm-modal-delete-facture"><i class="fad fa-check me-2"></i><?= __('forms.labels.yes_delete') ?></button>
                </div>
            </div>
        </div>
    </div>
    <!-- modal restore caisse  -->
    <div class="modal fade" id="modal-restore-caisse" tabindex="-1" aria-labelledby="modalRestoreCaisse" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <!-- modal header  -->
                <div class="modal-header bg-warning text-light">
                    <h6 class="modal-title fw-bold"><i class="fad fa-arrow-rotate-left me-2"></i><?= __('forms.titles.restore_caisse') ?></h6>
                </div>
                <!-- modal body  -->
                <div class="modal-body">
                    <div class="message">Voulez-vous vraiment récupérer ces ... caisses ?</div>
                </div>
                <!-- modal footer  -->
                <div class="modal-footer d-flex flex-nowrap justify-content-end">
                    <button class="btn btn-outline-secondary btn-sm fw-bold" data-bs-dismiss="modal" type="button" id="btn-close-modal-restore-caisse"><i class="fad fa-x me-2"></i><?= __('forms.labels.no_cancel') ?></button>
                    <button class="btn btn-primary btn-sm fw-bold" type="button" id="btn-confirm-restore-caisse"><i class="fad fa-check me-2"></i><?= __('forms.labels.yes_restore') ?></button>
                </div>
            </div>
        </div>
    </div>
    <!-- modal free caisse  -->
    <div class="modal fade" id="modal-free-caisse" tabindex="-1" aria-labelledby="modalFreeCaisse" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <!-- modal header  -->
                <div class="modal-header bg-info text-light">
                    <h6 class="modal-title fw-bold"><i class="fad fa-circle-minus me-2"></i><?= __('forms.titles.free_caisse') ?></h6>
                </div>
                <!-- modal body  -->
                <div class="modal-body">
                    <div class="message">Voulez-vous vraiment libérer ces ... caisses ?</div>
                </div>
                <!-- modal footer  -->
                <div class="modal-footer d-flex flex-nowrap justify-content-end">
                    <button class="btn btn-outline-secondary btn-sm fw-bold" data-bs-dismiss="modal" type="button" id="btn-close-modal-free-caisse"><i class="fad fa-x me-2"></i><?= __('forms.labels.no_cancel') ?></button>
                    <button class="btn btn-primary btn-sm fw-bold" type="button" id="btn-confirm-free-caisse"><i class="fad fa-check me-2"></i><?= __('forms.labels.yes_free') ?></button>
                </div>
            </div>
        </div>
    </div>
    <!-- modal add ligne_caisse  -->
    <div class="modal fade" id="modal-add-ligne-caisse" tabindex="-1" aria-labelledby="modalAddLigneCaisse" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <!-- modal header  -->
                <div class="modal-header bg-green-0 text-light">
                    <h6 class="modal-title fw-bold"><i class="fad fa-circle-plus me-2"></i><?= __('forms.titles.add_ligne_caisse') ?></h6>
                </div>
                <form>
                    <!-- modal body  -->
                    <div class="modal-body">
                        <!-- cash num  -->
                        <div class="text-center text-secondary mb-1"><?= strtolower(__('forms.labels.cash_num')) ?> : <b><span id="add-ligne-caisse-cash-num"></span></b></div>
                        <!-- date_debut  -->
                        <div class="mb-3">
                            <label for="input-add-date-debut" class="form-label"><?= __('forms.labels.date_start') ?> <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text text-success"><i class="fad fa-calendar"></i></span>
                                <input type="datetime-local" class="form-control form-control-sm text-secondary" id="input-add-date-debut" max="<?= date('Y-m-d\TH:i') ?>" required>
                            </div>
                        </div>
                        <!-- date_fin  -->
                        <div class="mb-3">
                            <label for="input-add-date-fin" class="form-label"><?= __('forms.labels.date_end') ?> <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text text-success"><i class="fad fa-calendar"></i></span>
                                <input type="datetime-local" class="form-control form-control-sm text-secondary" id="input-add-date-fin" max="<?= date('Y-m-d\TH:i') ?>">
                            </div>
                            <span class="form-text">(<?= __('forms.labels.message_date_end') ?>)</span>
                        </div>
                        <!-- select - id_utilisateur  -->
                        <div class="mb-3">
                            <label for="select-add-id-utilisateur" class="form-label"><?= ucfirst(__('forms.labels.cashier')) ?> <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text text-success"><i class="fad fa-user"></i></span>
                                <select name="" id="select-add-id-utilisateur" class="form-select form-select-sm select2" required>
                                    <option></option>
                                    <option value="loading" disabled><?= __('forms.labels.loading') ?>...</option>
                                </select>
                                <button type="button" class="input-group-text" id="btn-refresh-list-user"><i class="fad fa-arrow-rotate-left"></i></button>
                            </div>
                        </div>
                    </div>
                    <!-- modal footer  -->
                    <div class="modal-footer d-flex flex-nowrap justify-content-end">
                        <button class="btn btn-outline-secondary btn-sm fw-bold" data-bs-dismiss="modal" type="button" id="btn-close-modal-add-ligne-caisse"><i class="fad fa-x me-2"></i><?= __('forms.labels.cancel') ?></button>
                        <button class="btn btn-primary btn-sm fw-bold" type="submit" id="btn-confirm-add-ligne-caisse"><i class="fad fa-check me-2"></i><?= __('forms.labels.add') ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- modal update ligne_caisse  -->
    <div class="modal fade" id="modal-update-ligne-caisse" tabindex="-1" aria-labelledby="modalUpdateLigneCaisse" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <!-- modal header  -->
                <div class="modal-header bg-green-0 text-light">
                    <h6 class="modal-title fw-bold"><i class="fad fa-pen-to-square me-2"></i><?= __('forms.titles.update_ligne_caisse') ?></h6>
                </div>
                <form>
                    <!-- modal body  -->
                    <div class="modal-body">
                        <!-- id_lc  -->
                        <div class="text-center text-secondary mb-1"><span id="update-ligne-caisse-id-lc">00</span></div>
                        <!-- date_debut  -->
                        <div class="mb-3">
                            <label for="input-update-date-debut" class="form-label"><?= __('forms.labels.date_start') ?> <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text text-success"><i class="fad fa-calendar"></i></span>
                                <input type="datetime-local" class="form-control form-control-sm text-secondary" id="input-update-date-debut" max="<?= date('Y-m-d\TH:i') ?>" required>
                            </div>
                        </div>
                        <!-- date_fin  -->
                        <div class="mb-3">
                            <label for="input-update-date-fin" class="form-label"><?= __('forms.labels.date_end') ?> <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text text-success"><i class="fad fa-calendar"></i></span>
                                <input type="datetime-local" class="form-control form-control-sm text-secondary" id="input-update-date-fin" max="<?= date('Y-m-d\TH:i') ?>">
                            </div>
                            <span class="form-text">(<?= __('forms.labels.message_date_end') ?>)</span>
                        </div>
                        <!-- select - id_utilisateur  -->
                        <div class="mb-3">
                            <label for="select-update-id-utilisateur" class="form-label"><?= ucfirst(__('forms.labels.cashier')) ?> <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text text-success"><i class="fad fa-user"></i></span>
                                <select name="" id="select-update-id-utilisateur" class="form-select form-select-sm select2" required>
                                    <option></option>
                                    <option value="loading" disabled><?= __('forms.labels.loading') ?>...</option>
                                </select>
                                <button type="button" class="input-group-text" id="btn-refresh-list-user"><i class="fad fa-arrow-rotate-left"></i></button>
                            </div>
                        </div>
                    </div>
                    <!-- modal footer  -->
                    <div class="modal-footer d-flex flex-nowrap justify-content-end">
                        <button class="btn btn-outline-secondary btn-sm fw-bold" data-bs-dismiss="modal" type="button" id="btn-close-modal-update-ligne-caisse"><i class="fad fa-x me-2"></i><?= __('forms.labels.cancel') ?></button>
                        <button class="btn btn-primary btn-sm fw-bold" type="submit" id="btn-confirm-update-ligne-caisse"><i class="fad fa-floppy-disk me-2"></i><?= __('forms.labels.save') ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- modal delete ligne caisse -->
    <div class="modal fade" id="modal-delete-ligne-caisse" tabindex="-1" aria-labelledby="modalDeleteLigneCaisse" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <!-- modal header  -->
                <div class="modal-header bg-danger text-light">
                    <h6 class="modal-title fw-bold"><i class="fad fa-trash me-2"></i><?= __('forms.titles.delete_ligne_caisse') ?></h6>
                </div>
                <!-- modal body  -->
                <div class="modal-body">
                    <div class="message">Voulez-vous vraiment supprimer ces ... lignes de caisse ?</div>
                </div>
                <!-- modal footer  -->
                <div class="modal-footer d-flex flex-nowrap justify-content-end">
                    <button class="btn btn-outline-secondary btn-sm fw-bold" data-bs-dismiss="modal" type="button" id="btn-close-modal-delete-ligne-caisse"><i class="fad fa-x me-2"></i><?= __('forms.labels.no_cancel') ?></button>
                    <button class="btn btn-primary btn-sm fw-bold" type="button" id="btn-confirm-delete-ligne-caisse"><i class="fad fa-check me-2"></i><?= __('forms.labels.yes_delete') ?></button>
                </div>
            </div>
        </div>
    </div>
    <!-- modal occup caisse  -->
    <div class="modal fade" id="modal-occup-caisse" tabindex="-1" aria-labelledby="modalOccupCaisse" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <!-- modal header  -->
                <div class="modal-header bg-green-0 text-light">
                    <h6 class="modal-title fw-bold"><i class="fad fa-key-skeleton-left-right me-2"></i><?= __('forms.titles.cash_occupation') ?></h6>
                </div>
                <form>
                    <!-- modal body  -->
                    <div class="modal-body">
                        <!-- cash num  -->
                        <div class="text-center text-secondary mb-1"><?= strtolower(__('forms.labels.cash_num')) ?> : <b><span id="occup-caisse-cash-num"></span></b></div>
                        <!-- select - id_utilisateur -->
                        <div class="mb-3">
                            <label for="select-occup-caisse-id-utilisateur" class="form-label"><?= ucfirst(__('forms.labels.cashier')) ?> <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text text-success"><i class="fad fa-user"></i></span>
                                <select name="" id="select-occup-caisse-id-utilisateur" class="form-select form-select-sm select2" required>
                                    <option></option>
                                    <option value="loading" disabled><?= __('forms.labels.loading') ?>...</option>
                                </select>
                                <button type="button" class="input-group-text" id="btn-refresh-list-caissier"><i class="fad fa-arrow-rotate-left"></i></button>
                            </div>
                        </div>
                    </div>
                    <!-- modal footer  -->
                    <div class="modal-footer d-flex flex-nowrap justify-content-end">
                        <button class="btn btn-outline-secondary btn-sm fw-bold" data-bs-dismiss="modal" type="button" id="btn-close-modal-occup-caisse"><i class="fad fa-x me-2"></i><?= __('forms.labels.cancel') ?></button>
                        <button class="btn btn-primary btn-sm fw-bold" type="submit" id="btn-confirm-occup-caisse"><i class="fad fa-key me-2"></i><?= __('forms.labels.occup') ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- modal cash report -->
    <div class="modal fade" id="modal-cash-report" tabindex="-1" aria-labelledby="modalCashRport" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <!-- modal header  -->
                <div class="modal-header bg-dark text-light">
                    <h6 class="modal-title fw-bold"><i class="fad fa-file-pdf me-2"></i><?= ucfirst(strtolower(__('forms.titles.cash_report'))) ?></h6>
                </div>
                <form>
                    <!-- modal body  -->
                    <div class="modal-body">
                        <!-- caisse  -->
                        <div class="mb-2">
                            <label for="cash-report-select-num-caisse" class="form-label"><?= ucfirst(__('forms.labels.cash')) ?> <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text text-success"><i class="fad fa-cash-register"></i></span>
                                <select name="" id="cash-report-select-num-caisse" class="select2 form-select form-select-sm" required>
                                    <option></option>
                                    <option value="loading" disabled><?= __('forms.labels.loading') ?>....</option>
                                </select>
                            </div>
                        </div>
                        <!-- date_by  -->
                        <div class="d-flex flex-column justify-content-center border align-items-center rounded-1 p-2">
                            <!-- select date by  -->
                            <div class="mb-2 w-100">
                                <label for="cash-report-select-date-by" class="form-label"><?= __('forms.labels.date') ?></label>
                                <div class="input-group">
                                    <span class="text-success input-group-text"><i class="fad fa-calendar"></i></span>
                                    <select name="" class="form-select form-select-sm" id="cash-report-select-date-by">
                                        <option value="all" selected><?= __('forms.labels.all') ?></option>
                                        <option value="per"><?= __('forms.labels.period') ?></option>
                                        <option value="between"><?= __('forms.labels.between') ?></option>
                                        <option value="month_year"><?= __('forms.labels.month_year') ?></option>
                                    </select>
                                </div>
                            </div>
                            <!-- per select  -->
                            <div class="w-100 mb-2 date-by d-none" id="cash-report-div-per">
                                <label for="cash-report-select-per" class="form-label"><?= ucfirst(__('forms.labels.period')) ?></label>
                                <div class="flex-grow-1">
                                    <div class="input-group">
                                        <select name="" class="form-select form-select-sm" id="cash-report-select-per">
                                            <option value="day" selected><?= __('forms.labels.this_day') ?></option>
                                            <option value="week"><?= __('forms.labels.this_week') ?></option>
                                            <option value="month"><?= __('forms.labels.this_month') ?></option>
                                            <option value="year"><?= __('forms.labels.this_year') ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- between date_by  -->
                            <div class="w-100 mb-2 date-by d-none" id="cash-report-div-between">
                                <!-- from  -->
                                <div class="input-group mb-1">
                                    <label for="cash-report-date-from" class="form-label me-2"><?= __('forms.labels.on') ?></label>
                                    <input type="date" name="" id="cash-report-date-from" class="form-control form-control-sm" max="<?= date('Y-m-d') ?>" min="1700-01-01">
                                </div>
                                <!-- to  -->
                                <div class="input-group">
                                    <label for="cash-report-date-to" class="form-label me-2"><?= ucfirst(__('forms.labels.to')) ?></label>
                                    <input type="date" name="" id="cash-report-date-to" class="form-control form-control-sm" max="<?= date('Y-m-d') ?>">
                                </div>
                            </div>
                            <!-- month_year date_by  -->
                            <div class="w-100 mb-2 date-by d-none" id="cash-report-div-month_year">
                                <!-- month -->
                                <div class="input-group mb-1">
                                    <label for="cash-report-select-month" class="form-label me-2"><?= ucfirst(__('forms.labels.month')) ?></label>
                                    <select name="" id="cash-report-select-month" class="form-select form-select-sm">
                                        <?php $formatter = ''; ?>
                                        <?php if ($_COOKIE['lang'] === 'en'): ?>
                                            <?php $formatter = new IntlDateFormatter(
                                                'en-US',
                                                IntlDateFormatter::FULL,
                                                IntlDateFormatter::NONE,
                                                null,
                                                null,
                                                'MMMM'
                                            ); ?>
                                        <?php elseif ($_COOKIE['lang'] === 'fr'): ?>
                                            <?php $formatter = new IntlDateFormatter(
                                                'fr-FR',
                                                IntlDateFormatter::FULL,
                                                IntlDateFormatter::NONE,
                                                null,
                                                null,
                                                'MMMM'
                                            ); ?>
                                        <?php else: ?>
                                            <?php $formatter = new IntlDateFormatter(
                                                'mg-MG',
                                                IntlDateFormatter::FULL,
                                                IntlDateFormatter::NONE,
                                                null,
                                                null,
                                                'MMMM'
                                            ); ?>
                                        <?php endif; ?>
                                        <option value="all"><?= ucfirst(__('forms.labels.all')) ?></option>
                                        <?php for ($i = 1; $i <= 12; $i++): ?>
                                            <option value="<?= $i ?>"><?= ucfirst($formatter->format(DateTime::createFromFormat('!m', $i))); ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <!-- year  -->
                                <div class="input-group">
                                    <label for="cash-report-select-year" class="form-label me-2"><?= ucfirst(__('forms.labels.year')) ?></label>
                                    <select name="" id="cash-report-select-year" class="form-select form-select-sm">
                                        <?php for ($i = date('Y'); $i >= 1700; $i--): ?>
                                            <option value="<?= $i ?>"><?= $i ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- modal footer  -->
                    <div class="modal-footer d-flex flex-nowrap justify-content-end">
                        <button class="btn btn-outline-secondary btn-sm fw-bold" data-bs-dismiss="modal" type="button" id="btn-close-modal-cash-report"><i class="fad fa-x me-2"></i><?= __('forms.labels.cancel') ?></button>
                        <button class="btn btn-primary btn-sm fw-bold" type="submit" id="btn-confirm-cash-report"><i class="fad fa-download me-2"></i><?= __('forms.labels.download') ?></button>
                    </div>
                </form>
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