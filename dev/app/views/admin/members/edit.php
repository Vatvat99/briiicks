<div id="content" class="content">
    <div class="col-pd">
        <div class="col-xs-12 col-pd">
            <h1>Modifier le membre</h1>
            <?php if(array_key_exists('success', $_SESSION)) { ?>
                <p class="success">
                    <?php echo $_SESSION['success']; ?>
                </p>
            <?php } elseif(array_key_exists('error', $_SESSION)) { ?>
                <p class="error">
                    <?php echo $_SESSION['error']; ?>
                </p>
            <?php } ?>
        </div>
        <form action="/admin/members/edit?id=<?php if(isset($_GET['id'])) echo $_GET['id']; ?>" method="post" enctype="multipart/form-data">
            <div class="row font-size-zero">
                <div class="col-xs-12 col-s-6 col-m-4 col-pd font-size-default">
                    <input type="hidden" id="id" name="id" value="<?php if(isset($_GET['id'])) echo $_GET['id']; ?>">
                    <label for="firstname">
                        Prénom *
                        <?php if(array_key_exists('firstname', $errors)) echo '<br><span class="error">' . $errors['firstname'] . '</span>'; ?>
                    </label>
                    <input type="text" id="firstname" name="firstname" value="<?php if(isset($_POST['firstname'])) echo $_POST['firstname']; elseif(isset($member->firstname)) echo $member->firstname; ?>" tabindex="1">
                    <label for="lastname">
                        Nom *
                        <?php if(array_key_exists('lastname', $errors)) echo '<br><span class="error">' . $errors['lastname'] . '</span>'; ?>
                    </label>
                    <input type="text" id="lastname" name="lastname" value="<?php if(isset($_POST['lastname'])) echo $_POST['lastname']; elseif(isset($member->lastname)) echo $member->lastname; ?>" tabindex="2">
                    <label for="email">
                        E-mail *
                        <?php if(array_key_exists('email', $errors)) echo '<br><span class="error">' . $errors['email'] . '</span>'; ?>
                    </label>
                    <input type="text" id="email" name="email" value="<?php if(isset($_POST['email'])) echo $_POST['email']; elseif(isset($member->email)) echo $member->email; ?>" tabindex="3">
                    <label for="password">
                        Nouveau mot de passe
                        <?php if(array_key_exists('password', $errors)) echo '<br><span class="error">' . $errors['password'] . '</span>'; ?>
                    </label>
                    <input type="password" id="password" name="password" tabindex="4" value="<?php if(isset($_POST['password'])) echo $_POST['password']; ?>">
                    <label for="password-confirmation">
                        Confirmer le nouveau mot de passe
                        <?php if(array_key_exists('password_confirmation', $errors)) echo '<br><span class="error">' . $errors['password_confirmation'] . '</span>'; ?>
                    </label>
                    <input type="password" id="password-confirmation" name="password_confirmation" tabindex="5" value="<?php if(isset($_POST['password_confirmation'])) echo $_POST['password_confirmation']; ?>">
                </div>
                <div class="col-xs-12 col-s-6 col-m-4 col-pd font-size-default">
                    <label>Photo actuelle</label>
                    <div class="current-picture-container <?php echo ((isset($_POST['current_picture']) && $_POST['current_picture'] == '') || (isset($member->picture) && $member->picture == '')) ? 'no-picture' : ''; ?>">
                        <?php
                        if(isset($_POST['current_picture']) && $_POST['current_picture'] != '')
                        { ?>
                            <img class="resize-to-container" src="/assets/img/members/<?php echo $_POST['current_picture']; ?>">
                            <input type="hidden" id="current-picture" name="current_picture" value="<?php echo $_POST['current_picture']; ?>">
                        <?php
                        }
                        elseif(isset($member->picture) && $member->picture != '')
                        { ?>
                            <img class="resize-to-container" src="/assets/img/members/<?php echo $member->picture; ?>">
                            <input type="hidden" id="current-picture" name="current_picture" value="<?php echo $member->picture; ?>">
                        <?php
                        } ?>

                    </div>
                    <?php
                    if(isset($member->picture) && $member->picture != '')
                    { ?>
                        <input type="checkbox" class="delete-picture" id="delete-picture" name="delete_picture">
                        <label class="delete-picture" for="delete-picture">supprimer</label>
                    <?php
                    } ?>

                    <label for="profile-picture">
                        Nouvelle photo
                        <?php if(array_key_exists('profile_picture', $errors)) echo '<br><span class="error">' . $errors['profile_picture'] . '</span>'; ?>
                    </label>
                    <div class="input-file-container">
                        <input class="profile-picture" id="profile-picture" name="profile_picture" type="file">
                        <input type="text" class="input-file-return" readonly>
                        <button type="button" class="btn-1-s input-file-trigger" tabindex="6">Parcourir</button>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-m-8 col-pd">
                <p class="caption">* Champs obligatoires</p>
                <div class="buttons">
                    <a href="/admin/members/listing" tabindex="7"><span class="cancel"></span>Annuler</a>
                    <button type="submit" class="btn-1-m" tabindex="8">
                        <span class="check"></span>
                        Valider
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>