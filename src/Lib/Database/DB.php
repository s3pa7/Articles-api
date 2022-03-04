<?php


declare(strict_types=1);

namespace App\Lib\Database;

/**
 * Class DB
 */
class DB extends \PDO
{

    /**
     * @var null
     */
    private static $instance = null;

    /**
     * @param array $settings
     * @throws \Exception
     */
    private function __construct(array $settings)
    {
        try {
            parent::__construct(
                "mysql:host={$settings['host']};dbname={$settings['database']}",
                $settings['username'],
                $settings['password'],
                [
                    self::ATTR_ERRMODE => self::ERRMODE_EXCEPTION,
                    self::ATTR_DEFAULT_FETCH_MODE => self::FETCH_ASSOC,
                    self::ATTR_PERSISTENT => true,
                ]
            );
        } catch (\PDOException $e) {
            throw new \Exception("Can't create new PDO instance: "
                . $e->getMessage());
        }
    }

    /**
     * @param array $settings
     * @return DB
     */
    public static function getInstance(array $settings): DB
    {
        if (!self::$instance) {
            self::$instance = new DB($settings);
        }

        return self::$instance;
    }

}
