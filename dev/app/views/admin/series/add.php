<div id="content" class="content">
    <div class="col-pd">
        <div class="col-xs-12 col-pd">
            <h1>Nouvelle série</h1>
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
        <form action="/admin/series/add" method="post" enctype="multipart/form-data">
            <div class="row font-size-zero">
                <div class="col-xs-12 col-s-6 col-m-4 col-pd font-size-default">
                    <label for="name">
                        Nom *
                        <?php if(array_key_exists('name', $errors)) echo '<br><span class="error">' . $errors['name'] . '</span>'; ?>
                    </label>
                    <input type="text" id="name" name="name" value="<?php if(isset($_POST['name'])) echo $_POST['name']; ?>" tabindex="1">
                    <label for="range">
                        Appartient à la gamme *
                        <?php if(array_key_exists('range', $errors)) echo '<br><span class="error">' . $errors['range'] . '</span>'; ?>
                    </label>
                    <?php
                    if(isset($ranges_list) && $ranges_list != '')
                    { ?>
                        <select id="range" name="range" tabindex="2">
                            <?php
                            // On liste toutes les gammes
                            foreach ($ranges_list as $range)
                            {
                                $selected = (isset($_POST['range']) && $range->id == $_POST['range']) ? 'selected="selected"' : '';
                                ?>
                                <option value="<?php echo $range->id; ?>" <?php echo $selected; ?>><?php echo $range->name; ?></option>
                            <?php
                            } ?>
                        </select>
                    <?php
                    } ?>
                    <p>
                        <a href="/admin/ranges/add">
                            Ajouter une nouvelle gamme
                        </a>
                    </p>
                </div>
                <div class="col-xs-12 col-s-6 col-m-4 col-pd font-size-default">
                    <label>
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
                    <a href="/admin/series/listing" tabindex="4"><span class="cancel"></span>Annuler</a>
                    <button type="submit" class="btn-1-m" tabindex="5">
                        <span class="check"></span>
                        Valider
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>