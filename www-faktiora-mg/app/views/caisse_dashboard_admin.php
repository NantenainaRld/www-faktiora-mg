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
                <!-- chart && occup caisse -->
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
                                            <select name="" id="select-occup-caisse" class="form-select form-select-sm select2">
                                                <option></option>
                                                <option value="loading" disabled><?= __('forms.labels.loading') ?>...</option>
                                            </select>
                                        </div>
                                        <div class="w-100 mt-2 d-flex justify-content-center">
                                            <button type="button" class="btn btn-light btn-sm" id="btn-occup-caisse"><i class="fad fa-key me-2"></i><?= __('forms.labels.occup') ?></button>
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
                                                    <!-- btn free caisse -->
                                                    <button class="btn btn-outline-info fw-bold w-auto btn-sm me-2 my-2" id="btn-free-caisse">
                                                        <i class="fad fa-circle-minus me-2"></i><?= __('forms.labels.free_c') ?>
                                                    </button>
                                                    <!-- btn restore caisse  -->
                                                    <button class="btn btn-outline-warning fw-bold w-auto btn-sm me-2 my-2" id="btn-restore-caisse">
                                                        <i class="fad fa-arrow-rotate-left me-2"></i>
                                                        <?= __('forms.labels.restore') ?>
                                                    </button>
                                                    <!-- btn delete caisse -->
                                                    <button class="btn btn-outline-danger fw-bold w-auto btn-sm me-2 my-2" id="btn-delete-caisse">
                                                        <i class="fad fa-trash me-2"></i><?= __('forms.labels.delete') ?>
                                                    </button>
                                                    <!-- btn delete permanent caisse  -->
                                                    <button class="btn btn-danger fw-bold w-auto btn-sm me-2 my-2" id="btn-delete-permanent-caisse">
                                                        <i class="fad fa-trash me-2"></i><?= __('forms.labels.delete_permanent') ?>
                                                    </button>
                                                </div>
                                                <!-- table  -->
                                                <table class="w-100 table-striped">
                                                    <thead class="bg-success text-light align-items-center form-text">
                                                        <tr>
                                                            <th class="text-center"><input type="checkbox" class="form-check-input" id="check-all-caisse"></th>
                                                            <th class="text-center"><i class="fad fa-hashtag me-2"></i><?= __('forms.labels.num') ?></th>
                                                            <th class="text-center"><i class="fad fa-coins me-2"></i><?= __('forms.labels.balance') . " ({$currency_units})" ?></th>
                                                            <th class="text-center"><i class="fad fa-badge-dollar me-2"></i><?= __('forms.labels.treshold') . " ({$currency_units})" ?></th>
                                                            <th class="text-center"><i class="fad fa-circle-dot me-2"></i><?= __('forms.labels.status') ?></th>
                                                            <th class="text-center"><i class="fad fa-user me-2"></i><?= __('forms.labels.user') ?></th>
                                                            <th class="text-center"><i class="fad fa-gears me-2"></i><?= __('forms.labels.action') ?></th>
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
                            <span class="form-text text-secondary">(<?= strtolower(__('forms.labels.less_equal_balance')) ?>)</span>
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
                        <div class="text-center text-secondary mb-1" id="num-caisse">0</div>
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
                            <span class="form-text text-secondary">(<?= strtolower(__('forms.labels.less_equal_balance')) ?>)</span>
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
    <!-- modal delete caisse -->
    <div class="modal fade" id="modal-delete-caisse" tabindex="-1" aria-labelledby="modalDeleteCaisse" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <!-- modal header  -->
                <div class="modal-header bg-danger text-light">
                    <h6 class="modal-title fw-bold"><i class="fad fa-trash me-2"></i><?= __('forms.titles.cash_delete') ?></h6>
                </div>
                <!-- modal body  -->
                <div class="modal-body">
                    <div class="message">Voulez-vous vraiment supprimer ces ... caisses ?</div>
                </div>
                <!-- modal footer  -->
                <div class="modal-footer d-flex flex-nowrap justify-content-end">
                    <button class="btn btn-outline-secondary btn-sm fw-bold" data-bs-dismiss="modal" type="button" id="btn-close-modal-delete-caisse"><i class="fad fa-x me-2"></i><?= __('forms.labels.no_cancel') ?></button>
                    <button class="btn btn-primary btn-sm fw-bold" type="button" id="btn-confirm-delete-caisse"><i class="fad fa-check me-2"></i><?= __('forms.labels.yes_delete') ?></button>
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
                        <button class="btn btn-outline-secondary btn-sm fw-bold" data-bs-dismiss="modal" type="button" id="btn-close-modal-add-ligne-caisse"><i class="fad fa-x me-2"></i><?= __('forms.labels.cancel') ?></button>
                        <button class="btn btn-primary btn-sm fw-bold" type="submit" id="btn-confirm-add-ligne-caisse"><i class="fad fa-floppy-disk me-2"></i><?= __('forms.labels.save') ?></button>
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