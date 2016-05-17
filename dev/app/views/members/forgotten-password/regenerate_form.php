<div id="content" class="content">
    <div class="col-pd">
        <div class="col-xs-12 col-pd">
            <?php if(array_key_exists('success', $_SESSION)) { ?>
                <p class="success">
                    <?php echo $_SESSION['success']; ?>
                </p>
            <?php } ?>
        </div>
        <div class="col-xs-12 col-m-6 col-pd">
            <h1>Mot de passe oublié</h1>
        </div>
        <br>
        <form action="/members/regeneratePassword?email=<?php echo $email; ?>&key=<?php echo $key; ?>" method="post" class="col-xs-12 col-m-4 col-pd">
            <label for="password">
                Choisissez un nouveau mot de passe :
                <?php if(array_key_exists('password', $errors)) { ?>
                    <br><span class="error"><?php echo $errors['password']; ?></span>
                <?php } ?>
            </label>
            <input type="password" id="password" name="password" value="<?php echo isset($_POST['password']) ? $_POST['password'] : ''; ?>">
            <label for="password-confirmation">
                Confirmez votre nouveau mot de passe :
                <?php if(array_key_exists('password_confirmation', $errors)) { ?>
                    <br><span class="error"><?php echo $errors['password_confirmation']; ?></span>
                <?php } ?>
            </label>
            <input type="password" id="password-confirmation" name="password_confirmation" value="<?php echo isset($_POST['password_confirmation']) ? $_POST['password_confirmation'] : ''; ?>">
            <p class="caption">Tous les champs sont obligatoires</p>
            <button type="submit" class="btn-1-m">
                Valider
            </button>
        </form>
    </div>
</div>