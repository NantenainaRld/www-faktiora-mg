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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- bootstrap  -->
    <link rel="stylesheet" href="<?= SITE_URL ?>/bootstrap-5.3.3/css/bootstrap.min.css">
    <!-- fontawesome -->
    <link rel="stylesheet" href="<?= SITE_URL ?>/fontawesome-free-6.7.2-web/css/all.css">
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
        <div class="row h-100 align-items-center">
            <!-- welcome  -->
            <div class="d-none d-lg-flex flex-column col-md-6 col-lg-8 border h-100 align-items-center justify-content-center px-5 welcome">
                <h1 class="text-light fw-bold mb-4"><i class="fas fa-handshake me-2"></i>Bienvenue sur Faktiora</h1>
                <h6 class="text-center text-light bg-success rounded p-4">` Ici, vous pouvez voir et faire toutes les transactions de votre caisse. Ajouter, supprimer, modifier puis lister les transactions, avec une génération en PDF des factures et rapport de caisse . `</h6>
                <!-- login  -->
            </div>
            <div class="col-12 col-lg-4 col-md-6 d-flex align-items-end justify-content-center ">
                <div class="card">
                    <!-- card header  -->
                    <div class="card-header d-flex bg-success justify-content-between">
                        <!-- connection  -->
                        <h2 class="fw-bold text-white"><?= __('forms.labels.connection') ?></h2>
                        <!-- lang  -->
                        <div class="dropdown">
                            <button class="btn btn-outline-light dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-language me-2"></i>
                                <?= $data_lang[$current_lang] ?>
                            </button>
                            <ul class="dropdown-menu">
                                <?php foreach ($data_lang as $key => $lang): ?>
                                    <li>
                                        <a href="#" class="dropdown-item <?= $key === $current_lang ? ' text-success ' : '' ?>" data-lang="<?= $key ?>">
                                            <?php if ($key === $current_lang): ?>
                                                <i class="fas fa-check me-2"></i>
                                            <?php endif; ?>
                                            <?= $lang ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    <!-- card body  -->
                    <div class="card-body d-flex flex-column">
                        <!-- enter your lgin  -->
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
                                    <i class="fas me-2"></i>
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
                                <div class="input-goup d-flex">
                                    <span class="input-group-text text-secondary rounded-end-0 text-success"><i class="fas fa-at"></i></span>
                                    <input type="text" class="form-control text-secondary rounded-start-0" id="login" placeholder="<?= __('forms.placeholders.email') . ", 10008" ?>" required>
                                </div>
                            </div>
                            <!-- password input  -->
                            <div class="mb-3">
                                <label for="password" class="form-label text-secondary">
                                    <?= __('forms.labels.password') ?>
                                </label>
                                <div class="input-goup d-flex">
                                    <span class="input-group-text text-secondary rounded-end-0 text-success"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control text-secondary rounded-0" id="password" required>
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
                                <span class="text-secondary"><?= __('forms.labels.dont_have_account') ?><a href="#" class="text-primary text-decoration-none ms-2"><?= __('forms.labels.signup') ?></a></span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- bootstrap  -->
    <script src="<?= SITE_URL ?>/bootstrap-5.3.3/js/bootstrap.bundle.min.js"></script>
    <!-- fontawesome -->
    <script src=" <?= SITE_URL ?>/fontawesome-free-6.7.2-web/js/all.js"></script>
    <!-- login js  -->
    <script src="<?= SITE_URL ?>/js/users/login.js"></script>
</body>

</html>