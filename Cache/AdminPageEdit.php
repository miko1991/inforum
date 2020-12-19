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
.newBlock {
    border: 1px dashed black;
    padding: 2rem;
    display: flex;
    justify-content: center;
    align-items: center;
}
.newBlock__column {
    margin-right: 1rem;
    border: 1px solid black;
    cursor: pointer;
    padding: 1rem;
}
.newBlock__column:last-child{
    margin-right: 0;
}
.block {
    display: flex;
}
.column {
    border: 1px dashed black;
    min-height: 100px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    position: relative;
}
.column__settings-btn {
    position: absolute;
    top: 5px;
    right: 5px;
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
.ql-snow {
    width: 100%;
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
            
    <h1>Admin ACP Page::Edit</h1>

    <form id="form">
        <input type="text" name="title" id="title" placeholder="Title">
        <input type="text" name="slug" id="slug" placeholder="Slug">
        <button>Save</button>


        <div id="content">

        </div>
        <div class="newBlock">
            <div class="newBlock__column" id="new_one_column">1 column</div>
            <div class="newBlock__column" id="new_two_columns">2 columns</div>
            <div class="newBlock__column" id="new_three_columns">3 columns</div>
        </div>
    </form>


        </section>
    </main>

    <script src="/Public/js/AdminSidebar.js"></script>
    
<script src="https://cdn.quilljs.com/1.3.6/quill.js" defer></script>
<script src="/Public/js/DragAndDrop.js"></script>
<script src="/Public/js/PageBuilder.js"></script>
<script>
    const id = "<?php echo $id ?>";
    let page;

    const fetchPage = async () => {
        const response = await fetch("/admin/pages/"+parseInt(id)+"/fetch");
        page = await response.json();

        const title = document.getElementById("title");
        title.value = page.title;

        const slug = document.getElementById("slug");
        slug.value = page.slug;

        editBlocks(JSON.parse(page.content));
    }

    fetchPage()

    const onSubmit = async event => {
        event.preventDefault();

        const formData = new FormData();

        formData.append("id", page.id);
        formData.append("title", document.getElementById("title").value);
        formData.append("slug", document.getElementById("slug").value);
        formData.append("blocks", JSON.stringify(blocks));

        await fetch('/admin/pages/edit', {
            method: 'post',
            body: formData
        });
        window.location = "/admin/pages";
    }

    const form = document.getElementById("form");
    form.addEventListener("submit", onSubmit);
</script>


    <script src="/Public/js/Form.js"></script>
</body>
</html>







