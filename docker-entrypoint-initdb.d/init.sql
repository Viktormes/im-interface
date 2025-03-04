CREATE DATABASE IF NOT EXISTS iim_interface;

-- Check if the user exists before creating it
CREATE USER IF NOT EXISTS 'user'@'%' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON iim_interface.* TO 'user'@'%';
FLUSH PRIVILEGES;
