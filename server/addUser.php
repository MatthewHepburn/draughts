<?php
namespace hepcode\draughts;

(function () {
    require_once "./Config.php";
    $cfg = \hepcode\draughts\Config::CFG;

    $errorOut = function (int $responseCode, string $publicMessage) {
        http_response_code($responseCode);
        echo "\n{$publicMessage}\n\n";
        exit();
    };


    if (!isset($_POST)) {
        $errorOut(400, "Expected a POST.");
    }

    // Check that expected fields are present in $_POST
    $expectedVars = array("username", "password");

    foreach ($expectedVars as $var) {
        if (!array_key_exists($var, $_POST)) {
            $errorOut(400, "Expected {$var} in request.");
        }
    }

    // Validate and sanitise data
    $username = htmlspecialchars(trim($_POST["username"]));
    if (mb_strlen($username) === 0) {
        $errorOut(400, "Sanitised username is empty.");
    }
    if (mb_strlen($username) > $cfg['rules']['maxUsernameLength']) {
        $errorOut(400, "Username cannot be longer than {$cfg['rules']['maxUsernameLength']} characters long.");
    }
    $password = $_POST["password"];
    if (mb_strlen($password) < $cfg["rules"]["minPasswordLength"]) {
        $errorOut(400, "Password must be at least {$cfg['rules']['minPasswordLength']} characters long.");
    }

    // TODO: ban most common passwords
    // $TOP25 = array("123456","123456789","qwerty","12345678","111111","1234567890","1234567","password",
    //     "123123","987654321","qwertyuiop","mynoob","123321","666666","18atcskd2w","7777777","1q2w3e4r",
    //     "654321","555555","3rjs1la7qe","google","1q2w3e4r5t","123qwe","zxcvbnm","1q2w3e");

    //Check that username is available
    require_once 'DraughtsDB.php';
    $db = new \hepcode\draughts\DraughtsDB();

    if ($db->usernameExists($username)) {
        $errorOut(400, "Username is already taken.");
    }

    if (!$db->createUser($username, $password)) {
        $errorOut(500, "Database error. User creation failed.");
    }

    //User created, get auth token:
    require_once "Auth.php";
    $userID = $db->getUserID($username);
    $token = \hepcode\draughts\Auth::getAuthToken($userID);


    http_response_code(200);
    echo "OK\n";
    echo "\"{$token}\"";
    // echo json_encode($_POST);
})();
