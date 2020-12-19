<?php class_exists('Kernel\Template') or exit; ?>
<form class="form" action="/auth/login" method="POST">
    <h1>Login</h1>


    <?php if (isset($error)): ?>
        <div class="form__alert--error"><?php echo $error ?></div>
    <?php endif; ?>


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

    <button class="form__button">Login</button>
</form>
