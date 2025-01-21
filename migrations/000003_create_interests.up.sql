CREATE TABLE IF NOT EXISTS interests (
    id bigserial PRIMARY KEY,
    name text UNIQUE NOT NULL,
);