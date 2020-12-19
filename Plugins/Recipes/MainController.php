<?php

namespace Plugins\Recipes;

use Plugins\Recipes\Recipe;
use Kernel\Controller;
use Kernel\Kernel;
use Kernel\Response;
use Kernel\Template;

class MainController extends Controller
{
    public function index() {
        Template::view("Recipes.html", "./Plugins/Recipes/");
    }

    public function create() {
        Template::view("CreateRecipe.html", "./Plugins/Recipes/");

    }

    public function getAll() {
        $recipes = Recipe::all();
        Response::json($recipes);
    }

    public function createProcess() {
        $errors = $this->validate([
            ["title" => "required"],
            ["preparation_time" => "required"],
            ["cooking_time" => "required"],
            ["difficulty" => "required"],
            ["method" => "required"],
            ["ingredients" => "required"]
        ]);
        if (count($errors)) {
            http_response_code(400);
            Response::json($errors);
            exit();
        }

        $recipe = new Recipe();
        $recipe->title = $_POST["title"];
        $recipe->preparation_time = $_POST["preparation_time"];
        $recipe->cooking_time = $_POST["cooking_time"];
        $recipe->difficulty = $_POST["difficulty"];
        $recipe->method = $_POST["method"];
        $recipe->ingredients = $_POST["ingredients"];
        $recipe->save();

        Response::json(["success" => true]);
    }

    public function getRecipe() {
        $stmt = Kernel::$db->prepare("SELECT * FROM recipes WHERE id = ?");
        $stmt->execute([$_GET["recipe_id"]]);
        $resource = $stmt->fetch(\PDO::FETCH_OBJ);
        Response::json($resource);
    }
}