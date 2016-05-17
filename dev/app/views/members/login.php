<div id="visuel">
    <div class="login-container">
        <h1>Connexion</h1>
        <form action="/members/login" method="post">
            <?php if(array_key_exists('login', $errors)) echo '<p>' . $errors['login'] . '</p>'; ?>
            <input type="email" id="email" name="email" value="<?php echo (isset($_POST['email'])) ? $_POST['email'] : 'Adresse e-mail'; ?>">
            <input type="password" id ="password" name="password" value="<?php echo (isset($_POST['password'])) ? $_POST['password'] : 'Mot de passe'; ?>">
            <input type="checkbox" class="keep-me-logged inline" id="keep-me-logged" name="keep_me_logged">
            <label for="keep-me-logged">Rester connecté</label>
            <input type="submit" class="btn-2-m" value="connexion">
        </form>
        <p class="link"><a href="/members/forgottenPassword">J'ai oublié mon mot de passe</a></p>
    </div>
</div>