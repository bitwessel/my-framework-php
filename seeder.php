<?php

require_once 'vendor\autoload.php';

$recipes = [
[
    'id'    => 1,
    'name'  => 'Pannekoeken',
    'type'  => 'dinner',
    'level' => 'easy',
],
[
    'id'    => 24,
    'name'  => 'Tosti',
    'type'  => 'lunch',
    'level' => 'easy',
],
[
    'id'    => 36,
    'name'  => 'Boeren ommelet',
    'type'  => 'lunch',
    'level' => 'easy',
],
[
    'id'    => 47,
    'name'  => 'Broodje Pulled Pork',
    'type'  => 'lunch',
    'level' => 'hard',
],
[
    'id'    => 5,
    'name'  => 'Hutspot met draadjesvlees',
    'type'  => 'dinner',
    'level' => 'medium',
],
[
    'id'    => 6,
    'name'  => 'Nasi Goreng met Babi ketjap',
    'type'  => 'dinner',
    'level' => 'hard',
],
];

$kitchens = [
[
    'id' => 1,
    'name' => 'Franse keuken',
    'description' => 'De Franse keuken is een internationaal gewaardeerde keuken met een lange traditie. Deze 
        keuken wordt gekenmerkt door een zeer grote diversiteit, zoals dat ook wel gezien wordt in de Chinese 
        keuken en Indische keuken.',
],
[
    'id' => 2,
    'name' => 'Chineese keuken',
    'description' => 'De Chinese keuken is de culinaire traditie van China en de Chinesen die in de diaspora 
        leven, hoofdzakelijk in Zuid-Oost-Azië. Door de grootte van China en de aanwezigheid van vele volkeren met 
        eigen culturen, door klimatologische afhankelijkheden en regionale voedselbronnen zijn de variaties groot.',
],
[
    'id' => 3,
    'name' => 'Hollandse keuken',
    'description' => 'De Nederlandse keuken is met name geïnspireerd door het landbouwverleden van Nederland.
        Alhoewel de keuken per streek kan verschillen en er regionale specialiteiten bestaan, zijn er voor 
        Nederland typisch geachte gerechten. Nederlandse gerechten zijn vaak relatief eenvoudig en voedzaam, 
        zoals pap, Goudse kaas, pannenkoek, snert en stamppot.',
],
[
    'id' => 4,
    'name' => 'Mediterraans',
    'description' => 'De mediterrane keuken is de keuken van het Middellandse Zeegebied en bestaat onder 
        andere uit de tientallen verschillende keukens uit Marokko,Tunesie, Spanje, Italië, Albanië en Griekenland 
        en een deel van het zuiden van Frankrijk (zoals de Provençaalse keuken en de keuken van Roussillon).',
],
];

function dbRecipes($recipes)
{   
    $count = 0;
    R::wipe('recipe');
    foreach ($recipes as $recipe) {
        $recipeName = $recipe['name'];
        $recipeType = $recipe['type'];
        $recipeLevel = $recipe['level'];
        $recipe = R::dispense('recipe');
        $recipe->name = $recipeName;
        $recipe->type = $recipeType;
        $recipe->level = $recipeLevel;
        $id = R::store($recipe);
        $count++;
    }
    echo $count . ' recipes added to database' . PHP_EOL;
}

dbRecipes($recipes);

function dbKitchen($kitchens)
{   
    $count = 0;
    R::exec('SET FOREIGN_KEY_CHECKS = 0;');
    R::wipe('kitchen');
    foreach ($kitchens as $kitchen) {
        $kitchenName = $kitchen['name'];
        $kitchenDescription = $kitchen['description'];
        $kitchen = R::dispense('kitchen');
        $kitchen->name = $kitchenName;
        $kitchen->description = $kitchenDescription;
        $id = R::store($kitchen);
        $count++;
    }
    echo $count . ' kitchens added to database' . PHP_EOL;
}

dbKitchen($kitchens);

function recipeToKitchen()
{
    // get amount of recipes
    $amountRecipes = count(R::findAll('recipe')) + 1;
    $j = 1;

    // loop amount of recipes, recipes > kitchens so you can sort them all over the amount of kitchens
    for ($i = 1; $i < $amountRecipes; $i++) {
        if ($i === 3) {
            $j = 1;
        }
        $kitchen = R::load('kitchen', $j);
        $recipe = R::load('recipe', $i);
        $kitchen->ownRecipeList[] = $recipe;
        R::store($kitchen);
        $j++;
    }
}

recipeToKitchen();

// user table

function userTable()
{
    $password = password_hash('test', PASSWORD_DEFAULT);
    $user = R::dispense('user');
    $user->username = 'test';
    $user->password = $password;
    R::store($user);
    echo '1 user added to database' . PHP_EOL;
}

userTable();
