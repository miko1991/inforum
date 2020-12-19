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
    .users .list__item {
        cursor: pointer;
    }

    .hidden {
        display: none;
    }

    .close-modal {
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 2rem;
        color: #333;
        cursor: pointer;
        border: none;
        background: none;
    }

    .modal {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 70%;
        background-color: white;
        padding: 1rem;
        border-radius: 5px;
        box-shadow: 0 3rem 5rem rgba(0, 0, 0, 0.3);
        z-index: 10;
    }

    .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(3px);
        z-index: 5;
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
            
    <h1>Admin ACP Users::Browse</h1>

    <div class="list users" id="list">

    </div>

    <div class="modal hidden">
        <button class="close-modal">&times;</button>
        <div class="content">
            <h1 class="display_name"></h1>
            <h3 class="group"></h3>
        </div>
    </div>
    <div class="overlay hidden">

    </div>

        </section>
    </main>

    <script src="/Public/js/AdminSidebar.js"></script>
    
<script>
    let users = [];
</script>
<script>
    const modal = document.querySelector(".modal");
    const overlay = document.querySelector(".overlay");
    const closeBtn = document.querySelector(".close-modal");

    const openModal = function () {
        modal.classList.remove('hidden');
        overlay.classList.remove('hidden');
    };

    const closeModal = function () {
        modal.classList.add('hidden');
        overlay.classList.add('hidden');
    };

    closeBtn.addEventListener('click', closeModal);
    overlay.addEventListener('click', closeModal);

</script>
<script>
    const getUsers = async () => {
        const response = await fetch("/admin/users/browse");
        users = await response.json();

        const list = document.getElementById("list");

        users.forEach(user => {
            const listItem = document.createElement("div");
            listItem.classList.add("list__item");
            listItem.dataset.user_id = user.id;

            const displayName = document.createElement("span");
            displayName.innerText = user.displayName;
            listItem.appendChild(displayName);

            if (user.group) {
                const groupName = document.createElement("span");
                groupName.innerText = user.group.title;
                listItem.appendChild(groupName);
            }


            list.appendChild(listItem);
        })

        const btns = document.querySelectorAll(".users .list__item");
        btns.forEach(btn => {
            btn.addEventListener("click", () => {
                openModal();

                const content = modal.querySelector(".content");
                const displayName = content.querySelector(".display_name");
                const group = content.querySelector(".group");
                users.forEach(user => {
                    if (user.id === btn.dataset.user_id) {
                        displayName.innerText = user.displayName;
                        group.innerText = user.group.title;
                    }
                })
            })
        })
    }

    getUsers();
</script>


    <script src="/Public/js/Form.js"></script>
</body>
</html>







