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
        "host"=>"smtp.sendgrid.net",
        "port"=>"587",
        "user"=>"apikey",
        "passwd"=>"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",
        "from_name"=>"SALVA|FRAMEWORK",
        "from_email"=>"drycode7@gmail.com"
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

