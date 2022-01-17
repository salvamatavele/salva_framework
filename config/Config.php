<?php
/**
 * Base URL
 */
define('__URL__', 'http://localhost/salva');
/**
 * dominio
 */
define('_DOMIN_',$_SERVER["HTTP_HOST"]);
/**
 * Data base config
 */
define("DATA_LAYER_CONFIG", [
    "driver" => "mysql",
    "host" => "localhost",
    "port" => "3306",
    "dbname" => "salva",
    "username" => "root",
    "passwd" => "root",
    "options" => [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_CASE => PDO::CASE_NATURAL
    ]
]);

/**
     * Email configuration
     */
    define('MAIL', [
        "host"=>"smtp.zoho.com",
        "port"=>"587",
        "user"=>"smatavele1@zohomail.com",
        "passwd"=>"1INmIPqPtKFq",
        "from_name"=>"SALVA|FRAMEWORK",
        "from_email"=>"smatavele1@zohomail.com"
    ]);
/**
 * Para acessar css e javascript e img
 * asset
 */
function asset(string $path): string
{
    return __URL__."/public/{$path}";
}


/**
 * Urls
 *
 * @param string $uri
 * @return string
 */
function url(string $uri = null): string
{
    if ($uri){
        return __URL__."/{$uri}";
    }
    return __URL__;
}

