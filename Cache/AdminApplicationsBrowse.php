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
  <div class="sidebar__item">
    <a href="/admin/settings" class="sidebar__link">Settings</a>
  </div>
</div>
<a href="/auth/logout" class="sidebar__logout-btn">Logout</a>

        </aside>
        <aside class="sidebar" id="page_builder">

        </aside>
        <section class="content">
            
    <h1>Admin ACP Applications::Browse</h1>

    <div class="list" id="list">

    </div>

        </section>
    </main>

    <script src="/Public/js/AdminSidebar.js"></script>
    
<script src="/Public/js/DragAndDrop.js"></script>

<script>
    let apps;

    const container = document.getElementById("list");
    container.classList.add("droppable");
    handleDragOver(container);


    const getApps = async () => {
        const response = await fetch("/admin/applications/browse");
        const data = await response.json();

        apps = data.apps;
        apps.forEach((app, index) => {
            const listItem = document.createElement("div");
            listItem.draggable = true;
            listItem.classList.add("list__item");
            listItem.classList.add("draggable")

            listItem.dataset.id = app.id;
            listItem.dataset.order = app.order;

            const span = document.createElement("span");
            span.innerText = app.title;
            listItem.appendChild(span);

            const locked = document.createElement("span");
            locked.innerText = app.locked ? "Locked" : "Not Locked";
            listItem.appendChild(locked);

            const a = document.createElement("a");
            a.href = "#";
            a.innerText = parseInt(app.enabled) === 1 ? "Disable" : "Enable";
            a.addEventListener("click", async () => {
                const response = await fetch("/admin/applications/toggle/"+app.id);
                const enabled = await response.json();
                a.innerText = enabled ? "Disable" : "Enable";
            })
            listItem.appendChild(a);

            listItem.addEventListener("dragstart", () => {
                listItem.classList.add("dragging");
            })

            listItem.addEventListener("dragend", async () => {
                listItem.classList.remove("dragging");

                const _apps = [...apps];
                const children = [...container.querySelectorAll(".list__item")];
                children.forEach((child, childIndex) => {
                    for (const _app of _apps) {
                        if (_app.id === child.dataset.id) {
                            _app.order = childIndex;
                        }
                    }
                })

                const formData = new FormData();
                formData.append("apps", JSON.stringify(_apps));

                await fetch("/admin/applications/reorder", {
                    method: "post",
                    body: formData
                })
            })

            container.appendChild(listItem);
        })
    }

    getApps();
</script>


    <script src="/Public/js/Form.js"></script>
</body>
</html>







