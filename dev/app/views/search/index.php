<div class="container">
    <div id="content" class="content offset col-xs-12 col-pd">
        <h2 class="result-count dashed">
            <?php echo $items_list[$item_type . 's_count'] . ' ' . $item_type; ?><?php echo ($items_list[$item_type . 's_count'] == 1) ? '' : 's'; ?>
            <div class="search-btn-container">
                <div class="search-btn">
                    <a id="right-lateral-panel-link" class="coming-soon search-link" href="#"></a>
                    <p class="search-label">rechercher</p>
                </div>
            </div>
        </h2>
        <?php
        foreach ($items_list['ranges'] as $range)
        {
            // Si la gamme contient des items (figurines ou sets)
            if($range->{$item_type . 's_count'} > 0 ) { ?>
                <h1 class="range-title">
                    <?php echo $range->name; ?>
                    <span class="range-count">
						<?php echo $range->{$item_type . 's_count'} . ' '; ?><?php echo ($item_type == 'set') ? 'set' : 'fig'; ?><?php echo ($range->{$item_type . 's_count'} == 1) ? '' : 's'; ?>
					</span>
                </h1>
            <?php
            } ?>

            <?php
            foreach ($range->series as $serie)
            {
                // Si la série contient des items (figurines ou sets)
                if($serie->{$item_type . 's_count'} > 0 ) { ?>
                    <h3 class="serie-title line">
                        <span><?php echo $serie->name; ?></span>
						<span class="serie-count">
							<?php echo $serie->{$item_type . 's_count'} . ' '; ?><?php echo ($item_type == 'set') ? 'set' : 'fig'; ?><?php echo ($serie->{$item_type . 's_count'} == 1) ? '' : 's'; ?>
						</span>
                    </h3>
                    <div class="row serie font-size-zero">
                        <?php
                        foreach ($serie->{$item_type . 's'} as $item)
                        { ?>
                            <div class="item-col font-size-default">
                                <div class="item-container">
                                    <div class="item">
                                        <?php
                                        // Si une collection existe (l'utilisateur est connecté)
                                        if(isset($collection)) {
                                            // Si une des gamme correspond à la gamme de l'élément en train d'être affiché
                                            if(array_key_exists($range->id, $collection->ranges)) {
                                                // Si une des séries correspond à la série de l'élément en train d'être affiché
                                                if(array_key_exists($serie->id, $collection->ranges[$range->id]->series)) {
                                                    // Si l'élément dans la collection correspond à celui en train d'être affiché
                                                    if(array_key_exists($item->id, $collection->ranges[$range->id]->series[$serie->id]->{$item_type . 's'})) {
                                                        // On affiche le nombre présent dans la collection
                                                        ?>
                                                        <div class="item-count">
                                                            x<?php echo $collection->ranges[$range->id]->series[$serie->id]->{$item_type . 's'}[$item->id]->count; ?>
                                                        </div>
                                                        <?php
                                                    }
                                                }
                                            }
                                        } ?>
                                        <a class="add-link" href="#">
                                            Ajouter à ma collection
                                            <img src="/assets/img/plus-02.png">
                                        </a>
                                        <p class="name">- <?php echo (isset($item->number)) ? $item->number : $item->id; ?> -<br><?php echo $item->name; ?></p>
                                        <div class="transactions">
                                            <?php if($item->sell_total != 0 || $item->exchange_total != 0) { ?>
                                                <a href="/offers/listing?id=<?php echo $item->id; ?>&type=<?php echo $item_type; ?>" title="Voir les annonces pour &quot;<?php echo $item->name; ?>&quot;">
                                            <?php } ?>
                                                <p>
                                                    <span class="number"><?php echo $item->sell_total; ?></span>en vente
                                                    <br>
                                                    <?php if(!empty($item->price) && $item->price != 0) { ?>dès <?php echo str_replace(',00', '', str_replace('.', ',', $item->price)) ?> €<?php } ?>
                                                </p>
                                                <p>
                                                    <span class="number"><?php echo $item->exchange_total; ?></span>en<br>échange
                                                </p>
                                            <?php if($item->sell_total != 0 || $item->exchange_total != 0) { ?>
                                                </a>
                                            <?php } ?>
                                        </div>
                                        <div class="picture-container">
                                            <img class="picture resize-to-container" src="/assets/img/<?php echo $item_type . 's'; ?>/209x238/<?php echo ($item->picture != '') ? $item->picture : 'no-picture.png' ?>" alt="<?php echo $item->name; ?>">
                                        </div>
                                    </div>
                                    <div class="item-panel item-number animated">
                                        <div class="item-number-content">
                                            <h3 class="title">Nombre d'exemplaires</h3>
                                            <form>
                                                <div class="input-container">
                                                    <div class="less-item-col">
                                                        <button type="button" class="less-item-button">
                                                            <span class="icon-remove-alt"></span>
                                                        </button>
                                                    </div>
                                                    <div class="item-number-col">
                                                        <input type="text" class="item-number-input" name="item_number" value="1" readonly>
                                                        <input type="hidden" class="item-id-input" name="item_id" value="<?php echo $item->id; ?>">
                                                        <input type="hidden" class="item-type-input" name="item_type" value="<?php echo $item_type; ?>">
                                                    </div>
                                                    <div class="more-item-col">
                                                        <button type="button" class="more-item-button">
                                                            <span class="icon-add-alt"></span>
                                                        </button>
                                                    </div>
                                                </div>
                                                <button type="submit" class="add-button btn-2-m">Ajouter</button>
                                            </form>
                                        </div>
                                        <div class="item-confirmation-content hidden">
                                            <img src="/assets/img/success-02.png" alt="Succès">
                                            <h3>Ajouté !</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        } ?>
                    </div>
                <?php
                }
            }
        } ?>
        <h3 class="txt-center line">
            <span>Perdu quelque-chose ?</span>
        </h3>
        <div class="request font-size-zero">
            <p class="request-description font-size-default">
                Si vous ne trouvez pas la figurine que vous cherchez, c’est peut-être que celle-ci n’existe pas encore sur le site. Ne paniquez pas, vous pouvez la rajouter vous-même.
            </p>
            <p class="request-button font-size-default">
                <a class="coming-soon btn-1-m" href="/request">Soumettre une nouvelle fig</a>
            </p>
        </div>
    </div>

    <div id="right-lateral-panel" class="right-lateral-panel">
        <a class="close" id="right-lateral-panel-close" href="#"></a>
        <h1>Recherche</h1>
        <form id="search" action="/search" method="post">
            <label>Type :</label>
            <div class="buttons-container font-size-zero">
                <button class="item-type-button btn-2-s font-size-default <?php if($item_type == "minifigure") echo 'active'; ?>" type="button">Minifigs</button>
                <button class="item-type-button btn-2-s font-size-default <?php if($item_type == "set") echo 'active'; ?>" type="button">Sets</button>
            </div>
            <input type="hidden" name="item_type" id="item-type-field" value="minifigure">
            <label>Gamme :</label>
            <select id="selected-range-alias" name="selected_range_alias">
                <option value="aucune"></option>
                <?php
                // On liste toutes les gammes
                foreach ($ranges as $range)
                {
                    $selected = ($range->alias == $selected_range_alias) ? 'selected="selected"' : '';
                    ?>
                    <option value="<?php echo $range->alias; ?>" <?php echo $selected; ?>><?php echo $range->name; ?></option>
                <?php
                } ?>
            </select>

            <?php
            // On liste toutes les séries comprises dans une gamme
            foreach ($ranges as $range)
            {
                // Si il y a des séries dans cette gamme, on les liste
                if(count($range->series > 0))
                { ?>
                    <label class="series-list <?php echo $range->alias; ?>" <?php if($selected_range_alias != $range->alias) echo 'style="display: none;"'; ?>>Série :</label>
                    <select id="series-list" class="<?php echo $range->alias; ?>" <?php if($selected_range_alias != $range->alias) echo 'style="display: none;"'; ?>>
                        <option></option>
                        <?php
                        foreach ($range->series as $serie)
                        {
                            $selected = ($serie->alias == $selected_serie_alias) ? 'selected="selected"' : '';
                            ?>
                            <option value="<?php echo $serie->alias; ?>" <?php echo $selected;?>><?php echo $serie->name; ?></option>
                        <?php
                        } ?>
                    </select>
                <?php
                }
            } ?>

            <label class="series-list none">Série :</label>


            <select id="series-list" class="none" <?php if(!empty($selected_range_alias)) echo 'style="display: none;"'; ?>>
                <option></option>
                <?php
                // On liste toutes les séries
                foreach ($ranges as $range)
                {
                    // Si il y a des séries dans cette gamme, on les liste
                    if(count($range->series > 0))
                    {
                        ?>

                        <?php
                        foreach ($range->series as $serie)
                        {
                            $selected = ($serie->alias == $selected_serie_alias) ? 'selected="selected"' : '';
                            ?>
                            <option value="<?php echo $serie->alias; ?>" <?php echo $selected;?>><?php echo $serie->name; ?></option>
                        <?php
                        } ?>
                    <?php
                    }
                } ?>
            </select>
            <input type="hidden" id="selected_serie_alias" name="selected_serie_alias" value="aucune">
            <input type="submit" id="search" class="btn-2-m" value="rechercher">
        </form>
    </div>
</div>