<?php
function getDBConnection() {
    $envFile = __DIR__ . '/../../.env.local';
    $dbUrl = '';
    
    if (file_exists($envFile)) {
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), 'DATABASE_URL=') === 0) {
                $dbUrl = str_replace('DATABASE_URL=', '', trim($line));
                $dbUrl = trim($dbUrl, '"\'');
                break;
            }
        }
    }
    
    if (empty($dbUrl)) {
        throw new Exception("DATABASE_URL not found in .env.local");
    }
    
    // Parse mysql://user:pass@host:port/dbname
    $parsed = parse_url($dbUrl);
    $host = $parsed['host'];
    $port = $parsed['port'] ?? 3306;
    $user = $parsed['user'] ?? '';
    $pass = $parsed['pass'] ?? '';
    $dbname = isset($parsed['path']) ? ltrim($parsed['path'], '/') : '';
    
    // Aiven adds ?ssl-mode=REQUIRED which might be in query, ignore for DSN
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false, // Required for Aiven without CA bundle
    ];
    
    return new PDO($dsn, $user, $pass, $options);
}
?>
