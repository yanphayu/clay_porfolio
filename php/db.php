<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

/**
 * Shared PDO connection. Returns null when MySQL is unreachable so the
 * public site can gracefully fall back to the flat-file demo content.
 */
function portfolio_db(bool $retry = false): ?PDO
{
    static $pdo = null;
    static $tried = false;

    if ($tried && !$retry) {
        return $pdo;
    }
    $tried = true;
    $pdo = null;

    try {
        $pdo = new PDO(
            sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', DB_HOST, DB_PORT, DB_NAME),
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ],
        );
    } catch (Throwable) {
        $pdo = null;
    }

    return $pdo;
}
