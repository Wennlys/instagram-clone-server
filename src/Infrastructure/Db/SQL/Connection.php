<?php

declare(strict_types=1);

namespace App\Infrastructure\Db\SQL;

use Exception;
use PDO;
use PDOException;

class Connection
{
    // private const OPTIONS = [
    //     PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    //     PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    //     PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
    //     PDO::ATTR_CASE => PDO::CASE_NATURAL,
    // ];

    /** @var Exception|PDOException */
    public static $error;

    private static ?Connection $instance = null;

    private ?PDO $conn = null;

    private function __construct()
    {
        try {
            $this->conn = new PDO(
                $_ENV['HOST'].$_ENV['NAME'],
                $_ENV['USER'],
                $_ENV['PASSWORD'],
                // self::OPTIONS
            );
        } catch (PDOException $e) {
            self::$error = $e->getMessage();
        }
    }

    public static function getInstance(): self
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getConnection(): ?PDO
    {
        return $this->conn;
    }
}
