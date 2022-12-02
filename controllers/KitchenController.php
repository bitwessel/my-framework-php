<?php

require_once 'BaseController.php';

class KitchenController extends BaseController
{
    public function index($twigTemplate)
    { 
        $kitchens = R::findAll('kitchen');
        echo $twigTemplate->render('index.twig', [
            'kitchens' => $kitchens,
        ]);
    }
    public function validateBean($twigTemplate, $id)
    {
        if (is_numeric($id)) {
            $bean = $id;
        } else {
            echo $twigTemplate->render('error.twig', [
                'id' => "No kitchen ID specified",
            ]);
            http_response_code(404);
            return 0;
        }
        $beanItem = $this->getBeanById('kitchen', $bean);
        if ($beanItem['id'] === 0) {
            echo $twigTemplate->render('error.twig', [
                'id' => "No kitchen with " . $bean . " specified",
            ]);
            http_response_code(404);
            return 0;
        }
    }
    public function show($twigTemplate, $id)
    {
        $recipes = R::findAll('recipe');
        $this->validateBean($twigTemplate, $id); 
        $beanItem = $this->getBeanById('kitchen', $id);
        if ($beanItem['id'] !== 0) {
            echo $twigTemplate->render('show.twig', [
                'recipes' => $recipes,
                'kitchen' => $beanItem,
            ]);
            return 0;
        }
    }
    public function create($twigTemplate)
    {
        $this->checkLogin();
        if (!isset($_POST['createKitchen'])) {
            echo $twigTemplate->render('create.twig');
        } else {
            $this->createPost($twigTemplate);
        }
    }
    public function createPost()
    {
        $kitchenName = $_POST["kitchenName"];
        $kitchenDescription = $_POST["descriptionCreateKitchen"];
        $kitchen = R::dispense('kitchen');
        $kitchen->name = $kitchenName;
        $kitchen->description = $kitchenDescription;
        $id = R::store($kitchen);
        header("Location: /kitchen/show?id=$id");
    }
    public function edit($twigTemplate, $id)
    {
        $this->checkLogin();
        if (!isset($_POST['editKitchen'])) {
            $this->validateBean($twigTemplate, $id);
            $beanItem = $this->getBeanById('kitchen', $id);
            if ($beanItem['id'] !== 0) {
                echo $twigTemplate->render('edit.twig', [
                    'kitchen' => $beanItem,
                ]);
            }
        } else {
            $this->editPost($id);
        }  
    }
    public function editPost($id)
    {
        $kitchenName = $_POST["nameEditKitchen"];
        $kitchenDescription = $_POST["descriptionEditKitchen"];
        $kitchen = R::load('kitchen', $id);
        $kitchen->name = $kitchenName;
        $kitchen->description = $kitchenDescription;
        $id = R::store($kitchen);
        header("Location: /kitchen/show?id=$id");
    }
}
