<div id="content" class="content">
    <div class="col-pd">
        <div class="col-xs-12 col-pd">
            <h1 class="dashed">
                Editer mon profil
            </h1>
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
        <form method="post" enctype="multipart/form-data">
            <div class="font-size-zero">
                <div class="col-xs-12 col-s-8">
                    <div class="col-xs-12 col-s-6 col-pd font-size-default">
                        <label for="firstname">
                            Prénom : *
                            <?php if(array_key_exists('firstname', $errors)) { ?>
                                <br><span class="error"><?php echo $errors['firstname']; ?></span>
                            <?php } ?>
                        </label>
                        <input type="text" id="firstname" name="firstname" value="<?php if(isset($_POST['firstname'])) echo $_POST['firstname']; elseif(isset($member->firstname)) echo $member->firstname; ?>" tabindex="1">

                        <label for="lastname">
                            Nom : *
                            <?php if(array_key_exists('lastname', $errors)) { ?>
                                <br><span class="error"><?php echo $errors['lastname']; ?></span>
                            <?php } ?>
                        </label>
                        <input type="text" id="lastname" name="lastname" value="<?php if(isset($_POST['lastname'])) echo $_POST['lastname']; elseif(isset($member->lastname)) echo $member->lastname; ?>" tabindex="2">

                        <label for="email">
                            E-mail : *
                            <?php if(array_key_exists('email', $errors)) { ?>
                                <br><span class="error"><?php echo $errors['email']; ?></span>
                            <?php } ?>
                        </label>
                        <input type="text" id="email" name="email" value="<?php if(isset($_POST['email'])) echo $_POST['email']; elseif(isset($member->email)) echo $member->email; ?>" tabindex="3">
                    </div>
                    <div class="col-xs-12 col-s-6 col-pd font-size-default">
                        <label for="city">
                            Ville :
                        </label>
                        <input type="text" id="city" name="city" value="<?php if(isset($_POST['city'])) echo $_POST['city']; elseif(isset($member->city)) echo $member->city; ?>" tabindex="4">

                        <label for="region">
                            Région :
                        </label>
                        <?php
                        if(isset($regions) && $regions != '')
                        { ?>
                            <select id="region" name="region" tabindex="5">
                                <?php
                                // On liste toutes les régions
                                foreach ($regions as $region)
                                {
                                    $selected = ((isset($_POST['region']) && $_POST['region'] == $region) || (!isset($_POST['region']) && isset($member->region) && $member->region == $region)) ? 'selected="selected"' : '';
                                    ?>
                                    <option value="<?php echo $region; ?>" <?php echo $selected; ?>><?php echo $region; ?></option>
                                <?php
                                } ?>
                            </select>
                        <?php
                        } ?>
                    </div>
                </div>
                <div class="col-xs-12 col-s-4">
                    <div class="col-xs-12 col-pd font-size-default">
                        <label>Photo actuelle</label>
                        <div class="current-picture-container">
                            <?php if(isset($_POST['current_picture']) && $_POST['current_picture'] != '') { ?>
                                <img class="resize-to-container" src="/assets/img/members/<?php echo $_POST['current_picture']; ?>">
                                <input type="hidden" id="current-picture" name="current_picture" value="<?php echo $_POST['current_picture']; ?>">
                            <?php } elseif(isset($member->picture) && $member->picture != '') { ?>
                                <img class="resize-to-container" src="/assets/img/members/<?php echo $member->picture; ?>">
                                <input type="hidden" id="current-picture" name="current_picture" value="<?php echo $member->picture; ?>">
                            <?php } ?>
                        </div>
                        <?php if(isset($member->picture) && $member->picture != '') { ?>
                            <input type="checkbox" class="delete-picture" id="delete-picture" name="delete_picture" tabindex="6">
                            <label class="delete-picture" for="delete-picture">supprimer</label>
                        <?php } ?>
                        <label for="picture">
                            Nouvelle photo :
                            <?php if(array_key_exists('profile_picture', $errors)) { ?>
                                <br><span class="error"><?php echo $errors['profile_picture']; ?></span>
                            <?php } ?>
                        </label>
                        <div class="input-file-container">
                            <input class="picture" id="picture" name="profile_picture" type="file">
                            <input type="text" class="input-file-return" readonly>
                            <button type="button" class="btn-1-s input-file-trigger" tabindex="7">Parcourir</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="font-size-zero">
                <div class="col-xs-12 col-s-8 col-pd font-size-default">
                    <label for="message">
                        Texte de présentation :
                    </label>
                    <textarea id="message" name="message" tabindex="8"><?php if(isset($_POST['message'])) echo $_POST['message']; elseif(isset($member->message)) echo $member->message; ?></textarea>
                </div>
            </div>
            <div class="col-xs-12 col-pd">
                <p class="caption">
                    * Champs obligatoires
                </p>
                <div class="buttons">
                    <a href="/members/profile/<?php echo $member->id; ?>" tabindex="9"><span class="cancel"></span>Annuler</a>
                    <button type="submit" class="btn-1-m" tabindex="10">
                        Valider
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>