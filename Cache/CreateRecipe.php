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
    <link rel="stylesheet" href="/Public/admin.css">
    <title>Recipes</title>
    
<style>
.ingredients {
    display: flex;
    flex-direction: column;
}
</style>


    <style>
        #page_builder {
            display: none;
        }
    </style>
</head>
<body>
    <main class="main">
        <aside class="sidebar" id="sidebar">
            <div class="sidebar__logo">InForum</div>
<div class="sidebar__content">
  <div class="sidebar__item">
    <a href="/admin" class="sidebar__link">Dashboard</a>
  </div>
  <div class="sidebar__item">
    <a href="#" class="sidebar__link"> Applications </a>
    <article class="sidebar__submenu">
      <ul>
        <li>
          <a href="/admin/applications">Browse Applications</a>
        </li>
      </ul>
    </article>
  </div>
  <div class="sidebar__item">
    <a href="#" class="sidebar__link"> Forums </a>
    <article class="sidebar__submenu">
      <ul>
        <li>
          <a href="/admin/forums">Browse Forums</a>
        </li>
        <li>
          <a href="/admin/forums/add">Create Forum</a>
        </li>
      </ul>
    </article>
  </div>
  <div class="sidebar__item">
    <a href="#" class="sidebar__link"> Pages </a>
    <article class="sidebar__submenu">
      <ul>
        <li>
          <a href="/admin/pages">Browse Pages</a>
        </li>
        <li>
          <a href="/admin/pages/create">Create Page</a>
        </li>
      </ul>
    </article>
  </div>
  <div class="sidebar__item">
    <a href="#" class="sidebar__link"> Groups </a>
    <article class="sidebar__submenu">
      <ul>
        <li>
          <a href="/admin/groups">Browse Groups</a>
        </li>
        <li>
          <a href="/admin/groups/add">Create Group</a>
        </li>
      </ul>
    </article>
  </div>
  <div class="sidebar__item">
    <a href="#" class="sidebar__link"> Users </a>
    <article class="sidebar__submenu">
      <ul>
        <li>
          <a href="/admin/users">Browse Users</a>
        </li>
        <li>
          <a href="/admin/users/add">Add User</a>
        </li>
      </ul>
    </article>
  </div>
  <div class="sidebar__item">
    <a href="#" class="sidebar__link"> Menus </a>
    <article class="sidebar__submenu">
      <ul>
        <li>
          <a href="/admin/menus">Browse Menus</a>
        </li>
        <li>
          <a href="/admin/menus/add">Create Menu</a>
        </li>
      </ul>
    </article>
  </div>
  <div class="sidebar__item">
    <a href="#" class="sidebar__link"> Plugins </a>
    <article class="sidebar__submenu">
      <ul>
        <li>
          <a href="/admin/plugins">Browse Plugins</a>
        </li>
        <li>
          <a href="/admin/plugins/add">Add Plugin</a>
        </li>
      </ul>
    </article>
  </div>
</div>
<a href="/auth/logout" class="sidebar__logout-btn">Logout</a>

        </aside>
        <aside class="sidebar" id="page_builder">

        </aside>
        <section class="content">
            
<h1>Create Recipe</h1>
<form id="form-add-recipe">
    <div class="form__group">
        <label class="form__label">Difficulty</label>
        <select id="difficulty" class="form__input">
            <option value="">Select One</option>
            <option value="easy">Easy</option>
            <option value="medium">Medium</option>
            <option value="hard">Hard</option>
        </select>
        <p class="form__error"></p>
    </div>


    <div class="form__group">
        <label class="form__label">Title</label>
        <input id="title" class="form__input" />
        <p class="form__error"></p>
    </div>

    <div class="form__group">
        <label class="form__label">Preparation Time</label>
        <input id="preparation_time" class="form__input" type="number" />
        <p class="form__error"></p>
    </div>

    <div class="form__group">
        <label class="form__label">Cooking Time</label>
        <input id="cooking_time" class="form__input" type="number" />
        <p class="form__error"></p>
    </div>

    <div class="form__group">
        <label class="form__label">Method</label>
        <textarea id="method" class="form__input" rows="10"></textarea>
        <p class="form__error"></p>
    </div>

    <button class="btn_add_ingredient" type="button">Add Ingredient</button>

    <div class="ingredients"></div>

    <button>Save</button>
</form>

        </section>
    </main>

    <script src="/Public/js/AdminSidebar.js"></script>
    
<script>
    let ingredients = [];

    const ingredientsList = document.querySelector(".ingredients");

    const btnAddIngredient = document.querySelector(".btn_add_ingredient");
    btnAddIngredient.addEventListener("click", () => {
        const ingredient_id = "ingredient_"+(ingredients.length - 1)
        ingredients.push({
            id: ingredient_id,
            value: ""
        });
        const input = document.createElement("input");
        input.dataset.ingredient_id = ingredient_id;
        input.addEventListener("keyup", e => {
            ingredients.forEach(ingredient => {
                if (ingredient.id === e.target.dataset.ingredient_id) {
                    ingredient.value = e.target.value;
                }
            })
        })
        ingredientsList.appendChild(input)
    })

    const formAddRecipe = document.getElementById("form-add-recipe");
    formAddRecipe.addEventListener("submit", async e => {
        e.preventDefault();

        const newResource = {
            title: document.getElementById("title"),
            difficulty: document.getElementById("difficulty"),
            preparation_time: document.getElementById("preparation_time"),
            cooking_time: document.getElementById("cooking_time"),
            method: document.getElementById("method"),
        }

        const formData = new FormData();
        for (let [key, element] of Object.entries(newResource)) {
            formData.append(key, element.value);
        }

        formData.append("ingredients", JSON.stringify(ingredients));

        const response = await fetch("/admin/recipes/add", {
            method: "post",
            body: formData
        });
        const data = await response.json();
        if (response.status === 400) {
            validateForm(newResource, data, formAddRecipe)
        } else if (data.success) {
            window.location = "/admin/users"
        }
    })
</script>


    <script src="/Public/js/Form.js"></script>
</body>
</html>







