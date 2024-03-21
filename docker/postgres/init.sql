-- Grant permissions to drop and create the database
ALTER USER "lo-exc" CREATEDB;

-- Ensure the lo-exc user owns the lo-exc database
ALTER DATABASE "lo-exc" OWNER TO "lo-exc";
