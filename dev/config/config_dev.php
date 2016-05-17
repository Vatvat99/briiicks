<?php
return array(
    'title' => 'Briiicks',
    'meta_description' => 'Gestionnaire de collection Lego. Vente et échange entre membres.',

    'url_http' => 'http://briiicks.dev',

	'db_name' => '',
	'db_user' => 'root',
	'db_pass' => '',
	'db_host' => 'localhost',
    'tables_prefix' => '',

    'common_librairies_styles' => array(),
    'common_external_styles' => array(),
    'common_styles' => array(
        'main.css'
    ),

    'common_librairies_scripts' => array(
        'jquery-1.11.0.min.js',
        'jquery.mCustomScrollbar.concat.min.js'
    ),
    'common_external_scripts' => array(),
    'common_scripts' => array(
        'common.js'
    ),

    'max_file_size' => '3145728', // Taille maximale d'upload des fichiers
    'img_authorized_extensions' => array(
        'jpg',
        'jpeg',
        'JPG',
        'gif',
        'GIF',
        'png',
        'PNG'
    ),

    'smtp_email' => '', // E-mail de laquelle sont envoyé les messages du site
    'email_webmaster' => '', // E-mail de contact du webmaster
    'recaptcha_key' => '', // Clé secrète fournie par google pour l'identification du recaptcha

    'admin' => array(
        'title' => 'Administration',

        'tables_prefix' => '',

        'common_librairies_styles' => array(),
        'common_external_styles' => array(),
        'common_styles' => array(
            'admin/main.css'
        ),
        'common_librairies_scripts' => array(
            'admin/jquery-1.11.0.min.js'
        ),
        'common_external_scripts' => array(),
        'common_scripts' => array(
            'admin/common.js',
            'admin/navigation.js'
        )
    )
);
