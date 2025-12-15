<!-- header  -->
<?php require_once APP_PATH . '/views/layouts/header.php'; ?>
<!-- template  -->
<template id="template-home">
    <!-- main-content  -->
    <div class="col-12 col-lg-10 col-md-8 placeholder-glow h-100 d-flex flex-column justify-content-between">
        <!-- header  -->
        <header class="row justify-content-center flex-grow-0">
            <div class=" col-12">
                <!-- header  -->
                <div class="row justify-content-center g-3">
                    <!-- btn menu mobile-->
                    <div class="col-12 d-flex justify-content-start py-2 d-md-none">
                        <button class="btn btn-sm btn-second" type="button"><i class="fad fa-bars" id="btn-sidebar"></i></button>
                    </div>
                </div>
            </div>
        </header>
        <!-- main -->
        <main class="row mt-4 overflow-y-auto mb-2 flex-grow-1">
            <div class="col-12 h-100 d-flex flex-column justify-content-between ">
                <!-- welcome  -->
                <?php if ($role === 'caissier'): ?>
                    <div class="row h-50">
                        <div class="col-12 d-flex flex-column justify-content-center px-6">
                            <h1 class="text-secondary fw-bold mb-4 text-center"><i class="fas fa-handshake me-2"></i><?= __('forms.titles.welcome') ?></h1>
                            <h6 class="text-center text-secondary bg-second rounded p-4"><?= __('forms.titles.welcome_subtitle') ?></h6>
                        </div>
                    </div>
                <?php endif; ?>
                <!-- chart  -->
                <?php if ($role === 'admin'): ?>
                    <!-- chart  -->
                    <div class="row align-items-center py-2">
                        <!-- client and user -->
                        <div class="col-12 py-2 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <!-- title client and user -->
                                    <div class="card-title text-secondary" id="chart-client-title"><i class="fad fa-user me-2"></i><?= __('forms.labels.clients_users') ?></div>
                                    <!-- chart -->
                                    <div class="align-items-center d-flex justify-content-center row py-1">
                                        <!-- chart client  -->
                                        <div class="col-12 col-lg-6 d-flex flex-column align-items-center py-2 px-4" id="chart-client">
                                            <div class="row py-2 align-items-center justify-content-center">
                                                <h6 class="placeholder rounded-1 w-50 bg-second mb-3"></h6>
                                                <div class="rounded-circle bg-second placeholder mb-1 h-25" style="aspect-ratio: 1/1;"></div>
                                                <!-- legends -->
                                                <div class="d-flex justify-content-between gap-2">
                                                    <h6 class="placeholder rounded-1 w-25 bg-second"></h6>
                                                    <h6 class="placeholder rounded-1 w-25 bg-second"></h6>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- chart user  -->
                                        <div class="col-12 d-flex col-lg-6 flex-column align-items-center py-2 px-4" id="chart-user">
                                            <div class="row py-2 align-items-center justify-content-center">
                                                <h6 class="placeholder rounded-1 w-50 bg-second mb-3"></h6>
                                                <div class="rounded-circle bg-second placeholder mb-1 h-25" style="aspect-ratio: 1/1;"></div>
                                                <!-- legends -->
                                                <div class="d-flex justify-content-between gap-2">
                                                    <h6 class="placeholder rounded-1 w-25 bg-second"></h6>
                                                    <h6 class="placeholder rounded-1 w-25 bg-second"></h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- transactions -->
                        <div class="col-12 py-2 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <!-- title  transactions -->
                                    <div class="card-title text-secondary"><i class="fad fa-money-bill-transfer me-2"></i><?= __('forms.labels.transactions') ?></div>
                                    <!-- chart -->
                                    <div class="align-items-center d-flex justify-content-center row py-1" id="chart-transactions">
                                        <!-- chart number-->
                                        <div class="d-flex flex-column align-items-center py-2 col-12 col-lg-6 px-4" id="chart-transactions-number">
                                            <div class="row py-2 align-items-center justify-content-center">
                                                <h6 class="placeholder rounded-1 w-50 bg-second mb-3"></h6>
                                                <div class="rounded-circle bg-second placeholder mb-1 h-25" style=" aspect-ratio: 1/1;"></div>
                                                <!-- legends -->
                                                <div class="d-flex justify-content-between gap-2">
                                                    <h6 class="placeholder rounded-1 w-25 bg-second"></h6>
                                                    <h6 class="placeholder rounded-1 w-25 bg-second"></h6>
                                                    <h6 class="placeholder rounded-1 w-25 bg-second"></h6>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- chart amount -->
                                        <div class="d-flex flex-column align-items-center py-2 col-12 px-4  col-lg-6" id="chart-transactions-total">
                                            <div class="row py-2 align-items-center justify-content-center">
                                                <h6 class="placeholder rounded-1 w-50 bg-second mb-3"></h6>
                                                <div class="rounded-circle bg-second placeholder mb-1 h-25" style="aspect-ratio: 1/1;"></div>
                                                <!-- legends -->
                                                <div class="d-flex justify-content-between gap-2">
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
                    <!-- chart transactions curves-->
                    <div class="row py-2 h-100">
                        <div class="col-12 h-100 overflow-y-auto">
                            <div class="card h-100">
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <!-- title transactions curves  -->
                                    <div class="card-title text-secondary flex-grow-0" id="chart-transactions-curves-title">
                                        <i class="fad fa-chart-mixed-up-circle-dollar me-2"></i><?= __('forms.titles.curves_transactions') ?>
                                    </div>
                                    <!-- curves  -->
                                    <div class="row d-flex justify-content-between py-2 flex-grow-1" id="chart-transactions-curves">
                                        <!-- curve number  -->
                                        <div class="col-12 col-md-6 align-items-center justify-content-center d-flex flex-column mb-3 overflow-x-auto" id="chart-transactions-curves-nb">
                                            <h6 class="w-25 bg-second rounded-1 placeholder mb-2"></h6>
                                            <div class="placeholder rounded-1 w-75 bg-second mb-3" style="height: 20vh !important;"></div>
                                            <div class="d-flex justify-content-center w-100 align-items-center gap-4">
                                                <h6 class="w-25 bg-second placeholder rounded-1 "></h6>
                                                <h6 class="w-25 bg-second placeholder rounded-1"></h6>
                                                <h6 class="w-25 bg-second placeholder rounded-1"></h6>
                                            </div>
                                        </div>
                                        <!-- curve amount  -->
                                        <div class="col-12 col-md-6 align-items-center justify-content-center d-flex flex-column" id="chart-transactions-curves-total">
                                            <h6 class="w-25 bg-second rounded-1 placeholder mb-2"></h6>
                                            <div class="placeholder rounded-1 w-75 bg-second mb-3" style="height: 20vh !important;"></div>
                                            <div class="d-flex justify-content-center w-100 align-items-center gap-4">
                                                <h6 class="w-25 bg-second placeholder rounded-1 "></h6>
                                                <h6 class="w-25 bg-second placeholder rounded-1"></h6>
                                                <h6 class="w-25 bg-second placeholder rounded-1"></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</template>
<!-- footer  -->
<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>