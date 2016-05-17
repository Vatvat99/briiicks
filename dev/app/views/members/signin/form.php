<div id="content" class="content col-pd">
    <div class="col-pd">
        <h1>Créer mon compte</h1>
    </div>
    <div class="font-size-zero">
        <div class="col-xs-12 col-s-5 col-pd font-size-default">
            <div class="signin-container">
                <?php if(!empty($confirmation_message)) { ?>
                    <p class="confirmation-message"><?php echo $confirmation_message; ?></p>
                <?php } ?>
                <form action="/members/signin" method="post">
                    <label for="firstname">
                        <?php if(array_key_exists('firstname', $errors)) echo '<strong>' . $errors['firstname'] . '<br></strong>'; ?>
                        Votre prénom
                    </label>
                    <input type="text" id="firstname" name="firstname" value="<?php if(isset($_POST['firstname'])) echo $_POST['firstname']; ?>">
                    <label for="lastname">
                        <?php if(array_key_exists('lastname', $errors)) echo '<strong>' . $errors['lastname'] . '<br></strong>'; ?>
                        Votre nom
                    </label>
                    <input type="text" id="lastname" name="lastname" value="<?php if(isset($_POST['lastname'])) echo $_POST['lastname']; ?>">
                    <label for="email">
                        <?php if(array_key_exists('email', $errors)) echo '<strong>' . $errors['email'] . '<br></strong>'; ?>
                        Votre adresse e-mail
                    </label>
                    <input type="email" id="email" name="email" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>">
                    <label for="password">
                        <?php if(array_key_exists('password', $errors)) echo '<strong>' . $errors['password'] . '<br></strong>'; ?>
                        Choisissez un mot de passe
                    </label>
                    <input type="password" id="password" name="password" value="<?php if(isset($_POST['password'])) echo $_POST['password']; ?>">
                    <label for="password_confirmation">
                        <?php if(array_key_exists('password_confirmation', $errors)) echo '<strong>' . $errors['password_confirmation'] . '<br></strong>'; ?>
                        Confirmez votre mot de passe
                    </label>
                    <input type="password" id="password-confirmation" name="password_confirmation" value="<?php if(isset($_POST['password_confirmation'])) echo $_POST['password_confirmation']; ?>">
                    <p class="caption">Tous les champs sont obligatoires</p>
                    <input class="btn-2-m" type="submit" value="S'inscrire">
                </form>
            </div>
        </div>
        <div class="col-xs-12 col-s-7 col-pd font-size-default">
            <div class="argument">
                <h3>Gérez votre propre collection</h3>
                <p>Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis bibendum auctor, nisi elit consequat ipsum, nec sagittis sem nibh id elit. Duis sed odio sit amet.</p>
                <p><img src="/assets/img/rectangle-01.png" alt=""></p>
            </div>
            <div class="argument">
                <h3>Effectuez des transactions</h3>
                <p>Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis bibendum auctor, nisi elit consequat ipsum, nec sagittis sem nibh id elit. Duis sed odio sit amet.</p>
                <p><img src="/assets/img/rectangle-01.png" alt=""></p>
            </div>
        </div>
    </div>
</div>