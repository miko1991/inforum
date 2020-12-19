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
            

    <h1>Contact Forms</h1>

    <a href="/admin/contact-form/create">Create Contact Form</a>

    <div class="list">
        <?php foreach($widgets as $widget): ?>
            <div class="list__item">
                <span>
<?php 
ob_start(); 
echo $widget->widget_title; 
$output = ob_get_contents(); 
ob_end_clean(); 
if (preg_match_all("~\[widget id=\"([0-9]+)\"\]~is", $output, $matches)) {
    array_shift($matches);
    foreach($matches[0] as $match) {
        Kernel\PluginWidget::get($match);
    }
}
$output = preg_replace("~\[widget id=\"([0-9]+)\"\]~is", "", $output);
echo $output;

?>
</span>
            </div>
        <?php endforeach; ?>
    </div>


        </section>
    </main>

    <script src="/Public/js/AdminSidebar.js"></script>
    
</body>
</html>



