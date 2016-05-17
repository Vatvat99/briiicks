<div id="content" class="content">
    <div class="col-pd">
        <div class="col-xs-12 col-m-6 col-pd">
            <h1>Mot de passe oublié</h1>
            <p>Veuillez entre l’adresse e-mail qui vous sert d’identifiant. </p>
            <p>
                Vous recevrez un e-mail contenant un lien vous permettant de choisir un <strong>nouveau mot de passe.</strong>
            </p>
        </div>
        <br>
        <form action="/members/forgottenPassword" method="post" class="col-xs-12 col-m-4 col-pd">
            <label for="email">
                E-mail :
                <?php if(array_key_exists('email', $errors)) { ?>
                    <br><span class="error"><?php echo $errors['email']; ?></span>
                <?php } ?>
            </label>
            <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
            <button type="submit" class="btn-1-m">
                Valider
            </button>
        </form>
    </div>
</div>