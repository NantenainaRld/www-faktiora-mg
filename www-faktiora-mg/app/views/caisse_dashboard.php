<!-- header  -->
<?php require_once APP_PATH . '/views/layouts/header.php'; ?>
<!-- template caisse dashboard  -->
<template id="template-caisse-dashboard">
    <!-- main-content  -->
    <div class="col-12 col-lg-10 col-md-8 placeholder-glow h-100 ">
        <!-- header  -->
        <header class="row justify-content-center">
            <div class="col-12">
                <!-- header  -->
                <div class="row justify-content-center g-3">
                    <!-- btn menu mobile-->
                    <div class="col-2 d-flex justify-content-start py-2 d-md-none">
                        <button class="btn btn-second disabled placeholder " type="button" style="width: 10vw !important;"></button>
                    </div>
                    <!-- search filter  -->
                    <div class="col-8 d-flex justify-content-center py-2 col-md-11">
                        <div class="row justify-content-center">
                            <input type="text" class="form-control disabled bg-second placeholder rounded-4 w-100">
                        </div>
                    </div>
                    <!-- btn search-->
                    <div class="col-2 col-md-1 d-flex justify-content-end py-2">
                        <button class="btn btn-second disabled placeholder w-75" type="button"></button>
                    </div>
                </div>
            </div>
        </header>
        <!-- main -->
        <main class="row mt-4 overflow-y-auto h-100 overflow-y-auto">
            <div class="col-12">
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
            </div>
        </main>
    </div>
</template>
<!-- footer  -->
<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>