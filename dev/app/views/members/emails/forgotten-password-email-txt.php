-------------------------------------------------
<?php echo $site_name; ?> - gestion de collection lego
-------------------------------------------------

:::: Mot de passe oublié ::::::::::::::

Bonjour <?php echo $firstname; ?>

Vous avez demandé à obtenir un nouveau mot de passe. Pour cela, copiez-collez ce lien dans votre navigateur :

<?php echo $url_http; ?>/members/regeneratePassword?email=<?php echo $email; ?>&key=<?php echo $activation_key; ?>

Si vous n’êtes pas à l’origine de cette demande, merci d’ignorer cet e-mail.

-------------------------------------------------
L’équipe <?php echo $site_name; ?> - gestion de collection lego