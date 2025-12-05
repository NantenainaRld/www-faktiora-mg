<?php

class AuthController extends Controller
{
    private $user_model;
    public function __construct() {}

    //================== PAGE ======================

    //page - index
    public function index()
    {
        header('Location: ' . SITE_URL . '/auth/page_login');
        return;
    }

    //page - login
    public function pageLogin()
    {
        try {
            //is loged in
            $is_loged_in  = Auth::isLogedIn();
            //loged
            if ($is_loged_in->getLoged()) {
                //redirect to user index
                header('Location: ' . SITE_URL . '/user');
                return;
            }
            //show login page
            else {
                //lang
                $current_lang = $_COOKIE['lang'] ?? 'fr';
                $current_lang = !in_array($current_lang, ['fr', 'mg', 'en']) ? 'fr' : $current_lang;
                $data_lang = [
                    'fr' => __('forms.lang.fr'),
                    'mg' => __('forms.lang.mg'),
                    'en' => __('forms.lang.en')
                ];

                $this->render(
                    'login',
                    [
                        'title' => __('forms.labels.connection') . ' - faktiora',
                        'current_lang' => $current_lang,
                        'data_lang' => $data_lang
                    ]
                );
                return;
            }
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            //redirect to error page
            header('Location: ' . SITE_URL . '/error?messages=' . __('errors.catch.auth_isLogedIn', ['field' => $e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile()]));

            return;
        }
    }

    //page - signup
    public function pageSignup()
    {
        try {
            //is loged in
            $is_loged_in  = Auth::isLogedIn();
            //loged
            if ($is_loged_in->getLoged()) {
                //redirect to user index
                header('Location: ' . SITE_URL . '/user');
                return;
            }
            //show signup page
            else {
                //lang
                $current_lang = $_COOKIE['lang'] ?? 'fr';
                $current_lang = !in_array($current_lang, ['fr', 'mg', 'en']) ? 'fr' : $current_lang;
                $data_lang = [
                    'fr' => __('forms.lang.fr'),
                    'mg' => __('forms.lang.mg'),
                    'en' => __('forms.lang.en')
                ];

                $this->render(
                    'signup',
                    [
                        'title' => __('forms.labels.signup') . ' - faktiora',
                        'current_lang' => $current_lang,
                        'data_lang' => $data_lang
                    ]
                );
                return;
            }
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            //redirect to error page
            header('Location: ' . SITE_URL . '/error?messages=' . __('errors.catch.auth_isLogedIn', ['field' => $e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile()]));

            return;
        }
    }

    //================= ACTION ====================

