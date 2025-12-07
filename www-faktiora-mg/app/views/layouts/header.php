<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <!-- JS base URL -->
    <script>
        const SITE_URL = "<?= SITE_URL ?>";
    </script>
    <!-- helper js  -->
    <script src="<?= SITE_URL ?>/js/helpers/helper.js"></script>
    <!-- bootstrap  -->
    <link rel="stylesheet" href="<?= SITE_URL ?>/bootstrap-5.3.3/css/bootstrap.min.css">
    <!-- style header  -->
    <style>
        /* colors  */
        :root {
            --color-second: #E9ECEF;
            --green-first: #ECF4E8;
            --green-second: #CBF3BB;
        }

        /* vody  */

        body {
            background-color: #F8F9FA;
        }

        /* btn second  */
        .btn-second {
            background-color: var(--color-second) !important;
            border-color: var(--color-second) !important;
        }

        /* bg  second */
        .bg-second {
            background-color: var(--color-second) !important;
        }

        /* green first  */
        .green-first {
            color: var(--green-first);
        }

        /* green second  */
        .green-second {
            color: var(--green-second);
        }

        /* green third  */
        .bg-green-third {
            background-color: rgba(4, 30, 14, 0.4);
        }

        /* side bar bg  */
        .bg-sidebar {
            background: linear-gradient(to top, #0ba360 0%, #072c16ff 100%);
        }

        /* sidebar desktop  */
        .sidebar {
            transition: all 0.3s;
            margin-left: 0%;
            z-index: 1000;
        }

        /* side bar mobile  */
        @media(max-width: 767.18px) {
            .sidebar {
                margin-left: -100%;
                position: fixed;
                top: 0;
                left: 0;
            }

            .sidebar.active {
                margin-left: 0%;
                z-index: 1000;
            }

            /* overlay  */
            .overlay.active {
                display: block !important;
                z-index: 999;
            }
        }

        /* searc bar  */
        .searchbar {
            transition: all 0.3s;
            margin-right: -100%;
            z-index: 1000;
        }

        /* searc bar  */
        .searchbar.active {
            margin-right: 0%;
        }

        /* overlay  searchbar*/
        .overlay-searchbar.active {
            display: block !important;
            z-index: 999;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100 min-vw-100 overflow-hidden">
    <div class="container-fluid align-items-center">
        <div class="row vh-100" id="container">
            <!-- template placeholder -->
            <template id="template-placeholder">
                <!-- sidebar  -->
                <nav class="bg-sidebar overflow-y-auto min-vh-100 sidebar col-6 col-lg-2 col-md-4 placeholder-glow overflow-y-auto">
                    <!-- account info  -->
                    <div class="d-flex flex-column align-items-center py-4 g-0 px-2">
                        <!-- user name  -->
                        <a href="#" class="placeholder bg-success rounded-1 fs-4 col-8 mb-2"></a>
                        <!-- user email  -->
                        <span class="placeholder bg-success col-6 rounded-1 mb-4"></span>
                        <!-- id_user  -->
                        <span class="placeholder bg-success col-4 rounded-1  mb-2"></span>
                        <!-- num_caisse  -->
                        <span class="placeholder bg-success col-4 rounded-1 mb-2"></span>
                    </div>
                    <!-- menu  -->
                    <div class="d-flex flex-column align-items-start">
                        <!-- items  -->
                        <ul class="w-100 px-2 mt-4 h-80">
                            <span class="placeholder bg-success rounded-1 col-8 mb-2"></span>
                            <li class="sidebar-item placeholder rounded-1 col-12 bg-success mb-2" style="height: 3vh;"></li>
                            <li class="sidebar-item placeholder rounded-1 col-12 bg-success mb-2" style="height: 3vh;"></li>
                            <li class="sidebar-item placeholder rounded-1 col-12 bg-success mb-4" style="height: 3vh;"></li>
                            <span class="placeholder bg-success rounded-1 col-8 mb-2"></span>
                            <li class="sidebar-item placeholder rounded-1 col-12 bg-success mb-2" style="height: 3vh;"></li>
                            <li class="sidebar-item placeholder rounded-1 col-12 bg-success mb-2" style="height: 3vh;"></li>
                            <li class="sidebar-item placeholder rounded-1 col-12 bg-success mb-4" style="height: 3vh;"></li>
                            <span class="placeholder bg-success rounded-1 col-8 mb-2"></span>
                            <li class="sidebar-item placeholder rounded-1 col-12 bg-success mb-2" style="height: 3vh;"></li>
                            <li class="sidebar-item placeholder rounded-1 col-12 bg-success mb-2" style="height: 3vh;"></li>
                            <li class="sidebar-item placeholder rounded-1 col-12 bg-success mb-2" style="height: 3vh;"></li>
                            <li class="sidebar-item placeholder rounded-1 col-12 bg-success mb-2 mt-4" style="height: 3vh;"></li>
                        </ul>
                        <!-- logout  -->
                        <div class="w-100 align-tems-bottom d-flex mt-5 justify-content-center">
                            <button type="button" class="disabled placeholder btn-light btn col-6"></button>
                        </div>
                    </div>
                </nav>
                <!-- overlay sidebar  -->
                <div class="overlay min-vw-100 d-none min-vh-100 bg-dark bg-opacity-50 top-0 col-12 position-fixed "></div>
                <!-- overlay searchbar  -->
                <div class="overlay-searchbar min-vw-100 d-none min-vh-100 bg-dark bg-opacity-50 top-0 col-12 position-fixed "></div>
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
                <!-- search bar  -->
                <nav class="position-fixed overflow-y-auto min-vh-100 searchbar col-6 col-lg-2 col-md-4 placeholder-glow top-0 end-0 bg-light bg-second d-flex flex-column align-items-start placeholder-glow py-4">
                    <!-- search input  -->
                    <input type="text" class="form-control placeholder bg-secondary">
                    <ul class="d-flex flex-column mt-5 gap-4 w-100">
                        <li class="placeholder rounded-1" style="height: 3vh;width: 90%"></li>
                        <li class="placeholder rounded-1" style="height: 3vh;width: 90%"></li>
                        <li class="placeholder rounded-1" style="height: 3vh;width: 90%"></li>
                    </ul>
                </nav>
            </template>
            <!-- template real side bar  -->
            <template id="template-sidebar">
                <!-- sidebar  -->
                <nav class="bg-sidebar overflow-y-auto min-vh-100 sidebar col-6 col-lg-2 col-md-4 placeholder-glow overflow-y-auto">
                    <!-- accoun intinfo  -->
                    <div class="d-flex flex-column align-items-center py-4 g-0 px-2" id="account-info">
                        <!-- user name  -->
                        <a href="#" class="placeholder bg-success rounded-1 fs-4 col-8 mb-2" id="a-user_name"></a>
                        <!-- user email  -->
                        <span class="placeholder bg-success col-6 rounded-1 mb-4" id="a-user_email"></span>
                        <!-- role && id_user  -->
                        <span class="placeholder bg-success col-4 rounded-1  mb-2" id="a-user_role_id_user"></span>
                        <!-- num_caisse  -->
                        <span class="form-text col-4 rounded-1 green-second mb-2  <?= ($role === 'admin') ? 'd-none' : '' ?>"><?= ucfirst(__('forms.labels.cash')) ?>: <span class="placeholder col-3 bg-success rounded-1" id="a-user_num_caisse"></span></span>
                    </div>
                    <!-- menu  -->
                    <div class="d-flex flex-column align-items-start w-100">
                        <!-- items  -->
                        <ul class="w-100 px-2 mt-4 h-80">
                            <li class="d-flex"><button class="btn btn-success  btn-sm text-start w-100"><i class="fad fa-house"></i>sh</button></li>
                        </ul>
                        <!-- logout  -->
                        <div class="w-100 align-tems-bottom d-flex mt-5 justify-content-center">
                            <button type="button" class="disabled placeholder btn-light btn col-6"></button>
                        </div>
                    </div>
                </nav>
                <!-- overlay sidebar  -->
                <div class="overlay min-vw-100 d-none min-vh-100 bg-dark bg-opacity-50 top-0 col-12 position-fixed "></div>
            </template>
        </div>