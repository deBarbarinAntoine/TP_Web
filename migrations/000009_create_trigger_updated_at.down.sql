-- Drop the trigger on users table
DROP TRIGGER IF EXISTS update_users_updated_at_column ON users;

-- Drop the trigger on interests table
DROP TRIGGER IF EXISTS update_interests_updated_at_column ON interests;

-- Drop the trigger function
DROP FUNCTION IF EXISTS set_updated_at_column;