<div class="picture">
    <div class="search-container">
        <div class="search">
            <h1>
                Recherche
            </h1>
            <div class="buttons-container font-size-zero">
                <button class="item-type-button btn-2-s font-size-default active" type="button" data-type="minifigure">Minifigs</button>
                <button class="item-type-button btn-2-s font-size-default" type="button" data-type="set">Sets</button>
            </div>
            <form action="/search" method="post">
                <input type="hidden" name="item_type" class="item-type-field" value="minifigure">
                <div class="field">
                    <input type="text" id="range" name="range" value="Gamme" readonly>
                    <div class="range erase hidden"></div>
                </div>
                <div class="field">
                    <input type="text" id="serie" name="serie" value="Série" readonly>
                    <div class="serie erase hidden"></div>
                </div>
                <input type="hidden" id="selected-range-alias" name="selected_range_alias" value="none">
                <input type="hidden" id="selected-serie-alias" name="selected_serie_alias" value="none">
                <input type="submit" class="btn-2-m" value="rechercher">
            </form>
        </div>
        <div class="ranges-list" data-item-type="minifigure">
            <ul>
                <?php
                // On liste toutes les gammes
                foreach ($ranges as $range) {
                    if($range->minifigures_count > 0) { ?>
                        <li data-alias="<?php echo $range->alias; ?>"><img src="/assets/img/ranges/<?php echo $range->picture; ?>"><?php echo $range->name; ?></li>
                    <?php }
                } ?>
            </ul>
        </div>
        <div class="ranges-list" data-item-type="set">
            <ul>
                <?php
                // On liste toutes les gammes
                foreach ($ranges as $range) {
                    if($range->sets_count > 0) { ?>
                        <li data-alias="<?php echo $range->alias; ?>"><img src="/assets/img/ranges/<?php echo $range->picture; ?>"><?php echo $range->name; ?></li>
                    <?php }
                } ?>
            </ul>
        </div>

        <?php
        // On liste toutes les séries comprises dans une gamme
        foreach ($ranges as $range) {
            // Si il y a des séries dans cette gamme, on les liste
            if(array_key_exists(0, $range->series)) { ?>
                <div class="series-list" data-range="<?php echo $range->alias; ?>" data-item-type="minifigure">
                    <ul>
                        <?php foreach ($range->series as $serie) {
                            if($serie->minifigures_count > 0) { ?>
                                <li data-alias="<?php echo $serie->alias; ?>"><img src="/assets/img/series/<?php echo $serie->picture; ?>"><?php echo $serie->name; ?></li>
                            <?php }
                        } ?>
                    </ul>
                </div>
                <div class="series-list" data-range="<?php echo $range->alias; ?>" data-item-type="set">
                    <ul>
                        <?php foreach ($range->series as $serie) {
                            if($serie->sets_count > 0) { ?>
                                <li data-alias="<?php echo $serie->alias; ?>"><img src="/assets/img/series/<?php echo $serie->picture; ?>"><?php echo $serie->name; ?></li>
                            <?php }
                        } ?>
                    </ul>
                </div>
            <?php }
        } ?>

        <div class="series-list" data-range="none" data-item-type="minifigure">
            <ul>
                <?php
                // On liste toutes les séries de toutes les gammes
                foreach ($ranges as $range) {
                    // Si il y a des séries dans cette gamme, on les liste
                    if(array_key_exists(0, $range->series)) {
                        foreach ($range->series as $serie) {
                            if($serie->minifigures_count > 0) { ?>
                                <li data-alias="<?php echo $serie->alias; ?>"><img src="/assets/img/series/<?php echo $serie->picture; ?>"><?php echo $serie->name; ?></li>
                            <?php }
                        }
                    }
                } ?>
            </ul>
        </div>
        <div class="series-list" data-range="none" data-item-type="set">
            <ul>
                <?php
                // On liste toutes les séries de toutes les gammes
                foreach ($ranges as $range) {
                    // Si il y a des séries dans cette gamme, on les liste
                    if(array_key_exists(0, $range->series)) {
                        foreach ($range->series as $serie) {
                            if($serie->sets_count > 0) { ?>
                                <li data-alias="<?php echo $serie->alias; ?>"><img src="/assets/img/series/<?php echo $serie->picture; ?>"><?php echo $serie->name; ?></li>
                            <?php }
                        }
                    }
                } ?>
            </ul>
        </div>
    </div>
</div>
<div class="content">
    <div class="col-pd font-size-zero">
        <div class="argument col-xs-12 col-s-4 col-pd font-size-default">
            <p>
                <img src="/assets/img/round-01.png" alt="">
            </p>
            <p>
                <strong>Gérez votre collection</strong><br>Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis bibendum auctor, nisi elit consequat ipsum, nec sagittis sem nibh id elit.
            </p>
        </div>
        <div class="argument col-xs-12 col-s-4 col-pd font-size-default">
            <p>
                <img src="/assets/img/round-01.png" alt="">
            </p>
            <p>
                <strong>Echange / achat / vente facile</strong><br>Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis bibendum auctor, nisi elit consequat ipsum, nec sagittis sem nibh id elit.
            </p>
        </div>
        <div class="argument col-xs-12 col-s-4 col-pd font-size-default">
            <p>
                <img src="/assets/img/round-01.png" alt="">
            </p>
            <p>
                <strong>Ne manquez aucune nouveauté</strong><br>Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis bibendum auctor, nisi elit consequat ipsum, nec sagittis sem nibh id elit.
            </p>
        </div>
    </div>
</div>