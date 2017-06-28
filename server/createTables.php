#!/usr/bin/env php

<?php
$servername = "localhost";
$username = "draughts";

if (array_key_exists(1, $argv)) {
    $password = $argv[1]; // Password for draughts account must be first arguement to script
} else {
    die("Error: the password for username '{$username}' must be the first arguement when invoking this script.\n");
}


$dbname = "draughts";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
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
    seeking_game BOOLEAN DEFAULT FALSE NOT NULL,
    password_hash BINARY(255) NOT NULL,
    elo INT UNSIGNED DEFAULT 1000,
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
