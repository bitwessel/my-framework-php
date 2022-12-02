<?php

require_once 'BaseController.php';

class RecipeController extends BaseController
{
    public function index($twigTemplate)
    {
        $recipes = R::findAll('recipe');
        echo $twigTemplate->render('index.twig', [
            'recipes' => $recipes,
        ]);
    }
    public function validateBean($twigTemplate, $id)
    {
        if (is_numeric($id)) {
            $bean = $id;
        } else {
            echo $twigTemplate->render('error.twig', [
                'id' => "No recipe ID specified",
            ]);
            http_response_code(404);
            return 0;
        }
        $beanItem = $this->getBeanById('recipe', $bean);
        if ($beanItem['id'] === 0) {
            echo $twigTemplate->render('error.twig', [
                'id' => "No recipe with " . $bean . " specified",
            ]);
            http_response_code(404);
            return 0;
        }
    }
    public function show($twigTemplate, $id)
    {
        $this->validateBean($twigTemplate, $id);
        $beanItem = $this->getBeanById('recipe', $id);
        if ($beanItem['id'] !== 0) {
            echo $twigTemplate->render('show.twig', [
                'recipe' => $beanItem,
            ]);
            return 0;
        }
    }
    public function create($twigTemplate)
    {
        $this->checkLogin();
        $kitchens = R::findAll('kitchen');
        if (!isset($_POST['createRecipe'])) {
            define("DIFFICULTIES", [
                "easy",
                "medium",
                "hard",
            ]);
            echo $twigTemplate->render('create.twig', [
                'difficulties' => DIFFICULTIES,
                'kitchens' => $kitchens,
            ]);
        } else {
            $this->createPost();
        }
    }
    public function createPost()
    {
        $recipeName = $_POST["recipeName"];
        $recipeKitchen = $_POST["kitchen"];
        $recipeType = $_POST["typeOfMeal"];
        $recipeDifficulty = $_POST["difficulty"];
        $recipe = R::dispense('recipe');
        $recipe->name = $recipeName;
        $recipe->kitchen_id = $recipeKitchen;
        $recipe->type = $recipeType;
        $recipe->level = $recipeDifficulty;
        $id = R::store($recipe);
        header("Location: /kitchen/show?id=$recipeKitchen");
    }
    public function edit($twigTemplate, $id)
    {
        $this->checkLogin();
        if (!isset($_POST['createRecipe'])) {
            $this->validateBean($twigTemplate, $id);
            $beanItem = $this->getBeanById('recipe', $id);
            define("DIFFICULTIES", [
                "easy",
                "medium",
                "hard",
            ]);
            $kitchens = R::findAll('kitchen');
            echo $twigTemplate->render('edit.twig', [
                'recipe' => $beanItem,
                'kitchens' => $kitchens,
                'difficulties' => DIFFICULTIES,
            ]);
        } else {
            $this->editPost($id);
        }
    }
    public function editPost($id)
    {
        $recipeName = $_POST["recipeName"];
        $recipeType = $_POST["typeOfMeal"];
        $difficulty = $_POST["difficulty"];
        $kitchen = $_POST["kitchen"];
        $recipe = R::load('recipe', $id);
        $recipe->name = $recipeName;
        $recipe->type = $recipeType;
        $recipe->level = $difficulty;
        $recipe->kitchen_id = $kitchen;
        $id = R::store($recipe);
        header("Location: /recipe/show?id=$id");
    }
}