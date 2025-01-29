<?php

namespace models;

use models\DB;
use InvalidArgumentException;
use PDO;
use PDOException;


class Model
{
    /**
     * Validates and safely escapes table or column names to prevent SQL injection.
     *
     * @param string $name Table or column name.
     * @return string Escaped name suitable for SQL usage.
     * @throws InvalidArgumentException If the name contains invalid characters.
     */
    private function escape(string $name): string
    {
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $name)) {
            throw new \InvalidArgumentException("Invalid table or column name: $name");
        }
        return "`$name`";
    }

    private PDO $pdo; // Database connection
    protected string $table; // Table name
    protected string $primaryKey; // Primary key

    /**
     * Constructor to initialize the database connection, table name, and primary key.
     *
     * @param string $table - Table name to manipulate
     * @param string $primaryKey - Primary key column name, default is 'id'
     */
    public function __construct(string $table, string $primaryKey = 'id')
    {
        $this->pdo = DB::new();
        $this->table = $table;
        $this->primaryKey = $primaryKey;
    }

    /**
     * Create a new record in the table.
     *
     * @param array $attributes - Associative array of column names (keys) and values
     * @return bool - True if the record is created successfully, false otherwise
     */
    public function create(array $attributes): bool
    {
        $columns = implode(',', array_keys($attributes));
        $placeholders = ':' . implode(',:', array_keys($attributes));

        $sql = "INSERT INTO " . $this->escape($this->table) . " ($columns) VALUES ($placeholders)";

        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($attributes);
        } catch (PDOException $e) {
            // Handle error (log if required)
            return false;
        }
    }

    /**
     * Update a record in the table by its ID.
     *
     * @param int $id - Identifier of the row to update
     * @param array $attributes - Associative array of column names (keys) and values
     * @return bool - True if the record is updated successfully, false otherwise
     */
    public function update(int $id, array $attributes): bool
    {
        $setClause = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($attributes)));
        $sql = "UPDATE " . $this->escape($this->table) . " SET $setClause WHERE " . $this->escape($this->primaryKey) . " = :id";

        try {
            $stmt = $this->pdo->prepare($sql);
            $attributes['id'] = $id;
            return $stmt->execute($attributes);
        } catch (PDOException $e) {
            // Handle error (log if required)
            return false;
        }
    }

    /**
     * Retrieve records based on a specified attribute and value.
     *
     * @param string $attribute - Column name to filter by
     * @param mixed $value - Value to match against the attribute
     * @return array|false - Array of matching records or false on failure
     */
    public function getBy(string $attribute, $value): array|false
    {
        $sql = "SELECT * FROM " . $this->escape($this->table) . " WHERE " . $this->escape($attribute) . " = :value";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['value' => $value]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle error (log if required)
            return false;
        }
    }

    /**
     * Delete a record from the table by its ID.
     *
     * @param int $id - Identifier of the row to delete
     * @return bool - True if the record is deleted successfully, false otherwise
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM " . $this->escape($this->table) . " WHERE " . $this->escape($this->primaryKey) . " = :id";

        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            // Handle error (log if required)
            return false;
        }
    }
}