<?php

declare(strict_types=1);

namespace Tests;

use PDO;

class DatabaseSetUp
{
    public static function up(PDO $pdoConnection): void
    {
        $password = password_hash('123456', PASSWORD_DEFAULT);
        $dateNow = now();

        $pdoConnection->exec("
            DROP TABLE IF EXISTS users;
            CREATE TABLE users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username varchar(255) NOT NULL,
                email VARCHAR(255) NOT NULL,
                name varchar(255) NOT NULL,
                password VARCHAR(255) NOT NULL,
                created_at DATETIME,
                updated_at DATETIME,
                UNIQUE (email, username)
            );

            INSERT INTO users (username, email, name, password, created_at, updated_at) VALUES ('user1', 'user1@mail.com', 'User One', '{$password}', '{$dateNow}', '{$dateNow}');
            INSERT INTO users (username, email, name, password, created_at, updated_at) VALUES ('user2', 'user2@mail.com', 'User Two', '{$password}', '{$dateNow}', '{$dateNow}');
            INSERT INTO users (username, email, name, password, created_at, updated_at) VALUES ('user3', 'user3@mail.com', 'User Three', '{$password}', '{$dateNow}', '{$dateNow}');

            DROP TABLE IF EXISTS posts;
            CREATE TABLE posts (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                image_url varchar(255) NOT NULL,
                description varchar(255) NOT NULL,
                user_id INTEGER NOT NULL,
                created_at DATETIME,
                updated_at DATETIME,
                FOREIGN KEY (user_id) REFERENCES users(id)
            );

            INSERT INTO posts (image_url, description, user_id, created_at, updated_at) VALUES ('/tmp/avatar.jpg', 'Nothing to see here :P', 1, '{$dateNow}', '{$dateNow}');
            INSERT INTO posts (image_url, description, user_id, created_at, updated_at) VALUES ('/tmp/avatar.jpg', 'Nothing to see here :P', 2, '{$dateNow}', '{$dateNow}');
            INSERT INTO posts (image_url, description, user_id, created_at, updated_at) VALUES ('/tmp/avatar.jpg', 'Nothing to see here :P', 3, '{$dateNow}', '{$dateNow}');
            INSERT INTO posts (image_url, description, user_id, created_at, updated_at) VALUES ('/tmp/avatar.jpg', 'Nothing to see here :P', 1, '{$dateNow}', '{$dateNow}');
            INSERT INTO posts (image_url, description, user_id, created_at, updated_at) VALUES ('/tmp/avatar.jpg', 'Nothing to see here :P', 2, '{$dateNow}', '{$dateNow}');
            INSERT INTO posts (image_url, description, user_id, created_at, updated_at) VALUES ('/tmp/avatar.jpg', 'Nothing to see here :P', 3, '{$dateNow}', '{$dateNow}');

            DROP TABLE IF EXISTS followers;
            CREATE TABLE followers (
                following_user INTEGER NOT NULL,
                followed_user INTEGER NOT NULL,
                FOREIGN KEY (following_user) REFERENCES users(id),
                FOREIGN KEY (followed_user) REFERENCES users(id)
            );

            INSERT INTO followers (following_user, followed_user) VALUES (1, 2);
            INSERT INTO followers (following_user, followed_user) VALUES (3, 1);
            INSERT INTO followers (following_user, followed_user) VALUES (1, 3);
        ");
    }
}
