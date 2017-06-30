<?php
namespace hepcode\draughts;

use \Firebase\JWT\JWT;
use \DateTime;
use \DateInterval;

class Auth
{
    // Generates a token string to be sent to the client.
    public static function getAuthToken(int $userID):string
    {
        require_once "./Config.php";
        require_once "./vendor/firebase/php-jwt/src/JWT.php";

        $cfg = \hepcode\draughts\Config::CFG;
        $now =
        $expiresDateTime = date_add(new DateTime(), new DateInterval($cfg["auth"]["sessionLength"]));

        $token = array("id" => $userID,
            "expires" => $expiresDateTime->format(DateTime::ATOM));
        return JWT::encode($token, $cfg["auth"]["tokenKey"]);
    }

    // Returns false if token is invalid/exipired. Returns userID otherwise.
    public static function readAuthToken(string $token)
    {
        require_once "./vendor/firebase/php-jwt/src/JWT.php";

        try {
            $token = JWT::decode($token, \hepcode\draughts\Config::CFG["auth"]["tokenKey"]);
        } catch (Exception $e) {
            return false;
        }

        // Check expected data is present in decoded token
        if (!is_int($token["id"]) || !is_string($token["id"]["expires"])) {
            return false;
        }

        $expiresDateTime = DateTime::createFromFormat(DateTime::ATOM, $token["expires"]);

        if ($expiresDateTime === false) {
            return false;
        }

        if ($expiresDateTime > new DateTime()) {
            // Token expires in the future, so is valid:
            return $token["id"];
        } else {
            return false;
        }
    }
}
