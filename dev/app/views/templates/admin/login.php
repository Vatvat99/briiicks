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
    <body class="<?php echo $class_body; ?> no-navigation">
        <div class="container">
            <div id="header" class="header"></div>
            <?php echo $content; ?>
        </div>
    </body>
</html>