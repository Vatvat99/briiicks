<div id="content" class="content">
    <div class="col-pd">
        <div class="col-xs-12 col-pd">
            <h1>Nouveau membre</h1>
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
        <form action="/admin/members/add" method="post" enctype="multipart/form-data">
            <div class="row font-size-zero">
                <div class="col-xs-12 col-s-6 col-m-4 col-pd font-size-default">
                    <label for="firstname">
                        Prénom *
                        <?php if(array_key_exists('firstname', $errors)) echo '<br><span class="error">' . $errors['firstname'] . '</span>'; ?>
                    </label>
                    <input type="text" id="firstname" name="firstname" value="<?php if(isset($_POST['firstname'])) echo $_POST['firstname']; ?>" tabindex="1">
                    <label for="lastname">
                        Nom *
                        <?php if(array_key_exists('lastname', $errors)) echo '<br><span class="error">' . $errors['lastname'] . '</span>'; ?>
                    </label>
                    <input type="text" id="lastname" name="lastname" value="<?php if(isset($_POST['lastname'])) echo $_POST['lastname']; ?>" tabindex="2">
                    <label for="email">
                        E-mail *
                        <?php if(array_key_exists('email', $errors)) echo '<br><span class="error">' . $errors['email'] . '</span>'; ?>
                    </label>
                    <input type="text" id="email" name="email" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>" tabindex="3">
                    <label for="password">
                        Mot de passe *
                        <?php if(array_key_exists('password', $errors)) echo '<br><span class="error">' . $errors['password'] . '</span>'; ?>
                    </label>
                    <input type="password" id="password" name="password" value="<?php if(isset($_POST['password'])) echo $_POST['password']; ?>" tabindex="4">
                    <label for="password-confirmation">
                        Confirmer le mot de passe *
                        <?php if(array_key_exists('password_confirmation', $errors)) echo '<br><span class="error">' . $errors['password_confirmation'] . '</span>'; ?>
                    </label>
                    <input type="password" id="password-confirmation" name="password_confirmation" value="<?php if(isset($_POST['password_confirmation'])) echo $_POST['password_confirmation']; ?>" tabindex="5">
                </div>
                <div class="col-xs-12 col-s-6 col-m-4 col-pd font-size-default">
                    <label for="profile-picture">
                        Photo
                        <?php if(array_key_exists('profile_picture', $errors)) echo '<br><span class="error">' . $errors['profile_picture'] . '</span>'; ?>
                    </label>
                    <div class="input-file-container">
                        <input class="profile-picture" id="profile-picture" name="profile_picture" type="file">
                        <input type="text" class="input-file-return" readonly>
                        <button type="button" class="btn-1-s input-file-trigger" tabindex="6">Parcourir</button>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-m-8 col-pd">
                <p class="caption">* Champs obligatoires</p>
                <div class="buttons">
                    <a href="/admin/members/listing" tabindex="7"><span class="cancel"></span>Annuler</a>
                    <button type="submit" class="btn-1-m" tabindex="8">
                        <span class="check"></span>
                        Valider
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>