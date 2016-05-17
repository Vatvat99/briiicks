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
            <div class="page-header">
                <div class="page-header-picture">
                    <div class="picture-container">
                        <?php if($member->picture != '') { ?>
                            <img class="resize-to-container" src="/assets/img/members/<?php echo $member->picture; ?>">
                        <?php } else { ?>
                            <img class="resize-to-container" src="/assets/img/members/no-picture.png">
                        <?php } ?>
                    </div>
                </div>
                <div class="page-header-title">
                    <h2 class="title">
                        Contacter <?php echo $member->firstname . ' ' . $member->lastname; ?>
                    </h2>
                    <p class="subtitle">
                        <?php echo $member->city; ?>
                        <?php if($member->city != '' && $member->region != '') { echo ', '; } ?>
                        <?php echo $member->region; ?>
                        <?php if($member->city != '' || $member->region != '') { echo ' / '; } ?>
                        <?php echo $member->email; ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-pd txt-center">
        <form method="post">
            <div class="col-xs-12 col-s-8 col-pd txt-left font-size-default">
                <?php if(isset($offer)) { ?>
                    <p class="label">Objet du message :</p>
                    <p>Votre annonce ''Minifigures diverses - état neuf'' du 1er septembre - 17h52</p>
                <?php } ?>
                <label for="message">
                    Texte du message : *
                    <?php if(array_key_exists('message', $errors)) { ?>
                        <br><span class="error"><?php echo $errors['message']; ?></span>
                    <?php } ?>
                </label>
                <textarea name="message" tabindex="1"><?php echo isset($_POST['message']) ? $_POST['message'] : ''; ?></textarea>
            </div>
            <div class="col-xs-12 col-pd txt-left">
                <p class="caption">
                    * Champs obligatoires
                </p>
                <div class="buttons">
                    <a href="/members/profile/<?php echo $member->id; ?>" tabindex="2">
                        Annuler
                    </a>
                    <button type="submit" class="btn-1-m" tabindex="3">
                        Envoyer
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>