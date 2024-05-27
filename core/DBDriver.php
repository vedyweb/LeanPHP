<?php

namespace leanphp\core;

use PDO;
use PDOException;
use leanphp\core\Logger;
use leanphp\core\ErrorHandler;

class DBDriver {
    private static $instance = null;
    private $connection;

    private function __construct() {
        $this->connect();
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function connect() {
        $driver = getenv('db.driver');

        try {
            switch ($driver) {
                case 'sqlite':
                    $this->connectSQLite();
                    break;
                case 'pgsql':
                    $this->connectPostgres();
                    break;
                case 'mysql':
                    $this->connectMySQL();
                    break;
                case 'oci':
                    $this->connectOracle();
                    break;
                default:
                    throw new PDOException("Unsupported driver: $driver");
            }
        } catch (PDOException $e) {
            Logger::logError($e);
            ErrorHandler::handle($e);
        }
    }

    private function connectSQLite() {
        $path = getenv('db.path');
        $this->connection = new PDO("sqlite:$path");
        Logger::logInfo("Connected to SQLite database at $path");
    }

    private function connectPostgres() {
        $dsn = "pgsql:host=" . getenv('db.host') . ";port=" . getenv('db.port') . ";dbname=" . getenv('db.name');
        $this->connection = new PDO($dsn, getenv('db.user'), getenv('db.pass'), [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        Logger::logInfo("Connected to PostgreSQL database at " . getenv('db.host'));
    }

    private function connectMySQL() {
        $dsn = "mysql:host=" . getenv('db.host') . ";port=" . getenv('db.port') . ";dbname=" . getenv('db.name') . ";charset=utf8mb4";
        $user = getenv('db.user');
        $pass = getenv('db.pass');

        // Eğer parola boşsa, PDO'yu parolasız bağlantı kuracak şekilde yapılandıralım
        if ($pass === false || $pass === null || $pass === '') {
            $this->connection = new PDO($dsn, $user, '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        } else {
            $this->connection = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        }

        Logger::logInfo("Connected to MySQL database at " . getenv('db.host'));
    }

    private function connectOracle() {
        $dsn = "oci:dbname=" . getenv('db.name') . ";charset=UTF8";
        $this->connection = new PDO($dsn, getenv('db.user'), getenv('db.pass'), [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        Logger::logInfo("Connected to Oracle database with name " . getenv('db.name'));
    }

    public function getConnection() {
        return $this->connection;
    }
}
