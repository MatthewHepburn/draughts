<?php

/*
    Modified from https://code.tutsplus.com/tutorials/organize-your-next-php-project-the-right-way--net-5873
*/

$config = array(
    "db" => array(
        "draughts" => array(
            "dbname" => "draughts",
            "username" => "dbUser",
            "password" => "PASSWORD REDACTED",
            "host" => "localhost"
        )
    ),
    "urls" => array(
        "baseUrl" => "http://BASEURL"
    ),
    "paths" => array(
        "resources" => "/resources",
        "images" => array(
            "content" => $_SERVER["DOCUMENT_ROOT"] . "/images/content",
            "layout" => $_SERVER["DOCUMENT_ROOT"] . "/images/layout"
        )
    )
);
