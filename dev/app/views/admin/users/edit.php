<div id="content" class="content">
    <div class="col-pd">
        <div class="col-xs-12 col-pd">
            <h1>Modifier l'utilisateur</h1>
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
            <form action="/admin/users/edit?id=<?php if(isset($_GET['id'])) echo $_GET['id']; ?>" method="post">
                <input type="hidden" id="id" name="id" value="<?php if(isset($_GET['id'])) echo $_GET['id']; ?>">
                <div class="col-xs-12 col-s-6 col-m-4 col-pd font-size-default">
                    <label for="firstname">
                        Prénom *
                        <?php if(array_key_exists('firstname', $errors)) echo '<br><span class="error">' . $errors['firstname'] . '</span>'; ?>
                    </label>
                    <input type="text" id="firstname" name="firstname" value="<?php if(isset($_POST['firstname'])) echo $_POST['firstname']; elseif(isset($user->firstname)) echo $user->firstname; ?>">
                    <label for="lastname">
                        Nom *
                        <?php if(array_key_exists('lastname', $errors)) echo '<br><span class="error">' . $errors['lastname'] . '</span>'; ?>
                    </label>
                    <input type="text" id="lastname" name="lastname" value="<?php if(isset($_POST['lastname'])) echo $_POST['lastname']; elseif(isset($user->lastname)) echo $user->lastname; ?>">
                    <label for="email">
                        E-mail *
                        <?php if(array_key_exists('email', $errors)) echo '<br><span class="error">' . $errors['email'] . '</span>'; ?>
                    </label>
                    <input type="text" id="email" name="email" value="<?php if(isset($_POST['email'])) echo $_POST['email']; elseif(isset($user->email)) echo $user->email; ?>">
                    <label for="password">
                        Nouveau mot de passe
                        <?php if(array_key_exists('password', $errors)) echo '<br><span class="error">' . $errors['password'] . '</span>'; ?>
                    </label>
                    <input type="password" id="password" name="password" value="<?php if(isset($_POST['password'])) echo $_POST['password']; ?>">
                    <label for="password-confirmation">
                        Confirmer le nouveau mot de passe
                        <?php if(array_key_exists('password_confirmation', $errors)) echo '<br><span class="error">' . $errors['password_confirmation'] . '</span>'; ?>
                    </label>
                    <input type="password" id="password-confirmation" name="password_confirmation" value="<?php if(isset($_POST['password_confirmation'])) echo $_POST['password_confirmation']; ?>">
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