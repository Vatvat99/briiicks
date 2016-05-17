<div id="content" class="content">
    <div class="col-pd">
        <div class="col-xs-12 col-pd">
            <h1 class="margin-bottom-zero">Gammes</h1>
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
                <li class="total"><span class="number"><?php echo $ranges_number; ?></span> gamme<?php echo ($ranges_number == 1) ? '' : 's'; ?></li>
                <li>
                    <a href="/admin/ranges/add">
                        <span class="minifigurine-add"></span>
                        Nouvelle gamme
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
                        <a href="/admin/ranges/listing<?php if($i != 1) { ?>?p=<?php echo $i; ?><?php } ?>"<?php echo ($i == $current_page) ? ' class="current"' : ''; ?>><?php echo $i; ?></a>
                    <?php } ?>
                </p>
            <?php } ?>
            <table class="list">
                <tr>
                    <th class="thumbnail"></th>
                    <th class="range-name">Nom</th>
                    <th class="action"></th>
                    <th class="action"></th>
                </tr>
                <?php
                $i = 1;
                foreach ($ranges_list as $range)
                { ?>
                    <tr <?php echo ($i%2 == 0) ? 'class="even"' : 'class="uneven"'; ?>>
                        <td class="thumbnail">
                            <?php if($range->picture != null) { ?>
                                <img class="resize-to-container" src="/assets/img/ranges/<?php echo $range->picture; ?>">
                            <?php } ?>
                        </td>
                        <td class="range-name"><?php echo $range->name; ?></td>
                        <td class="action">
                            <a href="/admin/ranges/edit?id=<?php echo $range->id; ?>">
                                <span class="edit"></span>
                            </a>
                        </td>
                        <td class="action">
                            <a class="delete-link" href="/admin/ranges/delete?id=<?php echo $range->id; ?>">
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
                        <a href="/admin/ranges/listing<?php if($i != 1) { ?>?p=<?php echo $i; ?><?php } ?>"<?php echo ($i == $current_page) ? ' class="current"' : ''; ?>><?php echo $i; ?></a>
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
        <h2 class="center">Etes-vous sûr de vouloir supprimer la gamme<br>suivante ?</h2>
        <table>
            <tr>
                <th>Nom</th>
                <td class="range-name">Nom</td>
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