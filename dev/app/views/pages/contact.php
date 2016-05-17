<div id="content" class="content">
    <div class="col-pd">
        <div class="col-xs-12 col-pd">
            <h1 class="dashed">
                Contacter le webmaster
            </h1>
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
    </div>
    <form action="/pages/contact" method="post">
        <div class="col-pd font-size-zero">
            <div class="col-xs-12 col-s-4 col-pd font-size-default">
                <label for="firstname">
                    Prénom : *
                    <?php if(array_key_exists('firstname', $errors)) { ?>
                        <br><span class="error"><?php echo $errors['firstname']; ?></span>
                    <?php } ?>
                </label>
                <input type="text" class="firstname" id="firstname" name="firstname" value="<?php echo isset($_POST['firstname']) ? $_POST['firstname'] : ''; ?>">
            </div>
            <div class="col-xs-12 col-s-4 col-pd font-size-default">
                <label for="lastname">
                    Nom : *
                    <?php if(array_key_exists('lastname', $errors)) { ?>
                        <br><span class="error"><?php echo $errors['lastname']; ?></span>
                    <?php } ?>
                </label>
                <input type="text" class="lastname" id="lastname" name="lastname" value="<?php echo isset($_POST['lastname']) ? $_POST['lastname'] : ''; ?>">
            </div>
            <div class="col-xs-12 col-s-4 col-pd font-size-default">
                <label for="email">
                    E-mail : *
                    <?php if(array_key_exists('email', $errors)) { ?>
                        <br><span class="error"><?php echo $errors['email']; ?></span>
                    <?php } ?>
                </label>
                <input type="text" class="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
            </div>
        </div>
        <div class="col-pd font-size-zero">
            <div class="col-xs-12 col-pd font-size-default">
                <label for="message">
                    Texte du message : *
                    <?php if(array_key_exists('message', $errors)) { ?>
                        <br><span class="error"><?php echo $errors['message']; ?></span>
                    <?php } ?>
                </label>
                <textarea class="message" id="message" name="message"><?php echo isset($_POST['message']) ? $_POST['message'] : ''; ?></textarea>
            </div>
        </div>
        <div class="col-pd">
            <div class="col-xs-12 col-pd font-size-default">
                <p class="caption">
                    * Champs obligatoires
                </p>
                <div class="buttons">
                    <div class="captcha-container">
                        <?php if(array_key_exists('captcha', $errors)) { ?>
                            <label style="display: block;"><span class="error"><?php echo $errors['captcha']; ?></span></label>
                        <?php } ?>
                        <div class="g-recaptcha" data-sitekey="6Lf6nQATAAAAAA0bDrIps78EC3AZX5Ft0MdSKy72"></div>
                    </div>
                    <button type="submit" class="btn-1-m">
                        Envoyer
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>