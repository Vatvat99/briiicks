<div id="content" class="content">
    <div class="connection col-xs-12">
        <img class="logo" src="/assets/img/admin/gear-logo-01.png" alt="GEAR">
        <h1>Connexion</h1>
        <form method="post">
            <?php if(!empty($errors)) echo '<p class="error">' . $errors . '</p>'; ?>
            <input type="text" id="email" name="email" value="<?php if(isset($_POST['email'])) echo $_POST['email']; else echo 'Adresse e-mail'; ?>">
            <input type="password" id="password" name="password" value="<?php if(isset($_POST['password'])) echo $_POST['password']; else echo 'Mot de passe'; ?>">
            <input type="checkbox" id="keep-me-logged" name="keep_me_logged">
            <label for="keep-me-logged">Rester connecté</label>
            <p class="link"><a href="#">J'ai oublié mon mot de passe</a></p>
            <input type="submit" class="btn-1-m" value="connexion">
        </form>
    </div>
</div>