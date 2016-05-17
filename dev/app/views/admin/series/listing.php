<div id="content" class="content">
    <div class="col-pd">
        <div class="col-xs-12 col-pd">
            <h1 class="margin-bottom-zero">Séries</h1>
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
                    <span class="number"><?php echo $series_number; ?></span> série<?php echo ($series_number == 1) ? '' : 's'; ?>
                </li>
                <li>
                    <a href="/admin/series/add">
                        <span class="serie-add"></span>
                        Nouvelle série
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
                        <a href="/admin/series/listing<?php if($i != 1) { ?>?p=<?php echo $i; ?><?php } ?>"<?php echo ($i == $current_page) ? ' class="current"' : ''; ?>><?php echo $i; ?></a>
                    <?php } ?>
                </p>
            <?php } ?>
            <table class="list">
                <tr>
                    <th class="thumbnail"></th>
                    <th class="serie-name">Nom</th>
                    <th class="range-name">Appartient à la gamme</th>
                    <th class="action"></th>
                    <th class="action"></th>
                </tr>
                <?php
                $i = 1;
                foreach ($series_list as $serie)
                { ?>
                    <tr <?php echo ($i%2 == 0) ? 'class="even"' : 'class="uneven"'; ?>>
                        <td class="thumbnail">
                            <?php if($serie->picture != null) { ?>
                                <img class="resize-to-container" src="/assets/img/series/<?php echo $serie->picture; ?>">
                            <?php } ?>
                        </td>
                        <td class="serie-name"><?php echo $serie->name; ?></td>
                        <td class="range-name"><?php echo $serie->range_name; ?></td>
                        <td class="action">
                            <a href="/admin/series/edit?id=<?php echo $serie->id; ?>">
                                <span class="edit"></span>
                            </a>
                        </td>
                        <td class="action">
                            <a class="delete-link" href="/admin/series/delete?id=<?php echo $serie->id; ?>">
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
                        <a href="/admin/series/listing<?php if($i != 1) { ?>?p=<?php echo $i; ?><?php } ?>"<?php echo ($i == $current_page) ? ' class="current"' : ''; ?>><?php echo $i; ?></a>
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
        <h2 class="center">Etes-vous sûr de vouloir supprimer la série<br>suivante ?</h2>
        <table>
            <tr>
                <th>Nom</th>
                <td class="serie-name">Nom</td>
            </tr>
            <tr>
                <th>Gamme</th>
                <td class="range-name">Gamme</td>
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