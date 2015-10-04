<?php

class LoginController
{
    public static function LoginView($error = null)
    {
        $view = new LoginView();
        $view->show($error);
    }

    public static function LoginAction ()
    {
        $loginModel=new LoginModel();
        $regId = $loginModel->authenticate($_POST['username'],sha1($_POST['password']));

        if ($regId != "error")
        {
            session_start();

            $_SESSION['role'] = $regId;
            header('Location: /addStudent');
        }
        else
        {
            header('Location: /login/error');
        }

    }
}