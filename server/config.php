<?php
namespace hepcode\draughts;

/*
    Modified from https://code.tutsplus.com/tutorials/organize-your-next-php-project-the-right-way--net-5873
*/

mb_internal_encoding("UTF-8");

class Config
{
    const CFG = array(
        "db" => array(
            "draughts" => array(
                "dbname" => "draughts",
                "username" => "REDACTED",
                "password" => "REDACTED",
                "host" => "localhost"
            )
        ),
        "urls" => array(
            "baseUrl" => "http://BASEURL"
        ),
        "paths" => array(
            "resources" => "/resources",
            "images" => array()
        ),
        'rules' => array(
            'maxUsernameLength' => 50,
            'minPasswordLength' => 6
        ),
        "devMode" => true
    );
}
