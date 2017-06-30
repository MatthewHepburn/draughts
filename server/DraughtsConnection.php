<?php
namespace hepcode\draughts;

class DraughtsConnection
{
    private $connection = null;

    public function getConnection()
    {
        if (is_null($this->connection)) {
            $this->openConnection();
        }
        return $this->connection;
    }

    private function openConnection()
    {
        require_once './Config.php';

        // Pull settings from config
        $cfg = \hepcode\draughts\Config::CFG;
        $host = $cfg["db"]["draughts"]["host"];
        $username = $cfg["db"]["draughts"]["username"];
        $password = $cfg["db"]["draughts"]["password"];
        $dbname = $cfg["db"]["draughts"]["dbname"];

        // Create connection
        $connection = new \mysqli($host, $username, $password, $dbname);

        // Check connection
        if ($connection->connect_error) {
            if ($cfg["devMode"]) {
                die("Connection failed: " . $connection->connect_error);
            } else {
                return null;
            }
        }
        $this->connection = $connection;
    }
}
