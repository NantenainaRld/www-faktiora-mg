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
    <!-- i18n  -->
    <?php require_once PUBLIC_PATH . '/i18n/i18n.php'; ?>
    <!-- style header  -->
    <style>
        /* colors  */
        :root {
            --color-second: #E9ECEF;
            --color-third: #b7bbc0ff;
            --green-first: #ECF4E8;
            --green-second: #CBF3BB;
            --green-third: #10150fff;
        }

        /* body  */

        body {
            background-color: #F8F9FA;
        }

        /* btn second  */
        .btn-second {
            background-color: var(--color-second) !important;
            border-color: white !important;
        }

        .btn-second:active {
            background-color: var(--color-third) !important;
        }

        .btn-second:hover {
            background-color: #a0aba1ff !important;
            color: white;
        }

        /* bg  second */
        .bg-second {
            background-color: var(--color-second) !important;
        }

        /* green first  */
        .green-first {
            color: var(--green-first) !important;
        }

        /* green second  */
        .green-second {
            color: var(--green-second) !important;
        }

        /* green third  */
        .green-third {
            color: var(--green-third) !important;
        }

        /* bg green 0  */
        .bg-green-0 {
            background-color: #18392B !important;
        }

        /* green third  */
        .bg-green-third {
            background-color: rgba(4, 30, 14, 0.4) !important;
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
            position: fixed;
            top: 0;
            right: 0;
        }

        /* searc bar  */
        .searchbar.active {
            margin-right: 0%;
            z-index: 1000;
        }

        /* overlay  searchbar*/
        .overlay-searchbar.active {
            display: block !important;
            z-index: 999;
        }

        /* menu active  */
        .nav-item.active,
        .navbar.active {
            background-color: rgba(4, 30, 14, 0.4) !important;
        }

        .nav-item.action:hover,
        .navbar.action:hover {
            background-color: rgba(4, 30, 14, 0.4) !important;
        }

        .nav-item.action:active,
        .navbar.action:active {
            background-color: rgba(3, 26, 12, 0.4) !important;
        }

        /* submenu hover */
        .navbar li:hover {
            background-color: var(--bs-success) !important;
        }

        .navbar li:active {
            background-color: rgba(3, 26, 12, 0.4) !important;
        }

        ::placeholder {
            opacity: 0.5 !important;
            font-style: italic;
        }
    </style>
    <!-- style user dashboard  -->
    <?php if ($_SESSION['menu'] === 'user'): ?>
        <link rel="stylesheet" href="<?= SITE_URL ?>/css/user-dashboard.css">
    <?php endif; ?>
    <!-- style caisse dashboard  -->
    <?php if ($_SESSION['menu'] === 'cash'): ?>
        <link rel="stylesheet" href="<?= SITE_URL ?>/css/caisse-dashboard.css">
    <?php endif; ?>
    <!-- style select2  -->
    <link rel="stylesheet" href="<?= SITE_URL ?>/select2/css/select2.min.css">
    <link rel="stylesheet" href="<?= SITE_URL ?>/select2/css/select2-bootstrap-5-theme.min.css">
</head>

