<div id="content" class="content col-pd">
    <div class="confirmation col-pd">
        <?php if($activation == 'active') { ?>
            <h1>Bravo !<br>Vous êtes inscrit<br><img src="/assets/img/success-01.png" alt="Succès"></h1>
            <p>Votre adresse mail est confirmée. Vous pouvez dès à présent utiliser votre compte.</p>
            <h3><a href="/members/login">Commencez votre collection</a></h3>
        <?php } elseif($activation == 'already-active') { ?>
            <h1>Compte déjà actif<br><img src="/assets/img/interrogation-01.png" alt="Interrogation"></h1>
            <p>Votre adresse mail a déjà été confirmée. Vous pouvez déjà utiliser votre compte.</p>
            <h3><a href="/members/login">Commencez votre collection</a></h3>
        <?php } else {
            // Erreur, ou tous les autres cas ?>
            <h1>Oups...<br><img src="/assets/img/failure-01.png" alt="échec"></h1>
            <p>Votre compte ne peut pas être activé. Le lien de confirmation de votre adresse e-mail semble être erroné.</p>
            <h3><a href="/home">Retourner à l'accueil</a></h3>
        <?php } ?>
    </div>
</div>