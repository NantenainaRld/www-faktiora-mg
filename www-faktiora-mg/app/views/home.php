<!-- header  -->
<?php require_once APP_PATH . '/views/layouts/header.php'; ?>
<!-- template  -->
<template id="template-home">
    <!-- main-content  -->
    <div class="col-12 col-lg-10 col-md-8 placeholder-glow h-100 ">
        <!-- header  -->
        <header class="row justify-content-center">
            <div class="col-12">
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
        <main class="row mt-4 overflow-y-auto h-100 overflow-y-auto">
            <div class="col-12">
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
                    <div class="row align-items-center">
                        <!-- total  -->
                        <div class="col-12 col-md-4 mb-4">
                            <div class="card">
                                <div class="card-title">
                                    <div class="card-body">
                                        <!-- title total  -->
                                        <h5 class="placeholder bg-second rounded-1 w-25"></h5>
                                        <!-- chart total  -->
                                        <div class="align-items-center justify-content-center align-items-center mt-4 d-flex">
                                            <div class="rounded-circle bg-second placeholder" style="height: 12vh !important; aspect-ratio: 1/1;"></div>
                                        </div>
                                        <!-- legend  -->
                                        <div class="row mt-4">
                                            <div class="col d-flex justify-content-center">
                                                <h6 class="placeholder w-50 bg-second rounded-1"></h6>
                                            </div>
                                            <div class="col d-flex justify-content-center">
                                                <h6 class="placeholder w-50 bg-second rounded-1"></h6>
                                            </div>
                                            <div class="col d-flex justify-content-center">
                                                <h6 class="placeholder w-50 bg-second rounded-1"></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- details  -->
                        <div class="col-12 col-md-8 mb-4">
                            <div class="card">
                                <div class="card-title">
                                    <div class="card-body">
                                        <!-- title total  -->
                                        <h5 class="placeholder bg-second rounded-1 w-25"></h5>
                                        <!-- chart details  -->
                                        <div class="mt-4 row">
                                            <div class="col-12 col-lg-4 d-flex flex-column justify-content-center align-items-center gap-2">
                                                <div class="rounded-circle bg-second placeholder" style="height: 12vh;
                                                 aspect-ratio: 1/1;"></div>
                                                <h6 class="placeholder w-50 bg-second rounded-1"></h6>
                                            </div>
                                            <div class="col-12 col-lg-4  d-flex flex-column justify-content-center align-items-center gap-2">
                                                <div class="rounded-circle bg-second placeholder" style="height: 12vh;
                                                 aspect-ratio: 1/1;"></div>
                                                <h6 class="placeholder w-50 bg-second rounded-1"></h6>
                                            </div>
                                            <div class="col-12 d-flex flex-column justify-content-center align-items-center gap-2 col-lg-4
                                                ">
                                                <div class="rounded-circle bg-second placeholder" style="height: 12vh;
                                                 aspect-ratio: 1/1;"></div>
                                                <h6 class="placeholder w-50 bg-second rounded-1"></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- table  -->
                    <div class="row gap-3 mb-5 overflow-y-auto">
                        <div class="col-12">
                            <div class="card">
                                <!-- title  -->
                                <div class="card-body">
                                    <h5 class="placeholder w-25 bg-second"></h5>
                                </div>
                                <!-- table  -->
                                <div style="height: 30vh;" class="placeholder bg-second mx-3 mb-4"></div>
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