<?php

namespace App\Helpers;

class StaffHelper
{
    public static function user(): ?array
    {
        return session('staff');
    }

    public static function isAdmin(): bool
    {
        return session('staff.type') === 'admin';
    }

    public static function isOperator(): bool
    {
        return session('staff.type') === 'operator';
    }

    public static function id(): ?int
    {
        return session('staff.id');
    }

    public static function nama(): string
    {
        return session('staff.nama', 'User');
    }
}
