--create database
CREATE DATABASE IF NOT EXISTS drk_auth;
USE drk_auth;

--CREATE USER 'drk_user'@'localhost' IDENTIFIED BY '1';
--CREATE USER 'drk_user'@'127.0.0.1' IDENTIFIED BY '1';

--GRANT ALL PRIVILEGES ON drk_auth.* TO 'drk_user'@'localhost';
--GRANT ALL PRIVILEGES ON drk_auth.* TO 'drk_user'@'127.0.0.1';

--FLUSH PRIVILEGES;


--users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    passwd_hash VARCHAR(255) NOT NULL,
    birthday DATE NOT NULL,
    local_ass VARCHAR(100) NOT NULL,
    qualification VARCHAR(50) NOT NULL,
    is_admin TINYINT(1) DEFAULT 0,
    last_modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- appointments table
CREATE TABLE IF NOT EXISTS appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(50) NOT NULL,
    qualification VARCHAR(50) NOT NULL,
    time_start DATETIME NOT NULL,
    time_end DATETIME NOT NULL,
    helper_num INT NOT NULL,
    app_description TEXT NOT NULL,
    last_modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- appointment_users table
CREATE TABLE IF NOT EXISTS appointment_users (
    appointment_id INT NOT NULL,
    user_id INT NOT NULL,
    time_start DATETIME NOT NULL,
    time_end DATETIME NOT NULL,
    approval_status ENUM('pending', 'approved', 'denied') NOT NULL DEFAULT 'pending',
    last_modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (appointment_id, user_id),
    FOREIGN KEY (appointment_id) REFERENCES appointments(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);


--INSERT INTO users (first_name, last_name, email, passwd_hash, birthday, local_ass, qualification, is_admin, last_modified)
--VALUES ('Admin', 'User', 'admin@example.com', '$2y$10$hashedpassword', '1970-01-01', 'some association', 'ersthelfer', 1, '1970-01-01 01:01:01');

--execute in MariaDB shell like so: sudo mariadb -u root -p < /database/drk_schema.sql
