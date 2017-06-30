<?php
namespace hepcode\draughts;

class DraughtsDB
{
    private $con;
    private $cfg;

    public function __construct()
    {
        require_once './DraughtsConnection.php';
        $conHandler = new \hepcode\draughts\DraughtsConnection();
        $this->con = $conHandler->getConnection();
        if (is_null($this->con)) {
            die("Failed to connect to database");
        }
        $this->cfg = \hepcode\draughts\Config::CFG;
    }

    public function usernameExists(string $username):bool
    {
        $statement = $this->con->prepare("SELECT EXISTS(SELECT * FROM players WHERE username = ?)");
        $statement->bind_param("s", $username);
        $statement->bind_result($rawResult);
        $statement->execute();
        $statement->fetch();
        return $rawResult !== 0;
    }


    public function createUser(string $username, string $password)
    {
        $passwordHash = \password_hash($password, PASSWORD_DEFAULT);
        $accountCreated = date("Y-m-d H:i:s");
        $statement = $this->con->prepare("INSERT INTO players (username, password_hash, created, last_seen)
            VALUES (?, ?, ?, ?)");
        $statement->bind_param("ssss", $username, $passwordHash, $accountCreated, $accountCreated);
        $statement->execute();
        return $statement->affected_rows !== 0;
    }

    public function getUserID(string $username)
    {
        $statement = $this->con->prepare("SELECT id FROM players WHERE username = ?");
        $statement->bind_param("s", $username);
        $statement->bind_result($result);
        $statement->execute();
        $statement->fetch();
        return $result;
    }
}
