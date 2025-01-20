
-- +migrate Up
CREATE TABLE IF NOT EXISTS users (
    id bigserial PRIMARY KEY,
    created_at timestamp(0) with time zone NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    username text NOT NULL,
    email citext UNIQUE NOT NULL,
    password_hash bytea NOT NULL,
    avatar text NOT NULL,
);

-- +migrate Down
DROP TABLE IF EXISTS users;