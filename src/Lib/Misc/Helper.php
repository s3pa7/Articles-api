<?php


declare(strict_types=1);

namespace App\Lib\Misc;

/**
 * Class Helper
 */
class Helper
{

    /**
     * @var string[]
     */
    protected static $messages = [
        // Success Codes
        Constants::OK => 'OK',
        // Client error
        Constants::BAD_REQUEST => 'Bad Request',
        Constants::UNAUTHORIZED => 'Unauthorized',
        Constants::ROUTE_NOT_FOUND => 'Route not found',
        Constants::METHOD_NOT_ALLOWED => 'Method not allowed',
        Constants::SERVICE_NOT_EXIST => 'The service does not exist',
        Constants::BAD_SERVICE_DATA => 'Bad service data',
        // Server error
        Constants::ERROR_PROCCESSING_REQUEST => 'Error processing request',
    ];

    /**
     * @param int $statusCode
     * @param array $data
     * @return string
     */
    public static function responseWith(int $statusCode = 200, array $data = []): string
    {

        $myData = [
            'meta' => [
                'code' => (int)$statusCode,
                'text' => isset(self::$messages[$statusCode]) ? self::$messages[$statusCode] : '',
            ],
            'data' => (object)$data,
        ];

        return json_encode($myData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }





}
