<div id="content" class="content">
    <div class="col-pd">
        <div class="col-xs-12 col-pd">
            <h1 class="margin-bottom-zero">Annonces</h1>
            <?php if(array_key_exists('success', $_SESSION)) { ?>
                <p class="success">
                    <?php echo $_SESSION['success']; ?>
                </p>
            <?php } elseif(array_key_exists('error', $_SESSION)) { ?>
                <p class="error">
                    <?php echo $_SESSION['error']; ?>
                </p>
            <?php } ?>
            <ul class="links">
                <li class="total">
                    <span class="number"><?php echo $offers_number; ?></span> annonce<?php echo ($offers_number == 1) ? '' : 's'; ?>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-s-pd">
        <div class="col-xs-12 col-s-pd">
            <?php if($pages_number > 1) { ?>
                <p class="pagination">
                    <?php for($i = 1; $i <= $pages_number; $i++) { ?>
                        <a href="/admin/offers/listing<?php if($i != 1) { ?>?p=<?php echo $i; ?><?php } ?>"<?php echo ($i == $current_page) ? ' class="current"' : ''; ?>><?php echo $i; ?></a>
                    <?php } ?>
                </p>
            <?php } ?>
            <table class="list">
                <tr>
                    <th class="thumbnail"></th>
                    <th class="title">Titre</th>
                    <th class="author">Déposée par</th>
                    <th class="type">Type</th>
                    <th class="status">Statut</th>
                    <th class="action"></th>
                    <th class="action"></th>
                </tr>
                <?php $i = 1; ?>
                <?php foreach ($offers_list as $offer) { ?>
                    <tr <?php echo ($i%2 == 0) ? 'class="even"' : 'class="uneven"'; ?>>
                        <td class="thumbnail">
                            <?php if($offer->picture != null) { ?>
                                <img class="resize-to-container" src="/assets/img/offers/57x57/<?php echo $offer->picture; ?>">
                            <?php } ?>
                        </td>
                        <td class="title"><?php echo $offer->title; ?></td>
                        <td class="author"><?php echo $offer->member_firstname; ?> <?php echo $offer->member_lastname; ?></td>
                        <td class="type"><?php echo $offer->type; ?></td>
                        <td class="status"><?php echo ($offer->active) ? 'En cours' : 'Terminé'; ?></td>
                        <td class="action">
                            <a href="/admin/offers/edit?id=<?php echo $offer->id; ?>">
                                <span class="edit"></span>
                            </a>
                        </td>
                        <td class="action">
                            <a class="delete-link" href="/admin/offers/delete?id=<?php echo $offer->id; ?>">
                                <span class="delete"></span>
                            </a>
                        </td>
                    </tr>
                    <?php $i++; ?>
                <?php } ?>

                <?php if($pages_number > 1) { ?>
                    <p class="pagination bottom-pagination">
                        <?php for($i = 1; $i <= $pages_number; $i++) { ?>
                            <a href="/admin/offers/listing<?php if($i != 1) { ?>?p=<?php echo $i; ?><?php } ?>"<?php echo ($i == $current_page) ? ' class="current"' : ''; ?>><?php echo $i; ?></a>
                        <?php } ?>
                    </p>
                <?php } ?>
            </table>
        </div>
    </div>
</div>

<div class="delete-dialog dialog">
    <div class="header">
        <a href="#" class="close" title="Fermer"></a>
    </div>
    <div class="content">
        <h2 class="center">Etes-vous sûr de vouloir supprimer l'annonce<br>suivante ?</h2>
        <table>
            <tr>
                <th>Titre</th>
                <td class="offer-title">Titre</td>
            </tr>
            <tr>
                <th>Déposée par</th>
                <td class="offer-author">Déposée par</td>
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