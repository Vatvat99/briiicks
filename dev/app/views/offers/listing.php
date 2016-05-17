<?php echo '<pre>'; ?>
<?php var_dump($offers_list); ?>
    <?php echo '</pre>'; ?>

<div id="content" class="content">
    <div class="col-pd">
        <div class="col-xs-12 col-pd">
            <?php if(!isset($item)) { ?>
                <h1 class="main-title dashed">
                    Toutes les annonces
                    <span class="count">
                        <?php echo $offers_number; ?> annonce<?php echo ($offers_number == 1) ? '' : 's'; ?>
                    </span>
                </h1>
            <?php } else { ?>
                <div class="page-header">
                    <div class="page-header-picture">
                        <div class="picture-container">
                            <img class="resize-to-container" src="/assets/img/<?php echo $item->type; ?>s/57x57/<?php echo $item->picture; ?>">
                        </div>
                    </div>
                    <div class="page-header-title">
                        <h2 class="title">
                            <?php echo $item->name; ?>
                        </h2>
                        <p class="subtitle"><?php echo $item->range_name; ?> - <?php echo $item->serie_name; ?></p>
                    </div>
                    <div class="page-header-info">
                        <span class="count">
                            <?php echo $offers_number; ?> annonce<?php echo ($offers_number == 1) ? '' : 's'; ?>
                        </span>
                    </div>
                </div>
            <?php } ?>
            <!-- fin si -->
        </div>
    </div>
    <div class="col-s-pd">
        <div class="col-xs-12 col-s-pd">
            <?php if(!empty($offers_list)) { ?>
                <table class="list offers-list">
                    <?php $i = 1; ?>
                    <?php foreach ($offers_list as $offer) { ?>
                        <tr <?php echo ($i%2 == 0) ? 'class="even"' : 'class="uneven"'; ?> onclick="document.location='<?php echo $offer->link; ?>'">
                            <td class="thumbnail">
                                <?php if($offer->picture != null) { ?>
                                    <img class="resize-to-container" src="/assets/img/offers/57x57/<?php echo $offer->picture; ?>">
                                <?php } ?>
                            </td>
                            <td class="date">
                                <a href="<?php echo $offer->link; ?>" title="Voir l'annonce &#147;<?php echo $offer->title; ?>&#148;">
                                    <?php echo $offer->date; ?><br>
                                    <?php echo $offer->time; ?>
                                </a>
                            </td>
                            <td class="title">
                                <a href="<?php echo $offer->link; ?>" title="Voir l'annonce &#147;<?php echo $offer->title; ?>&#148;">
                                    <?php echo $offer->title; ?>
                                    <span class="type">
                                        <?php echo $offer->type; ?>
                                    </span>
                                </a>
                            </td>
                            <td class="type">
                                <a href="<?php echo $offer->link; ?>" title="Voir l'annonce &#147;<?php echo $offer->title; ?>&#148;">
                                    <?php echo $offer->type; ?>
                                </a>
                            </td>
                            <td class="author">
                                <a href="<?php echo $offer->link; ?>" title="Voir l'annonce &#147;<?php echo $offer->title; ?>&#148;">
                                    <strong>déposée par :</strong> <?php echo $offer->author; ?><br>
                                    <?php if(!empty($offer->member_city) || !empty($offer->member_region)) { ?>
                                        (<?php echo $offer->member_city; ?><?php if(!empty($offer->member_city) && !empty($offer->member_region)) { ?>, <?php } ?><?php echo $offer->member_region; ?>)
                                    <?php } ?>
                                </a>
                            </td>
                            <td class="price">
                                <a href="<?php echo $offer->link; ?>" title="Voir l'annonce &#147;<?php echo $offer->title; ?>&#148;">
                                    <?php echo $offer->price; ?> €
                                </a>
                            </td>
                        </tr>
                        <?php $i++; ?>
                    <?php } ?>
                </table>
            <?php } else { ?>
                <p>Aucune annonce en cours, revenez plus tard.</p>
            <?php } ?>
            <?php if($pages_number > 1) { ?>
                <p class="pagination bottom-pagination">
                    <?php for($i = 1; $i <= $pages_number; $i++) { ?>
                        <a href="/admin/offers/listing<?php if($i != 1) { ?>?p=<?php echo $i; ?><?php } ?>"<?php echo ($i == $current_page) ? ' class="current"' : ''; ?>><?php echo $i; ?></a>
                    <?php } ?>
                </p>
            <?php } ?>
        </div>
    </div>
</div>
