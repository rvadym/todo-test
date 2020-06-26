<?php declare(strict_types=1);

namespace ToDoTest\Adapters;

abstract class Helpers
{
    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public static function validatePassword(string $password, string $passwordHash): bool
    {
        return password_verify($password, $passwordHash);
    }
}
