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
    <form class="form" action="/admin/install/createAdminUser" method="POST">
    <h1>Install InForum - Admin Account</h1>

        <?php

        if (isset($error)) {
            echo '<div class="form__alert--error">'.$error.'</div>';
        }

        ?>

        <div class="form__group">
            <label class="form__label">Display Name</label>
            <input name="display_name" value="<?= isset($with->display_name) ? $with->display_name : "" ?>" class="form__input" />
            <?php
            if (isset($errors->display_name)) {
                echo "<span class='form__error'>{$errors->display_name}</span>";
            }
            ?>
        </div>

        <div class="form__group">
            <label class="form__label">Admin Email</label>
            <input name="email" value="<?= isset($with->email) ? $with->email : "" ?>" class="form__input" />
            <?php
            if (isset($errors->email)) {
                echo "<span class='form__error'>{$errors->email}</span>";
            }
            ?>
        </div>

        <div class="form__group">
            <label class="form__label">Admin Password</label>
            <input name="password" class="form__input" />
            <?php
            if (isset($errors->password)) {
                echo "<span class='form__error'>{$errors->password}</span>";
            }
            ?>
        </div>

        <div class="form__group">
            <label class="form__label">Admin Password Confirmation</label>
            <input name="password_again" class="form__input" />
            <?php
            if (isset($errors->password_again)) {
                echo "<span class='form__error'>{$errors->password_again}</span>";
            }
            ?>
        </div>

        <button class="form__button">Install</button>
    </form>
</body>
</html>