ALTER TABLE IF EXISTS users_interests (
    ADD CONSTRAINT IF NOT EXISTS fk_user
        FOREIGN KEY(id_user)
            REFERENCES users(id),
    ADD CONSTRAINT IF NOT EXISTS fk_interest
        FOREIGN KEY(id_interest)
            REFERENCES interests(id),
    ADD CONSTRAINT IF NOT EXISTS pk_users_interests
        PRIMARY KEY(id_user, id_interest)
    );