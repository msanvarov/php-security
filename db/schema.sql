-- Schema for the php-security lab.
-- Loaded automatically by docker-compose; apply manually with:
--   mysql -u root -p php_security < db/schema.sql

CREATE DATABASE IF NOT EXISTS php_security CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE php_security;

CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS comments (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    body TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO users (email, name, password) VALUES
    ('alice@example.com', 'alice', ''),
    ('bob@example.com', 'bob', '')
ON DUPLICATE KEY UPDATE email = email;

INSERT INTO comments (name, body) VALUES
    ('alice', 'first comment'),
    ('bob', 'second comment');
