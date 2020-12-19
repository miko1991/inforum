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
Admin ACP
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
            
    <h1>Admin ACP Groups::Add</h1>

    <form id="form">
        <div class="form__group">
            <label class="form__label">Title</label>
            <input id="title" class="form__input" />
            <p class='form__error'></p>
        </div>

        <h3>Permissions</h3>

        <div class="form__group">
            <label>
                Can Access Acp
                <input type="checkbox" id="can_access_acp" />
            </label>

            <p class='form__error'></p>
        </div>

        <button class="form__button">Add</button>
    </form>

<br>


        </section>
    </main>

    <script src="/Public/js/AdminSidebar.js"></script>
    
<script src="/Public/js/Form.js"></script>
<script>
    const form = document.getElementById("form");
    form.addEventListener("submit", async e => {
        e.preventDefault();

        const newResource = {
            title: document.getElementById("title"),
        }

        const formData = new FormData();
        for (let [key, element] of Object.entries(newResource)) {
            formData.append(key, element.value);
        }

        formData.append("can_access_acp", document.getElementById("can_access_acp").checked ? "1" : "0");

        const response = await fetch("/admin/groups/add", {
            method: "post",
            body: formData
        });
        const data = await response.json();
        if (response.status === 400) {
            validateForm(newResource, data)
        } else if (data.success) {
            window.location = "/admin/groups"
        }
    })
</script>


    <script src="/Public/js/Form.js"></script>
</body>
</html>





