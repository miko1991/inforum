<?php class_exists('Kernel\Template') or exit; ?>
<style>
.ingredients {
    display: flex;
    flex-direction: column;
}
</style>



<h1><?php echo $widget->title ?></h1>
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