ALTER TABLE IF EXISTS users_interests (
    DROP CONSTRAINT IF EXISTS fk_user,
    DROP CONSTRAINT IF EXISTS fk_interest,
    DROP CONSTRAINT IF EXISTS pk_users_interests
    );