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
    <title>Register</title>
    

</head>
<body>
    

<nav class="navbar">
    <h3 class="navbar__logo">Inforum</h3>
    <ul class="navbar__list" id="menuList">

    </ul>
</nav>
    
<form class="form" action="/auth/register" method="POST">
    <h1>Register</h1>

    <?php if (isset($error)): ?>
    <div class="form__alert--error"><?php echo $error ?></div>
    <?php endif; ?>


    <div class="form__group">
        <label class="form__label">Display Name</label>
        <input name="display_name" value="<?php if (isset($with->display_name)) echo $with->display_name ?>" class="form__input" />
        <?php if (isset($errors->display_name)): ?>
        <span class='form__error'><?php echo $errors->display_name ?></span>
        <?php endif; ?>
    </div>

    <div class="form__group">
        <label class="form__label">Email</label>
        <input name="email" value="<?php if (isset($with->email)) echo $with->email ?>" class="form__input" />
        <?php if (isset($errors->email)): ?>
        <span class='form__error'><?php echo $errors->email ?></span>
        <?php endif; ?>
    </div>

    <div class="form__group">
        <label class="form__label">Password</label>
        <input name="password" class="form__input" />
        <?php if (isset($errors->password)): ?>
        <span class='form__error'><?php echo $errors->password ?></span>
        <?php endif; ?>
    </div>

    <div class="form__group">
        <label class="form__label">Password Confirmation</label>
        <input name="password_again" class="form__input" />
        <?php if (isset($errors->password_again)): ?>
        <span class='form__error'><?php echo $errors->password_again ?></span>
        <?php endif; ?>
    </div>

    <button class="form__button">Register</button>
</form>

    

    <script src="/Public/js/Form.js"></script>
</body>
</html>



