<div id="content" class="content">
    <div class="profile-header col-pd font-size-zero">
        <?php if(array_key_exists('success', $_SESSION)) { ?>
            <div class="col-pd font-size-default">
                <p class="success">
                    <?php echo $_SESSION['success']; ?>
                </p>
            </div>
        <?php } ?>
        <div class="col-xs-12 col-s-8 col-pd font-size-default">
            <div class="profile-header-top">
                <div class="member-picture">
                    <div class="picture-container">
                        <?php if($member->picture != '') { ?>
                            <img class="resize-to-container" src="/assets/img/members/<?php echo $member->picture; ?>">
                        <?php } else { ?>
                            <img src="/assets/img/members/no-picture.png">
                        <?php } ?>
                    </div>
                </div>
                <div class="profile-header-title">
                    <h1 class="title">
                        <?php echo ucfirst($member->firstname) . ' ' . ucfirst($member->lastname); ?>
                    </h1>
                    <p class="subtitle">
                        <?php echo $member->city; ?>
                        <?php if($member->city != '' && $member->region != '') { echo ', '; } ?>
                        <?php echo $member->region; ?>
                        <?php if($member->city != '' || $member->region != '') { echo '<br>'; } ?>
                        <?php echo $member->email; ?>
                    </p>
                </div>
            </div>
            <?php if($member->message != '') { ?>
                <p class="quote">
                    <?php echo $member->message; ?>
                </p>
            <?php } ?>
        </div>
        <div class="col-xs-12 col-s-4 col-pd font-size-default txt-right">
            <p>
                <a href="/members/contact/<?php echo $member->id; ?>" class="action-link">
                    <span class="text">Contacter ce membre</span>
                    <span class="icon-mail"></span>
                </a>
                <a href="#" class="coming-soon action-link">
                    <span class="text">Suivre ce membre</span>
                    <span class="icon-user"></span>
                </a>
            </p>
        </div>
    </div>
    <div class="col-pd">
        <div class="col-pd">
            <h1 class="dashed">Sa collection</h1>
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
                                Ses annonces en cours
                            </h3>
                        </div>
                    </div>
                    <div class="col-s-pd">
                        <table class="list offers-list">
                            <tr class="uneven">
                                <td class="thumbnail">
                                    <img class="resize-to-container" src="/assets/img/offers/57x57/1-picture-01.jpg">
                                </td>
                                <td class="date">
                                    hier<br>
                                    17h52
                                </td>
                                <td class="title">
                                    Le barbare
                                    <span class="type">
                                        Vente
                                    </span>
                                </td>
                                <td class="type">
                                    Vente
                                </td>
                                <td class="price">
                                    3 €
                                </td>
                            </tr>
                            <tr class="even">
                                <td class="thumbnail">
                                    <img class="resize-to-container" src="/assets/img/offers/57x57/1-picture-01.jpg">
                                </td>
                                <td class="date">
                                    1 septembre 2014<br>
                                    22h17
                                </td>
                                <td class="title">
                                    Le barbare - neuf sous blister
                                </td>
                                <td class="type">
                                    Echange
                                </td>
                                <td class="price"></td>
                            </tr>
                        </table>
                    </div>
                <?php } else { ?>
                    <div class="col-pd">
                        <div class="col-pd col-s-no-pd">
                            <div class="empty-collection">
                                <h3><?php echo ucfirst($member->firstname); ?> n'a aucun élément dans sa collection.<br>C'est triste, mais cela arrive.</h3>
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
                                <h3 class="range-title line">
                                    <span><?php echo $range->name; ?></span>
                                    <span class="range-count">
                                        <?php echo $range->minifigures_count; ?> fig<?php echo ($range->minifigures_count == 1) ? '' : 's'; ?>
                                    </span>
                                </h3>
                                <div class="font-size-zero">
                                    <?php foreach($range->series as $serie) { ?>
                                        <?php foreach($serie->minifigures as $minifigure) { ?>
                                            <div class="picture-container font-size-default">
                                                <img src="/assets/img/minifigures/57x57/<?php echo $minifigure->picture; ?>">
                                            </div>
                                        <?php } ?>
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
                                <h3 class="range-title line">
                                    <span><?php echo $range->name; ?></span>
                                    <span class="range-count">
                                        <?php echo $range->sets_count; ?> set<?php echo ($range->sets_count == 1) ? '' : 's'; ?>
                                    </span>
                                </h3>
                                <div class="font-size-zero">
                                    <?php foreach($range->series as $serie) { ?>
                                        <?php foreach($serie->sets as $set) { ?>
                                            <div class="picture-container font-size-default">
                                                <img src="/assets/img/sets/57x57/<?php echo $set->picture; ?>">
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