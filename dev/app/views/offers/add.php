<div id="content" class="content">
    <div class="col-pd">
        <div class="col-xs-12 col-pd">
            <?php if(isset($item)) { ?>
                <div class="page-header">
                    <div class="page-header-picture">
                        <div class="picture-container">
                            <?php if(!empty($item->picture)) { ?>
                                <img class="resize-to-container" src="/assets/img/<?php echo $item->type; ?>s/57x57/<?php echo $item->picture; ?>">
                            <?php } ?>
                        </div>
                    </div>
                    <div class="page-header-title">
                        <h2 class="title">
                            <?php echo $item->name; ?>
                            <?php if(isset($item->number)) { ?>
                                (<?php echo $item->number; ?>)
                            <?php } ?>
                        </h2>
                        <p class="subtitle"><?php echo $item->range_name; ?> - <?php echo $item->serie_name; ?></p>
                    </div>
                </div>
            <?php } else { ?>
                <h1 class="dashed">
                    Déposer une annonce
                </h1>
        <?php } ?>
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
        <form action="/offers/add<?php if(isset($item)) { ?>?id=<?php echo $item->id; ?>&type=<?php echo $item->type; ?> <?php } ?>" method="post" enctype="multipart/form-data">
        <?php if(isset($item)) { ?>
            <input type="hidden" name="id" value="<?php echo $item->id; ?>">
            <input type="hidden" name="type" value="<?php echo $item->type; ?>">
        <?php } ?>
            <div class="col-xs-12 col-pd font-size-default">
                <label class="type-title-label">
                    Type d'annonce : *
                    <?php if(array_key_exists('type', $errors)) { ?>
                        <br><span class="error"><?php echo $errors['type']; ?></span>
                    <?php } ?>
                </label>
                <input type="checkbox" class="type-sell inline" id="type-sell" name="type_sell"<?php if(isset($_POST['type_sell'])) { ?> checked="checked"<?php } ?>>
                <label class="type-element-label" for="type-sell">
                    Vente
                </label>
                <input type="checkbox" class="type-exchange inline" id="type-exchange" name="type_exchange"<?php if(isset($_POST['type_exchange'])) { ?> checked="checked"<?php } ?>>
                <label class="type-element-label" for="type-exchange">
                    Echange
                </label>
            </div>
            <div class="font-size-zero">
                <div class="col-xs-12 col-s-8">
                    <div class="col-xs-12 col-s-6 col-pd font-size-default">
                        <?php if(!isset($item)) { ?>
                            <label for="title">
                                Titre de l'annonce :<br>
                                <span class="caption">
                                    (seulement si l'annonce contient plusieurs objets)
                                </span>
                                <?php if(array_key_exists('title', $errors)) { ?>
                                    <br><span class="error"><?php echo $errors['title']; ?></span>
                                <?php } ?>
                            </label>
                            <input type="text" id="title" name="title" value="<?php echo isset($_POST['title']) ? $_POST['title'] : ''; ?>">
                        <?php } ?>
                        <label for="price">
                            Prix : <span class="caption">(ex: 99,99)</span>
                            <?php if(array_key_exists('price', $errors)) { ?>
                                <br><span class="error"><?php echo $errors['price']; ?></span>
                            <?php } ?>
                        </label>
                        <input type="text" id="price" name="price" value="<?php echo isset($_POST['price']) ? $_POST['price'] : ''; ?>">
                    </div>
                    <div class="col-xs-12 col-s-6 col-pd font-size-default">
                        <?php if(isset($item)) { ?>
                            <label for="count">
                                Nombre d'exemplaires : *
                                <?php if(array_key_exists('count', $errors)) { ?>
                                    <br><span class="error"><?php echo $errors['count']; ?></span>
                                <?php } ?>
                            </label>
                            <select id="count" name="count">
                                <?php for($i = 1; $i <= 9; $i++) { ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php } ?>
                            </select>
                        <?php } else { ?>
                            <?php if(array_key_exists('items', $errors)) { ?>
                                <label><span class="error"><?php echo $errors['items']; ?></span></label>
                            <?php } ?>
                            <label for="sets">
                                Sélection des sets : **
                            </label>
                            <select id="sets">
                                <option></option>
                                <?php foreach ($sets_list as $set) { ?>
                                    <?php $selected = (isset($_POST['sets']) && in_array($set->id, $_POST['sets'])) ? 'selected="selected"' : ''; ?>
                                    <option value="<?php echo $set->id; ?>" <?php echo $selected; ?>><?php echo $set->name; ?> (<?php echo $set->number; ?>)</option>
                                <?php } ?>
                            </select>
                            <div class="item-box-container set">
                                <?php if(isset($selected_sets)) { ?>
                                    <?php $i = 0; ?>
                                    <?php foreach($selected_sets as $set) { ?>
                                        <div class="item-box">
                                            <input type="hidden" name="sets[]" value="<?php echo $set->id; ?>">
                                            <div class="item">
                                                <div class="action">
                                                    <span class="close"></span>
                                                </div>
                                                <img class="picture" src="/assets/img/sets/57x57/<?php echo $set->picture; ?>">
                                                <p><span class="name"><?php echo $set->name; ?></span><br><span class="range"><?php echo $set->range_name; ?></span> - <span class="serie"><?php echo $set->serie_name; ?></span></p>
                                            </div>
                                            <div class="number">
                                                <select name="sets_count[]">
                                                    <?php for($j = 1; $j <= 9; $j++) { ?>
                                                        <?php $selected = ($_POST['sets_count'][$i] == $j) ? 'selected="selected"' : ''; ?>
                                                        <option value="<?php echo $j; ?>" <?php echo $selected; ?>><?php echo $j; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <label for="number">Nombre d'exemplaires</label>
                                            </div>
                                        </div>
                                        <?php $i++; ?>
                                    <?php } ?>
                                <?php } ?>
                            </div>

                            <label for="minifigures">
                                Sélection des figurines : **
                            </label>
                            <select id="minifigures">
                                <option></option>
                                <?php foreach ($minifigures_list as $minifigure) { ?>
                                    <?php $selected = (isset($_POST['minifigures']) && in_array($minifigure->id, $_POST['minifigures'])) ? 'selected="selected"' : ''; ?>
                                    <option value="<?php echo $minifigure->id; ?>" <?php echo $selected; ?>><?php echo $minifigure->name; ?></option>
                                <?php } ?>
                            </select>
                            <div class="item-box-container minifigure">
                                <?php if(isset($selected_minifigures)) { ?>
                                    <?php $i = 0; ?>
                                    <?php foreach($selected_minifigures as $minifigure) { ?>
                                        <div class="item-box">
                                            <input type="hidden" name="minifigures[]" value="<?php echo $minifigure->id; ?>">
                                            <div class="item">
                                                <div class="action">
                                                    <span class="close"></span>
                                                </div>
                                                <img class="picture" src="/assets/img/minifigures/57x57/<?php echo $minifigure->picture; ?>">
                                                <p><span class="name"><?php echo $minifigure->name; ?></span><br><span class="range"><?php echo $minifigure->range_name; ?></span> - <span class="serie"><?php echo $minifigure->serie_name; ?></span></p>
                                            </div>
                                            <div class="number">
                                                <select name="minifigures_count[]">
                                                    <?php for($j = 1; $j <= 9; $j++) { ?>
                                                        <?php $selected = ($_POST['minifigures_count'][$i] == $j) ? 'selected="selected"' : ''; ?>
                                                        <option value="<?php echo $j; ?>" <?php echo $selected; ?>><?php echo $j; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <label for="number">Nombre d'exemplaires</label>
                                            </div>
                                        </div>
                                        <?php $i++; ?>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="col-xs-12 col-pd font-size-default">
                        <label for="description">
                            Texte de l'annonce :
                        </label>
                        <textarea id="description" name="description"><?php echo isset($_POST['description']) ? $_POST['description'] : ''; ?></textarea>
                    </div>
                </div>
                <div class="col-xs-12 col-s-4">
                    <div class="col-xs-12 col-pd font-size-default">
                        <label for="picture-0">
                            Photos :
                            <?php if(array_key_exists('pictures', $errors)) { ?>
                                <br><span class="error"><?php echo $errors['pictures']; ?></span>
                            <?php } ?>
                        </label>
                        <div class="input-file-container">
                            <input class="picture" id="picture-0" name="pictures[]" type="file">
                            <input type="text" class="input-file-return" readonly>
                            <button type="button" class="btn-1-s input-file-trigger" tabindex="6">Parcourir</button>
                        </div>
                        <p>
                            <a class="add-picture-link link style-1" href="#">
                                <span class="icon-add"></span>
                                Ajouter une autre photo
                            </a>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-pd">
                <p class="caption">
                    * Champs obligatoires
                    <?php if(!isset($item)) { ?>
                        <br>
                        ** Un des deux champs est obligatoire
                    <?php } ?>
                </p>
                <h3 class="line">
                    <span>Informations personnelles</span>
                </h3>
                <p>
                    <strong>Adresse e-mail :</strong> <?php echo $member->email; ?>
                    <br>
                    <strong>Localisation :</strong>
                    <?php echo $member->city; ?>
                    <?php if($member->city != '' && $member->region != '') { echo ', '; } ?>
                    <?php echo $member->region; ?>
                </p>
                <div class="buttons">
                    <button type="submit" class="btn-1-m">
                        Valider
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="item-box hidden">
    <input type="hidden" name="" value="">
    <div class="item">
        <div class="action">
            <span class="close"></span>
        </div>
        <img class="picture" src="/assets/img/minifigures/57x57/31-le-chevalier-heroique.jpg">
        <p><span class="name">Luke skywalker</span><br><span class="range">Star Wars</span> - <span class="serie">Trilogie</span></p>
    </div>
    <div class="number">
        <select name="">
            <?php for($i = 1; $i <= 9; $i++) { ?>
                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
            <?php } ?>
        </select>
        <label for="number">Nombre d'exemplaires</label>
    </div>
</div>