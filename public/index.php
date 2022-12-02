<?php

session_start();

require_once '..\vendor\autoload.php';

function displayRouterPath()
{
    // get url and split it into parts

    $urlRequest = $_SERVER['REQUEST_URI'];
    $url = parse_url($urlRequest);
    $urlParts = explode('/', $url['path']);

    // if controller not given in url unset urlParts[1] and redirect to recipes

    if ($urlParts[1] === '') {
        $urlParts[1] = 'recipe';
    }

    $urlController = $urlParts[1] ?? 'Recipe';

    // if controller is given in url make it uppercase and add Controller to it

    $urlController = ucfirst($urlController) . 'Controller';

    if (class_exists($urlController)) {
        $Controller = new $urlController();
    } else {
        echo "
            <div class='flex flex-col items-center justify-center max-w-[80%] bg-red-300 p-2'>
            <p class='text-xl'> Error - 404 </p>
            <p class='font-bold'>You searched for' " .  $urlParts[1] .  "' this controller does not exist. </p> </div>";
        return 0;
    }

    // load the twig template that belongs to the controller

    $loadTemplate = new \twig\loader\FilesystemLoader('../views/' . $urlParts[1] . 's');
    $twigTemplate = new \Twig\Environment($loadTemplate);

    // check for id, otherwise return empty string, id will be validated in controller itself

    $query = $url['query'] ?? '';
    $queryParts = explode('=', $query);
    $id = $queryParts[1] ?? '';

    if (count($urlParts) > 2) {
        if (method_exists($Controller, $urlParts[2])) {
            $method = $urlParts[2];
            if (isset($id)) {
                $Controller->$method($twigTemplate, $id);
                return 0;
            }
            $Controller->$method($twigTemplate);
            return 0;
        }
    }
    $Controller->index($twigTemplate);
    return 0;
}

displayRouterPath();