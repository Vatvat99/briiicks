<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?php echo $site_name; ?> - Un visiteur vient de vous envoyer un message</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="font-family: 'Helvetica Neue', Arial, Helvetica, sans-serif; font-size: 14px; margin: 0px; padding: 0px;">

<table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff">
    <tr>
        <td width="100%" height="32"></td>
    </tr>
    <tr>
        <td width="100%">
            <h1 style="color: #555555; font-size: 28px; font-weight: normal; margin: 0;">
                <?php echo $site_name; ?> - Un visiteur vient de vous envoyer un message
            </h1>
        </td>
    </tr>
    <tr>
        <td width="100%" height="32"></td>
    </tr>
    <tr>
        <td width="100%">
            <p style="color: #555555; margin: 0;">
                <strong>Prénom :</strong> <?php echo $firstname; ?>
            </p>
        </td>
    </tr>
    <tr>
        <td width="100%" height="16"></td>
    </tr>
    <tr>
        <td width="100%">
            <p style="color: #555555; margin: 0;">
                <strong>Nom :</strong> <?php echo $lastname; ?>
            </p>
        </td>
    </tr>
    <tr>
        <td width="100%" height="16"></td>
    </tr>
    <tr>
        <td width="100%">
            <p style="color: #555555; margin: 0;">
                <strong>Email :</strong> <?php echo $email; ?>
            </p>
        </td>
    </tr>
    <tr>
        <td width="100%" height="32"></td>
    </tr>
    <tr>
        <td width="100%">
            <p style="color: #555555; margin: 0;">
                <strong>Message :</strong><br />
                <?php echo $message; ?>
            </p>
        </td>
    </tr>
    <tr>
        <td width="100%" height="32"></td>
    </tr>
</table>
</body>
</html>