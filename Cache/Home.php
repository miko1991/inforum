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
Home
</title>
    

</head>
<body>
    

<nav class="navbar">
    <h3 class="navbar__logo">Inforum</h3>
    <ul class="navbar__list" id="menuList">

    </ul>
</nav>
    
<?php if (!$user): ?>
    <a href="/auth/login">
        Login
    </a>
<br>
<a href="/auth/register">
        Register
</a>

<?php else: ?>

    <?php if ($user->group->permissionSet->can_access_acp): ?>
        <a href="/admin">Admin ACP</a>
    <?php endif ?>
    <a href="/profile/<?php echo $user->id ?>">View Your Profile</a>
    <a href="/auth/logout">Logout</a>
<?php endif; ?>

    

    <script src="/Public/js/Form.js"></script>
</body>
</html>



