ALTER TABLE IF EXISTS users_interests
    ADD CONSTRAINT fk_user
        FOREIGN KEY(id_user)
            REFERENCES users(id),
    ADD CONSTRAINT fk_interest
        FOREIGN KEY(id_interest)
            REFERENCES interests(id),
    ADD CONSTRAINT pk_users_interests
        PRIMARY KEY(id_user, id_interest);