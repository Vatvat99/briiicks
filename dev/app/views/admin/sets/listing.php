<div id="content" class="content">
    <div class="col-pd">
        <div class="col-xs-12 col-pd">
            <h1 class="margin-bottom-zero">Sets</h1>
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
                    <span class="number"><?php echo $sets_number; ?></span> set<?php echo ($sets_number == 1) ? '' : 's'; ?>
                </li>
                <li>
                    <a href="/admin/sets/add">
                        <span class="set-add"></span>
                        Nouveau set
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-s-pd">
        <div class="col-xs-12 col-s-pd">
            <?php if($pages_number > 1) { ?>
                <p class="pagination">
                    <?php for($i = 1; $i <= $pages_number; $i++) { ?>
                        <a href="/admin/sets/listing<?php if($i != 1) { ?>?p=<?php echo $i; ?><?php } ?>"<?php echo ($i == $current_page) ? ' class="current"' : ''; ?>><?php echo $i; ?></a>
                    <?php } ?>
                </p>
            <?php } ?>
            <table class="list">
                <tr>
                    <th class="thumbnail"></th>
                    <th class="name">Nom</th>
                    <th class="number">Numéro</th>
                    <th class="release-year">Année</th>
                    <th class="price">Prix</th>
                    <th class="action"></th>
                    <th class="action"></th>
                </tr>
                <?php
                $i = 1;
                foreach ($sets_list as $set)
                {
                    $price = str_replace('.', ',', $set->price);
                    ?>
                    <tr <?php echo ($i%2 == 0) ? 'class="even"' : 'class="uneven"'; ?>>
                        <td class="thumbnail">
                            <?php if($set->picture != null)
                            { ?>
                                <img class="resize-to-container" src="/assets/img/sets/57x57/<?php echo $set->picture; ?>">
                            <?php
                            } ?>
                        </td>
                        <td class="name"><?php echo $set->name; ?></td>
                        <td class="number"><?php echo $set->number; ?></td>
                        <td class="release-year"><?php echo $set->release_year; ?></td>
                        <td class="price"><?php echo $price ?> €</td>
                        <td class="action">
                            <a href="/admin/sets/edit?id=<?php echo $set->id; ?>">
                                <span class="edit"></span>
                            </a>
                        </td>
                        <td class="action">
                            <a class="delete-link" href="/admin/sets/delete?id=<?php echo $set->id; ?>">
                                <span class="delete"></span>
                            </a>
                        </td>
                    </tr>
                    <?php
                    $i++;
                } ?>
            </table>
            <?php if($pages_number > 1) { ?>
                <p class="pagination bottom-pagination">
                    <?php for($i = 1; $i <= $pages_number; $i++) { ?>
                        <a href="/admin/sets/listing<?php if($i != 1) { ?>?p=<?php echo $i; ?><?php } ?>"<?php echo ($i == $current_page) ? ' class="current"' : ''; ?>><?php echo $i; ?></a>
                    <?php } ?>
                </p>
            <?php } ?>
        </div>
    </div>
</div>

<div class="delete-dialog dialog">
    <div class="header">
        <a href="#" class="close" title="Fermer"></a>
    </div>
    <div class="content">
        <h2 class="center">Etes-vous sûr de vouloir supprimer le set <br>suivant ?</h2>
        <table>
            <tr>
                <th>Nom</th>
                <td class="name">Nom</td>
            </tr>
            <tr>
                <th>Numéro</th>
                <td class="number">Numéro</td>
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