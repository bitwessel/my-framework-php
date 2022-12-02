<?php

class UserController
{
    public function login($twigTemplate)
    {
        if (isset($_SESSION['user'])) {
            session_destroy();
            header('Location: /user/login');
        }
        if (!isset($_POST['login'])) {
            echo $twigTemplate->render('login.twig');
        } else {
            $this->loginPost($twigTemplate);
        }
    }
    public function loginPost($twigTemplate)
    {
        $user = R::findOne('user', 'username = ?', [$_POST['username']]);
        if ($user) {
            if (password_verify($_POST['password'], $user->password)) {
                $_SESSION['user'] = $user->id;
                header('Location: /recipe');
            } else {
                echo $twigTemplate->render('login.twig', [
                    'error' => 'Incorrect username or password',
                ]);
            }
        } else {
            echo $twigTemplate->render('login.twig', [
                'error' => 'Incorrect username or password',
            ]);
        }
    }
    public function register($twigTemplate)
    {
        if (!isset($_POST['register'])) {
            echo $twigTemplate->render('register.twig');
        } else {
            $this->registerPost($twigTemplate);
        }
    }
    public function registerPost($twigTemplate)
    {
        $user = R::dispense('user');
        if (R::find('user', 'username = ?', [$_POST['username']])) {
            echo $twigTemplate->render('register.twig', [
                'error' => 'Username already exists',
            ]);
            return 0;
        }
        if ($_POST['password'] === $_POST['passwordConfirm']) {
            $user->username = $_POST['username'];
            $user->password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $id = R::store($user);
            $_SESSION['user'] = $id;
            header('Location: /recipe');
        } else {
            echo $twigTemplate->render('register.twig', [
                'error' => 'Passwords do not match',
            ]);
            return 0;
        }
    }
}