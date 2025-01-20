
-- +migrate Up
CREATE TABLE IF NOT EXISTS users_interests (
    id_user bigserial,
    id_interest bigserial,

);
-- +migrate Down