<body class="d-flex flex-column vw-100 vh-100 overflow-hidden">
    <div class="container-fluid g-0 justify-content-center align-items-center d-flex h-100 w-100">
        <div class="row h-100 w-100" id="container">
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
                                            <!-- table  -->
                                            <div style="height: 30vh;" class="placeholder bg-second mx-3 mb-4"></div>
                                        </div>
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
                <nav class="bg-sidebar overflow-y-auto h-100 sidebar col-6 col-lg-2 col-md-4 placeholder-glow ">
                    <!-- account intinfo  -->
                    <div class="d-flex flex-column align-items-center pt-3 g-0 px-2 border-bottom mb-2" id="account-info">
                        <!-- user name  -->
                        <a href="#" class="placeholder bg-success rounded-1 fs-5 col-8 mb-1" id="a-user_name"></a>
                        <!-- user email  -->
                        <span class="placeholder bg-success col-6 rounded-1 mb-1" id="a-user_email"></span>
                        <!-- role && id_user  -->
                        <span class="placeholder bg-success col-4 rounded-1  mb-2" id="a-user_role_id_user"></span>
                        <!-- num_caisse  -->
                        <span class="form-text col-4 rounded-1 green-second mb-1  <?= ($role === 'admin') ? 'd-none' : '' ?>"><?= ucfirst(__('forms.labels.cash')) ?>: <span class="placeholder col-3 bg-success rounded-1" id="a-user_num_caisse"></span></span>
                    </div>
                    <!-- menu  -->
                    <div class="d-flex flex-column align-items-start w-100 h-100">
                        <!-- items  -->
                        <ul class="w-100 px-2 h-80 nav-pills nav-fill flex-column list-unstyled overflow-y-auto">
                            <!-- home  -->
                            <li class="nav-item action <?= $_SESSION['menu'] === 'home' ? 'active' : '' ?> bg-success rounded-1 mb-2 text-light">
                                <a href="<?= SITE_URL ?>/user/page_home" class="nav-link fw-bold p-2 text-start" style="font-size: 0.85rem;">
                                    <i class="fad fa-house me-2"></i><?= __('forms.labels.home') ?>
                                </a>
                            </li>
                            <!-- title dashboard  -->
                            <li class="nav-item green-second text-start rounded-1 mb-2 fw-bold" style="font-size: 0.85rem;"><i class="fad fa-table-columns me-2"></i><?= __('forms.labels.dashboard') ?></li>
                            <!-- user  -->
                            <?php if ($role === 'admin'): ?>
                                <li class="nav-item action fw-bold text-light mb-2 rounded-2">
                                    <nav class="navbar action bg-success p-0 rounded <?= $_SESSION['menu'] === 'user' ? 'active' : '' ?>">
                                        <a href="#"
                                            class="nav-link navbar-toggler text-light p-2 justify-content-between d-flex"
                                            data-bs-toggle="collapse" data-bs-target="#user-menu-navbar" style="font-size: 0.85rem;">
                                            <i class="fad fa-user me-2 text-start">
                                            </i>
                                            <span class="w-100 text-start"><?= __('forms.labels.user') ?>
                                            </span>
                                            <span class="text-end w-100">
                                                <i class="fad fa-caret-down"></i>
                                            </span>
                                        </a>
                                        <div class="collapse navbar-collapse" id="user-menu-navbar">
                                            <ul class="navbar-nav py-2 px-1" style="font-size: 0.75rem;">
                                                <!-- user dashboard  -->
                                                <li class="nav-item green-second <?= $_SESSION['menu'] === 'user' ? 'bg-success' : 'bg-green-third' ?> rounded-1 mb-1 text-start px-3">
                                                    <a href="<?= SITE_URL ?>/user/page_user" class="nav-link text-light green-second">
                                                        <i class="fad fa-chart-area me-2">
                                                        </i><?= __('forms.labels.dashboard') ?>
                                                    </a>
                                                </li>
                                                <!--print list user  -->
                                                <li class="nav-item green-second bg-green-third rounded-1 text-start px-3">
                                                    <a href="#" class="nav-link text-light green-second" id="a-print-all-user">
                                                        <i class="fad fa-file-pdf me-2">
                                                        </i><?= __('forms.labels.download_list') ?>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </nav>
                                </li>
                            <?php endif; ?>
                            <!-- caisse  -->
                            <li class="nav-item fw-bold text-light mb-2 rounded-2">
                                <nav class="navbar action bg-success p-0 rounded <?= $_SESSION['menu'] === 'cash' ? 'active' : '' ?>">
                                    <a href="#"
                                        class="nav-link navbar-toggler text-light p-2 justify-content-between d-flex"
                                        data-bs-toggle="collapse" data-bs-target="#caisse-menu-navbar" style="font-size: 0.85rem;">
                                        <i class="fad fa-cash-register me-2 text-start">
                                        </i>
                                        <span class="w-100 text-start"><?= ucfirst(__('forms.labels.cash')) ?>
                                        </span>
                                        <span class="text-end w-100">
                                            <i class="fad fa-caret-down"></i>
                                        </span>
                                    </a>
                                    <div class="collapse navbar-collapse" id="caisse-menu-navbar">
                                        <ul class="navbar-nav py-2 px-1" style="font-size: 0.75rem;">
                                            <!-- caisse dashboard  -->
                                            <li class="nav-item green-second rounded-1 mb-1 text-start px-3 <?= $_SESSION['menu'] === 'cash' ? 'bg-success' : 'bg-green-third' ?>">
                                                <a href="<?= SITE_URL ?>/caisse/page_caisse" class="nav-link text-light green-second">
                                                    <i class="fad fa-chart-area me-2">
                                                    </i><?= __('forms.labels.dashboard') ?>
                                                </a>
                                            </li>
                                            <!-- cash report  -->
                                            <li class="nav-item green-second bg-green-third rounded-1 text-start px-3">
                                                <a href="" class="nav-link text-light green-second">
                                                    <i class="fad fa-file-pdf me-2">
                                                    </i><?= ucfirst(strtolower(__('forms.titles.cash_report'))) ?>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </nav>
                            </li>
                            <!-- client -->
                            <li class="nav-item action bg-success rounded-1 mb-2 text-light">
                                <a href="#" class="nav-link fw-bold p-2 text-start" style="font-size: 0.85rem;">
                                    <i class="fad fa-user-check me-2"></i><?= __('forms.labels.client') ?>
                                </a>
                            </li>
                            <!-- entree  -->
                            <li class="nav-item fw-bold text-light mb-2 rounded-2">
                                <nav class="navbar action bg-success p-0 rounded">
                                    <a href=""
                                        class="nav-link navbar-toggler text-light p-2 justify-content-between d-flex"
                                        data-bs-toggle="collapse" data-bs-target="#entree-menu-navbar" style="font-size: 0.85rem;">
                                        <i class="fad fa-chart-line-up me-2 text-start">
                                        </i>
                                        <span class="w-100 text-start"><?= ucfirst(__('forms.labels.inflow')) ?>
                                        </span>
                                        <span class="text-end w-100">
                                            <i class="fad fa-caret-down"></i>
                                        </span>
                                    </a>
                                    <div class="collapse navbar-collapse" id="entree-menu-navbar">
                                        <ul class="navbar-nav py-2 px-1" style="font-size: 0.75rem;">
                                            <!-- facture  -->
                                            <li class="nav-item green-second bg-green-third rounded-1 mb-1 text-start px-3">
                                                <a href="" class="nav-link text-light green-second">
                                                    <i class="fad fa-file-invoice me-2">
                                                    </i><?= ucfirst(__('forms.labels.bill')) ?>
                                                </a>
                                            </li>
                                            <!-- autre entree -->
                                            <li class="nav-item green-second bg-green-third rounded-1 text-start px-3">
                                                <a href="" class="nav-link text-light green-second">
                                                    <i class="fad fa-file-invoice-dollar me-2">
                                                    </i><?= __('forms.labels.autre_entree') ?>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </nav>
                            </li>
                            <!-- sortie -->
                            <li class="nav-item action bg-success rounded-1 mb-2 text-light">
                                <a href="#" class="nav-link fw-bold p-2 text-start" style="font-size: 0.85rem;">
                                    <i class="fad fa-chart-line-down me-2"></i><?= ucfirst(__('forms.labels.outflow')) ?>
                                </a>
                            </li>
                            <!-- produit -->
                            <li class="nav-item action bg-success rounded-1 mb-2 text-light">
                                <a href="#" class="nav-link fw-bold p-2 text-start" style="font-size: 0.85rem;">
                                    <i class="fad fa-bin-bottles me-2"></i><?= __('forms.labels.product') ?>
                                </a>
                            </li>
                            <!-- article -->
                            <li class="nav-item action bg-success rounded-1 mb-2 text-light">
                                <a href="#" class="nav-link fw-bold p-2 text-start" style="font-size: 0.85rem;">
                                    <i class="fad fa-minus me-2"></i><?= __('forms.labels.article') ?>
                                </a>
                            </li>
                            <!-- title setting  -->
                            <li class="nav-item green-second text-start rounded-1 mb-2 fw-bold" style="font-size: 0.85rem;">
                                <i class="fad fa-gear me-2">
                                </i><?= __('forms.labels.setting') ?>
                            </li>
                            <!-- account setting -->
                            <li class="nav-item action bg-success rounded-1 mb-2 text-light">
                                <a href="#" class="nav-link fw-bold p-2 text-start" style="font-size: 0.85rem;">
                                    <i class="fad fa-user-gear me-2"></i><?= __('forms.labels.account') ?>
                                </a>
                            </li>
                            <!-- application setting -->
                            <li class="nav-item action bg-success rounded-1 mb-2 text-light">
                                <a href="#" class="nav-link fw-bold p-2 text-start" style="font-size: 0.85rem;">
                                    <i class="fad fa-gears me-2"></i><?= __('forms.labels.application') ?>
                                </a>
                            </li>
                            <!-- btn logout  -->
                            <li class="nav-item mt-3 mb-1">
                                <button type="button" class="btn btn-light btn-sm"><i class="fad fa-arrow-left-from-bracket me-2"></i><?= __('forms.labels.logout') ?></button>
                            </li>
                        </ul>
                    </div>
                </nav>
                <!-- overlay sidebar  -->
                <div class="overlay min-vw-100 d-none min-vh-100 bg-dark bg-opacity-50 top-0 col-12 position-fixed "></div>
            </template>
        </div>