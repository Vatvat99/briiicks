<div id="content" class="content">
    <div class="profile-header col-pd font-size-zero">
        <?php if(array_key_exists('success', $_SESSION)) { ?>
            <div class="col-xs-12 col-pd font-size-default">
                <p class="success">
                    <?php echo $_SESSION['success']; ?>
                </p>
            </div>
        <?php } elseif(array_key_exists('error', $_SESSION)) { ?>
            <div class="col-xs-12 col-pd font-size-default">
                <p class="error">
                    <?php echo $_SESSION['error']; ?>
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
                        <?php echo $member->firstname . ' ' . $member->lastname; ?>
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
                <span class="icon-quote-left"></span>
                <span class="icon-quote-right"></span>
                <?php echo $member->message; ?>
            </p>
            <?php } ?>
        </div>
        <div class="col-xs-12 col-s-4 col-pd font-size-default txt-right">
            <p>
                <a class="action-link" href="/members/edit">
                    <span class="text">Compléter mon profil</span>
                    <span class="icon-pencil"></span>
                </a>
                <a href="/collection" class="btn-1-s">Accéder à ma collection</a>
            </p>
        </div>
    </div>
    <div class="col-pd">
        <div class="col-pd">
            <div class="horizontal-border"></div>
        </div>
    </div>
    <div class="col-s-pd font-size-zero">
        <div class="col-xs-12 col-s-8 col font-size-default">
            <div class="col-pd">
                <div class="col-pd col-s-no-pd">
                    <h3>Activité récente</h3>
                </div>
            </div>
            <div class="col-s-pd">
                <div class="activities">
                    <div class="activity-container uneven">
                        <div class="activity-member-thumbnail">
                            <div class="picture-container">
                                <img class="resize-to-container" src="/assets/img/members/38-julie-deslanges.jpg">
                            </div>
                        </div>
                        <div class="activity-content">
                            <span class="member-name">Julie Deslanges</span> a ajouté la figurine <span class="minifigure-name">L'acteur dramatique</span> à sa collection
                            <div class="date">le 1 septembre 2014 à 17h52</div>
                        </div>
                        <div class="activity-minifigure-thumbnail">
                            <div class="picture-container">
                                <img class="resize-to-container" src="/assets/img/minifigures/57x57/1-l-acteur-dramatique.jpg">
                            </div>
                        </div>
                    </div>
                    <div class="activity-container even">
                        <div class="activity-member-thumbnail">
                            <div class="picture-container">
                                <img class="resize-to-container" src="/assets/img/members/39-marie-desmont.jpg">
                            </div>
                        </div>
                        <div class="activity-content">
                            <span class="member-name">Marie Desmont</span> a ajouté la figurine <span class="minifigure-name">Le capitaine des pirates</span> à sa collection
                            <div class="date">le 1 septembre 2014 à 17h52</div>
                        </div>
                        <div class="activity-minifigure-thumbnail">
                            <div class="picture-container">
                                <img class="resize-to-container" src="/assets/img/minifigures/57x57/2-le-capitaine-des-pirates.jpg">
                            </div>
                        </div>
                    </div>
                    <div class="activity-container uneven">
                        <div class="activity-member-thumbnail">
                            <div class="picture-container">
                                <img class="resize-to-container" src="/assets/img/members/38-julie-deslanges.jpg">
                            </div>
                        </div>
                        <div class="activity-content">
                            <span class="member-name">Julie Deslanges</span> a ajouté la figurine <span class="minifigure-name">Le chevalier héroïque</span> à sa collection
                            <div class="date">le 1 septembre 2014 à 17h52</div>
                        </div>
                        <div class="activity-minifigure-thumbnail">
                            <div class="picture-container">
                                <img class="resize-to-container" src="/assets/img/minifigures/57x57/31-le-chevalier-heroique.jpg">
                            </div>
                        </div>
                    </div>
                    <div class="activity-container even">
                        <div class="activity-member-thumbnail">
                            <div class="picture-container">
                                <img class="resize-to-container" src="/assets/img/members/40-celine-dumesnil.jpg">
                            </div>
                        </div>
                        <div class="activity-content">
                            <span class="member-name">Céline Dumesnil</span> a ajouté la figurine <span class="minifigure-name">La bibliothécaire</span> à sa collection
                            <div class="date">le 1 septembre 2014 à 17h52</div>
                        </div>
                        <div class="activity-minifigure-thumbnail">
                            <div class="picture-container">
                                <img class="resize-to-container" src="/assets/img/minifigures/57x57/43-la-bibliothecaire.jpg">
                            </div>
                        </div>
                    </div>
                    <div class="activity-container uneven">
                        <div class="activity-member-thumbnail">
                            <div class="picture-container">
                                <img class="resize-to-container" src="/assets/img/members/42-jerome-meunier.jpg">
                            </div>
                        </div>
                        <div class="activity-content">
                            <span class="member-name">Jérome Meunier</span> a ajouté la figurine <span class="minifigure-name">Général Grievous</span> à sa collection
                            <div class="date">le 1 septembre 2014 à 17h52</div>
                        </div>
                        <div class="activity-minifigure-thumbnail">
                            <div class="picture-container">
                                <img class="resize-to-container" src="/assets/img/minifigures/57x57/102-general-grievous.jpg">
                            </div>
                        </div>
                    </div>
                    <div class="activity-container even">
                        <div class="activity-member-thumbnail">
                            <div class="picture-container">
                                <img class="resize-to-container" src="/assets/img/members/42-jerome-meunier.jpg">
                            </div>
                        </div>
                        <div class="activity-content">
                            <span class="member-name">Jérome Meunier</span> a ajouté la figurine <span class="minifigure-name">Obi-Wan Kenobi</span> à sa collection
                            <div class="date">le 1 septembre 2014 à 17h52</div>
                        </div>
                        <div class="activity-minifigure-thumbnail">
                            <div class="picture-container">
                                <img class="resize-to-container" src="/assets/img/minifigures/57x57/103-obi-wan-kenobi.jpg">
                            </div>
                        </div>
                    </div>
                    <div class="activity-container uneven">
                        <div class="activity-member-thumbnail">
                            <div class="picture-container">
                                <img class="resize-to-container" src="/assets/img/members/41-marjorie-blanc.jpg">
                            </div>
                        </div>
                        <div class="activity-content">
                            <span class="member-name">Marjorie Blanc</span> a ajouté la figurine <span class="minifigure-name">Homer Simpson</span> à sa collection
                            <div class="date">le 1 septembre 2014 à 17h52</div>
                        </div>
                        <div class="activity-minifigure-thumbnail">
                            <div class="picture-container">
                                <img class="resize-to-container" src="/assets/img/minifigures/57x57/82-homer-simpson.jpg">
                            </div>
                        </div>
                    </div>
                    <div class="activity-container even">
                        <div class="activity-member-thumbnail">
                            <div class="picture-container">
                                <img class="resize-to-container" src="/assets/img/members/41-marjorie-blanc.jpg">
                            </div>
                        </div>
                        <div class="activity-content">
                            <span class="member-name">Marjorie Blanc</span> a ajouté la figurine <span class="minifigure-name">Maggie Simpson</span> à sa collection
                            <div class="date">le 1 septembre 2014 à 17h52</div>
                        </div>
                        <div class="activity-minifigure-thumbnail">
                            <div class="picture-container">
                                <img class="resize-to-container" src="/assets/img/minifigures/57x57/86-maggie-simpson.jpg">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-s-4 col font-size-default">
            <div class="col-pd">
                <div class="col-pd col-s-no-pd">
                    <h3>Membres suivis</h3>
                    <div class="followed-members font-size-zero">
                        <div class="picture-container font-size-default">
                            <img class="resize-to-container" src="/assets/img/members/40-celine-dumesnil.jpg">
                        </div>
                        <div class="picture-container font-size-default">
                            <img class="resize-to-container" src="/assets/img/members/42-jerome-meunier.jpg">
                        </div>
                        <div class="picture-container font-size-default">
                            <img class="resize-to-container" src="/assets/img/members/39-marie-desmont.jpg">
                        </div>
                        <div class="picture-container font-size-default">
                            <img class="resize-to-container" src="/assets/img/members/41-marjorie-blanc.jpg">
                        </div>
                        <div class="picture-container font-size-default">
                            <img class="resize-to-container" src="/assets/img/members/38-julie-deslanges.jpg">
                        </div>
                    </div>
                    <h3>Trouver un membre</h3>
                    <form class="search-form" method="post">
                        <?php if(array_key_exists('search', $errors)) { ?>
                            <p class="error">
                                <?php echo $errors['search']; ?>
                            </p>
                        <?php } ?>
                        <div class="input-container">
                            <button type="submit"><span class="icon-search"></span></button>
                            <input type="text" id="email" name="email" value="Adresse e-mail du membre">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>