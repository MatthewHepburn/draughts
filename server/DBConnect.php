<?php
require_once './config.php';

$dbname = $config["db"]["draughts"]["db_name"];
$host = $config["db"]["draughts"]["host"];
$username = $config["db"]["draughts"]["username"];
$password = $config["db"]["draughts"]["password"];

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$create_player_table = <<<SQL
CREATE TABLE players (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    created TIMESTAMP,
    last_seen TIMESTAMP,
    password_hash BINARY(64),
    salt BINARY(64),
    google_id CHAR(35)
);
SQL;


if ($conn->query($create_player_table) === true) {
    echo "Table players created successfully\n";
} else {
    echo "Error creating table: " . $conn->error . "\n";
}

$create_game_table = <<<SQL
CREATE TABLE games (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    status ENUM("p1_to_move", "p2_to_move", "p1_won", "p2_won", "draw"),
    started_time TIMESTAMP,
    last_move_time TIMESTAMP,
    move_history VARCHAR(2000),
    p1_id INT UNSIGNED,
    p2_id INT UNSIGNED,
    FOREIGN KEY (p1_id)
        REFERENCES players(id)
        ON DELETE SET NULL
    )
SQL;

if ($conn->query($create_game_table) === true) {
    echo "Table games created successfully\n";
} else {
    echo "Error creating table: " . $conn->error . "\n";
}
