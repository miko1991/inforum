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
    <title>
Admin ACP::Add Forum
</title>
    

    <style>
        #page_builder {
            display: none;
        }
    </style>
</head>
<body>
    <main class="main">
        <aside class="sidebar" id="sidebar">
            <div class="sidebar__logo">Origade</div>
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
            
<h1>Create Forum</h1>
<form id="form">
    <div class="form__group">
        <label class="form__label">Title</label>
        <input class="form__input" id="title" />
        <span class="form__error"></span>
    </div>

    <div class="form__group">
        <label class="form__label">Parent</label>
        <select class="form__input" id="parent_id">
            <option value="">No Parent</option>
        </select>
    </div>

    <button class="form__button">Create</button>
</form>

        </section>
    </main>

    <script src="/Public/js/AdminSidebar.js"></script>
    
<script src="/Public/js/Form.js"></script>
<script>
    const form = document.querySelector("#form");
    form.addEventListener("submit", async e => {
        e.preventDefault();

        const newResource = {
            title: document.getElementById("title"),
            parent_id: document.getElementById("parent_id")
        }

        const formData = new FormData();
        for (let [key, element] of Object.entries(newResource)) {
            formData.append(key, element.value);
        }

        const response = await fetch("/admin/forums/add", {
            method: "post",
            body: formData
        });
        const data = await response.json();
        if (response.status === 400) {
            validateForm(newResource, data)
        } else if (data.success) {
            window.location = "/admin/forums"
        }
    })

    const getParents = async () => {
        const response = await fetch("/admin/forums/getAll");
        const forums = await response.json();
        const select = document.querySelector("#parent_id");

        const parsedForums = parseForumsLinear(forums, 1);
        parsedForums.forEach(forum => {
            const option = document.createElement("option");
            option.value = forum.id;
            option.innerText = forum.selectTitle;
            select.appendChild(option)
        })
    }

    getParents()

    const parseForumsLinear = (forums, level) => {
        let parsedLevel = "";
        for (let i = 0; i < level; i++) {
            parsedLevel += "-";
        }

        const parsed = [];
        for (let forum of forums) {
            if (!forum.parent_id) {
                parsed.push({ ...forum, selectTitle: parsedLevel + " " + forum.title });
                parseChildrenLinear(forum, forums, parsed, level + 1);
            }
        }

        return parsed;
    };

    const parseChildrenLinear = (forum, forums, parsed, level) => {
        let parsedLevel = "";
        for (let i = 0; i < level; i++) {
            parsedLevel += "-";
        }

        const children = forums.filter((f) => f.parent_id === forum.id);
        for (let child of children) {
            parsed.push({ ...child, selectTitle: parsedLevel + " " + child.title });
            parseChildrenLinear(child, forums, parsed, level + 1);
        }
    };
</script>


    <script src="/Public/js/Form.js"></script>
</body>
</html>





