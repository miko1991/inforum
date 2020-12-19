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
    <title>
<?php echo $page->title ?>
</title>
    
<style>
    .block {
        display: flex;
    }
    .column {
        display: flex;
        align-items: center;
    }
    .column-left {
        justify-content: flex-start;
    }
    .column-center {
        justify-content: center;
    }
    .column-right {
        justify-content: flex-end;
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
</style>


</head>
<body>
    

<nav class="navbar">
    <h3 class="navbar__logo">Inforum</h3>
    <ul class="navbar__list" id="menuList">

    </ul>
</nav>
    
    <h1><?php echo $page->title ?></h1>

    <div>
        <?php foreach(json_decode($page->content, true) as $block): ?>
        <div class="block">
                <?php if (count($block["columns"]) == 1): ?>
                <?php $width = "100"; ?>
                <?php elseif (count($block["columns"]) == 2): ?>
                <?php $width = "50"; ?>
                <?php else: ?>
                <?php $width = "33" ?>
                <?php endif; ?>

                <?php foreach($block["columns"] as $column): ?>
                <div class="column column-<?php echo 100 / count($block['columns']) ?> column-<?php echo $column['horizontal_align'] ?>">
                    <?php if (isset($column["type"])): ?>
                    <div>
                        <?php Kernel\PageSection::parse($column["type"], isset($column["value"]) ? $column["value"] : null) ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
        </div>
        <?php endforeach; ?>
    </div>

    
    <h1><?php echo $page->title ?></h1>

    <div>
        <?php foreach(json_decode($page->content, true) as $block): ?>
        <div class="block">
                <?php if (count($block["columns"]) == 1): ?>
                <?php $width = "100"; ?>
                <?php elseif (count($block["columns"]) == 2): ?>
                <?php $width = "50"; ?>
                <?php else: ?>
                <?php $width = "33" ?>
                <?php endif; ?>

                <?php foreach($block["columns"] as $column): ?>
                <div class="column column-<?php echo 100 / count($block['columns']) ?> column-<?php echo $column['horizontal_align'] ?>">
                    <?php if (isset($column["type"])): ?>
                    <div>
                        <?php Kernel\PageSection::parse($column["type"], isset($column["value"]) ? $column["value"] : null) ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
        </div>
        <?php endforeach; ?>
    </div>

    

    <script src="/Public/js/Form.js"></script>
</body>
</html>





