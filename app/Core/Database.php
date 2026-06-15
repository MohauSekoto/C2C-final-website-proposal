<?php
// app/Core/Database.php
namespace App\Core;

use PDO;
use PDOException;

class Database {
    private $connection;

    public function __construct() {
        // Load .env file if it exists
        $envFile = __DIR__ . '/../../.env';
        if (file_exists($envFile)) {
            $env = parse_ini_file($envFile);
            foreach ($env as $key => $value) {
                putenv("$key=$value");
                $_ENV[$key] = $value;
            }
        }

        $host = getenv('DB_HOST') ?: 'localhost';
        $port = getenv('DB_PORT') ?: '3306';
        $dbname = getenv('DB_NAME') ?: 'kasibuy';
        $username = getenv('DB_USER') ?: 'root';
        $password = getenv('DB_PASS') ?: '';
        $useSsl = getenv('DB_SSL') === 'true';
        
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false
        ];

        if ($useSsl) {
            $options[PDO::MYSQL_ATTR_SSL_CA] = __DIR__ . '/../../ca.pem';
            $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
        }

        try {
            // First connect without dbname to ensure the database exists (only if not using SSL/Aiven directly)
            if (!$useSsl) {
                $tempDsn = "mysql:host={$host};port={$port};charset=utf8mb4";
                $tempConn = new PDO($tempDsn, $username, $password, $options);
                $tempConn->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            }
            
            // Now connect to the actual database
            $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
            $this->connection = new PDO($dsn, $username, $password, $options);
        } catch (PDOException $e) {
            die("Database Connection failed: " . $e->getMessage());
        }
    }

    public function query($sql, $params = []) {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function getConnection() {
        return $this->connection;
    }
}
