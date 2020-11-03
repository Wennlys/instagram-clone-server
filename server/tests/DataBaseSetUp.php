<?php

declare(strict_types=1);

namespace Tests;

use App\Infrastructure\Connection;

class DataBaseSetUp
{
    public static function up(): void
    {
        $password1 = '$2y$10$OMMj4VRsyovweGTmDJmDy.T4gCK7LW.pLXk6IY7psR9B.dsxpHJaG';
        $password2 = '$2y$10$jXJDu3/ZrbueY2wYl2kb8Owvv.BkXKNipHs2wmtdemUVCNzv1Pcja';
        $dateNow = now();

        Connection::getInstance()->getConnection()->exec("
                DROP TABLE IF EXISTS users;

                CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username varchar(255) NOT NULL,
                email VARCHAR(255) NOT NULL,
                name varchar(255) NOT NULL,
                password VARCHAR(255) NOT NULL,
                created_at DATETIME,
                updated_at DATETIME,
                UNIQUE (email, username));

                INSERT INTO users (username, email, name, password, created_at, updated_at) VALUES ('user1', 'user1@mail.com', 'User One', '{$password1}', '{$dateNow}', '{$dateNow}');
                INSERT INTO users (username, email, name, password, created_at, updated_at) VALUES ('user2', 'user2@mail.com', 'User Two', '{$password2}', '{$dateNow}', '{$dateNow}');
        ");
    }
}
