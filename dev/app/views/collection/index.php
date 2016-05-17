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
            <h1 class="dashed">Ma collection</h1>
        </div>
    </div>
    <div class="col-m-pd">
        <div class="collection-summary col-xs-12 font-size-zero">
            <div class="col-xs-12 col-s-4 col-pd">
                <div class="minifigures-count col-xs-6 col-s-12 col-pd col-s-no-pd font-size-default">
                    <span class="number"><?php echo $minifigures_count; ?> </span>figurine<?php echo ($minifigures_count == 1) ? '' : 's'; ?>
                </div>
                <div class="sets-count col-xs-6 col-s-12 col-pd col-s-no-pd font-size-default">
                    <span class="number"><?php echo $sets_count; ?> </span>set<?php echo ($sets_count == 1) ? '' : 's'; ?>
                </div>
            </div>
            <div class="resume col-xs-12 col-s-8 font-size-default">
                <?php if(count($ranges) > 0) { ?>
                    <div class="col-pd">
                        <div class="col-pd col-s-no-pd">
                            <h3>Répartition par gamme</h3>
                            <div class="chart-container">
                                <?php foreach($ranges as $range) { ?>
                                    <div class="chart-segment" data-width="<?php echo $range->percentage; ?>">
                                        <div class="chart-bar" style="background: <?php echo $range->color; ?>;"></div>
                                        <p class="chart-label" title="<?php echo $range->name; ?>"><?php echo $range->name; ?></p>
                                    </div>
                                <?php } ?>
                            </div>
                            <h3 class="offers-title">
                                <span class="title-label">
                                    Mes annonces en cours
                                </span>
                                <div class="action">
                                    <a href="/offers/add" class="link">
                                        <span class="icon-arrow"></span>
                                        Déposer une annonce
                                    </a>
                                </div>
                            </h3>
                        </div>
                    </div>
                    <div class="col-s-pd">
                        <?php if(!empty($offers_list)) { ?>
                            <table class="list offers-list">
                                <?php $i = 1; ?>
                                <?php foreach($offers_list as $offer) { ?>
                                    <tr <?php echo ($i%2 == 0) ? 'class="even"' : 'class="uneven"'; ?> onclick="document.location='<?php echo $offer->link; ?>'">
                                        <td class="thumbnail">
                                            <?php if($offer->picture != null) { ?>
                                                <img class="resize-to-container" src="/assets/img/offers/57x57/<?php echo $offer->picture; ?>">
                                            <?php } ?>
                                        </td>
                                        <td class="title">
                                            <a href="<?php echo $offer->link; ?>" title="Voir l'annonce &#147;<?php echo $offer->title; ?>&#148;">
                                                <span class="title-text"><?php echo $offer->title; ?></span>
                                                <span class="type">
                                                    <span class="type-text"><?php echo $offer->type; ?></span> - <i><?php echo $offer->remaining_days; ?> jour<?php if($offer->remaining_days > 1) { ?>s<?php } ?> restant<?php if($offer->remaining_days > 1) { ?>s<?php } ?></i>
                                                </span>
                                            </a>
                                        </td>
                                        <td class="price">
                                            <?php if($offer->price) { ?>
                                                <a href="<?php echo $offer->link; ?>" title="Voir l'annonce &#147;<?php echo $offer->title; ?>&#148;">
                                                    <?php echo $offer->price; ?> €
                                                </a>
                                            <?php } ?>
                                        </td>
                                        <td class="actions">
                                            <a href="<?php echo $offer->edit_link; ?>" title="Editer l'annonce &#147;<?php echo $offer->title; ?>&#148;">
                                                <span class="icon-pencil"></span>
                                            </a>
                                            <a class="delete-link" href="<?php echo $offer->delete_link; ?>" title="Supprimer l'annonce &#147;<?php echo $offer->title; ?>&#148;">
                                                <span class="icon-delete"></span>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php $i++; ?>
                                <?php } ?>
                            </table>
                        <?php } else { ?>
                            <p>Aucune annonce en cours.</p>
                        <?php } ?>
                    </div>
                <?php } else { ?>
                    <div class="col-pd">
                        <div class="col-pd col-s-no-pd">
                            <div class="empty-collection">
                                <p>Vous n'avez aucun élément dans votre collection. Il serait peut-être temps d'en ajouter.</p>
                                <h3><a href="/home">Commencez votre collection</a></h3>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php if(count($ranges) > 0) { ?>
        <div class="col-xs-12 col-pd">
            <div class="col-pd">
                <div class="horizontal-border"></div>
                <div class="buttons-container font-size-zero">
                    <button class="item-type-button btn-3-s font-size-default animated active" type="button" data-type="minifigures">Figurines</button>
                    <button class="item-type-button btn-3-s font-size-default animated" type="button" data-type="sets">Sets</button>
                </div>

                <div class="minifigures-list item-list shown">
                    <?php if($minifigures_count > 0) { ?>
                        <?php foreach($ranges as $range) { ?>
                            <?php if(isset($range->minifigures_count)) { ?>
                                <div class="range-container">
                                    <h1 class="range-title">
                                        <?php echo $range->name; ?>
                                        <span class="range-count">
                                            <?php echo $range->minifigures_count; ?> fig<?php echo ($range->minifigures_count == 1) ? '' : 's'; ?>
                                        </span>
                                    </h1>
                                    <?php foreach($range->series as $serie) { ?>
                                        <div class="serie-container">
                                            <?php if(isset($serie->minifigures_count)) { ?>
                                                <h3 class="serie-title line">
                                                    <span><?php echo $serie->name; ?></span>
                                                    <span class="serie-count">
                                                        <?php echo $serie->minifigures_count; ?> fig<?php echo ($serie->minifigures_count == 1) ? '' : 's'; ?>
                                                    </span>
                                                </h3>
                                                <div class="font-size-zero">
                                                    <?php foreach($serie->minifigures as $minifigure) { ?>
                                                        <div class="item-col font-size-default">
                                                            <div class="item-container">
                                                                <div class="item" data-id="<?php echo $minifigure->id; ?>">
                                                                    <div class="item-count">
                                                                        x<?php echo $minifigure->count; ?>
                                                                    </div>
                                                                    <div class="actions-container">
                                                                        <div class="wrapper">
                                                                            <a href="/offers/add?id=<?php echo $minifigure->id; ?>&type=minifigure">
                                                                                €
                                                                                <span class="label">Vendre</span>
                                                                            </a>
                                                                            <button type="button" class="edit-button">
                                                                                <span class="icon-pencil"></span>
                                                                                <span class="label">Modifier</span>
                                                                            </button>
                                                                            <button type="button" class="delete-button">
                                                                                <span class="icon-delete"></span>
                                                                                <span class="label">Supprimer</span>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                    <p class="name">- <?php echo $minifigure->id; ?> -<br><?php echo $minifigure->name; ?></p>
                                                                    <div class="transactions">
                                                                        <?php if($minifigure->sell_total != 0 || $minifigure->exchange_total != 0) { ?>
                                                                            <a href="/offers/listing?id=<?php echo $minifigure->id; ?>&type=minifigure" title="Voir les annonces pour &quot;<?php echo $minifigure->name; ?>&quot;">
                                                                                <?php } ?>
                                                                                <p>
                                                                                    <span class="number"><?php echo $minifigure->sell_total; ?></span>en vente
                                                                                    <br>
                                                                                    <?php if(!empty($minifigure->price) && $minifigure->price != 0) { ?>dès <?php echo str_replace(',00', '', str_replace('.', ',', $minifigure->price)) ?> €<?php } ?>
                                                                                </p>
                                                                                <p>
                                                                                    <span class="number"><?php echo $minifigure->exchange_total; ?></span>en<br>échange
                                                                                </p>
                                                                                <?php if($minifigure->sell_total != 0 || $minifigure->exchange_total != 0) { ?>
                                                                            </a>
                                                                        <?php } ?>
                                                                    </div>
                                                                    <div class="picture-container">
                                                                        <img class="picture resize-to-container" src="/assets/img/minifigures/209x238/<?php echo $minifigure->picture; ?>" alt="<?php echo $minifigure->name; ?>">
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
                                                                                    <input type="text" class="item-number-input" name="item_number" value="<?php echo $minifigure->count; ?>" readonly>
                                                                                    <input type="hidden" class="item-id-input" name="item_id" value="<?php echo $minifigure->id; ?>">
                                                                                    <input type="hidden" class="item-type-input" name="item_type" value="minifigure">
                                                                                </div>
                                                                                <div class="more-item-col">
                                                                                    <button type="button" class="more-item-button">
                                                                                        <span class="icon-add-alt"></span>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                            <button type="submit" class="edit-button btn-2-m">Modifier</button>
                                                                        </form>
                                                                    </div>
                                                                    <div class="item-confirmation-content hidden">
                                                                        <img src="/assets/img/success-02.png" alt="Succès">
                                                                        <h3>Modification effectuée !</h3>
                                                                    </div>
                                                                </div>

                                                                <div class="item-panel item-delete animated">
                                                                    <div class="item-delete-content">
                                                                        <h3 class="title">Supprimer cette figurine ?</h3>
                                                                        <form>
                                                                            <input type="hidden" class="item-id-input" name="item_id" value="<?php echo $minifigure->id; ?>">
                                                                            <input type="hidden" class="item-type-input" name="item_type" value="minifigure">
                                                                            <button type="submit" class="delete-button btn-2-m">Supprimer</button>
                                                                            <button type="button" class="cancel-button btn-2-m">Annuler</button>
                                                                        </form>
                                                                    </div>
                                                                    <div class="item-confirmation-content hidden">
                                                                        <img src="/assets/img/success-02.png" alt="Succès">
                                                                        <h3>Suppression effectuée !</h3>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    <?php } else { ?>
                        <p class="empty-item-list">Votre collection ne contient aucune figurine.</p>
                    <?php } ?>
                </div>
                <div class="sets-list item-list">
                    <?php if($sets_count > 0) { ?>
                        <?php foreach($ranges as $range) { ?>
                            <?php if(isset($range->sets_count)) { ?>
                                <div class="range-container">
                                    <h1 class="range-title">
                                        <?php echo $range->name; ?>
                                        <span class="range-count">
                                            <?php echo $range->sets_count; ?> set<?php echo ($range->sets_count == 1) ? '' : 's'; ?>
                                        </span>
                                    </h1>
                                    <?php foreach($range->series as $serie) { ?>
                                        <?php if(isset($serie->sets_count)) { ?>
                                            <div class="serie-container">
                                                <h3 class="serie-title line">
                                                    <span><?php echo $serie->name; ?></span>
                                                    <span class="serie-count">
                                                        <?php echo $serie->sets_count; ?> set<?php echo ($serie->sets_count == 1) ? '' : 's'; ?>
                                                    </span>
                                                </h3>
                                                <div class="font-size-zero">
                                                    <?php foreach($serie->sets as $set) { ?>
                                                        <div class="item-col font-size-default">
                                                            <div class="item-container">
                                                                <div class="item">
                                                                    <div class="item-count">
                                                                        x<?php echo $set->count; ?>
                                                                    </div>
                                                                    <div class="actions-container">
                                                                        <div class="wrapper">
                                                                            <a href="/offers/add?id=<?php echo $set->id; ?>&type=set">
                                                                                €
                                                                                <span class="label">Vendre</span>
                                                                            </a>
                                                                            <button type="button" class="edit-button">
                                                                                <span class="icon-pencil"></span>
                                                                                <span class="label">Modifier</span>
                                                                            </button>
                                                                            <button type="button" class="delete-button">
                                                                                <span class="icon-delete"></span>
                                                                                <span class="label">Supprimer</span>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                    <p class="name">- <?php echo $set->number; ?> -<br><?php echo $set->name; ?></p>
                                                                    <div class="transactions">
                                                                        <?php if($set->sell_total != 0 || $set->exchange_total != 0) { ?>
                                                                            <a href="/offers/listing?id=<?php echo $set->id; ?>&type=set" title="Voir les annonces pour &quot;<?php echo $set->name; ?>&quot;">
                                                                                <?php } ?>
                                                                                <p>
                                                                                    <span class="number"><?php echo $set->sell_total; ?></span>en vente
                                                                                    <br>
                                                                                    <?php if(!empty($set->price) && $set->price != 0) { ?>dès <?php echo str_replace(',00', '', str_replace('.', ',', $set->price)) ?> €<?php } ?>
                                                                                </p>
                                                                                <p>
                                                                                    <span class="number"><?php echo $set->exchange_total; ?></span>en<br>échange
                                                                                </p>
                                                                                <?php if($set->sell_total != 0 || $set->exchange_total != 0) { ?>
                                                                            </a>
                                                                        <?php } ?>
                                                                    </div>
                                                                    <div class="picture-container">
                                                                        <img class="picture resize-to-container" src="/assets/img/sets/209x238/<?php echo $set->picture; ?>" alt="<?php echo $set->name; ?>">
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
                                                                                    <input type="text" class="item-number-input" name="item_number" value="<?php echo $set->count; ?>" readonly>
                                                                                    <input type="hidden" class="item-id-input" name="item_id" value="<?php echo $set->id; ?>">
                                                                                    <input type="hidden" class="item-type-input" name="item_type" value="set">
                                                                                </div>
                                                                                <div class="more-item-col">
                                                                                    <button type="button" class="more-item-button">
                                                                                        <span class="icon-add-alt"></span>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                            <button type="submit" class="edit-button btn-2-m">Modifier</button>
                                                                        </form>
                                                                    </div>
                                                                    <div class="item-confirmation-content hidden">
                                                                        <img src="/assets/img/success-02.png" alt="Succès">
                                                                        <h3>Modification effectuée !</h3>
                                                                    </div>
                                                                </div>

                                                                <div class="item-panel item-delete animated">
                                                                    <div class="item-delete-content">
                                                                        <h3 class="title">Supprimer ce set ?</h3>
                                                                        <form>
                                                                            <input type="hidden" class="item-id-input" name="item_id" value="<?php echo $set->id; ?>">
                                                                            <input type="hidden" class="item-type-input" name="item_type" value="set">
                                                                            <button type="submit" class="delete-button btn-2-m">Supprimer</button>
                                                                            <button type="button" class="cancel-button btn-2-m">Annuler</button>
                                                                        </form>
                                                                    </div>
                                                                    <div class="item-confirmation-content hidden">
                                                                        <img src="/assets/img/success-02.png" alt="Succès">
                                                                        <h3>Suppression effectuée !</h3>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    <?php } else { ?>
                        <p class="empty-item-list">Votre collection ne contient aucun set.</p>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
<div class="delete-dialog dialog">
    <div class="header">
        <a href="#" class="close" title="Fermer"></a>
    </div>
    <div class="content">
        <h2>Etes-vous sûr de vouloir supprimer l'annonce <br>suivante ?</h2>
        <table>
            <tr>
                <th>Titre</th>
                <td class="title">Titre</td>
            </tr>
            <tr>
                <th>Type</th>
                <td class="type">Type</td>
            </tr>
        </table>
    </div>
    <div class="dialog-buttons">
        <table>
            <tr>
                <td>
                    <a class="cancel" href="#">Annuler</a>
                </td>
                <td>
                    <a class="delete" href="#">Supprimer</a>
                </td>
            </tr>
        </table>
    </div>
</div>