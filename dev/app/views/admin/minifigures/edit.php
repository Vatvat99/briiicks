<div id="content" class="content">
    <div class="col-pd">
        <div class="col-xs-12 col-pd">
            <h1>Modifier la figurine</h1>
            <?php
            if(array_key_exists('success', $_SESSION))
            { ?>
                <p class="success">
                    <?php echo $_SESSION['success']; ?>
                </p>
            <?php
            }
            elseif(array_key_exists('error', $_SESSION))
            { ?>
                <p class="error">
                    <?php echo $_SESSION['error']; ?>
                </p>
            <?php
            } ?>
        </div>
        <form action="/admin/minifigures/edit?id=<?php if(isset($_GET['id'])) echo $_GET['id']; ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" id="id" name="id" value="<?php if(isset($_GET['id'])) echo $_GET['id']; ?>">
            <div class="row font-size-zero">
                <div class="col-xs-12 col-s-6 col-m-4 col-pd font-size-default">
                    <label for="name">
                        Nom *
                        <?php if(array_key_exists('name', $errors)) echo '<br><span class="error">' . $errors['name'] . '</span>'; ?>
                    </label>
                    <input type="text" id="name" name="name" value="<?php if(isset($_POST['name'])) echo $_POST['name']; elseif(isset($minifigure->name)) echo $minifigure->name; ?>" tabindex="1">
                    <label for="range">
                        Gamme *
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
                                $selected = ((isset($_POST['range']) && $_POST['range'] == $range->id) || (!isset($_POST['range']) && isset($minifigure->range_id) && $minifigure->range_id == $range->id)) ? 'selected="selected"' : '';
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
                        <select id="serie" name="serie" tabindex="3" <?php echo (count($series_list) == 0) ? 'class="hidden"' : ''; ?>>
                            <?php
                            // On liste toutes les séries
                            foreach ($series_list as $serie)
                            {
                                $selected = ((isset($_POST['serie']) && $_POST['serie'] == $serie->id) || (!isset($_POST['serie']) && isset($minifigure->serie_id) && $minifigure->serie_id == $serie->id)) ? 'selected="selected"' : '';
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

                    <label for="release-year">
                        Année de sortie <span class="caption">(ex: 2015)</span>
                        <?php if(array_key_exists('release_year', $errors)) echo '<br><span class="error">' . $errors['release_year'] . '</span>'; ?>
                    </label>
                    <input type="text" id="release-year" name="release_year" value="<?php if(isset($_POST['release_year'])) echo $_POST['release_year']; elseif(isset($minifigure->release_year)) echo $minifigure->release_year; ?>" tabindex="4">
                </div>
                <div class="col-xs-12 col-s-6 col-m-4 col-pd font-size-default">
                    <label>
                        Visuel actuel
                    </label>
                    <div class="current-picture-container big <?php echo ((isset($_POST['current_picture']) && $_POST['current_picture'] == '') || (isset($minifigure->picture) && $minifigure->picture == '')) ? 'no-picture' : ''; ?>">
                        <?php
                        if(isset($_POST['current_picture']) && $_POST['current_picture'] != '')
                        { ?>
                            <img class="resize-to-container" src="/assets/img/minifigures/209x238/<?php echo $_POST['current_picture']; ?>">
                            <input type="hidden" id="current-picture" name="current_picture" value="<?php echo $_POST['current_picture']; ?>">
                        <?php
                        }
                        elseif(isset($minifigure->picture) && $minifigure->picture != '')
                        { ?>
                            <img class="resize-to-container" src="/assets/img/minifigures/209x238/<?php echo $minifigure->picture; ?>">
                            <input type="hidden" id="current-picture" name="current_picture" value="<?php echo $minifigure->picture; ?>">
                        <?php
                        } ?>
                    </div>
                    <?php
                    if(isset($minifigure->picture) && $minifigure->picture != '')
                    { ?>
                        <input type="checkbox" class="delete-picture" id="delete-picture" name="delete_picture">
                        <label class="delete-picture" for="delete-picture">supprimer</label>
                    <?php
                    } ?>

                    <label for="picture">
                        Nouveau visuel
                        <?php if(array_key_exists('picture', $errors)) echo '<br><span class="error">' . $errors['picture'] . '</span>'; ?>
                    </label>
                    <div class="input-file-container">
                        <input class="picture" id="picture" name="picture" type="file">
                        <input type="text" class="input-file-return" readonly>
                        <button type="button" class="btn-1-s input-file-trigger" tabindex="5">Parcourir</button>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-m-8 col-pd">
                <p class="caption">* Champs obligatoires</p>
                <div class="buttons">
                    <a href="/admin/minifigures/listing" tabindex="6"><span class="cancel"></span>Annuler</a>
                    <button type="submit" class="btn-1-m" tabindex="7">
                        <span class="check"></span>
                        Valider
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>