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

    // Default SIP channel
    const CHANNEL_TYPE = 'PJSIP';

    // Tiff files directory
    const TIFF_DIRECTORY = '/tmp/fax/';

    // Tiff file extension
    const TIFF_EXTENSION = '.tiff';

    // Tiff files owner and group
    const TIFF_OWNER = 'asterisk';

    // DB Tables
    const TABLE_ARTICLES = 'articles';


    // File upload (word, csv, excel, pdf)
    const ALLOWED_FILE_TYPES = [
        'text/csv',
        'application/pdf',
        'application/msword',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    ];
    const MAX_FILE_SIZE = 2097152; // 2 MB

    // Call originating
    const CALL_ACTION = 'originate';
    const CALL_CONTEXT = 'outgoing';
    const CALL_PRIORITY = 1;
    const CALL_CALLERID = '123';
    const CALL_ASYNC = true;

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
