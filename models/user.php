<?php

namespace models;

    use DateTime;
    use Exception;
    require_once('includes/utils.php');
    require_once('includes/db.php');


/**
 * Represents a user in the system with their username, email, avatar, password hash, ID, interests,
 * and timestamp fields for creation and updates.
 */
class User
{

    /**
     * @var int The unique identifier of the user.
     */
    private int $id;

    /**
     * @var array An array of Interest objects representing the user's interests.
     */
    private array $interests;

    /**
     * @var string The username of the user.
     */
    private string $username;

    /**
     * @var string The email address of the user.
     */
    private string $email;

    /**
     * @var string The hashed password of the user.
     */
    private string $password_hash;

    /**
     * @var string The URL or path to the user's avatar.
     */
    private string $avatar;

    /**
     * @var DateTime The timestamp when the user was created.
     */
    private DateTime $created_at;

    /**
     * @var DateTime The timestamp when the user was last updated.
     */
    private DateTime $updated_at;

    /**
     * Initializes the User object with the provided details.
     *
     * @param int $id The unique identifier of the user.
     * @param string $username The username of the user.
     * @param string $email The email address of the user.
     * @param string $avatar The avatar of the user.
     * @param array $interests An optional array of Interest objects representing the user's interests.
     * @param string $password_hash The hashed password of the user.
     * @param DateTime|null $created_at The timestamp when the user was created (optional).
     * @param DateTime|null $updated_at The timestamp when the user was last updated (optional).
     */
    private function __construct(int $id, string $username, string $email, string $avatar, array $interests = [], string $password_hash = '', ?DateTime $created_at = null, ?DateTime $updated_at = null)
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->avatar = $avatar;
        $this->interests = $interests;
        $this->password_hash = $password_hash;
        $this->created_at = $created_at ?? new DateTime();
        $this->updated_at = $updated_at ?? new DateTime();
    }

    /**
     * Creates a new User object with a generated avatar, hashed password, and default values
     * and push it to the database.
     *
     * @param string $username The username of the user.
     * @param string $email The email address of the user.
     * @param string $password The plain text password of the user.
     * @return User|null A new User object if it was successfully created and null otherwise.
     */
    public static function new(string $username, string $email, string $password): ?User
    {
        $avatar = 'https://ui-avatars.com/api/?name=' . $username . '&background=random&size=256&rounded=true';
        $user = new User(
            -1,
            $username,
            $email,
            $avatar
        );
        $user->setPasswordHash(hashPassword($password));

        if ($user->create()) {
            return $user;
        }

        return null;
    }

    /**
     * Gets the username of the user.
     *
     * @return string The username of the user.
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Sets the username of the user.
     *
     * @param string $username The new username to set.
     * @return void
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * Gets the email address of the user.
     *
     * @return string The email address of the user.
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Gets the hashed password of the user.
     *
     * @return string The hashed password of the user.
     */
    public function getPasswordHash(): string
    {
        return $this->password_hash;
    }

    /**
     * Gets the creation timestamp of the user.
     *
     * @return DateTime The timestamp when the user was created.
     */
    public function getCreatedAt(): DateTime
    {
        return $this->created_at;
    }

    /**
     * Sets the creation timestamp of the user.
     *
     * @param DateTime $created_at The new creation timestamp.
     * @return void
     */
    public function setCreatedAt(DateTime $created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * Gets the last updated timestamp of the user.
     *
     * @return DateTime The timestamp when the user was last updated.
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updated_at;
    }

    /**
     * Sets the last updated timestamp of the user.
     *
     * @param DateTime $updated_at The new last updated timestamp.
     * @return void
     */
    public function setUpdatedAt(DateTime $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    /**
     * Sets the hashed password of the user.
     *
     * @param string $password_hash The hashed password to set for the user.
     * @return void
     */
    public function setPasswordHash(string $password_hash): void
    {
        $this->password_hash = $password_hash;
    }

    /**
     * Sets the email address of the user.
     *
     * @param string $email The new email to set.
     * @return void
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * Gets the avatar of the user.
     *
     * @return string The avatar URL or path of the user.
     */
    public function getAvatar(): string
    {
        return $this->avatar;
    }

    /**
     * Sets the avatar of the user.
     *
     * @param string $avatar The new avatar URL or path to set.
     * @return void
     */
    public function setAvatar(string $avatar): void
    {
        $this->avatar = $avatar;
    }

    /**
     * Gets the ID of the user.
     *
     * @return int The unique identifier of the user.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets the ID of the user.
     *
     * @param int $id The new ID to set.
     * @return void
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Gets the interests of the user.
     *
     * @return array An array of Interest objects.
     */
    public function getInterests(): array
    {
        return $this->interests;
    }

    /**
     * Sets the interests of the user.
     *
     * @param array $interests The new array of Interest objects to set.
     * @return void
     */
    public function setInterests(array $interests): void
    {
        $this->interests = $interests;
    }

    /**
     * Inserts a new user record into the database.
     *
     * @return bool True if the user was created successfully, false otherwise.
     */
    public function create(): bool
    {
        $sql = '
                    INSERT INTO users(username, email, avatar, password_hash)
                    VALUES(:username, :email, :avatar, :password_hash)
                    ';

        try {
            $rowCount = executeQuery(
                $sql,
                [
                    'username' => $this->username,
                    'email' => $this->email,
                    'avatar' => $this->avatar,
                    'password_hash' => $this->password_hash
                ]
            );
            return $rowCount > 0;

        } catch (Exception $e) {
            // TODO -> implement logging
            return false;
        }
    }

    /**
     * Updates the user's details in the database.
     *
     * @return bool True if the user was updated successfully, false otherwise.
     */
    public function update(): bool
    {
        $sql = '
                    UPDATE users
                    SET username = :username, email = :email, avatar = :avatar, password_hash = :password_hash
                    WHERE id = :id;
                    ';

        try {
            $rowCount = executeQuery(
                $sql,
                [
                    'id' => $this->id,
                    'username' => $this->username,
                    'email' => $this->email,
                    'avatar' => $this->avatar,
                    'password_hash' => $this->password_hash
                ]
            );
            return $rowCount > 0;
        } catch (Exception $e) {
            // TODO -> implement logging
            return false;
        }
    }

    /**
     * Deletes a user record from the database by ID.
     *
     * @param int $id The unique identifier of the user to delete.
     * @return bool True if the user was deleted successfully, false otherwise.
     */
    public static function delete(int $id): bool
    {
        $sql = '
                    DELETE FROM users
                    WHERE id = :id;
                    ';

        try {
            $rowCount = executeQuery($sql, ['id' => $id]);
            return $rowCount > 0;

        } catch (Exception $e) {
            // TODO -> implement logging
            return false;
        }
    }

    /**
     * Retrieves a user from the database along with their related interests.
     *
     * @param int $id The unique identifier of the user.
     * @return User|null The User object if found, or null if not found.
     * @throws \DateMalformedStringException
     */
    public static function get(int $id): ?User
    {
        $sql = '
        SELECT u.id, u.username, u.email, u.avatar, u.created_at, u.updated_at, i.id AS interest_id, i.name AS interest_name
        FROM users u 
        LEFT JOIN users_interests ui ON u.id = ui.user_id
        LEFT JOIN interests i ON ui.interest_id = i.id
        WHERE u.id = :id;
    ';

        $results = executeQuery($sql, ['id' => $id]);

        if (empty($results)) {
            return null;
        }

        $userId = $results[0]['id'];
        $user = new User(
            $userId,
            $results[0]['username'],
            $results[0]['email'],
            $results[0]['avatar'] ?? null,
            [],
            '',
            new DateTime($results[0]['created_at']),
            new DateTime($results[0]['updated_at'])
        );

        foreach ($results as $row) {
            if ($row['interest_id']) {
                $user->setInterests(array_merge($user->getInterests(), [$row['interest_name']]));
            }
        }

        return $user;
    }

    /**
     * Checks if a user record exists in the database by email.
     *
     * @param string $email The email address to check for existence.
     * @return bool True if a user with the given email exists, false otherwise.
     */
    public static function exists(string $email): bool
    {
        $sql = '
                SELECT id
                FROM users
                WHERE email = :email;
                ';
        $results = executeQuery($sql, ['email' => $email]);
        if (empty($results)) {
            return false;
        }

        return true;
    }

    /**
     * Authenticates a user by their email and password.
     *
     * @param string $email The user's email address.
     * @param string $password The plain text password for verification.
     * @return User|null The User object if login is successful, or null otherwise.
     */
    public static function login(string $email, string $password): ?User
    {
        $sql = '
                SELECT id, password_hash, email
                FROM users
                WHERE email = :email;
                ';

        $results = executeQuery($sql, ['email' => $email]);

        if (empty($results)) {
            return null;
        }

        if (verifyPassword($password, $results[0]['password_hash'])) {
            try {
                $user = User::get($results[0]['id']);
                return $user;
            } catch (Exception $e) {
                // TODO -> implement logging
                return null;
            }
        }

        return null;
    }

    /**
     * Retrieves all users from the database along with their related interests.
     *
     * @return array An array of User objects representing all users in the database.
     */
    public static function getAll(): array
    {
        $sql = '
            SELECT u.id, u.username, u.email, u.avatar, u.created_at, u.updated_at, i.name AS interest_name
            FROM users u
            LEFT JOIN users_interests ui ON u.id = ui.user_id
            LEFT JOIN interests i ON ui.interest_id = i.id
            ';
        $results = executeQuery($sql);

        if (empty($results)) {
            return [];
        }

        $users = [];
        $userInterests = [];

        foreach ($results as $row) {
            $userId = $row['id'];

            if (!isset($userInterests[$userId])) {
                $userInterests[$userId] = [];
            }

            // Collect interests for each user
            if (isset($row['interest_name'])) {
                $userInterests[$userId][] = $row['interest_name'];
            }
        }

        foreach ($results as $row) {
            $userId = $row['id'];

            // Create a new User object and set the collected interests
            $user = new User(
                $row['id'],
                $row['username'],
                $row['email'],
                $row['avatar'] ?? null,
                [],
                '',
                new DateTime($row['created_at']),
                new DateTime($row['updated_at'])
            );

            // Set the collected interests for each user
            if (isset($userInterests[$userId])) {
                $user->setInterests($userInterests[$userId]);
            }

            $users[] = $user;
        }

        return $users;
    }

}