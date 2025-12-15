<!-- header  -->
<?php require_once APP_PATH . '/views/layouts/header.php'; ?>
<!-- template  -->
<template id="template-user">
    <!-- main-content  -->
    <div class="col-12 col-lg-10 col-md-8 placeholder-glow d-flex flex-column justify-content-between h-100 overflow-auto">
        <!-- header  -->
        <header class="row justify-content-center flex-grow-0">
            <div class="col-12">
                <!-- header  -->
                <div class="row justify-content-center g-3">
                    <!-- btn menu mobile-->
                    <div class="col-2 d-flex justify-content-start py-2 d-md-none">
                        <button class="btn btn-sm btn-second" type="button"><i class="fad fa-bars" id="btn-sidebar"></i></button>
                    </div>
                    <!-- search filter  -->
                    <div class="col-8 d-flex justify-content-center py-2 col-md-11">
                        <div class="row">
                            <div class="input-group col-12 justify-content-center">
                                <input type="text" class="form-control bg-second rounded-start-4 form-control-sm text-secondary " placeholder="id, nom, prenoms, email" id="input-search">
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
                <div class="row align-items-center py-2 justify-content-between">
                    <!-- effective and transactions -->
                    <div class="col-lg-6 col-12">
                        <div class="row">
                            <!-- effective  -->
                            <div class="col-12 py-2">
                                <div class="card">
                                    <div class="card-body">
                                        <!-- title effective-->
                                        <div class="card-title text-secondary"><i class="fad fa-user me-2"></i><?= __('forms.titles.effective_user') ?><span id="chart-effective-title"></span></div>
                                        <!-- chart -->
                                        <div class="align-items-center row py-1">
                                            <!-- chart role  -->
                                            <div class="col-12 col-md-6 d-flex flex-column align-items-center py-2 px-4 justify-content-center" id="chart-role">
                                                <h6 class="placeholder rounded-1 w-25 bg-second mb-3"></h6>
                                                <div class="rounded-circle bg-second placeholder mb-2" style="aspect-ratio: 1/1;width: 35% !important;"></div>
                                                <!-- legends -->
                                                <div class="d-flex justify-content-center gap-2 w-75">
                                                    <h6 class="placeholder rounded-1 w-25 bg-second"></h6>
                                                    <h6 class="placeholder rounded-1 w-25 bg-second"></h6>
                                                </div>
                                            </div>
                                            <!-- chart status  -->
                                            <div class="col-12 d-flex flex-column align-items-center col-md-6 py-2 px-4 justify-content-center" id="chart-status">
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
                            <!-- transactions number and total-->
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
                    </div>
                    <!-- histo number and total-->
                    <div class="col-12 py-2 col-lg-6 h-100">
                        <div class="card h-100">
                            <div class="card-body">
                                <!-- title histo -->
                                <div class="card-title text-secondary" id="chart-client-title"><i class="fad fa-chart-mixed-up-circle-dollar me-2"></i><?= __('forms.titles.curves_transactions') ?></div>
                                <!-- chart -->
                                <div class="align-items-center row py-1" id="chart-histo">
                                    <!-- histo number  -->
                                    <div class="col-12 d-flex flex-column align-items-center py-2 px-4 justify-content-center" id="chart-histo-nb-transactions">
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
                                    <div class="col-12 d-flex flex-column align-items-center py-2 px-4 justify-content-center" id="chart-histo-total-transactions">
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
                <!-- table user -->
                <div class="row py-2">
                    <div class="col-12 h-100">
                        <div class="card h-100">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <!-- title table -->
                                <div class="card-title text-secondary flex-grow-0" id="chart-transactions-curves-title">
                                    <i class="fad fa-list-dropdown me-2"></i><?= __('forms.titles.user_list') ?>
                                </div>
                                <div class="row justify-content-center  overflow-auto align-items-top flex-grow-1">
                                    <div class="col-12">
                                        <!-- alert template  -->
                                        <template id='alert-template'>
                                            <div class="alert alert-dismissible fade show alert-slide" role="alert">
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
                                        <table class="w-100 table-striped">
                                            <thead class="bg-success text-light align-items-center form-text">
                                                <tr>
                                                    <th><input type="checkbox" class="form-check-input" id="check-all"></th>
                                                    <th><i class="fad fa-hashtag me-2"></i>ID</th>
                                                    <th><i class="fad fa-address-card me-2"></i><?= __('forms.labels.name_firstname') ?></th>
                                                    <th><i class="fad fa-venus me-2"></i><?= __('forms.labels.sex') ?></th>
                                                    <th><i class="fad fa-envelope me-2"></i><?= __('forms.labels.email') ?></th>
                                                    <th><i class="fad fa-circle-user-circle-user me-2"></i><?= __('forms.labels.role') ?></th>
                                                    <th><i class="fad fa-circle-dot me-2"></i><?= __('forms.labels.status') ?></th>
                                                    <th><i class="fad fa-cash-register me-2"></i><?= ucfirst(__('forms.labels.cash')) ?></th>
                                                    <th><i class="fad fa-gears me-2"></i><?= ucfirst(__('forms.labels.action')) ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
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
        </main>
        <!-- search bar  -->
        <nav class="position-fixed overflow-y-auto h-100 searchbar col-6 col-lg-2 col-md-4 top-0 end-0 bg-light d-flex flex-column align-items-center placeholder-glow py-4 px-2">
            <!-- search input  -->
            <div class="input-group justify-content-center mb-4">
                <input type="text" class="form-control bg-second rounded-start-4 form-control-sm text-secondary " placeholder="id, nom, prenoms, email" id="input-search-searchbar">
                <span class="input-group-text rounded-end-4 text-secondary"><i class="fad fa-magnifying-glass"></i></span>
            </div>
            <!-- status select  -->
            <div class="text-secondary w-100 row justify-content-between mb-2">
                <div class="flex-grow-0">
                    <label for="" class="w-auto form-label "><i class="fad fa-circle-dot me-2"></i><?= __('forms.labels.status') ?></label>
                </div>
                <div class="flex-grow-1">
                    <div class="input-group">
                        <select class="form-select form-select-sm" id="select-status">
                            <option value="all" selected><?= __('forms.labels.all') ?></option>
                            <option value="connected"><?= __('forms.labels.connected') ?></option>
                            <option value="disconnected"><?= __('forms.labels.disconnected') ?></option>
                            <option value="deleted"><?= __('forms.labels.deleted') ?></option>
                        </select>
                    </div>
                </div>
            </div>
            <!-- role select  -->
            <div class="text-secondary w-100 row justify-content-between mb-2">
                <div class="flex-grow-0">
                    <label for="" class="w-auto form-label
                    "><i class="fad fa-circle-user-circle-user me-2"></i><?= __('forms.labels.role') ?></label>
                </div>
                <div class="flex-grow-1">
                    <div class="input-group">
                        <select name="" class="form-select form-select-sm" id="select-role">
                            <option value="all" selected><?= __('forms.labels.all') ?></option>
                            <option value="admin"><?= __('forms.labels.admin') ?></option>
                            <option value="caissier"><?= __('forms.labels.cashier') ?></option>
                        </select>
                    </div>
                </div>
            </div>
            <!-- sex select  -->
            <div class="text-secondary w-100 row justify-content-between mb-2">
                <div class="flex-grow-0">
                    <label for="" class="w-auto form-label
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
                    <label for="" class="w-auto form-label
                    "><i class="fad fa-venus me-2"></i><?= __('forms.labels.arrange_by') ?></label>
                </div>
                <div class="flex-grow-1">
                    <div class="input-group">
                        <select name="" class="form-select form-select-sm" id="select-arrange-by">
                            <option value="name" selected><?= strtolower(__('forms.labels.name')) ?></option>
                            <option value="id"><?= __('forms.labels.account_number') ?></option>
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
                    <label for="" class="w-auto form-label
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
            <!-- num_caisse select  -->
            <div class="text-secondary w-100 row justify-content-between mb-2">
                <div class="flex-grow-0">
                    <label for="" class="w-auto form-label
                    "><i class="fad fa-cash-register me-2"></i><?= __('forms.labels.cash_num') ?></label>
                </div>
                <div class="flex-grow-1">
                    <div class="input-group">
                        <select name="" class="form-select form-select-sm" id="select-num-caisse">
                            <option value="all" selected><?= __('forms.labels.all') ?></option>
                            <option value="" class="placeholder w-100 bg-second rounded-1" disabled><?= __('forms.labels.loading') ?>...</option>
                        </select>
                    </div>
                </div>
            </div>
            <!-- date_by  -->
            <div class="d-flex flex-column justify-content-center border align-items-center rounded-1 py-1">
                <!-- date_by select  -->
                <div class="text-secondary w-100 row justify-content-between mb-2">
                    <div class="flex-grow-0">
                        <label for="" class="w-auto form-label
                    "><i class="fad fa-calendar me-2"></i><?= __('forms.labels.date_by') ?></label>
                    </div>
                    <div class="flex-grow-1">
                        <div class="input-group">
                            <select name="" class="form-select form-select-sm" id="date-by">
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
                        <label for="" class="w-auto form-label
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
                        <label for="" class="form-label me-2
                    "><?= __('forms.labels.on') ?></label>
                        <input type="date" name="" id="date-from" class="form-control form-control-sm" max="<?= date('Y-m-d') ?>" min="1700-01-01">
                    </div>
                    <!-- to  -->
                    <div class="input-group">
                        <label for="" class="form-label me-2
                    "><?= ucfirst(__('forms.labels.to')) ?></label>
                        <input type="date" name="" id="date-to" class="form-control form-control-sm" max="<?= date('Y-m-d') ?>">
                    </div>
                </div>
                <!-- month_year date_by  -->
                <div class="text-secondary d-none w-100 row justify-content-between mb-2 date-by" id="div-month_year">
                    <!-- month -->
                    <div class="input-group mb-1">
                        <label for="" class="form-label me-2
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
                        <label for="" class="form-label me-2
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
</template>
<!-- footer  -->
<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>