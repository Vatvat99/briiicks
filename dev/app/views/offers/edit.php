<div id="content" class="content">
    <div class="col-pd">
        <div class="col-xs-12 col-pd">
            <?php if(array_key_exists('success', $_SESSION)) { ?>
                <p class="success">
                    <?php echo $_SESSION['success']; ?>
                </p>
            <?php } elseif(array_key_exists('error', $_SESSION)) { ?>
                <p class="error">
                    <?php echo $_SESSION['error']; ?>
                </p>
            <?php } ?>
            <div class="page-header">
                <div class="page-header-picture">
                    <div class="picture-container">
                        <?php if(!empty($offer->pictures)) { ?>
                            <img class="resize-to-container" src="/assets/img/offers/57x57/<?php echo $offer->pictures[0]->filename; ?>">
                        <?php } ?>
                    </div>
                </div>
                <div class="page-header-title">
                    <h2 class="title">
                        <?php echo $offer->title; ?>
                    </h2>
                    <p class="subtitle"><?php echo $offer->type; ?></p>
                </div>
                <div class="page-header-info">
                    <?php if($offer->price != 0) { ?>
                        <span class="price">
                            <?php echo $offer->price; ?> €
                        </span>
                        <br>
                    <?php } ?>
					<span class="date">
						<?php echo $offer->date; ?> <?php echo $offer->time; ?>
					</span>
                </div>
            </div>
        </div>
        <form action="/offers/edit?id=<?php echo $offer->id; ?>" method="post" enctype="multipart/form-data">
            <div class="col-xs-12 col-pd font-size-default">
                <label class="type-title-label">
                    Type d'annonce : *
                    <?php if(array_key_exists('type', $errors)) { ?>
                        <br><span class="error"><?php echo $errors['type']; ?></span>
                    <?php } ?>
                </label>
                <input type="checkbox" class="type-sell inline" id="type-sell" name="type_sell"<?php if(isset($_POST['type_sell']) || !isset($_POST['type_sell']) && ($offer->type == 'Vente' || $offer->type == 'Vente/Echange' )) { ?> checked="checked"<?php } ?>>
                <label class="type-element-label" for="type-sell">
                    Vente
                </label>
                <input type="checkbox" class="type-exchange inline" id="type-exchange" name="type_exchange"<?php if(isset($_POST['type_exchange']) || !isset($_POST['type_exchange']) && ($offer->type == 'Echange' || $offer->type == 'Vente/Echange')) { ?> checked="checked"<?php } ?>>
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
                            <input type="text" id="title" name="title" value="<?php if(isset($_POST['title'])) echo $_POST['title']; elseif(count($offer->minifigures) + count($offer->sets) > 1) echo $offer->title; ?>">
                        <?php } ?>
                        <label for="price">
                            Prix : <span class="caption">(ex: 99,99)</span>
                            <?php if(array_key_exists('price', $errors)) { ?>
                                <br><span class="error"><?php echo $errors['price']; ?></span>
                            <?php } ?>
                        </label>
                        <input type="text" id="price" name="price" value="<?php if(isset($_POST['price'])) echo $_POST['price']; elseif(!empty($offer->price)) echo $offer->price; ?>">
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
                                                        <?php $selected = ($set->count == $j) ? 'selected="selected"' : ''; ?>
                                                        <option value="<?php echo $j; ?>" <?php echo $selected; ?>><?php echo $j; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <label for="number">Nombre d'exemplaires</label>
                                            </div>
                                        </div>
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
                                                        <?php $selected = ($minifigure->count == $j) ? 'selected="selected"' : ''; ?>
                                                        <option value="<?php echo $j; ?>" <?php echo $selected; ?>><?php echo $j; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <label for="number">Nombre d'exemplaires</label>
                                            </div>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="col-xs-12 col-pd font-size-default">
                        <label for="description">
                            Texte de l'annonce :
                        </label>
                        <textarea id="description" name="description"><?php if(isset($_POST['description'])) echo $_POST['description']; elseif(!empty($offer->description)) echo $offer->description; ?></textarea>
                    </div>
                </div>
                <div class="col-xs-12 col-s-4">
                    <div class="col-xs-12 col-pd font-size-default">
                        <div class="add-picture-container <?php if(count($offer->pictures) == 4) { ?> hidden<?php } ?>">
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
                            <p id="addPictureLink">
                                <a class="add-picture-link link style-1" href="#">
                                    <span class="icon-add"></span>
                                    Ajouter une autre photo
                                </a>
                            </p>
                        </div>
                        <?php if(!empty($offer->pictures)) { ?>
                            <p class="label">Photos actuelles :</p>
                            <div class="pictures-container">
                                <?php $i = 0; ?>
                                <?php foreach($offer->pictures as $picture) { ?>
                                    <div class="column">
                                        <div class="picture-container">
                                            <img class="resize-to-container" src="/assets/img/offers/96x96/<?php echo $picture->filename; ?>">
                                        </div>
                                        <input type="checkbox" class="delete-picture" id="delete-picture-<?php echo $i; ?>" name="delete_picture[<?php echo $i; ?>]" value="<?php echo $picture->filename ; ?>">
                                        <label class="delete-picture" for="delete-picture-<?php echo $i; ?>">supprimer</label>
                                    </div>
                                    <?php $i++; ?>
                                <?php } ?>
                            </div>
                        <?php } ?>
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