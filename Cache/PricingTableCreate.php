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
    <title>Pricing Tables</title>
    
<style>
    #fields {
        display: flex;
    }

    /* Create three column of equal width */
    .columns {
        width: 200px;
    }

    /* Style the list */
    .price {
        list-style-type: none;
        border: 1px solid #eee;
        margin: 0;
        padding: 0;
        -webkit-transition: 0.3s;
        transition: 0.3s;
    }

    /* Add shadows on hover */
    .price:hover {
        box-shadow: 0 8px 12px 0 rgba(0,0,0,0.2)
    }

    /* Pricing header */
    .price .header {
        background-color: #c10303;
        color: white;
        font-size: 25px;
    }

    /* List items */
    .price li {
        border-bottom: 1px solid #fff;
        background-color: white;
        padding: 20px;
        text-align: center;
    }

    /* Grey list item */
    .price .grey {
        background-color: #eee;
        font-size: 20px;
    }

    /* The "Sign Up" button */
    .button {
        background-color: #4CAF50;
        border: none;
        color: white;
        padding: 10px 25px;
        text-align: center;
        text-decoration: none;
        font-size: 18px;
    }

    /* Change the width of the three columns to 100%
    (to stack horizontally on small screens) */
    @media only screen and (max-width: 600px) {
        .columns {
            width: 100%;
        }
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
        <a href="#" class="sidebar__link">
            Applications
        </a>
        <article class="sidebar__submenu">
            <ul>
                <li>
                    <a href="/admin/applications">Browse Applications</a>
                </li>
            </ul>
        </article>
    </div>
    <div class="sidebar__item">
        <a href="#" class="sidebar__link">
            Pages
        </a>
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
        <a href="#" class="sidebar__link">
            Groups
        </a>
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
        <a href="#" class="sidebar__link">
            Users
        </a>
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
        <a href="#" class="sidebar__link">
            Menus
        </a>
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
        <a href="#" class="sidebar__link">
            Plugins
        </a>
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
            

<form id="form">
    <input type="text" name="title" placeholder="Pricing Table Title" id="title">

    <button onclick="addField()" type="button">Add Column</button>
    <br>

    <div id="fields">


    </div>


    <button>Save</button>
</form>


        </section>
    </main>

    <script src="/Public/js/AdminSidebar.js"></script>
    
<script>
    const fields = [];

    const onSubmit = async event => {
        event.preventDefault();
        var obj = {};
        var x = document.querySelectorAll('input');
        for (var i = 0; i < x.length; i++) {
            obj[x[i].name] = x[i].value
        }



        const formData = new FormData();
        const parsedMatches = {};

        let last = 0;
        for (const [key, value] of Object.entries(obj)) {
            const regexp = /^(column)_([0-9]+)_([a-zA-Z0-9_-]+)$/;
            const matches = key.match(regexp);
            if (!matches) continue;
            if (!parsedMatches["column_"+matches[2]]) {
                parsedMatches["column_"+matches[2]] = {};
            }
            const liRegexp = /^(liInput)_([0-9]+)$/;
            const liMatches = matches[3].match(liRegexp);
            if (liMatches) {
                if (!parsedMatches["column_"+matches[2]]["li"]) {
                    parsedMatches["column_"+matches[2]]["li"] = [];
                }
                parsedMatches["column_"+matches[2]]["li"].push(value);
            } else {
                parsedMatches["column_"+matches[2]][matches[3]] = value;
            }

        }

        formData.append("title", document.getElementById("title").value);
        formData.append("columns", JSON.stringify(parsedMatches));

        await fetch('/admin/pricing-tables/create', {
            method: 'post',
            body: formData
        });
        window.location = "/admin/pricing-tables";
    }

    const formElm = document.getElementById("form");
    formElm.addEventListener("submit", onSubmit);

    const addField = () => {
        const fieldsElm = document.querySelector("#fields");

        fields.push({});
        const index = fields.length - 1;

        const columns = document.createElement("div");
        columns.classList.add("columns");
        fieldsElm.appendChild(columns);

        const price = document.createElement("div");
        price.classList.add("price");
        columns.appendChild(price);

        const header = document.createElement("li");
        header.classList.add("header");
        const headerInput = document.createElement("input");
        headerInput.name = "column_"+index+"_header";
        header.appendChild(headerInput);
        price.appendChild(header);

        const grey = document.createElement("li");
        grey.classList.add("grey");
        const greyInput = document.createElement("input");
        greyInput.name = "column_"+index+"_grey";
        grey.appendChild(greyInput);
        price.appendChild(grey);

        const newLi = document.createElement("li");
        price.appendChild(newLi);
        const button = document.createElement("button");
        button.textContent = "Add Row";
        button.type = "button";
        button.addEventListener("click", () => {
            const lis = price.querySelectorAll("li:not([class])");
            const li = document.createElement("li");
            const liInput = document.createElement("input");
            liInput.name = "column_"+index+"_liInput_"+(lis.length-1);
            li.appendChild(liInput);
            price.appendChild(li);
        })
        newLi.appendChild(button);
    }


</script>

</body>
</html>







