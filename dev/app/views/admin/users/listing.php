<div id="content" class="content">
    <div class="col-pd">
        <div class="col-xs-12 col-pd">
            <h1 class="margin-bottom-zero">Utilisateurs</h1>
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
                <?php $plural = ($users_number == 1) ? '' : 's'; ?>
                <li class="total">
                    <span class="number"><?php echo $users_number; ?></span> utilisateur<?php echo $plural; ?>
                </li>
                <li>
                    <a href="/admin/users/add">
                        <span class="user-add"></span>
                        Nouvel utilisateur
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
                        <a href="/admin/users/listing<?php if($i != 1) { ?>?p=<?php echo $i; ?><?php } ?>"<?php echo ($i == $current_page) ? ' class="current"' : ''; ?>><?php echo $i; ?></a>
                    <?php } ?>
                </p>
            <?php } ?>
            <table class="list">
                <tr>
                    <th class="name">Nom / Prénom</th>
                    <th class="email">E-mail</th>
                    <th class="action"></th>
                    <th class="action"></th>
                </tr>
                <?php
                $i = 1;
                foreach ($users_list as $user)
                {
                    $plural = ($i%2 == 0) ? 'even' : 'uneven';
                    ?>
                    <tr class="<?php echo $plural; ?>">
                        <td class="name"><?php echo $user->lastname . ' ' . $user->firstname; ?></td>
                        <td class="email"><?php echo $user->email; ?></td>
                        <td class="action">
                            <a href="/admin/users/edit?id=<?php echo $user->id; ?>">
                                <span class="edit"></span>
                            </a>
                        </td>
                        <td class="action">
                            <?php if(!$user->is_admin) { ?>
                                <a class="delete-link" href="/admin/users/delete?id=<?php echo $user->id; ?>">
                                    <span class="delete"></span>
                                </a>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php
                    $i++;
                } ?>
            </table>
            <?php if($pages_number > 1) { ?>
                <p class="pagination bottom-pagination">
                    <?php for($i = 1; $i <= $pages_number; $i++) { ?>
                        <a href="/admin/users/listing<?php if($i != 1) { ?>?p=<?php echo $i; ?><?php } ?>"<?php echo ($i == $current_page) ? ' class="current"' : ''; ?>><?php echo $i; ?></a>
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
        <h2 class="center">Etes-vous sûr de vouloir supprimer l’utilisateur<br>suivant ?</h2>
        <table>
            <tr>
                <th>Nom / Prénom</th>
                <td class="name">Nom Prénom</td>
            </tr>
            <tr>
                <th>E-mail</th>
                <td class="email">Adresse e-mail</td>
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