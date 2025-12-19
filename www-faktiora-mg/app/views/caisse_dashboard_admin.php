<!-- header  -->
<?php require_once APP_PATH . '/views/layouts/header.php'; ?>
<!-- template  -->
<template id="template-cash">
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
                                <input type="text" class="form-control bg-second rounded-start-4 form-control-sm text-secondary " placeholder="<?= strtolower(__('forms.labels.cash_number')) ?>" id="input-search">
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
                <!-- chart -->
                <div class="row align-items-top py-2">
                    <!-- occup and number -->
                    <div class="col-lg-3 col-12 d-flex flex-column ">
                        <!-- occup  -->
                        <div class="row ">
                            <div class="col-12 py-2">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <!-- title occup  -->
                                        <div class="card-title text-secondary"><i class="fad fa-key-skeleton-left-right me-2"></i><?= __('forms.labels.occupation') ?></div>
                                        <div class="form-group">
                                            <label for="select-occup-caisse" class="form-label text-secondary"><?= __('forms.labels.occup_caisse') ?></label>
                                            <select name="" id="select-occup-caisse" class="form-select form-select-sm ">
                                                <option value="" disabled selected><?= strtolower(__('forms.labels.select')) ?></option>
                                                <option value="" disabled><?= __('forms.labels.loading') ?>...</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- number of cash  -->
                        <div class="row h-auto">
                            <div class="col-12 py-2">
                                <div class="card h-100">
                                    <div class="card-body h-100">
                                        <!-- title caisse number -->
                                        <div class="card-title text-secondary"><i class="fad fa-cash-register me-2"></i><?= __('forms.labels.caisse_number') ?><span id="chart-caisse-number-title"></span></div>
                                        <!-- chart -->
                                        <div class="align-items-center row py-1 justify-content-center">
                                            <!-- chart cash number -->
                                            <div class="col-12 d-flex flex-column align-items-center col-md-6 py-2 px-4 justify-content-center w-100" id="chart-cash-number">
                                                <h6 class="placeholder rounded-1 w-50 bg-second mb-3"></h6>
                                                <div class="rounded-circle bg-second placeholder mb-2" style="aspect-ratio: 1/1;width: 35% !important;"></div>
                                                <!-- legends -->
                                                <div class="d-flex justify-content-center gap-2 w-75">
                                                    <h6 class="placeholder rounded-1 w-25 bg-second"></h6>
                                                    <h6 class="placeholder rounded-1 w-25 bg-second"></h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- chart and histo transactons -->
                    <div class="col-12 py-2 col-lg-9 d-flex flex-column justify-content-between">
                        <!-- chart transactions  -->
                        <div class="row">
                            <!-- transactions number and total -->
                            <div class="col-12 py-2">
                                <div class="card">
                                    <div class="card-body">
                                        <!-- title transactions -->
                                        <div class="card-title text-secondary" id="chart-client-title"><i class="fad fa-money-bill-transfer me-2"></i><?= __('forms.labels.transactions') ?></div>
                                        <!-- chart -->
                                        <div class="align-items-center row py-1">
                                            <!-- chart nb_transactions  -->
                                            <div class="col-12 col-md-6 d-flex flex-column align-items-center py-2 justify-content-center px-4" id="chart-nb-transactions">
                                                <h6 class="placeholder rounded-1 w-25 bg-second mb-3"></h6>
                                                <div class="rounded-circle bg-second placeholder mb-2" style="aspect-ratio: 1/1;width: 35% !important;"></div>
                                                <!-- legends -->
                                                <div class="d-flex justify-content-center gap-2 w-75">
                                                    <h6 class="placeholder rounded-1 w-25 bg-second"></h6>
                                                    <h6 class="placeholder rounded-1 w-25 bg-second"></h6>
                                                    <h6 class="placeholder rounded-1 w-25 bg-second"></h6>
                                                </div>
                                            </div>
                                            <!-- chart status  -->
                                            <div class="col-12 col-md-6 d-flex flex-column align-items-center py-2 px-4 justify-content-center" id="chart-total-transactions">
                                                <h6 class="placeholder rounded-1 w-25 bg-second mb-3"></h6>
                                                <div class="rounded-circle bg-second placeholder mb-2" style="aspect-ratio: 1/1;width: 35% !important;"></div>
                                                <!-- legends -->
                                                <div class="d-flex justify-content-center gap-2 w-75">
                                                    <h6 class="placeholder rounded-1 w-25 bg-second"></h6>
                                                    <h6 class="placeholder rounded-1 w-25 bg-second"></h6>
                                                    <h6 class="placeholder rounded-1 w-25 bg-second"></h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- chart histo transactions  -->
                        <div class="row">
                            <div class="col-12 py-2">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <!-- title histo -->
                                        <div class="card-title text-secondary" id="chart-histo-transactions-title"><i class="fad fa-chart-mixed-up-circle-dollar me-2"></i><?= __('forms.titles.curves_transactions') ?></div>
                                        <!-- chart -->
                                        <div class="align-items-center row py-1" id="chart-histo">
                                            <!-- histo number  -->
                                            <div class="col-12 col-md-6 d-flex flex-column align-items-center py-2 px-4 justify-content-center" id="chart-histo-nb-transactions">
                                                <h6 class="placeholder rounded-1 w-25 bg-second mb-3"></h6>
                                                <div class="bg-second placeholder mb-2 w-75 rounded-1" style="height: 15vh !important;"></div>
                                                <!-- legends -->
                                                <div class="d-flex justify-content-center gap-2 w-75">
                                                    <h6 class="placeholder rounded-1 w-25 bg-second"></h6>
                                                    <h6 class="placeholder rounded-1 w-25 bg-second"></h6>
                                                    <h6 class="placeholder rounded-1 w-25 bg-second"></h6>
                                                </div>
                                            </div>
                                            <!-- histo total  -->
                                            <div class="col-12 col-md-6 d-flex flex-column align-items-center py-2 px-4 justify-content-center" id="chart-histo-total-transactions">
                                                <h6 class="placeholder rounded-1 w-25 bg-second mb-3"></h6>
                                                <div class="bg-second placeholder mb-2 w-75 rounded-1" style="height: 15vh !important;"></div>
                                                <!-- legends -->
                                                <div class="d-flex justify-content-center gap-2 w-75">
                                                    <h6 class="placeholder rounded-1 w-25 bg-second"></h6>
                                                    <h6 class="placeholder rounded-1 w-25 bg-second"></h6>
                                                    <h6 class="placeholder rounded-1 w-25 bg-second"></h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- table caisse and ligne caisse -->
                <div class="row py-2 flex-nowrap">
                    <div class="col-12">
                        <div class="row overflow-x-auto w-100 flex-nowrap g-0">
                            <!-- table caisse  -->
                            <div class="col-12 h-100 col-lg-6">
                                <div class="card h-100">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <!-- title table caisse-->
                                        <div class="card-title text-secondary flex-grow-0" id="chart-transactions-curves-title">
                                            <i class="fad fa-list-dropdown me-2"></i><?= __('forms.titles.cash_list') ?>
                                        </div>
                                        <div class="row justify-content-center  overflow-auto align-items-top flex-grow-1">
                                            <div class="col-12">
                                                <!-- btn  -->
                                                <div class="row g-0 justify-content-start mb-2">
                                                    <!-- btn add caisse -->
                                                    <button class="btn btn-outline-primary fw-bold w-auto btn-sm me-2 my-2" data-bs-toggle='modal' data-bs-target='#modal-add-caisse'>
                                                        <i class="fad fa-circle-plus me-2"></i><?= __('forms.labels.add') ?>
                                                    </button>
                                                    <!-- btn restore caisse  -->
                                                    <button class="btn btn-outline-dark fw-bold w-auto btn-sm me-2 my-2" id="btn-deconnect-user">
                                                        <i class="fad fa-arrow-rotate-left me-2"></i>
                                                        <?= __('forms.labels.restore') ?>
                                                    </button>
                                                    <!-- btn delete caisse -->
                                                    <button class="btn btn-outline-danger fw-bold w-auto btn-sm me-2 my-2" id="btn-delete-user">
                                                        <i class="fad fa-trash me-2"></i><?= __('forms.labels.delete') ?>
                                                    </button>
                                                    <!-- btn delete permanent caisse  -->
                                                    <button class="btn btn-danger fw-bold w-auto btn-sm me-2 my-2" id="btn-delete-permanent-user">
                                                        <i class="fad fa-trash me-2"></i><?= __('forms.labels.delete_permanent') ?>
                                                    </button>
                                                </div>
                                                <!-- table  -->
                                                <table class="w-100 table-striped">
                                                    <thead class="bg-success text-light align-items-center form-text">
                                                        <tr>
                                                            <th><input type="checkbox" class="form-check-input" id="check-all"></th>
                                                            <th><i class="fad fa-hashtag me-2"></i><?= __('forms.labels.num') ?></th>
                                                            <th><i class="fad fa-coins me-2"></i><?= __('forms.labels.balance') . " ({$currency_units})" ?></th>
                                                            <th><i class="fad fa-badge-dollar me-2"></i><?= __('forms.labels.treshold') . " ({$currency_units})" ?></th>
                                                            <th><i class="fad fa-circle-dot me-2"></i><?= __('forms.labels.status') ?></th>
                                                            <th><i class="fad fa-user me-2"></i><?= __('forms.labels.user') ?></th>
                                                            <th><i class="fad fa-gears me-2"></i><?= __('forms.labels.action') ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tbody-caisse">
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
                            <div class="col-12 h-100 ms-2 col-lg-6">
                                <div class="card h-100">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <!-- title table -->
                                        <div class="card-title text-secondary flex-grow-0" id="chart-transactions-curves-title">
                                            <i class="fad fa-list-dropdown me-2"></i><?= __('forms.titles.cash_line') ?>
                                        </div>
                                        <div class="row justify-content-center  overflow-auto align-items-top flex-grow-1">
                                            <div class="col-12">
                                                <!-- btn  -->
                                                <div class="row g-0 justify-content-start mb-2">
                                                    <!-- btn add ligne_caisse  -->
                                                    <button class="btn btn-outline-primary fw-bold w-auto btn-sm me-2 my-2" data-bs-toggle='modal' data-bs-target='#modal-add-user'>
                                                        <i class="fad fa-circle-plus me-2"></i><?= __('forms.labels.add') ?>
                                                    </button>
                                                    <!-- btn delete ligne_caisse  -->
                                                    <button class="btn btn-danger fw-bold w-auto btn-sm me-2 my-2" id="btn-deconnect-user">
                                                        <i class="fad fa-trash  me-2"></i><?= __('forms.labels.delete') ?>
                                                    </button>
                                                </div>
                                                <table class="w-100 table-striped">
                                                    <thead class="bg-success text-light align-items-center form-text">
                                                        <tr>
                                                            <th><input type="checkbox" class="form-check-input" id="check-all"></th>
                                                            <th><i class="fad fa-hashtag me-2"></i>ID</th>
                                                            <th><i class="fad fa-calendar me-2"></i>Date début</th>
                                                            <th><i class="fad fa-calendar me-2"></i>Date fin</th>
                                                            <th><i class="fad fa-user me-2"></i>Utilisateur</th>
                                                            <th><i class="fad fa-gears me-2"></i>Action</th>
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
                <input type="text" class="form-control bg-second rounded-start-4 form-control-sm text-secondary " placeholder="<?= strtolower(__('forms.labels.cash_number')) ?>" id="input-search-searchbar">
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
                            <option value="free"><?= strtolower(__('forms.labels.free')) ?></option>
                            <option value="occuped"><?= strtolower(__('forms.labels.occuped')) ?></option>
                            <option value="deleted"><?= __('forms.labels.deleted') ?></option>
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
                            <option value="num" selected><?= strtolower(__('forms.labels.cash_number')) ?></option>
                            <optgroup label="Nombre">
                                <option value="nb_transaction"><?= strtolower(__('forms.labels.nb_transactions')) ?></option>
                                <option value="nb_entree"><?= __('forms.labels.nb_entree') ?></option>
                                <option value="nb_sortie"><?= __('forms.labels.nb_sortie') ?></option>
                                <option value="nb_facture"><?= __('forms.labels.nb_facture') ?></option>
                                <option value="nb_ae"><?= __('forms.labels.nb_ae') ?></option>
                            </optgroup>
                            <optgroup label="Total">
                                <option value="total_transactions"><?= strtolower(__('forms.labels.total_transactions')) ?></option>
                                <option value="total_entrees"><?= __('forms.labels.total_entree') ?></option>
                                <option value="total_sortie"><?= __('forms.labels.total_sortie') ?></option>
                                <option value="total_facture"><?= __('forms.labels.total_facture') ?></option>
                                <option value="total_ae"><?= __('forms.labels.total_ae') ?></option>
                            </optgroup>
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
    <!-- modal add caisse  -->
    <div class="modal fade" id="modal-add-caisse" tabindex="-1" aria-labelledby="modalAddCaisse" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <!-- modal header  -->
                <div class="modal-header bg-green-0 text-light">
                    <h6 class="modal-title fw-bold"><i class="fad fa-circle-plus me-2"></i><?= __('forms.titles.cash_add') ?></h6>
                </div>
                <!-- form add caisse  -->
                <form>
                    <!-- modal body  -->
                    <div class="modal-body">
                        <!-- add num_caisse  -->
                        <div class="mb-2">
                            <label for="input-add-num-caisse" class="form-label"><?= __('forms.labels.cash_num') ?> <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text text-success">
                                    <i class="fad fa-hashtag"></i>
                                </span>
                                <input type="text" class="form-control form-control-sm text-secondary" id="input-add-num-caisse" min="0" required>
                            </div>
                        </div>
                        <!--  add solde -->
                        <div class="mb-2">
                            <label for="input-add-solde" class="form-label"><?= __('forms.labels.balance') ?> <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text text-success">
                                    <i class="fad fa-coins"></i>
                                </span>
                                <input type="text" class="form-control form-control-sm text-secondary" id="input-add-solde" required>
                            </div>
                        </div>
                        <!--  add seuil -->
                        <div class="mb-2">
                            <label for="select-add-seuil" class="form-label me-2 mt-1"><?= __('forms.labels.treshold') ?> <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text text-success">
                                    <i class="fad fa-badge-dollar"></i>
                                </span>
                                <input type="text" class="form-control form-control-sm text-secondary" id="input-add-seuil" required>
                            </div>
                            <span class="form-text text-secondary"><?= strtolower(__('forms.labels.less_equal_balance')) ?></span>
                        </div>
                    </div>
                    <!-- modal footer  -->
                    <div class="modal-footer d-flex flex-nowrap justify-content-end">
                        <button class="btn btn-outline-secondary btn-sm fw-bold" data-bs-dismiss="modal" type="button" id="btn-close-modal-add-caisse"><i class="fad fa-x me-2"></i><?= __('forms.labels.cancel') ?></button>
                        <button class="btn btn-primary btn-sm fw-bold" type="submit"><i class="fad fa-circle-plus me-2"></i><?= __('forms.labels.add') ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- modal update caisse  -->
    <div class="modal fade" id="modal-update-caisse" tabindex="-1" aria-labelledby="modalUpdateCaisse" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <!-- modal header  -->
                <div class="modal-header bg-green-0 text-light">
                    <h6 class="modal-title fw-bold"><i class="fad fa-pen-to-square me-2"></i><?= __('forms.titles.cash_update') ?></h6>
                </div>
                <!-- form update caisse  -->
                <form>
                    <!-- modal body  -->
                    <div class="modal-body">
                        <!-- num_caisse  -->
                        <div class="text-center text-secondary" id="num-caisse">0</div>
                        <!-- update num_caisse_update  -->
                        <div class="mb-2">
                            <label for="input-update-num-caisse-update" class="form-label"><?= __('forms.labels.cash_num') ?> <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text text-success">
                                    <i class="fad fa-hashtag"></i>
                                </span>
                                <input type="text" class="form-control form-control-sm text-secondary" id="input-update-num-caisse-update" min="0" required>
                            </div>
                        </div>
                        <!--  update solde -->
                        <div class="mb-2">
                            <label for="input-update-solde" class="form-label"><?= __('forms.labels.balance') ?> <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text text-success">
                                    <i class="fad fa-coins"></i>
                                </span>
                                <input type="text" class="form-control form-control-sm text-secondary" id="input-update-solde" required>
                            </div>
                        </div>
                        <!-- update seuil -->
                        <div class="mb-2">
                            <label for="select-update-seuil" class="form-label me-2 mt-1"><?= __('forms.labels.treshold') ?> <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text text-success">
                                    <i class="fad fa-badge-dollar"></i>
                                </span>
                                <input type="text" class="form-control form-control-sm text-secondary" id="input-update-seuil" required>
                            </div>
                            <span class="form-text text-secondary"><?= strtolower(__('forms.labels.less_equal_balance')) ?></span>
                        </div>
                    </div>
                    <!-- modal footer  -->
                    <div class="modal-footer d-flex flex-nowrap justify-content-end">
                        <button class="btn btn-outline-secondary btn-sm fw-bold" data-bs-dismiss="modal" type="button" id="btn-close-modal-update-caisse"><i class="fad fa-x me-2"></i><?= __('forms.labels.cancel') ?></button>
                        <button class="btn btn-primary btn-sm fw-bold" type="submit"><i class="fad fa-floppy-disk me-2"></i><?= __('forms.labels.save') ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- modal delete user  -->
    <div class="modal fade" id="modal-delete-user" tabindex="-1" aria-labelledby="modalDeleteUser" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <!-- modal header  -->
                <div class="modal-header bg-danger text-light">
                    <h6 class="modal-title fw-bold"><i class="fad fa-user-circle-minus me-2"></i><?= __('forms.titles.delete_user') ?></h6>
                </div>
                <!-- modal body  -->
                <div class="modal-body">
                    <div class="message">Voulez-vous vraiment supprimer ces ... utilisateurs ?</div>
                </div>
                <!-- modal footer  -->
                <div class="modal-footer d-flex flex-nowrap justify-content-end">
                    <button class="btn btn-outline-secondary btn-sm fw-bold" data-bs-dismiss="modal" type="button" id="btn-close-modal-delete-user"><i class="fad fa-x me-2"></i><?= __('forms.labels.no_cancel') ?></button>
                    <button class="btn btn-primary btn-sm fw-bold" type="button" id="btn-confirm-delete-user"><i class="fad fa-check me-2"></i><?= __('forms.labels.yes_delete') ?></button>
                </div>
            </div>
        </div>
    </div>
    <!-- modal deconnect user  -->
    <div class="modal fade" id="modal-deconnect-user" tabindex="-1" aria-labelledby="modalDeconnectUser" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <!-- modal header  -->
                <div class="modal-header bg-dark text-light">
                    <h6 class="modal-title fw-bold"><i class="fad fa-arrow-left-from-bracket me-2"></i><?= __('forms.titles.deconnect_user') ?></h6>
                </div>
                <!-- modal body  -->
                <div class="modal-body">
                    <div class="message">Voulez-vous vraiment déconnecter ces ... utilisateurs ?</div>
                </div>
                <!-- modal footer  -->
                <div class="modal-footer d-flex flex-nowrap justify-content-end">
                    <button class="btn btn-outline-secondary btn-sm fw-bold" data-bs-dismiss="modal" type="button" id="btn-close-modal-deconnect-user"><i class="fad fa-x me-2"></i><?= __('forms.labels.no_cancel') ?></button>
                    <button class="btn btn-primary btn-sm fw-bold" type="button" id="btn-confirm-deconnect-user"><i class="fad fa-check me-2"></i><?= __('forms.labels.yes_deconnect') ?></button>
                </div>
            </div>
        </div>
    </div>
    <!-- modal print all user  -->
    <div class="modal fade" id="modal-print-all-user" tabindex="-1" aria-labelledby="modalPrintAllUser" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <!-- modal header  -->
                <div class="modal-header bg-dark text-light">
                    <h6 class="modal-title fw-bold"><i class="fad fa-file-pdf me-2"></i><?= __('forms.titles.print_all_user') ?></h6>
                </div>
                <!-- modal body  -->
                <div class="modal-body">
                    <div class="message mb-4"><?= __('forms.labels.ask_print_all_user') ?></div>
                    <div class="d-flex justify-content-center gap-4">
                        <!-- status active  -->
                        <div class="form-check">
                            <input type="radio" class="form-check-input form-check-input-sm" name="download-status-user" id="download-status-user-active" value="acive" checked>
                            <label for="form-check-label" for="download-status-user-active"><?= __('forms.labels.active') ?></label>
                        </div>
                        <!-- status deleted  -->
                        <div class="form-check">
                            <input type="radio" class="form-check-input form-check-input-sm" name="download-status-user" id="download-status-user-deleted" value="deleted">
                            <label for="form-check-label" for="download-status-user-deleted"><?= __('forms.labels.deleted') ?></label>
                        </div>
                    </div>
                </div>
                <!-- modal footer  -->
                <div class="modal-footer d-flex flex-nowrap justify-content-end">
                    <button class="btn btn-outline-secondary btn-sm fw-bold" data-bs-dismiss="modal" type="button" id="btn-close-modal-print-all-user"><i class="fad fa-x me-2"></i><?= __('forms.labels.cancel') ?></button>
                    <button class="btn btn-primary btn-sm fw-bold" type="button" id="btn-confirm-print-all-user"><i class="fad fa-download me-2"></i><?= __('forms.labels.download') ?></button>
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