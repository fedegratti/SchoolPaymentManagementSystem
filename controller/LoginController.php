<?php

class LoginController
{
    public static function loginView($error = null)
    {
        $view = new LoginView();
        $view->show($error);
    }

    public static function loginAction ()
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $rolesAction = array(1 => "/ListConfigurations/", 2 => "/ListAdmittedStudents/", 3 => "/ListAdmittedStudents/");

        $loginModel=new LoginModel();
        $roleId = $loginModel->authenticate($username,$password);

        if ($roleId == "error") header('Location: /login/3');

        $userModel = new UserModel();

        if(!$userModel->isEnabled($username)) header('Location: /login/2');

        $_SESSION['role'] = $roleId;
        $_SESSION['username'] = $username;
        $_SESSION['siteTitle'] = (new ConfigurationModel())->getConfiguration("title")["value"];
        $_SESSION['siteDescription'] = (new ConfigurationModel())->getConfiguration("description")["value"];

        header('Location: '.$rolesAction[$roleId]);
    }

    public static function logoutView($error = null)
    {
        session_destroy();
        header('Location: /login/');
    }
}