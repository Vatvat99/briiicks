<div id="content" class="content">
    <div class="col-pd">
        <div class="col-xs-12 col-pd">
            <h1>Nouveau set</h1>
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
        <form action="/admin/sets/add" method="post" enctype="multipart/form-data">
            <div class="row font-size-zero">
                <div class="col-xs-12 col-s-6 col-m-4 col-pd font-size-default">
                    <label for="number">
                        Numéro *
                        <?php if(array_key_exists('number', $errors)) echo '<br><span class="error">' . $errors['number'] . '</span>'; ?>
                    </label>
                    <input type="text" id="number" name="number" value="<?php if(isset($_POST['number'])) echo $_POST['number']; ?>" tabindex="1">
                    <label for="name">
                        Nom *
                        <?php if(array_key_exists('name', $errors)) echo '<br><span class="error">' . $errors['name'] . '</span>'; ?>
                    </label>
                    <input type="text" id="name" name="name" value="<?php if(isset($_POST['name'])) echo $_POST['name']; ?>" tabindex="2">

                    <label for="release-year">
                        Année de sortie <span class="caption">(ex: 2015)</span>
                        <?php if(array_key_exists('release_year', $errors)) echo '<br><span class="error">' . $errors['release_year'] . '</span>'; ?>
                    </label>
                    <input type="text" id="release-year" name="release_year" value="<?php if(isset($_POST['release_year'])) echo $_POST['release_year']; ?>" tabindex="3">

                    <label for="price">
                        Prix <span class="caption">(ex: 99,99)</span>
                        <?php if(array_key_exists('price', $errors)) echo '<br><span class="error">' . $errors['price'] . '</span>'; ?>
                    </label>
                    <input type="text" id="price" name="price" value="<?php if(isset($_POST['price'])) echo $_POST['price']; ?>" tabindex="4">

                    <label for="range">
                        Gamme *
                        <?php if(array_key_exists('range', $errors)) echo '<br><span class="error">' . $errors['range'] . '</span>'; ?>
                    </label>
                    <?php
                    if(isset($ranges_list) && $ranges_list != '')
                    { ?>
                        <select id="range" name="range" tabindex="5">
                            <?php
                            // On liste toutes les gammes
                            foreach ($ranges_list as $range)
                            {
                                $selected = (isset($_POST['range']) && $_POST['range'] == $range->id) ? 'selected="selected"' : '';
                                ?>
                                <option value="<?php echo $range->id; ?>" <?php echo $selected; ?>><?php echo $range->name; ?></option>
                            <?php
                            } ?>
                        </select>
                    <?php
                    } ?>

                    <label for="serie">
                        Série *
                        <?php if(array_key_exists('serie', $errors)) echo '<br><span class="error">' . $errors['serie'] . '</span>'; ?>
                    </label>
                    <?php
                    if(isset($series_list) && $series_list != '')
                    { ?>
                        <select id="serie" name="serie" tabindex="6" <?php echo (count($series_list) == 0) ? 'class="hidden"' : ''; ?>>
                            <?php
                            // On liste toutes les séries
                            foreach ($series_list as $serie)
                            {
                                $selected = (isset($_POST['serie']) && $_POST['serie'] == $serie->id) ? 'selected="selected"' : '';
                                ?>
                                <option value="<?php echo $serie->id; ?>" <?php echo $selected; ?>><?php echo $serie->name; ?></option>
                            <?php
                            } ?>
                        </select>
                    <?php
                    } ?>
                    <p id="no-serie"<?php echo (count($series_list) == 0) ? '' : 'class="hidden"'; ?>>
                        Aucune série dans cette gamme (<a href="/admin/series/add">en ajouter une</a>)
                    </p>

                    <label for="minifigures">
                        Contient les figurines
                        <?php if(array_key_exists('minifigures', $errors)) echo '<br><span class="error">' . $errors['minifigures'] . '</span>'; ?>
                    </label>
                    <?php
                    if(isset($minifigures_list) && $minifigures_list != '')
                    { ?>
                        <select id="minifigures" name="minifigures[]" tabindex="7" <?php echo (count($minifigures_list) == 0) ? 'class="hidden"' : ''; ?> multiple>
                            <?php
                            // On liste toutes les figurines
                            foreach ($minifigures_list as $minifigure)
                            {
                                $selected = (isset($_POST['minifigures']) && $_POST['minifigures'] == $minifigure->id) ? 'selected="selected"' : '';
                                ?>
                                <option value="<?php echo $minifigure->id; ?>" <?php echo $selected; ?>><?php echo $minifigure->name; ?></option>
                            <?php
                            } ?>
                        </select>
                        <p id="no-minifigure"<?php echo (count($minifigures_list) == 0) ? '' : 'class="hidden"'; ?>>
                            Aucune figurine dans cette série (<a href="/admin/minifigures/add">en ajouter une</a>)
                        </p>
                    <?php
                    }
                    // Si on n'a pas de séries
                    else
                    { ?>
                        <select id="minifigures" name="minifigures[]" tabindex="7" class="hidden" multiple></select>
                        <p id="no-minifigure">
                            Veuillez sélectionner une série
                        </p>
                    <?php
                    } ?>
                </div>
                <div class="col-xs-12 col-s-6 col-m-4 col-pd font-size-default">
                    <label for="picture">
                        Visuel
                        <?php if(array_key_exists('picture', $errors)) echo '<br><span class="error">' . $errors['picture'] . '</span>'; ?>
                    </label>
                    <div class="input-file-container">
                        <input class="picture" id="picture" name="picture" type="file">
                        <input type="text" class="input-file-return" readonly>
                        <button type="button" class="btn-1-s input-file-trigger" tabindex="8">Parcourir</button>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-m-8 col-pd">
                <p class="caption">* Champs obligatoires</p>
                <div class="buttons">
                    <a href="/admin/sets/listing" tabindex="9"><span class="cancel"></span>Annuler</a>
                    <button type="submit" class="btn-1-m" tabindex="10">
                        <span class="check"></span>
                        Valider
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>