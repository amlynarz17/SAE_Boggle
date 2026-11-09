<?php
function getPdo(): PDO {
    static $pdo = null;
    if ($pdo) return $pdo;
    $u = parse_url(getenv('DATABASE_URL'));
    $dsn = sprintf('pgsql:host=%s;port=%d;dbname=%s',
        $u['host'], $u['port'] ?? 5432, ltrim($u['path'], '/'));
    $pdo = new PDO($dsn, $u['user'], $u['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    return $pdo;
}
