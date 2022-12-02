<?php

class BaseController
{
    public function checkLogin()
    {
        if (!isset($_SESSION['user'])) {
            $loadTemplate = new \twig\loader\FilesystemLoader('../views/users');
            $twigTemplate = new \Twig\Environment($loadTemplate);
            $userController = new UserController();
            $userController->login($twigTemplate);
            exit(0);
        }
    }
    public function getBeanById($typeOfBean, $queryStringKey)
    {
        if ($typeOfBean === 'kitchen') {
            $bean = R::load('kitchen', $queryStringKey);
        }
        if ($typeOfBean === 'recipe') {
            $bean = R::load('recipe', $queryStringKey);
        }
        return $bean;
    }
}