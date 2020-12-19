<?php class_exists('Kernel\Template') or exit; ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <link rel="stylesheet" href="/Themes/<?php echo \Kernel\Template::getTheme() ?>/base.css">
    <title>
<?php echo $page->title ?>
</title>
    
<style>
    .block {
        display: flex;
    }
    .column {
    }
    .column-left {
        justify-content: flex-start;
    }
    .column-center {
        justify-content: center;
    }
    .column-right {
        justify-content: flex-end;
    }
    .column-100 {
        width: 100%;
    }
    .column-50 {
        width: 50%;
    }
    .column-33 {
        width: 33.3%;
    }
</style>

<style>
    .recipes {
        display: grid;
        grid-template-columns: 300px 1fr;
        grid-gap: 1rem;
    }

    .recipes__list {
        border: 1px solid black;
    }

    .recipes__recipe {
        border: 1px solid black;
    }

    .recipes__list-recipe {
        cursor: pointer;
    }
</style>


</head>
<body>
    

<nav class="navbar">
    <h3 class="navbar__logo">Inforum</h3>
    <ul class="navbar__list" id="menuList">

    </ul>
</nav>
    
    <h1><?php echo $page->title ?></h1>

    <div>
        <div class='block'><div class="column column-50"><h1>Members List</h1>

<div class="list">
    <?php foreach($members as $member): ?>
        <div class="list__item">
            <?php echo $member->displayName ?>
        </div>
    <?php endforeach; ?>
</div></div><div class="column column-50"><form class="form" action="/auth/login" method="POST">
    <h1>Login</h1>


    <?php if (isset($error)): ?>
        <div class="form__alert--error"><?php echo $error ?></div>
    <?php endif; ?>


    <div class="form__group">
        <label class="form__label">Email</label>
        <input name="email" value="<?php if (isset($with->email)) echo $with->email ?>" class="form__input" />
        <?php if (isset($errors->email)): ?>
            <span class='form__error'><?php echo $errors->email ?></span>
        <?php endif; ?>
    </div>

    <div class="form__group">
        <label class="form__label">Password</label>
        <input name="password" class="form__input" />
        <?php if (isset($errors->password)): ?>
            <span class='form__error'><?php echo $errors->password ?></span>
        <?php endif; ?>
    </div>

    <button class="form__button">Login</button>
</form>
</div></div><div class='block'><div class="column column-100">



<h1>Browse Recipes</h1>
<div class="recipes">
    <div class="recipes__list">

    </div>
    <div class="recipes__recipe">

    </div>
</div></div></div>
    </div>

    
<script>
    const recipesList = document.querySelector(".recipes__list");

    const renderCurrentRecipe = (recipe) => {
        const recipesRecipe = document.querySelector(".recipes__recipe");
        recipesRecipe.innerHTML = `
<div>
    <h3>${recipe.title}</h3>
    <div>
        <span>Preparation Time: ${recipe.preparation_time}</span>
        <span>||</span>
        <span>Cooking Time: ${recipe.cooking_time}</span>
        <span>||</span>
        <span>Difficulty: ${recipe.difficulty}</span>
    </div>
</div>
        `
    }

    const getAllRecipes = async () => {
        const response = await fetch("/admin/recipes/getAll");
        const recipes = await response.json();

        recipes.forEach(recipe => {
            recipesList.innerHTML += `
<div class="recipes__list-recipe" data-recipe_id="${recipe.id}">
<h3>${recipe.title}</h3>
</div>
`;
        })

        const recipesListRecipe = recipesList.querySelectorAll(".recipes__list-recipe");
        recipes.forEach(recipe => {
            recipesListRecipe.forEach((recipeListItem) => {
                recipeListItem.addEventListener("click", () => {


                    if (recipeListItem.dataset.recipe_id === recipe.id) {
                        renderCurrentRecipe(recipe);
                    }

                })


            })
        })

    }

    getAllRecipes()
</script>


    <script src="/Public/js/Form.js"></script>
</body>
</html>