    //action - login user
    public function loginUser()
    {
        header('Content-Type: application/json');
        $response = null;

        try {
            //is loged in
            $is_loged_in  = Auth::isLogedIn();
            //loged
            if ($is_loged_in->getLoged()) {
                //redirect to user index
                header('Location: ' . SITE_URL . '/user');
                return;
            }
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            //redirect to error page
            header('Location: ' . SITE_URL . '/error?messages=' . __('errors.catch.auth_isLogedIn', ['field' => $e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile()]));

            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $json = json_decode(file_get_contents("php://input"), true);
            //json - login not found
            if (!isset($json['login'])) {
                $response = [
                    'message_type' => 'error',
                    'message' => __('errors.not_found.json', ['field' => 'login'])
                ];

                echo json_encode($response);
                return;
            }
            //json - password not found
            if (!isset($json['password'])) {
                $response = [
                    'message_type' => 'error',
                    'message' => __('errors.not_found.json', ['field' => 'password'])
                ];

                echo json_encode($response);
                return;
            }
            //trim
            $json['login'] = trim($json['login']);

            //login empty
            if ($json['login'] === '') {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.auth_login_empty')
                ];

                echo json_encode($response);
                return;
            }

            //password - empty
            if ($json['password'] === '') {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.auth_password_empty')
                ];

                echo json_encode($response);
                return;
            }
            try {

                //does user id exist ?
                $id_utilisateur = "";
                $password = "";
                $mdp_oublie  = "";
                $mdp_oublie_expire = "";
                if (ctype_digit($json['login'])) {
                    $response = User::findById($json['login']);
                    //error
                    if ($response['message_type'] === 'error') {
                        echo json_encode($response);
                        return;
                    }
                    //not found
                    if (!$response['found']) {
                        $response = [
                            'message_type' => 'invalid',
                            'message' => __('messages.not_found.user_id', ['field' => $json['login']])
                        ];

                        echo json_encode($response);
                        return;
                    }
                    //user - deleted
                    if ($response['model']->getEtatUtilisateur() === 'supprimé') {
                        $response = [
                            'message_type' => 'invalid',
                            'message' => __('messages.invalids.user_deleted', ['field' => $json['login']])
                        ];

                        echo json_encode($response);
                        return;
                    }
                    $password = $response['model']->getMdp();
                    $id_utilisateur = $response['model']->getIdUtilisateur();
                    $mdp_oublie = $response['model']->getMdpOublieExpire();
                    $mdp_oublie_expire = $response['model']->getMdpOublieExpire();
                }
                //does user email exist ?
                else {
                    //email invalid
                    $email = filter_var($json['login'], FILTER_VALIDATE_EMAIL);
                    if (!$email) {
                        $response = [
                            'message_type' => 'invalid',
                            'message' => __('messages.invalids.email', ['field' => $json['login']])
                        ];

                        echo json_encode($response);
                        return;
                    }
                    $response = User::findByEmail($json['login']);
                    //error
                    if ($response['message_type'] === 'error') {
                        echo json_encode($response);
                        return;
                    }
                    //not found
                    if (!$response['found']) {
                        $response = [
                            'message_type' => 'invalid',
                            'message' => __('messages.not_found.user_email', ['field' => $json['login']])
                        ];

                        echo json_encode($response);
                        return;
                    }
                    //user - deleted
                    if ($response['model']->getEtatUtilisateur() === 'supprimé') {
                        $response = [
                            'message_type' => 'invalid',
                            'message' => __('messages.invalids.user_deleted', ['field' => $json['login']])
                        ];

                        echo json_encode($response);
                        return;
                    }
                    $password = $response['model']->getMdp();
                    $id_utilisateur = $response['model']->getIdUtilisateur();
                    $mdp_oublie = $response['model']->getMdpOublieExpire();
                    $mdp_oublie_expire = $response['model']->getMdpOublieExpire();
                }

                //login correct
                if (password_verify($json['password'], $password)) {
                    //create session
                    session_regenerate_id(true);
                    $_SESSION['auth'] = ['id_utilisateur' => $id_utilisateur];
                    //update user status
                    $user_model = new User();
                    $user_model
                        ->setIdUtilsateur($id_utilisateur);
                    $response = $user_model->updateToConnected();
                    //error
                    if ($response['message_type'] === 'error') {
                        echo json_encode($response);
                        return;
                    }
                    //redirect to user index
                    header('Location: ' . SITE_URL . '/user');
                    return;
                }
                //login incorrect
                else {
                    //mdp_oublie and mdp_oublie_expire - not empty
                    if (!empty($mdp_oublie) && !empty($mdp_oublie_expire)) {
                        //mdp_oublie_expire - expired
                        $mdp_oublie_expire = DateTime::createFromFormat('Y-m-d H:i:s', $mdp_oublie_expire);
                        $date = new DateTime();
                        if ($mdp_oublie_expire < $date) {
                            $response = [
                                'message_type' => 'invalid',
                                'message' => __('messages.invalids.mdp_incorrect')
                            ];

                            echo json_encode($response);
                            return;
                        }
                        //mdp_oublie_expire - not expired
                        else {
                            //mdp_oublie - correct
                            if (password_verify($json['password'], $mdp_oublie)) {
                                //create session
                                session_regenerate_id(true);
                                $_SESSION['auth'] = ['id_utilisateur' => $id_utilisateur];
                                //update user status
                                $user_model = new User();
                                $user_model
                                    ->setIdUtilsateur($id_utilisateur);
                                $response = $user_model->updateToConnected();
                                //error
                                if ($response['message_type'] === 'error') {
                                    echo json_encode($response);
                                    return;
                                }
                                //redirect to user index
                                header('Location: ' . SITE_URL . '/user');
                                return;
                            }
                            //mdp_oublie - incorrect
                            else {
                                $response = [
                                    'message_type' => 'invalid',
                                    'message' => __('messages.invalids.mdp_incorrect')
                                ];

                                echo json_encode($response);
                                return;
                            }
                        }
                    }
                    //mdp_oublie or mdp_oublie_expire - empty
                    else {
                        $response = [
                            'message_type' => 'invalid',
                            'message' => __('messages.invalids.mdp_incorrect')
                        ];

                        echo json_encode($response);
                        return;
                    }
                }

                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                error_log(
                    $e->getMessage() .
                        ' - Line : ' . $e->getLine() .
                        ' - File : ' . $e->getFile()
                );

                $response = [
                    'message_type' => 'error',
                    'message' => __(
                        'errors.catch.auth_loginUser',
                        ['field' => $e->getMessage() .
                            ' - Line : ' . $e->getLine() .
                            ' - File : ' . $e->getFile()]
                    )
                ];

                echo json_encode($response);
                return;
            }
        }
        //redirect to auth index
        else {
            header('Location: ' . SITE_URL . '/auth');
            return;
        }
    }

    //action - signup
    public function signUp()
    {
        header('Content-Type: application/json');
        $response = null;

        try {
            //is loged in
            $is_loged_in  = Auth::isLogedIn();
            //loged
            if ($is_loged_in->getLoged()) {
                //redirect to user index
                header('Location: ' . SITE_URL . '/user');
                return;
            }
        } catch (Throwable $e) {
            error_log($e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile());

            //redirect to error page
            header('Location: ' . SITE_URL . '/error?messages=' . __('errors.catch.auth_isLogedIn', ['field' => $e->getMessage() .
                ' - Line : ' . $e->getLine() .
                ' - File : ' . $e->getFile()]));

            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $json = json_decode(file_get_contents('php://input'), true);
            //json - nom_utilisateur not found
            if (!isset($json['nom_utilisateur'])) {
                $response = [
                    'message_type' => 'error',
                    'message' => __('errors.not_found.json', ['field' => 'nom_utilisateur'])
                ];

                echo json_encode($response);
                return;
            }
            //json - prenoms_utilisateur not found
            if (!isset($json['prenoms_utilisateur'])) {
                $response = [
                    'message_type' => 'error',
                    'message' => __('errors.not_found.json', ['field' => 'prenoms_utilisateur'])
                ];

                echo json_encode($response);
                return;
            }
            //json - sexe_utilisateur not found
            if (!isset($json['sexe_utilisateur'])) {
                $response = [
                    'message_type' => 'error',
                    'message' => __('errors.not_found.json', ['field' => 'sexe_utilisateur'])
                ];

                echo json_encode($response);
                return;
            }
            //json - email_utilisateur not found
            if (!isset($json['email_utilisateur'])) {
                $response = [
                    'message_type' => 'error',
                    'message' => __('errors.not_found.json', ['field' => 'emai_utilisateur'])
                ];

                echo json_encode($response);
                return;
            }
            //json - mdp not found
            if (!isset($json['mdp'])) {
                $response = [
                    'message_type' => 'error',
                    'message' => __('errors.not_found.json', ['field' => 'mdp'])
                ];

                echo json_encode($response);
                return;
            }
            //json - mdp_confirm not found
            if (!isset($json['mdp_confirm'])) {
                $response = [
                    'message_type' => 'error',
                    'message' => __('errors.not_found.json', ['field' => 'mdp_confirm'])
                ];

                echo json_encode($response);
                return;
            }

            //trim
            foreach ($json as $key => &$value) {
                if ($key !== 'mdp' && $key !== 'mdp_confirm') {
                    $value = trim($value);
                }
                if ($key === 'sexe_utilisateur') {
                    $value = strtolower($value);
                }
            }

            //nom_utilisateur - empty
            if (empty($json['nom_utilisateur'])) {
                $response['message_type'] = 'invalid';
                $response['message'] = __('messages.empty.nom');

                echo json_encode($response);
                return;
            }
            //nom_utilisateur - invalid
            if (strlen($json['nom_utilisateur']) > 100) {
                $response['message_type'] = 'invalid';
                $response['message'] = __('messages.invalids.nom');

                echo json_encode($response);
                return;
            }

            //prenoms_utilisateur not empty - invalid
            if (!empty($json['prenoms_utilisateur']) && strlen($json['prenoms_utilisateur']) > 100) {
                $response['message_type'] = 'invalid';
                $response['message'] = __('messages.invalids.prenoms');

                echo json_encode($response);
                return;
            }

            //sexe - invalid
            if (!in_array($json['sexe_utilisateur'], ['masculin', 'féminin'], true)) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __(
                        'messages.invalids.sexe',
                        ['field' => $json['sexe_utilisateur']]
                    )
                ];

                echo json_encode($response);
                return;
            }

            //email_utilisateur - empty
            if (empty($json['email_utilisateur'])) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.email')
                ];

                echo json_encode($response);
                return;
            }
            //email_utilisateur - invalid
            if (!filter_var($json['email_utilisateur'], FILTER_VALIDATE_EMAIL)) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __(
                        'messages.invalids.email',
                        ['field' => $json['email_utilisateur']]
                    )
                ];

                echo json_encode($response);
                return;
            }
            //email_utilisateur - length > 150
            if (strlen($json['email_utilisateur']) > 150) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.email_length')
                ];

                echo json_encode($response);
                return;
            }

            //mdp - empty
            if (empty($json['mdp'])) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.mdp')
                ];

                echo json_encode($response);
                return;
            }
            //mdp - invalid
            if (strlen($json['mdp']) < 6) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.mdp')
                ];

                echo json_encode($response);
                return;
            }
            //mdp_confirm - empty
            if (empty($json['mdp_confirm'])) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.empty.mdp_confirm')
                ];

                echo json_encode($response);
                return;
            }
            //mdp_confirm - invalid
            if ($json['mdp_confirm'] !== $json['mdp']) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => __('messages.invalids.mdp_confirm')
                ];

                echo json_encode($response);
                return;
            }

            try {

                $user_model = new User();

                //signup
                $user_model
                    ->setNomUtilisateur($json['nom_utilisateur'])
                    ->setPrenomsUtilisateur($json['prenoms_utilisateur'])
                    ->setSexeUtilisateur($json['sexe_utilisateur'])
                    ->setEmailUtilisateur($json['email_utilisateur'])
                    ->setMdp($json['mdp']);
                $response = $user_model->signUp();

                echo json_encode($response);
                return;
            } catch (Throwable $e) {
                error_log($e->getMessage() .
                    ' - Line : ' . $e->getLine() .
                    ' - File : ' . $e->getFile());

                $response = [
                    'message_type' => 'error',
                    'message' => __(
                        'errors.catch.user_createUser',
                        ['field' => $e->getMessage() .
                            ' - Line : ' . $e->getLine() .
                            ' - File : ' . $e->getFile()]
                    )
                ];

                echo json_encode($response);
                return;
            }

            echo json_encode($response);
            return;
        }
        //redirect to signup page
        else {
            header('Location: ' . SITE_URL . '/auth/page_signup');
            return;
        }
    }
}
