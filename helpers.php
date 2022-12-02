<?php

require_once 'conn.php';

    $loader = new Twig\Loader\FilesystemLoader('..\views');
    $twig = new \Twig\Environment($loader);

displayTemplate($twig);

function displayTemplate($twig)
{
    $template = $twig->load('welcome.twig');
    
    if (isset($_SESSION['user'])) {
        $user = R::findOne('user', 'id = ?', [$_SESSION['user']]);
        echo $template->render([
            'user' => $user,
        ]);
    } else {
        echo $template->render();
    }
}