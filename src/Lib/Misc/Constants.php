<?php


declare(strict_types=1);

namespace App\Lib\Misc;

/**
 * Class Constants
 */
abstract class Constants
{

    // OAuth
    const OAUTH_TOKEN_EXPIRE = 3600;
    const OAUTH_AUD = 'localhost';




    // DB Tables
    const TABLE_ARTICLES = 'articles';


    const MAX_FILE_SIZE = 2097152; // 2 MB


    /* Response codes */
    // Success Codes
    const OK = 200;

    // Client error
    const BAD_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const ROUTE_NOT_FOUND = 404;
    const METHOD_NOT_ALLOWED = 405;
    const SERVICE_NOT_EXIST = 406;
    const BAD_SERVICE_DATA = 407;

    // Server error
    const ERROR_PROCCESSING_REQUEST = 500;

}
