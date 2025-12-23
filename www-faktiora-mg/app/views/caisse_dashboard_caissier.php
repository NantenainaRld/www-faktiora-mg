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
                <div class="row w-100 g-3">
                    <!-- btn menu mobile-->
                    <div class="col-6 d-flex justify-content-start py-2 d-md-none">
                        <button class="btn btn-sm btn-second" type="button" id="btn-sidebar"><i class="fad fa-bars"></i></button>
                    </div>
                    <!-- btn seachbar -->
                    <div class="col-6 col-md-12 d-flex justify-content-end py-2 ">
                        <button class="btn btn-sm btn-second" type="button" id="btn-searchbar"><i class="fad fa-bars-filter"></i></button>
                    </div>
                </div>
            </div>
        </header>
        <!-- main -->
        <main class="row mt-4 mb-2 flex-grow-1 overflow-y-auto">
            <div class="col-12 d-flex flex-column justify-content-between overflow-y-auto h-100">
                <!-- occupation  -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title text-secondary">
                                    <i class="fad fa-key-skeleton-left-right me-2"></i><?= __('forms.labels.occupation') ?>
                                </div>
                                <!-- form occup caisse  -->
                                <div class="d-flex justify-content-start mb-2">
                                    <form class="card w-100 p-2" id="form-occup-caisse">
                                        <div class="mb-2">
                                            <label for="select-occup-caisse" class="form-label text-secondary"><?= __('forms.labels.occup_caisse') ?></label>
                                            <select name="" id="select-occup-caisse" class="form-select form-select-sm seelct2" required>
                                                <option></option>
                                                <option value="loading" disabled><?= __('forms.labels.loading') ?></option>
                                            </select>
                                        </div>
                                        <div class="d-flex justify-content-center"><button class="btn btn-light btn-sm" type="submit"><i class="fad fa-key me-2"></i><?= __('forms.labels.occup') ?></button></div>
                                    </form>
                                </div>
                                <!-- btn quit caisse  -->
                                <div class="d-flex justify-content-center">
                                    <button type="button" class="btn btn-outline-danger btn-sm" id="btn-quit-caisse"><i class="fad fa-arrow-left-from-bracket me-2"></i><?= __('forms.labels.quit_caisse') ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- chart && occup caisse -->
                <div class="row align-items-top py-2">
                    <!-- chart and histo transactons -->
                    <div class="col-12 py-2 d-flex flex-column justify-content-between">
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
                                            <div class="col-12 col-lg-6 d-flex flex-column align-items-center py-2 justify-content-center px-4">
                                                <div class="row w-100 justify-content-center">
                                                    <div class="col-12 col-md-6" id="chart-nb-transactions">
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
                                            <!-- chart status  -->
                                            <div class="col-12 col-lg-6 d-flex flex-column align-items-center py-2 px-4 justify-content-center">
                                                <div class="row w-100 justify-content-center">
                                                    <div class="col-12 col-md-6" id="chart-total-transactions">
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
                                            <div class="col-12 col-lg-6 d-flex flex-column align-items-center py-2 px-4 justify-content-center" id="chart-histo-nb-transactions">
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
                                            <div class="col-12 col-lg-6 d-flex flex-column align-items-center py-2 px-4 justify-content-center" id="chart-histo-total-transactions">
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
                <!-- table ligne caisse -->
                <div class="row py-2 flex-nowrap">
                    <div class="col-12">
                        <div class="row overflow-x-auto w-100 flex-nowrap g-0">
                            <!-- table ligne_caisse -->
                            <div class="col-12 h-100">
                                <div class="card h-100">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <!-- title table -->
                                        <div class="card-title text-secondary flex-grow-0" id="chart-transactions-curves-title">
                                            <i class="fad fa-list-dropdown me-2"></i><?= __('forms.titles.cash_line') ?>
                                        </div>
                                        <div class="row justify-content-center  overflow-auto align-items-top flex-grow-1">
                                            <!-- search ligne_caisse  -->
                                            <dvi class="col-12 bg-second rounded-1 py-2 align-items-center d-flex flex-column mb-2">
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
                                                <table class="w-100 table-striped">
                                                    <thead class="bg-success text-light align-items-center form-text">
                                                        <tr>
                                                            <th class="text-center"><i class="fad fa-hashtag me-2"></i>ID</th>
                                                            <th class="text-center"><i class="fad fa-calendar me-2"></i><?= __('forms.labels.date_start') ?></th>
                                                            <th class="text-center"><i class="fad fa-calendar me-2"></i><?= __('forms.labels.date_end') ?></th>
                                                            <th class="text-center"><i class="fad fa-user me-2"></i><?= __('forms.labels.user') ?></th>
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
        <!-- search bar -->
        <nav class="position-fixed overflow-y-auto h-100 searchbar col-6 col-lg-2 col-md-4 top-0 end-0 bg-light d-flex flex-column align-items-center placeholder-glow py-4 px-2">
            <!-- alert -->
            <div class="alert-container"></div>
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
        </nav>
    </div>
    <!-- overlay searchbar  -->
    <div class="overlay-searchbar d-none min-vh-100 bg-dark bg-opacity-50 top-0 col-12 position-fixed "></div>
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