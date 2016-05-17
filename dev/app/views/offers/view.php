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
    </div>

    <div class="col-s-pd font-size-zero">
        <div class="slider-container col-xs-12 col-s-5 col-s-pd">
            <div class="slider-for big-picture-container">
                <?php foreach($offer->pictures as $picture) { ?>
                    <div>
                        <img class="center-in-container" src="/assets/img/offers/400x300/<?php echo $picture->filename; ?>">
                    </div>
                <?php } ?>
            </div>
            <?php if(!empty($offer->pictures)) { ?>
                <div class="slider-navigation little-picture-container">
                    <?php foreach($offer->pictures as $picture) { ?>
                        <div>
                            <img class="resize-to-container" src="/assets/img/offers/57x57/<?php echo $picture->filename; ?>">
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
        <div class="col-xs-12 col-s-7 font-size-default">
            <div class="col-pd">
                <div class="col-pd col-s-no-pd">
                    <div class="description">
                        <p class="label">Description :</p>
                        <p><?php echo $offer->description; ?></p>
                    </div>
                    <p class="label">Liste des figurines / sets :</p>
                </div>
            </div>
            <div class="col-s-pd">
                <table class="list offer-content">
                    <?php $i = 1; ?>
                    <?php foreach($offer->minifigures as $minifigure) { ?>
                        <tr <?php echo ($i%2 == 0) ? 'class="even"' : 'class="uneven"'; ?>>
                            <td class="thumbnail">
                                <img class="resize-to-container" src="/assets/img/minifigures/57x57/<?php echo $minifigure->picture; ?>">
                            </td>
                            <td class="info">
                                <span class="name"><?php echo $minifigure->name; ?></span>
                                <br>
                                <?php echo $minifigure->range_name; ?> - <?php echo $minifigure->serie_name; ?>
                            </td>
                            <td class="count">
                                <div class="count-container">
                                    x<?php echo $minifigure->count; ?>
                                </div>
                            </td>
                        </tr>
                        <?php $i++; ?>
                    <?php } ?>
                    <?php foreach($offer->sets as $set) { ?>
                        <tr <?php echo ($i%2 == 0) ? 'class="even"' : 'class="uneven"'; ?>>
                            <td class="thumbnail">
                                <img class="resize-to-container" src="/assets/img/sets/57x57/<?php echo $set->picture; ?>">
                            </td>
                            <td class="info">
                                <span class="name"><?php echo $set->name; ?> (<?php echo $set->number; ?>)</span>
                                <br>
                                <?php echo $set->range_name; ?> - <?php echo $set->serie_name; ?>
                            </td>
                            <td class="count">
                                <div class="count-container">
                                    x<?php echo $set->count; ?>
                                </div>
                            </td>
                        </tr>
                        <?php $i++; ?>
                    <?php } ?>
                </table>
            </div>
            <?php if(!isset($_SESSION['front']['id']) || (isset($_SESSION['front']['id']) && $_SESSION['front']['id'] != $offer->member_id)) { ?>
                <div class="col-pd">
                    <div class="member-info col-pd col-s-no-pd font-size-zero">
                        <div class="col-xs-12 col-s-6 font-size-default">
                            <p>
                                <strong>Membre :</strong> <?php echo $offer->author; ?>
                                <br>
                                <strong>Lieu :</strong>
                                <?php if(!empty($offer->member_city) || !empty($offer->member_region)) { ?>
                                    <?php echo $offer->member_city; ?><?php if(!empty($offer->member_city) && !empty($offer->member_region)) { ?>, <?php } ?><?php echo $offer->member_region; ?>
                                <?php } else { ?>
                                    inconnu
                                <?php } ?>
                            </p>
                        </div>
                        <div class="contact-button col-xs-12 col-s-6 font-size-default">
                            <a class="btn-1-s" href="/members/contact?member_id=<?php echo $offer->member_id; ?>">
                                <span class="icon-mail"></span>
                                <span class="text">Contacter le membre</span>
                            </a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="col-pd">
        <div class="col-xs-12 col-pd">
            <h3>Autres annonces de ce membre</h3>
        </div>
    </div>
    <div class="col-s-pd">
        <div class="col-xs-12 col-s-pd">
            <?php if(!empty($other_offers)) { ?>
                <table class="list offers-list">
                    <?php $i = 1; ?>
                    <?php foreach($other_offers as $other_offer) { ?>
                        <?php if($other_offer->id != $offer->id) { ?>
                            <tr <?php echo ($i%2 == 0) ? 'class="even"' : 'class="uneven"'; ?> onclick="document.location='<?php echo $other_offer->link; ?>'">
                                <td class="thumbnail">
                                    <?php if($other_offer->picture != null) { ?>
                                        <img class="resize-to-container" src="/assets/img/offers/57x57/<?php echo $other_offer->picture; ?>">
                                    <?php } ?>
                                </td>
                                <td class="date">
                                    <a href="<?php echo $other_offer->link; ?>" title="Voir l'annonce &#147;<?php echo $other_offer->title; ?>&#148;">
                                        <?php echo $other_offer->date; ?><br>
                                        <?php echo $other_offer->time; ?>
                                    </a>
                                </td>
                                <td class="title">
                                    <a href="<?php echo $other_offer->link; ?>" title="Voir l'annonce &#147;<?php echo $other_offer->title; ?>&#148;">
                                        <?php echo $other_offer->title; ?>
                                        <span class="type">
                                            <?php echo $other_offer->type; ?>
                                        </span>
                                    </a>
                                </td>
                                <td class="type">
                                    <a href="<?php echo $other_offer->link; ?>" title="Voir l'annonce &#147;<?php echo $other_offer->title; ?>&#148;">
                                        <?php echo $other_offer->type; ?>
                                    </a>
                                </td>
                                <td class="price">
                                    <?php if($other_offer->price != 0) { ?>
                                        <a href="<?php echo $other_offer->link; ?>" title="Voir l'annonce &#147;<?php echo $other_offer->title; ?>&#148;">
                                            <?php echo $other_offer->price; ?> €
                                        </a>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php $i++; ?>
                        <?php } ?>
                    <?php } ?>
                </table>
            <?php } else { ?>
                <p>Aucune autre annonce.</p>
            <?php } ?>
        </div>
    </div>
</div>