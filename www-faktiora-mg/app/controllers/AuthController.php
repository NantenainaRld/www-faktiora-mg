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
        $this->render('login', array(
            'title' => "Connexion",
            'description' => "Page de connection"
        ));
        return;
    }
    //page - signup
    public function pageSignup()
    {
        $this->render('signup', array(
            'title' => __('forms.titles.signup'),
        ));
        return;
    }

    //================= ACTION ====================

    //action - login user
    public function loginUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            $json = json_decode(file_get_contents("php://input"), true);
            $response = null;
            //trim
            $json['login'] = trim($json['login']);

            //empty
            if (empty($json['login']) || empty($json['mdp'])) {
                $response = [
                    'message_type' => 'invalid',
                    'message' => "Veuiller compléter les deux champs ."
                ];
            }
            //not empty
            else {
                //login_user
                $response = $this->user_model->loginUser($json);
            }

            echo json_encode($response);
        }
    }
}
