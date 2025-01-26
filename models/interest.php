<?php

namespace models;

    use DateTime;
    use Exception;

    require_once(dirname(__FILE__).'/../includes/db.php');

    /**
 * Represents an interest with a unique identifier, name, timestamps for creation and last update,
 * and associated user IDs.
 */
class Interest
{
    /**
     * @var int The unique identifier of the interest.
     */
    private int $id;

    /**
     * @var DateTime The timestamp when the interest was created.
     */
    private DateTime $created_at;

    /**
     * @var DateTime The timestamp when the interest was last updated.
     */
    private DateTime $updated_at;

    /**
     * @var string The name of the interest.
     */
    private string $name;

    /**
     * @var array A list of user IDs associated with the interest.
     */
    private array $user_ids;

    /**
     * Initializes the Interest object with the provided ID, name, user IDs, and optional timestamps for creation and updates.
     *
     * @param int $id The unique identifier of the interest.
     * @param string $name The name of the interest.
     * @param array $user_ids A list of user IDs associated with the interest.
     * @param DateTime|null $created_at The timestamp when the interest was created. Defaults to current datetime if null.
     * @param DateTime|null $updated_at The timestamp when the interest was last updated. Defaults to current datetime if null.
     */
    public function __construct(int $id, string $name, array $user_ids, ?DateTime $created_at = null, ?DateTime $updated_at = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->user_ids = $user_ids;
        $this->created_at = $created_at ?? new DateTime();
        $this->updated_at = $updated_at ?? new DateTime();
    }

    /**
     * Gets the unique identifier of the interest.
     *
     * @return int The ID of the interest.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets the unique identifier for the interest.
     *
     * @param int $id The new ID to set for the interest.
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Gets the name of the interest.
     *
     * @return string The name of the interest.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the name of the interest.
     *
     * @param string $name The new name to set for the interest.
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Gets the list of user IDs associated with the interest.
     *
     * @return array A list of associated user IDs.
     */
    public function getUserIds(): array
    {
        return $this->user_ids;
    }

    /**
     * Sets the list of user IDs associated with the interest.
     *
     * @param array $user_ids A new list of user IDs for the interest.
     */
    public function setUserIds(array $user_ids): void
    {
        $this->user_ids = $user_ids;
    }

    /**
     * Gets the creation timestamp of the interest.
     *
     * @return DateTime The created_at timestamp.
     */
    public function getCreatedAt(): DateTime
    {
        return $this->created_at;
    }

    /**
     * Sets the creation timestamp of the interest.
     *
     * @param DateTime $created_at The new created_at timestamp.
     * @return void
     */
    public function setCreatedAt(DateTime $created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * Gets the last update timestamp of the interest.
     *
     * @return DateTime The updated_at timestamp.
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updated_at;
    }

    /**
     * Sets the last update timestamp of the interest.
     *
     * @param DateTime $updated_at The new updated_at timestamp.
     * @return void
     */
    public function setUpdatedAt(DateTime $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    public static function exists(string $name): bool
    {
        $sql = 'SELECT COUNT(*) as count FROM interests WHERE name = :name';
        $result = executeQuery($sql, ['name' => $name]);

        return isset($result[0]['count']) && $result[0]['count'] > 0;
    }

    /**
     * Retrieves all interests from the database and their associated user IDs using a single query.
     *
     * @return array An array of Interest objects.
     */
    public static function getAllInterests(): array
    {
        $sql = '
            SELECT i.id, i.name, i.created_at, i.updated_at, ui.id_user
            FROM interests i
            LEFT JOIN users_interests ui ON i.id = ui.id_interest
            ORDER BY i.id;
        ';
        $results = executeQuery($sql);

        $interestsMap = [];
        foreach ($results as $row) {
            if (!isset($interestsMap[$row['id']])) {
                $interestsMap[$row['id']] = new Interest(
                    $row['id'],
                    $row['created_at'],
                    $row['updated_at'],
                    $row['name'],
                    []
                );
            }
            $interestsMap[$row['id']]->setUserIds(array_merge($interestsMap[$row['id']]->getUserIds(), [$row['id_user']]));
        }

        return array_values($interestsMap);
    }

    /**
     * Adds a new interest to the database.
     *
     * @param string $name The name of the interest to be added.
     * @return bool True if the interest was added successfully, false otherwise.
     */
    public static function create(string $name): bool
    {
        $sql = 'INSERT INTO interests (name) VALUES (:name)';
        try {
            $rowCount = executeQuery($sql, [
                'name' => $name
            ]);
            return $rowCount > 0;
        } catch (Exception $e) {
            // TODO -> implement logging
            return false;
        }
    }

    /**
     * Updates the name of an interest in the database.
     *
     * @param int $id The ID of the interest to update.
     * @param string $name The new name for the interest.
     * @return bool True if the interest was updated successfully, false otherwise.
     */
    public static function update(int $id, string $name): bool
    {
        $sql = 'UPDATE interests SET name = :name WHERE id = :id';
        try {
            $rowCount = executeQuery($sql, [
                'id' => $id,
                'name' => $name
            ]);
            return $rowCount > 0;
        } catch (Exception $e) {
            // Log or handle the error as necessary
            return false;
        }
    }

    /**
     * Deletes an interest from the database by its ID.
     *
     * @param int $id The ID of the interest to delete.
     * @return bool True if the interest was deleted successfully, false otherwise.
     */
    public static function delete(int $id): bool
    {
        $sql = 'DELETE FROM interests WHERE id = :id';
        try {
            $rowCount = executeQuery($sql, ['id' => $id]);
            return $rowCount > 0;
        } catch (Exception $e) {
            // Log or handle the error as necessary
            return false;
        }
    }

    /**
     * Retrieves a single Interest based on its ID, including its associated user IDs.
     *
     * @param int $id The ID of the interest to retrieve.
     * @return Interest|null The Interest object if found, or null if not found.
     */
    public static function getById(int $id): ?Interest
    {
        $sql = '
            SELECT i.id, i.name, i.created_at, i.updated_at, ui.id_user
            FROM interests i
            LEFT JOIN users_interests ui ON i.id = ui.id_interest
            WHERE i.id = :id
        ';
        $results = executeQuery($sql, ['id' => $id]);

        if (empty($results)) {
            return null;
        }

        // Initialize the Interest object using the first record
        $interest = new Interest(
            $results[0]['id'],
            $results[0]['created_at'],
            $results[0]['updated_at'],
            $results[0]['name'],
            []
        );

        // Collect all associated user IDs
        foreach ($results as $row) {
            if (!empty($row['id_user'])) {
                $interest->setUserIds(
                    array_merge($interest->getUserIds(), [$row['id_user']])
                );
            }
        }

        return $interest;
    }
}