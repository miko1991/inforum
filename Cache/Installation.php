<?php class_exists('Kernel\Template') or exit; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Install InForum</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/Public/base.css" />
</head>
<body>
    <form class="form" action="/admin/install/installTables" method="POST">
    <h1>Install InForum</h1>
        <?php if (isset($error)): ?>
        <div class="form__alert--error"><?php echo $error ?></div>
        <?php endif; ?>


        <div class="form__group">
            <label class="form__label">Database Host</label>
            <input name="db_host" value="<?php if (isset($with->db_host)) echo $with->db_host ?>" class="form__input" />
            <?php if (isset($errors->db_host)): ?>
            <span class='form__error'><?php echo $errors->db_host ?></span>
            <?php endif; ?>
        </div>

        <div class="form__group">
            <label class="form__label">Database Name</label>
            <input name="db_name" value="<?php if (isset($with->db_name)) echo $with->db_name ?>" class="form__input" />
            <?php if (isset($errors->db_name)): ?>
            <span class='form__error'><?php echo $errors->db_name ?></span>
            <?php endif; ?>
        </div>

        <div class="form__group">
            <label class="form__label">Database User</label>
            <input name="db_user" class="form__input" />
            <?php if (isset($errors->db_user)): ?>
            <span class='form__error'><?php echo $errors->db_user ?></span>
            <?php endif; ?>
        </div>

        <div class="form__group">
            <label class="form__label">Database Password</label>
            <input name="db_password" class="form__input" />
            <?php if (isset($errors->db_password)): ?>
            <span class='form__error'><?php echo $errors->db_password ?></span>
            <?php endif; ?>
        </div>

        <button class="form__button">Install</button>
    </form>
</body>
</html>