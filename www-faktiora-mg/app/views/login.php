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
    <style>
        ::placeholder {
            opacity: 0.5 !important;
            font-style: italic;
        }

        a:hover {
            text-decoration: underline !important;
        }

        .alert-slide {
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .welcome {
            background: linear-gradient(to top, #0ba360 0%, #072c16ff 100%);
        }
    </style>
</head>

<body class="bg-light d-flex flex-column min-vh-100 ">
    <div class="container-fluid vh-100">
        <!-- template placeholder  -->
        <template id="template-placeholder">
            <div class="placeholder-glow col-12 d-flex g-0 h-100 align-items-center">
                <!-- welcome  -->
                <div class="d-none d-lg-flex flex-column col-md-6 col-lg-8 border h-100 align-items-center justify-content-center px-5 welcome">
                    <h1 class="placeholder rounded-1 bg-success col-7 mb-4"></h1>
                    <span class="placeholder rounded-1 col-8 bg-success mb-2"></span>
                    <span class="placeholder rounded-1 col-10 bg-success"></span>
                </div>
                <!-- signin  -->
                <div class="col-12 col-lg-4 col-md-6 d-flex align-items-end justify-content-center">
                    <div class="card">
                        <!-- card header  -->
                        <div class="card-header d-flex bg-success justify-content-between">
                            <!-- signup  -->
                            <h2 class="placeholder rounded-1 bg-light col-6"></h2>
                            <!-- lang  -->
                            <div class="dropdown rounded-1 placeholder col-4 rounded-1 bg-light">
                            </div>
                        </div>
                        <!-- card body  -->
                        <div class="card-body d-flex flex-column overflow-y-auto">
                            <!-- enter your login  -->
                            <div class="row justify-content-center my-2">
                                <h4 class="placeholder rounded-1 bg-secondary col-6"></h4>
                            </div>
                            <hr>
                            <!-- form  -->
                            <form class="p-3">
                                <!-- login -->
                                <div class="mb-3">
                                    <label class="placeholder rounded-1 col-4 bg-secondary">
                                    </label>
                                    <input type="text" class="form-control disabled placeholder bg-secondary">
                                </div>
                                <!-- password -->
                                <div class="mb-3">
                                    <label class="placeholder rounded-1 col-4 bg-secondary">
                                    </label>
                                    <input class="form-control  bg-secondary disabled placeholder">
                                </div>
                                <!-- forgot password  -->
                                <div class="mb-3 d-flex justify-content-end">
                                    <span class="placeholder rounded-1 bg-secondary col-4"></span>
                                </div>
                                <!-- btn submit  -->
                                <div class="mt-4 mb-2 text-center">
                                    <button class="btn rounded-1 btn-success placeholder disabled col-6" type="button"></button>
                                </div>
                                <!-- signup -->
                                <div class="text-center mt-4">
                                    <span class="placeholder rounded-1 bg-secondary col-4"></span>
                                    <span class="placeholder rounded-1 bg-secondary col-3"></span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <!-- template real  -->
        <template id="template-real">
            <!-- welcome  -->
            <div class="d-none d-lg-flex flex-column col-md-6 col-lg-8 border h-100 align-items-center justify-content-center px-5 welcome">
                <h1 class="text-light fw-bold mb-4"><i class="fas fa-handshake me-2"></i><?= __('forms.titles.welcome') ?></h1>
                <h6 class="text-center text-light bg-success rounded p-4"><?= __('forms.titles.welcome_subtitle') ?></h6>
            </div>
            <!-- login  -->
            <div class="col-12 col-lg-4 col-md-6 d-flex align-items-end justify-content-center ">
                <div class="card" style="max-height: 90vh;">
                    <!-- card header  -->
                    <div class="card-header d-flex bg-success justify-content-between align-items-center">
                        <!-- connection  -->
                        <h2 class="fw-bold text-white"><?= __('forms.labels.connection') ?></h2>
                        <!-- lang  -->
                        <div class="dropdown ms-2">
                            <button class="btn btn-outline-light dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown">
                                <i class="fad fa-language me-2"></i>
                                <?= $data_lang[$current_lang] ?>
                            </button>
                            <ul class="dropdown-menu">
                                <?php foreach ($data_lang as $key => $lang): ?>
                                    <li>
                                        <a href="#" class="dropdown-item <?= $key === $current_lang ? ' text-success ' : '' ?>" data-lang="<?= $key ?>">
                                            <?php if ($key === $current_lang): ?>
                                                <i class="fad fa-check me-2"></i>
                                            <?php endif; ?>
                                            <?= $lang ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    <!-- card body  -->
                    <div class="card-body d-flex flex-column overflow-y-auto">
                        <!-- enter your login  -->
                        <div class="row text-center my-2">
                            <h4 class="form-text text-muted"><?= __('forms.labels.enter_your_login') ?></h4>
                        </div>
                        <hr>
                        <!-- form  -->
                        <form class="p-3">
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
                            <!-- alert container  -->
                            <div id="alert-container"></div>
                            <!-- login input -->
                            <div class="mb-3">
                                <label for="login" class="form-label text-secondary">
                                    <?= __('forms.labels.login') ?>
                                </label>
                                <div class="input-group d-flex">
                                    <span class="input-group-text text-secondary text-success"><i class="fad fa-at"></i></span>
                                    <input type="text" class="form-control text-secondary real-input" id="login" placeholder="<?= __('forms.placeholders.email') . ", 10008" ?>" required>
                                </div>
                            </div>
                            <!-- password input  -->
                            <div class="mb-3">
                                <label for="password" class="form-label text-secondary">
                                    <?= __('forms.labels.password') ?>
                                </label>
                                <div class="input-group d-flex">
                                    <span class="input-group-text text-secondary rounded-end-0 text-success"><i class="fad fa-lock"></i></span>
                                    <input type="password" class="form-control text-secondary real-input" id="password" required>
                                    <button class="input-group-text" type="button"><i class="fad fa-eye-slash"></i></button>
                                </div>
                            </div>
                            <!-- forgot password  -->
                            <div class="d-flex justify-content-end mb-4">
                                <a href="#" class="text-decoration-none text-primary a-style"><?= __('forms.labels.forgot_password') ?></a>
                            </div>
                            <!-- btn submit  -->
                            <div class="mt-4 mb-2 text-center">
                                <button class="btn btn-outline-success " type="submit">
                                    <i class="fas fa-sign-in-alt me-2"></i><?= __('forms.labels.signin') ?></button>
                            </div>
                            <!-- signup -->
                            <div class="text-center mt-4">
                                <span class="text-secondary"><?= __('forms.labels.dont_have_account') ?><a href="<?= SITE_URL ?>/auth/page_signup" class="text-primary text-decoration-none ms-2"><?= __('forms.labels.signup') ?></a></span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>
        <!-- container  -->
        <div class="row h-100 align-items-center" id="container">
        </div>
    </div>
    <!-- bootstrap  -->
    <script src="<?= SITE_URL ?>/bootstrap-5.3.3/js/bootstrap.bundle.min.js"></script>
    <!-- script fontawesome  -->
    <script src="<?= SITE_URL ?>/fontawesome-pro-7.1.0-web/js/fontawesome.js"></script>
    <script src="<?= SITE_URL ?>/fontawesome-pro-7.1.0-web/js/duotone.js"></script>
    <!-- login js  -->
    <script src="<?= SITE_URL ?>/js/users/login.js"></script>
</body>

</html>