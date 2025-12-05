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
        <!-- template placeholder  -->
        <template id="template-placeholder">
            <div class="placeholder-glow col-12 d-flex g-0 h-100 align-items-center">
                <!-- welcome  -->
                <div class="d-none d-lg-flex flex-column col-md-6 col-lg-8 border h-100 align-items-center justify-content-center px-5 welcome">
                    <h1 class="placeholder bg-success col-7 mb-4"></h1>
                    <span class="placeholder col-8 bg-success mb-2"></span>
                    <span class="placeholder col-10 bg-success"></span>
                </div>
                <!-- signup  -->
                <div class="col-12 col-lg-4 col-md-6 d-flex align-items-end justify-content-center">
                    <div class="card" style="height: 90vh;">
                        <!-- card header  -->
                        <div class="card-header d-flex bg-success justify-content-between">
                            <!-- signup  -->
                            <h2 class="placeholder bg-light col-6"></h2>
                            <!-- lang  -->
                            <div class="dropdown placeholder col-4 rounded-1 bg-light">
                            </div>
                        </div>
                        <!-- card body  -->
                        <div class="card-body d-flex flex-column overflow-y-auto">
                            <!-- fill your information  -->
                            <div class="row justify-content-center my-2">
                                <h4 class="placeholder bg-secondary col-6"></h4>
                            </div>
                            <hr>
                            <!-- form  -->
                            <form class="p-3">
                                <!-- user name input -->
                                <div class="mb-3">
                                    <label class="placeholder col-4 bg-secondary">
                                    </label>
                                    <input type="text" class="form-control disabled placeholder bg-secondary">
                                </div>
                                <!-- user firstname input -->
                                <div class="mb-3">
                                    <label class="placeholder col-4 bg-secondary">
                                    </label>
                                    <input class="form-control bg-secondary disabled placeholder">
                                </div>
                                <!-- user sex -->
                                <div class="mb-3 py-3 d-flex gap-2 align-items-center">
                                    <label class="placeholder bg-secondary col-4 mt-2">
                                    </label>
                                    <select class="form-select-md form-select bg-secondary placeholder">
                                    </select>
                                </div>
                                <!-- user email -->
                                <div class="mb-3">
                                    <label class="bg-secondary col-4 placeholder">
                                    </label>
                                    <input class="form-control bg-secondary placeholder">
                                </div>
                                <!-- password input  -->
                                <div class="mb-3">
                                    <label class="bg-secondary placeholder col-4">
                                    </label>
                                    <input class="form-control bg-secondary placeholder">
                                </div>
                                <!-- password confirm input  -->
                                <div class="mb-3">
                                    <label class="bg-secondary col-4 placeholder">
                                    </label>
                                    <input class="form-control placeholder bg-secondary">
                                </div>
                                <!-- btn submit  -->
                                <div class="mt-4 mb-2 text-center">
                                    <button class="btn btn-success placeholder disabled col-6" type="button"></button>
                                </div>
                                <!-- signin -->
                                <div class="text-center mt-4">
                                    <span class="placeholder bg-secondary col-4"></span>
                                    <span class="placeholder bg-secondary col-3"></span>
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
            <!-- signup  -->
            <div class="col-12 col-lg-4 col-md-6 d-flex align-items-end justify-content-center">
                <div class="card " style="max-height: 90vh;">
                    <!-- card header  -->
                    <div class="card-header d-flex bg-success justify-content-between ">
                        <!-- signup  -->
                        <h2 class="fw-bold text-white"><?= __('forms.labels.signup') ?></h2>
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
                    <div class="card-body d-flex flex-column overflow-y-auto">
                        <!-- fill your information  -->
                        <div class="row text-center my-2">
                            <h4 class="form-text text-muted"><?= __('forms.labels.fill_information') ?></h4>
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
                            <!-- user name input -->
                            <div class="mb-3">
                                <label for="user-name" class="form-label text-secondary">
                                    <?= __('forms.labels.name') ?><span class="text-danger ms-2">*</span>
                                </label>
                                <div class="input-goup d-flex">
                                    <span class="input-group-text text-secondary rounded-end-0 text-success"><i class="fas fa-user-tag"></i></span>
                                    <input type="text" class="form-control text-secondary rounded-start-0 real-input" id="user-name" placeholder="RALANDISON" maxlength="100" required>
                                </div>
                            </div>
                            <!-- user firstname input -->
                            <div class="mb-3">
                                <label for="user-first-name" class="form-label text-secondary">
                                    <?= __('forms.labels.firstname') ?>
                                </label>
                                <div class="input-goup d-flex">
                                    <span class="input-group-text text-secondary rounded-end-0 text-success"><i class="fas fa-user-tag"></i></span>
                                    <input type="text" class="form-control text-secondary rounded-start-0 real-input" id="user-first-name" placeholder="Nantenaina" maxlength="100">
                                </div>
                            </div>
                            <!-- user sex -->
                            <div class="mb-3 py-3 d-flex gap-2 align-items-center">
                                <label for="user-sex" class="form-label text-secondary mt-2">
                                    <?= __('forms.labels.sex') ?>
                                </label>
                                <select id="user-sex" class="form-select-md form-select text-secondary real-select">
                                    <option value="masculin"><?= __('forms.labels.male') ?></option>
                                    <option value="féminin"><?= __('forms.labels.female') ?></option>
                                </select>
                            </div>
                            <!-- user email -->
                            <div class="mb-3">
                                <label for="user-email" class="form-label text-secondary">
                                    <?= __('forms.labels.email') ?><span class="text-danger ms-2">*</span>
                                </label>
                                <div class="input-goup d-flex">
                                    <span class="input-group-text text-secondary rounded-end-0 text-success"><i class="fas fa-at"></i></span>
                                    <input type="email" class="form-control text-secondary rounded-start-0 real-input" id="user-email" placeholder="nantenaina@faktiora.mg" required>
                                </div>
                            </div>
                            <!-- password input  -->
                            <div class="mb-3">
                                <label for="password" class="form-label text-secondary">
                                    <?= __('forms.labels.password') ?><span class="text-danger ms-2">*</span>
                                </label>
                                <div class="input-goup d-flex">
                                    <span class="input-group-text text-secondary rounded-end-0 text-success"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control text-secondary rounded-0 real-input" id="password" minlength="6" required>
                                </div>
                            </div>
                            <!-- password confirm input  -->
                            <div class="mb-3">
                                <label for="password-confirm" class="form-label text-secondary">
                                    <?= __('forms.labels.password_confirm') ?><span class="text-danger ms-2">*</span>
                                </label>
                                <div class="input-goup d-flex">
                                    <span class="input-group-text text-secondary rounded-end-0 text-success"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control text-secondary rounded-0 real-input" id="password-confirm" minlength="6" required>
                                </div>
                            </div>
                            <!-- btn submit  -->
                            <div class="mt-4 mb-2 text-center">
                                <button class="btn btn-outline-success " type="submit">
                                    <i class="fas fa-sign-in-alt me-2"></i><?= strtolower(__('forms.labels.signup_0')) ?></button>
                            </div>
                            <!-- signin -->
                            <div class="text-center mt-4">
                                <span class="text-secondary"><?= __('forms.labels.have_account') ?><a href="#" class="text-primary text-decoration-none ms-2"><?= ucfirst(__('forms.labels.signin')) ?></a></span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>
        <!-- container  -->
        <div class="row h-100 align-items-center" id='container'>
        </div>
    </div>
    <!-- bootstrap  -->
    <script src="<?= SITE_URL ?>/bootstrap-5.3.3/js/bootstrap.bundle.min.js"></script>
    <!-- fontawesome -->
    <script src=" <?= SITE_URL ?>/fontawesome-free-6.7.2-web/js/all.js"></script>
    <!-- login js  -->
    <script src="<?= SITE_URL ?>/js/users/signup.js"></script>
</body>

</html>