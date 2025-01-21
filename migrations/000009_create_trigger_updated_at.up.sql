-- Define the trigger function
CREATE OR REPLACE FUNCTION set_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Create the trigger for users
CREATE TRIGGER update_users_updated_at_column
    BEFORE UPDATE ON users
    FOR EACH ROW
    EXECUTE FUNCTION set_updated_at_column();

-- Create the trigger for users
CREATE TRIGGER update_interests_updated_at_column
    BEFORE UPDATE ON interests
    FOR EACH ROW
    EXECUTE FUNCTION set_updated_at_column();