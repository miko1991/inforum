<?php class_exists('Kernel\Template') or exit; ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/Themes/<?php echo \Kernel\Template::getTheme() ?>/base.css">
    <link rel="stylesheet" href="/Public/admin.css">
    <title>Contact Forms</title>
    
<style>

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
        <section class="content">
            

<form id="form">
    <input type="text" name="title" placeholder="Contact Form Title" id="title">
    <br>

    <button onclick="addField()" type="button">Add Field</button>


    <div id="fields"></div>

    <button>Save</button>
</form>


        </section>
    </main>

    <script src="/Public/js/AdminSidebar.js"></script>
    
<script>
    const fields = [];

    const onSubmit = async event => {
        event.preventDefault();
        console.log("submitting");

        var obj = {};
        var x = document.querySelectorAll('input');
        for (var i = 0; i < x.length; i++) {
            obj[x[i].name] = x[i].value
        }

        const formData = new FormData();

        for (const [key, value] of Object.entries(obj)) {
            formData.append(key, value);
        }

        formData.append("title", document.getElementById("title").value);

        await fetch('/admin/contact-form/create', {
            method: 'post',
            body: formData
        });
        window.location = "/admin/contact-form";
    }

    const formElm = document.getElementById("form");
    formElm.addEventListener("submit", onSubmit);

    const addField = () => {
        const fieldsElm = document.getElementById("fields");

        fields.push({});
        const index = fields.length - 1;

        const div = document.createElement("div");
        const inputLabel = document.createElement("input");
        inputLabel.name = "field_"+index+"_label";
        inputLabel.placeholder = "Label";
        div.appendChild(inputLabel);

        const inputPlaceholder = document.createElement("input");
        inputPlaceholder.name = "field_"+index+"_placeholder";
        inputPlaceholder.placeholder = "Placeholder";
        div.appendChild(inputPlaceholder);

        fieldsElm.appendChild(div);
    }


</script>

</body>
</html>







