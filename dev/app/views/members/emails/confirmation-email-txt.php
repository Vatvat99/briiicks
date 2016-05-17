-------------------------------------------------
<?php echo $site_name; ?> - gestion de collection lego
-------------------------------------------------

:::: Votre inscription sur le site ::::::::::::::

Bonjour <?php echo $firstname; ?>

Merci de votre inscription sur <?php echo $site_name; ?> - gestion de collection lego.

Veuillez confirmer votre adresse e-mail afin de pouvoir utiliser votre compte. Pour cela, copiez-collez ce lien dans votre navigateur :

<?php echo $url_http; ?>/members/activateAccount?email=<?php echo $email; ?>&key=<?php echo $activation_key; ?>

Pour des raisons de sécurité, votre mot de passe n'a pas été envoyé dans cet e-mail. En cas d'oubli, vous pouvez le réinitialiser en accédant à cette page :

<?php echo $url_http; ?>/forgottenPassword

-------------------------------------------------
L’équipe <?php echo $site_name; ?> - gestion de collection lego