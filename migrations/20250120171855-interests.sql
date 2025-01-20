
-- +migrate Up
CREATE TABLE IF NOT EXISTS interests (
    id bigserial PRIMARY KEY,
    name text UNIQUE NOT NULL,
);

-- +migrate Down
DROP TABLE IF EXISTS interests;