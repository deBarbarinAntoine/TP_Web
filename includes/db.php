<?php

/**
 * Establishes a PostgreSQL connection using PDO.
 *
 * @return PDO The PDO instance connected to the PostgreSQL database.
 * @throws PDOException If the connection fails.
 */
function createPostgresConnection(): PDO
{
    $host = getenv('DB_HOST'); // Retrieve the host from the environment variable DB_HOST
    $port = getenv('DB_PORT'); // Retrieve the port from the environment variable DB_PORT
    $dbname = getenv('DB_NAME'); // Retrieve the database name from the environment variable DB_NAME
    $username = getenv('DB_USERNAME'); // Retrieve the username from the environment variable DB_USERNAME
    $password = getenv('DB_PASSWORD'); // Retrieve the password from the environment variable DB_PASSWORD

    try {
        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
        $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_AUTOCOMMIT => false,
        ];
        return new PDO($dsn, $username, $password, $options);
    } catch (PDOException $e) {
        die('Connection failed: ' . $e->getMessage());
    }
}

/**
 * Executes a prepared statement and returns the result.
 *
 * @param string $sql The SQL query to execute.
 * @param array $params An associative array of parameters for the prepared statement.
 * @return int|array|null The result of the query, depending on the type of query executed.
 */
function executeQuery(string $sql, array $params = []): int|array|null
{
    $pdo = createPostgresConnection();
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    // DEBUG
//    print_r($stmt->errorInfo());
//    print_r($stmt->rowCount());

    $result = $stmt->fetchAll();

    if (str_contains($sql, 'SELECT')) {
        return $result;
    } elseif (str_contains($sql, 'INSERT') || str_contains($sql, 'UPDATE') || str_contains($sql, 'DELETE')) {
        return $stmt->rowCount();
    }
    return null;
}

?>