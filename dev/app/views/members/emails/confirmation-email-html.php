<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?php echo $site_name; ?> - Confirmation de votre adresse e-mail</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="font-family: 'Helvetica Neue', Arial, Helvetica, sans-serif; font-size: 14px; margin: 0px; padding: 0px;">

<table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#eeeeee">
    <tr>
        <td align="center" bgcolor="#eeeeee">
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td width="480" height="32"></td>
                </tr>

                <!-- entete -->
                <tr>
                    <td width="480">
                        <table cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff">
                            <tr>
                                <td width="480" style="font-family: 'Helvetica Neue', Arial, Helvetica, sans-serif; font-size: 24px;">
                                    <a href="#" style="color: #555555; text-decoration: none;"><span style="color: #555555; text-decoration: none;">
											<img style="text-decoration: none; display: block; color: #555555; font-size: 24px;" src="<?php echo $url_http; ?>/assets/img/emails/logo-briiicks-01.png" alt="<?php echo $site_name; ?> - gestion de collection lego" width="480" height="121"/>
										</span></a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <!-- separateur horizontal -->
                <tr>
                    <td width="480" height="1" bgcolor="#eeeeee"></td>
                </tr>

                <!-- contenu -->
                <tr class="content">
                    <td width="480" bgcolor="#ffffff">
                        <table width="480" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td width="32"></td>
                                <td width="416">
                                    <!-- une zone de contenu -->
                                    <table width="416" cellpadding="0" cellspacing="0" border="0">
                                        <tr>
                                            <td width="416" style="font-family: 'Helvetica Neue', Arial, Helvetica, sans-serif; font-size: 14px;">
                                                <table width="416" cellpadding="0" cellspacing="0" border="0">
                                                    <tr>
                                                        <td width="416" height="32"></td>
                                                    </tr>
                                                    <tr>
                                                        <td width="416">
                                                            <h1 style="color: #555555; font-size: 28px; font-weight: normal; margin: 0;">
                                                                Votre inscription sur le site
                                                            </h1>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="416" height="32"></td>
                                                    </tr>
                                                    <tr>
                                                        <td width="416">
                                                            <p style="color: #555555; margin: 0;">Bonjour <?php echo $firstname; ?></p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="416" height="16"></td>
                                                    </tr>
                                                    <tr>
                                                        <td width="416">
                                                            <p style="color: #555555; margin: 0;">
                                                                Merci de votre inscription sur <span style="font-style: italic;"><?php echo $site_name; ?> - gestion de collection lego</span>.
                                                            </p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="416" height="16"></td>
                                                    </tr>
                                                    <tr>
                                                        <td width="416">
                                                            <p style="color: #555555; margin: 0;">
                                                                Veuillez confirmer votre adresse e-mail afin de pouvoir utiliser votre compte.
                                                            </p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="416" height="16"></td>
                                                    </tr>
                                                </table>
                                                <table width="416" cellpadding="0" cellspacing="0" border="0">
                                                    <tr>
                                                        <td width="58"></td>
                                                        <td width="300" height="16"></td>
                                                        <td width="58"></td>
                                                    </tr>
                                                    <tr>
                                                        <td width="58"></td>
                                                        <td width="300" height="16" bgcolor="#e06342"></td>
                                                        <td width="58"></td>
                                                    </tr>
                                                    <tr>
                                                        <td width="58"></td>
                                                        <td width="300" align="center" bgcolor="#e06342" style="color: #ffffff; font-weight: bold;">
                                                            <a href="<?php echo $url_http; ?>/members/activateAccount?email=<?php echo $email; ?>&key=<?php echo $activation_key; ?>" style="color: #ffffff; text-decoration: none;">
                                                                CONFIRMER MON ADRESSE E-MAIL
                                                            </a>
                                                        </td>
                                                        <td width="58"></td>
                                                    </tr>
                                                    <tr>
                                                        <td width="58"></td>
                                                        <td width="300" height="16" bgcolor="#e06342"></td>
                                                        <td width="58"></td>
                                                    </tr>
                                                    <tr>
                                                        <td width="58"></td>
                                                        <td width="300" height="32"></td>
                                                        <td width="58"></td>
                                                    </tr>
                                                </table>
                                                <table width="416" cellpadding="0" cellspacing="0" border="0">
                                                    <tr>
                                                        <td width="416">
                                                            <p style="color: #555555; margin: 0;">
                                                                Pour des raisons de sécurité, votre mot de passe n'a pas été envoyé dans cet e-mail. En cas d'oubli, vous pouvez en obtenir un nouveau en <a href="<?php echo $url_http; ?>/forgottenPassword" style="color: #555555; text-decoration: underline;"><span style="color: #555555; text-decoration: underline;">cliquant ici</span></a>.
                                                            </p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="416" height="16"></td>
                                                    </tr>
                                                    <tr>
                                                        <td width="416">
                                                            <p style="color: #555555; margin: 0;">
                                                                L’équipe <span style="font-style: italic;"><?php echo $site_name; ?> - gestion de collection lego</span>
                                                            </p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="416" height="16"></td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="416" height="96"></td>
                                        </tr>
                                    </table>
                                    <!-- fin zone -->
                                </td>
                                <td width="32"></td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <!--  marge horizontale -->
                <tr>
                    <td width="480" height="128"></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>