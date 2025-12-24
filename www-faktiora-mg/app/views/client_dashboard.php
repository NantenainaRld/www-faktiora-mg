<!-- header  -->
<?php require_once APP_PATH . '/views/layouts/header.php'; ?>
<!-- template  -->
<template id="template-client">
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
                                <input type="text" class="form-control bg-second rounded-start-4 form-control-sm text-secondary " placeholder="<?= __('forms.placeholders.search_client') ?>" id="input-search">
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
                            <!-- table ligne_caisse -->
                            <div class="col-12 h-100">
                                <div class="card h-100">
                                    <div class="card-body d-flex flex-column justify-content-between h-100">
                                        <!-- title table -->
                                        <div class="card-title text-secondary flex-grow-0" id="chart-transactions-curves-title">
                                            <i class="fad fa-list-dropdown me-2"></i><?= __('forms.titles.client_list') ?>
                                        </div>
                                        <div class="row justify-content-center  overflow-auto align-items-top flex-grow-1">
                                            <!-- table  -->
                                            <div class="col-12">
                                                <!-- btns  -->
                                                <div class="d-flex gp-2 justify-content-start w-100 my-2">
                                                    <!-- btn add client  -->
                                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                                        data-bs-target="#modal-add-client" id="btn-add-client"><i class="fad fa-user-circle-plus me-2">
                                                        </i><?= __('forms.labels.add') ?>
                                                    </button>
                                                </div>
                                                <table class=" w-100 table-striped">
                                                    <thead class="bg-success text-light align-items-center form-text">
                                                        <tr>
                                                            <th class="text-center"><i class="fad fa-hashtag me-2"></i>ID</th>
                                                            <th class="text-center"><i class="fad fa-address-card me-2"></i><?= __('forms.labels.name_firstname') ?></th>
                                                            <th class="text-center"><i class="fad fa-venus me-2"></i><?= ucfirst(__('forms.labels.sex')) ?></th>
                                                            <th class="text-center"><i class="fad fa-phone me-2"></i><?= __('forms.labels.phone') ?></th>
                                                            <th class="text-center"><i class="fad fa-location-dot me-2"></i><?= __('forms.labels.address') ?></th>
                                                            <th class="text-center"><i class="fad fa-dot-circle me-2"></i><?= __('forms.labels.status') ?></th>
                                                            <th class="text-center"><i class="fad fa-money-bill-transfer me-2"></i><?= ucfirst(__('forms.labels.nb_facture')) ?></th>
                                                            <th class="text-center"><i class="fad fa-money-bill-transfer me-2"></i><?= ucfirst(__('forms.labels.total_facture')) ?> (<?= $currency_units ?>)</th>
                                                            <th class="text-center"><i class="fad fa-gears me-2"></i><?= __('forms.labels.actions') ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tbody-client">
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
            <!-- status select  -->
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
            <!-- sex select  -->
            <div class="text-secondary w-100 row justify-content-between mb-2">
                <div class="flex-grow-0">
                    <label for="select-sex" class="w-auto form-label
                    "><i class="fad fa-venus me-2"></i><?= __('forms.labels.sex') ?></label>
                </div>
                <div class="flex-grow-1">
                    <div class="input-group">
                        <select name="" class="form-select form-select-sm" id="select-sex">
                            <option value="all" selected><?= __('forms.labels.all') ?></option>
                            <option value="masculin"><?= __('forms.labels.male') ?></option>
                            <option value="féminin"><?= __('forms.labels.female') ?></option>
                        </select>
                    </div>
                </div>
            </div>
            <!-- arrange by select select  -->
            <div class="text-secondary w-100 row justify-content-between mb-2">
                <div class="flex-grow-0">
                    <label for="select-arrange-by" class="w-auto form-label
                    "><i class="fad fa-venus me-2"></i><?= __('forms.labels.arrange_by') ?></label>
                </div>
                <div class="flex-grow-1">
                    <div class="input-group">
                        <select name="" class="form-select form-select-sm" id="select-arrange-by">
                            <option value="id">ID</option>
                            <option value="name"><?= strtolower(__('forms.labels.name')) ?></option>
                            <option value="nb_facture"><?= __('forms.labels.nb_facture') ?></option>
                            <option value="total_facture"><?= __('forms.labels.total_facture') ?></option>
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
            <div class="d-flex flex-column justify-content-center border align-items-center rounded-1 py-1">
                <!-- select date by  -->
                <div class="text-secondary w-100 row justify-content-between mb-2">
                    <div class="flex-grow-0">
                        <label for="select-date-by" class="w-auto form-label
                    "><i class="fad fa-calendar me-2"></i><?= __('forms.labels.date_by') ?></label>
                    </div>
                    <div class="flex-grow-1">
                        <div class="input-group">
                            <select name="" class="form-select form-select-sm" id="select-date-by">
                                <option value="all" selected><?= __('forms.labels.all') ?></option>
                                <option value="per"><?= __('forms.labels.period') ?></option>
                                <option value="between"><?= __('forms.labels.between') ?></option>
                                <option value="month_year"><?= __('forms.labels.month_year') ?></option>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- per select  -->
                <div class="text-secondary d-none w-100 row justify-content-between mb-2 date-by" id="div-per">
                    <div class="flex-grow-0">
                        <label for="select-per" class="w-auto form-label
                    "><?= ucfirst(__('forms.labels.period')) ?></label>
                    </div>
                    <div class="flex-grow-1">
                        <div class="input-group">
                            <select name="" class="form-select form-select-sm" id="select-per">
                                <option value="day" selected><?= __('forms.labels.this_day') ?></option>
                                <option value="week"><?= __('forms.labels.this_week') ?></option>
                                <option value="month"><?= __('forms.labels.this_month') ?></option>
                                <option value="year"><?= __('forms.labels.this_year') ?></option>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- between date_by  -->
                <div class="text-secondary d-none w-100 row justify-content-between mb-2 date-by" id="div-between">
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
                <!-- month_year date_by  -->
                <div class="text-secondary d-none w-100 row justify-content-between mb-2 date-by" id="div-month_year">
                    <!-- month -->
                    <div class="input-group mb-1">
                        <label for="select-month" class="form-label me-2
                    "><?= ucfirst(__('forms.labels.month')) ?></label>
                        <select name="" id="select-month" class="form-select form-select-sm">
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
                        <label for="select-year" class="form-label me-2
                    "><?= ucfirst(__('forms.labels.year')) ?></label>
                        <select name="" id="select-year" class="form-select form-select-sm">
                            <?php for ($i = date('Y'); $i >= 1700; $i--): ?>
                                <option value="<?= $i ?>"><?= $i ?></option>
                            <?php endfor; ?>
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
                        <button class="btn btn-outline-secondary btn-sm fw-bold" data-bs-dismiss="modal" type="button" id="btn-close-modal-add-client"><i class="fad fa-x me-2"></i><?= __('forms.labels.cancel') ?></button>
                        <button class="btn btn-primary btn-sm fw-bold" type="submit" id="btn-confirm-add-client"><i class="fad fa-user-circle-plus me-2"></i><?= __('forms.labels.add') ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- modal add client -->
    <div class="modal fade" id="modal-update-client" tabindex="-1" aria-labelledby="modalUpdateClient" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <!-- modal header  -->
                <div class="modal-header bg-green-0 text-light">
                    <h6 class="modal-title fw-bold"><i class="fad fa-user-pen me-2"></i><?= __('forms.titles.client_update') ?></h6>
                </div>
                <form>
                    <!-- modal body  -->
                    <div class="modal-body">
                        <!-- id client  -->
                        <div class="mb-2 text-center text-secondary w-100 fw-bold" id="update-id-client"></div>
                        <!-- input - nom_client  -->
                        <div class="mb-2">
                            <label for="input-update-nom-client" class="form-label"><?= __('forms.labels.name') ?> <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text text-success"><i class="fad fa-address-card"></i></span>
                                <input type="text" maxlength="100" class="form-control form-control-sm" id="input-update-nom-client" placeholder="RALANDISON" required>
                            </div>
                        </div>
                        <!-- input - prenoms_client  -->
                        <div class="mb-2">
                            <label for="input-update-prenoms-client" class="form-label"><?= __('forms.labels.firstname') ?></label>
                            <div class="input-group">
                                <span class="input-group-text text-success"><i class="fad fa-address-card"></i></span>
                                <input type="text" maxlength="100" class="form-control form-control-sm" id="input-update-prenoms-client" placeholder="Nantenaina">
                            </div>
                        </div>
                        <!-- select - sexe_client  -->
                        <div class="mb-2">
                            <label for="select-update-sexe-client" class="form-label"><?= __('forms.labels.sex') ?></label>
                            <div class="input-group">
                                <span class="input-group-text text-success"><i class="fad fa-venus"></i></span>
                                <select name="" id="select-update-sexe-client" class="form-select form-select-sm">
                                    <option value="masculin"><?= __('forms.labels.male') ?></option>
                                    <option value="féminin"><?= __('forms.labels.female') ?></option>
                                </select>
                            </div>
                        </div>
                        <!-- input - telephone -->
                        <div class="mb-2">
                            <label for="input-update-telephone" class="form-label"><?= __('forms.labels.phone') ?> </label>
                            <div class="input-group">
                                <span class="input-group-text text-success"><i class="fad fa-phone"></i></span>
                                <input type="text" maxlength="20" minlength="10" class="form-control form-control-sm" id="input-update-telephone" placeholder="032 00 000 00">
                            </div>
                        </div>
                        <!-- input - adresse -->
                        <div class="mb-2">
                            <label for="input-update-adresse" class="form-label"><?= __('forms.labels.address') ?> </label>
                            <div class="input-group">
                                <span class="input-group-text text-success"><i class="fad fa-location-dot"></i></span>
                                <input type="text" maxlength="20" class="form-control form-control-sm" id="input-update-adresse" placeholder="Andrainjato Fianarantsoa">
                            </div>
                        </div>
                    </div>
                    <!-- modal footer  -->
                    <div class="modal-footer d-flex flex-nowrap justify-content-end">
                        <button class="btn btn-outline-secondary btn-sm fw-bold" data-bs-dismiss="modal" type="button" id="btn-close-modal-update-client"><i class="fad fa-x me-2"></i><?= __('forms.labels.cancel') ?></button>
                        <button class="btn btn-primary btn-sm fw-bold" type="submit" id="btn-confirm-update-client"><i class="fad fa-floppy-disk me-2"></i><?= __('forms.labels.save') ?></button>
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