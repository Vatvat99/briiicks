<div id="content" class="content">
    <div class="col-pd">
        <div class="col-xs-12 col-pd">
            <h1>Nouvel utilisateur</h1>
            <?php if(array_key_exists('success', $_SESSION)) { ?>
                <p class="success">
                    <?php echo $_SESSION['success']; ?>
                </p>
            <?php } elseif(array_key_exists('error', $_SESSION)) { ?>
                <p class="error">
                    <?php echo $_SESSION['error']; ?>
                </p>
            <?php } ?>
        </div>
        <div class="row font-size-zero">
            <form action="/admin/users/add" method="post">
                <div class="col-xs-12 col-s-6 col-m-4 col-pd font-size-default">
                    <label for="firstname">
                        Prénom *
                        <?php if(array_key_exists('firstname', $errors)) echo '<br><span class="error">' . $errors['firstname'] . '</span>'; ?>
                    </label>
                    <input type="text" id="firstname" name="firstname" value="<?php if(isset($_POST['firstname'])) echo $_POST['firstname']; ?>">
                    <label for="lastname">
                        Nom *
                        <?php if(array_key_exists('lastname', $errors)) echo '<br><span class="error">' . $errors['lastname'] . '</span>'; ?>
                    </label>
                    <input type="text" id="lastname" name="lastname" value="<?php if(isset($_POST['lastname'])) echo $_POST['lastname']; ?>">
                    <label for="email">
                        E-mail *
                        <?php if(array_key_exists('email', $errors)) echo '<br><span class="error">' . $errors['email'] . '</span>'; ?>
                    </label>
                    <input type="text" id="email" name="email" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>">
                    <label for="password">
                        Mot de passe *
                        <?php if(array_key_exists('password', $errors)) echo '<br><span class="error">' . $errors['password'] . '</span>'; ?>
                    </label>
                    <input type="password" id="password" name="password" value="<?php if(isset($_POST['password'])) echo $_POST['password']; ?>">
                    <label for="password_confirmation">
                        Confirmer le mot de passe *
                        <?php if(array_key_exists('password_confirmation', $errors)) echo '<br><span class="error">' . $errors['password_confirmation'] . '</span>'; ?>
                    </label>
                    <input type="password" id="password-confirmation" name="password_confirmation" value="<?php if(isset($_POST['password_confirmation'])) echo $_POST['password_confirmation']; ?>">
                    <p class="caption">* Champs obligatoires</p>
                    <div class="buttons">
                        <a href="/admin/users/listing"><span class="cancel"></span>Annuler</a>
                        <button type="submit" class="btn-1-m">
                            <span class="check"></span>
                            Valider
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>