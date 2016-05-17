<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Les 3 balises précédentes doivent être au début de la partie head -->
    <title><?php echo $title; ?></title>
    <link rel="icon" href="/assets/img/favicon.ico" />
    <?php if($meta_description != null) { ?>
        <meta name="description" content="<?php echo $meta_description; ?>">
    <?php } ?>
    <?php foreach ($styles as $style) { ?>
        <link rel="stylesheet" href="<?php echo $style; ?>" />
    <?php } ?>
    <?php foreach ($scripts as $script) { ?>
        <script src="<?php echo $script; ?>"></script>
    <?php } ?>
</head>
<body class="<?php echo $class_body; ?>">
    <div class="header">
        <h1 class="logo">
            <a href="/">
                <div class="head"></div>
                briiicks<span class="beta">beta</span><br>
                <span>gestion de collection lego</span>
            </a>
        </h1>
        <button id="navigation-button" class="navigation-button inactive" type="button">
            <span class="icon-burger-menu size-24"></span>
            <br>
            menu
        </button>
        <?php // Si on est connecté
        if(isset($_SESSION['front']['id'])) { ?>
            <div class="logged-user">
                <div class="picture-container">
                    <?php if($_SESSION['front']['picture'] != '') { ?>
                        <img class="resize-to-container" src="/assets/img/members/<?php echo $_SESSION['front']['picture']; ?>">
                    <?php } ?>
                </div>
                <p>
                    <a href="/members/profile/<?php echo $_SESSION['front']['id']; ?>" class="link">
                        <?php echo $_SESSION['front']['firstname']; ?> <?php echo $_SESSION['front']['lastname']; ?>
                    </a>
                    <br>
                    <a href="/members/logout">déconnexion</a>
                </p>
            </div>
        <?php }
        // Si on n'est pas connecté
        else { ?>
            <ul class="anonymous-user">
                <li><a href="/members/signin">S'inscrire</a></li>
                <li><a href="/members/login">Se connecter</a></li>
            </ul>
        <?php } ?>
    </div>
    <div id="navigation" class="navigation">
        <div id="right-lateral-header" class="right-lateral-header"></div>
        <?php
        // Si on est connecté
        if(isset($_SESSION['front']['id'])) { ?>
            <ul class="logged-user">
                <li class="picture">
                    <div class="picture-container">
                        <?php if($_SESSION['front']['picture'] != '') { ?>
                            <img class="resize-to-container" src="/assets/img/members/<?php echo $_SESSION['front']['picture']; ?>">
                        <?php } ?>
                    </div>
                </li>
                <li class="name">
                    <a href="/members/profile/<?php echo $_SESSION['front']['id']; ?>" class="link">
                        <?php echo $_SESSION['front']['firstname']; ?> <?php echo $_SESSION['front']['lastname']; ?>
                    </a>
                </li>
                <li class="logout">
                    <a href="/members/logout" class="link">
                        <span class="icon-logout size-16"></span>
                    </a>
                </li>
            </ul>
        <?php }
        // Si on n'est pas connecté
        else { ?>
            <ul class="anonymous-user">
                <li><a href="/members/signin">S'inscrire</a></li>
                <li><a href="/members/login">Se connecter</a></li>
            </ul>
        <?php } ?>
        <ul class="links">
            <li><a href="/home">Rechercher des figs / sets</a></li>
            <?php // Si on est connecté
            if(isset($_SESSION['front']['id'])) { ?>
                <li><a href="/collection">Ma collection</a></li>
                <li><a href="/offers/add">Déposer une annonce</a></li>
            <?php } ?>
            <li><a href="/offers/listing">Toutes les annonces</a></li>
        </ul>
    </div>
    <?php echo $content; ?>
    <div class="footer">
        <ul class="links">
            <li>
                <a href="/pages/contact" title="Signaler un problème au webmaster">
                    Signaler un problème au webmaster
                </a>
            </li>
        </ul>
        <p>Tous droits réservés briiicks.fr 2015</p>
    </div>
    <div class="coming-soon-dialog dialog">
        <div class="header">
            <a href="#" class="close" title="Fermer"></a>
        </div>
        <div class="content">
            <h2>Fonctionnalité en cours de développement</h2>

        </div>
    </div>
</body>
</html>