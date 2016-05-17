<?php
namespace Core\Recaptcha;

/**
 * Gestion de Google Recaptcha
 */
class Recaptcha {

    /**
     * @var string $secret_key Clé secrète fournie par google pour l'identification du recaptcha
     */
    private $secret_key;

    /**
     * Instanciation du recaptcha
     * @param string $secret_key Clé secrète fournie par google pour l'identification du recaptcha
     */
    function __construct($secret_key) {
        $this->secret_key = $secret_key;
    }

    /**
     * Permet de vérifier avec le code généré par Google suite à la soumission du recaptcha si le recaptcha est valide ou non
     * @param string $code : code généré par Google suite à la soumission du recaptcha
     * @param null $ip : ip de la machine du client sur laquelle s'exécute le site
     * @return bool : true si le captcha est valide, false s'il n'y est pas
     */
    public function isValid($code, $ip = null) {
        // Si on a pas renseigné de code
        if(empty($code)) {
            // Echec de la validation
            return false;
        }
        $params = array(
            'secret' => $this->secret_key,
            'response' => $code,
        );
        if($ip) {
            $params['remoteip'] = $ip;
        }
        $url = 'https://www.google.com/recaptcha/api/siteverify?' . http_build_query($params);
        // Si l'extension php Curl est activée
        if(function_exists('curl_version')) {
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_TIMEOUT, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($curl);
        }
        // Si Curl n'est pas activé
        else {
            $response = file_get_contents($url);
        }
        // Si Google n'a renvoyé aucune réponse
        if(empty($response) || is_null($response)) {
            // Echec de la validation
            return false;
        }
        // Analyse de la réponse
        $json = json_decode($response);
        return $json->success;
    }

}