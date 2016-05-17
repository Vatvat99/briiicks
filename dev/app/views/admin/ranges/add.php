<div id="content" class="content">
    <div class="col-pd">
        <div class="col-xs-12 col-pd">
            <h1>Nouvelle gamme</h1>
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
        <form action="/admin/ranges/add" method="post" enctype="multipart/form-data">
            <div class="row font-size-zero">
                <div class="col-xs-12 col-s-6 col-m-4 col-pd font-size-default">
                    <label for="name">
                        Nom *
                        <?php if(array_key_exists('name', $errors)) echo '<br><span class="error">' . $errors['name'] . '</span>'; ?>
                    </label>
                    <input type="text" id="name" name="name" value="<?php if(isset($_POST['name'])) echo $_POST['name']; ?>" tabindex="1">
                    <label for="color">
                        Couleur * <span class="caption">(ex: #39910e)</span>
                        <?php if(array_key_exists('color', $errors)) echo '<br><span class="error">' . $errors['color'] . '</span>'; ?>
                    </label>
                    <input type="text" id="color" name="color" value="<?php if(isset($_POST['color'])) echo $_POST['color']; ?>" tabindex="2">
                </div>
                <div class="col-xs-12 col-s-6 col-m-4 col-pd font-size-default">
                    <label for="picture">
                        Logo
                        <?php if(array_key_exists('picture', $errors)) echo '<br><span class="error">' . $errors['picture'] . '</span>'; ?>
                    </label>
                    <div class="input-file-container">
                        <input class="picture" id="picture" name="picture" type="file">
                        <input type="text" class="input-file-return" readonly>
                        <button type="button" class="btn-1-s input-file-trigger" tabindex="3">Parcourir</button>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-m-8 col-pd">
                <p class="caption">* Champs obligatoires</p>
                <div class="buttons">
                    <a href="/admin/ranges/listing" tabindex="4"><span class="cancel"></span>Annuler</a>
                    <button type="submit" class="btn-1-m" tabindex="5">
                        <span class="check"></span>
                        Valider
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>