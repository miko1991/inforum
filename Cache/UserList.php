<?php class_exists('Kernel\Template') or exit; ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/Public/admin.css">
    <title>PHP Info</title>
</head>
<body>
    <main class="main">
        <aside class="sidebar" id="sidebar">
            <div class="sidebar__logo">InForum</div>
<div class="sidebar__content">
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
            

    <div>
        <?php foreach($users as $user): ?>
        <div><?php echo $user->displayName ?></div>
        <?php endforeach; ?>
    </div>



        </section>
    </main>

    <script src="/Public/js/AdminSidebar.js"></script>
</body>
</html>



