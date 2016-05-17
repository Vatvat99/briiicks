<?php
namespace Core\Utilities;

/**
 * Classe regroupant des fonctions utilitaires
 */
class Utilities {

    /**
     * Transforme une chaîne de caractère en alias (pas d'accents, pas caractères spéciaux, minuscules)
     * @param string $string Chaîne de caractère
     * @return string
     */
    static function aliasFormat($string)
    {
        // On transforme la chaîne en minuscules
        $string = mb_strtolower($string, 'UTF-8');
        // On supprime les accents et on remplace les espaces par des tirets
        $string = str_replace(
            array(
                'à', 'â', 'ä', 'á', 'ã', 'å',
                'î', 'ï', 'ì', 'í',
                'ô', 'ö', 'ò', 'ó', 'õ', 'ø',
                'ù', 'û', 'ü', 'ú',
                'é', 'è', 'ê', 'ë',
                'ç', 'ÿ', 'ñ',
                'À', 'Â', 'Ä', 'Á', 'Ã', 'Å',
                'Î', 'Ï', 'Ì', 'Í',
                'Ô', 'Ö', 'Ò', 'Ó', 'Õ', 'Ø',
                'Ù', 'Û', 'Ü', 'Ú',
                'É', 'È', 'Ê', 'Ë',
                'Ç', 'Ÿ', 'Ñ',
                ' ', '.', '\''
            ),
            array(
                'a', 'a', 'a', 'a', 'a', 'a',
                'i', 'i', 'i', 'i',
                'o', 'o', 'o', 'o', 'o', 'o',
                'u', 'u', 'u', 'u',
                'e', 'e', 'e', 'e',
                'c', 'y', 'n',
                'A', 'A', 'A', 'A', 'A', 'A',
                'I', 'I', 'I', 'I',
                'O', 'O', 'O', 'O', 'O', 'O',
                'U', 'U', 'U', 'U',
                'E', 'E', 'E', 'E',
                'C', 'Y', 'N',
                '-', '', '-'
            ),$string);
        return $string;
    }

    /**
     * Redimensionne une image uploadée depuis un input[type="file"] et l'enregistre sur le serveur
     * @param array $picture_name Nom de l'image à redimensionner ($_FILES['name'])
     * @param array $picture_tmp_name Emplacement temporaire de l'image à redimensionner ($_FILES['tmp_name'])
     * @param int $width Largeur minimum (si $max = false) que doit faire l'image une fois redimensionnée
     * @param int $height Hauteur minimum (si $max = false) que doit faire l'image une fois redimensionnée
     * @param string $path Emplacement où enregistrer l'image
     * @param string $name Nom du fichier sous lequel il doit être enregistré
     * @param boolean $max Spécifie si les largeurs et hauteurs sont des valsuers maximum ou minimum
     */
    static function resizeAndSavePicture($picture_name, $picture_tmp_name, $width, $height, $path, $name, $max = false)
    {
        $extension = strtolower(substr(strrchr($picture_name, '.'), 1));
        // On redimensionne l'image
        switch($extension)
        {
            case 'jpg':
                $original_picture = imagecreatefromjpeg($picture_tmp_name);
                break;
            case 'jpeg':
                $original_picture = imagecreatefromjpeg($picture_tmp_name);
                break;
            case 'png':
                $original_picture = imagecreatefrompng($picture_tmp_name);
                break;
            case 'gif':
                $original_picture = imagecreatefromgif($picture_tmp_name);
                break;
            default:
                $original_picture = imagecreatefromjpeg($picture_tmp_name);
                break;
        }

        // Les fonctions imagesx et imagesy renvoient la largeur et la hauteur d'une image
        $original_picture_width = imagesx($original_picture);
        $original_picture_height = imagesy($original_picture);

        $over = $original_picture_width / $original_picture_height;
        $under = $original_picture_height / $original_picture_width;

        // Les dimensions finales de l'image sont des dimensions minimales
        if(!$max)
        {
            // L'image originale est plus haute que le format final
            if ($width / $height >= $over) {
                $resized_width = $width;
                $resized_height = ceil($under * $width);
            }
            // L'image originale est plus large que le format final
            else {
                $resized_width = ceil($over * $height);
                $resized_height = $height;
            }
        }
        // Les dimensions finales de l'image sont des dimensions maximales
        else {
            // L'image originale est plus haute que le format final
            if ($width / $height >= $over) {
                $resized_width = ceil($over * $height);
                $resized_height = $height;
            }
            // L'image originale est plus large que le format final
            else {
                $resized_width = $width;
                $resized_height = ceil($under * $width);
            }
        }


        $resized_picture = imagecreatetruecolor($resized_width, $resized_height); // On crée la miniature vide
        $resized_picture_width = imagesx($resized_picture);
        $resized_picture_height = imagesy($resized_picture);

        // On crée la miniature
        imagecopyresampled($resized_picture, $original_picture, 0, 0, 0, 0, $resized_picture_width, $resized_picture_height, $original_picture_width, $original_picture_height);

        // On enregistre la miniature
        switch($extension)
        {
            case 'jpg':
                imagejpeg($resized_picture, $_SERVER['DOCUMENT_ROOT'] . $path . $name . '.' . $extension, 90);
                break;
            case 'jpeg':
                imagejpeg($resized_picture, $_SERVER['DOCUMENT_ROOT'] . $path . $name . '.' . $extension, 90);
                break;
            case 'png':
                imagepng($resized_picture, $_SERVER['DOCUMENT_ROOT'] . $path . $name . '.' . $extension, 9);
                break;
            case 'gif':
                imagegif($resized_picture, $_SERVER['DOCUMENT_ROOT'] . $path . $name . '.' . $extension);
                break;
            default:
                imagejpeg($resized_picture, $_SERVER['DOCUMENT_ROOT'] . $path . $name . '.' . $extension, 90);
                break;
        }
    }

}