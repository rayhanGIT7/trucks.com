<?php

namespace App\Models;

use App\Core\Model;

class User extends Model
{
    public const ROLE_CUSTOMER = 'customer';
    public const ROLE_ADMIN = 'admin';

    protected static string $table = 'users';

    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $password_hash = '';
    public string $role = self::ROLE_CUSTOMER;
    public ?string $created_at = null;
    public ?string $updated_at = null;

    public static function register(string $name, string $email, string $phone, string $password): self
    {
        return self::create([
            'name'          => $name,
            'email'         => strtolower($email),
            'phone'         => $phone,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'role'          => self::ROLE_CUSTOMER,
        ]);
    }

    public static function findByEmailOrPhone(string $login): ?self
    {
        return self::queryOne('SELECT * FROM users WHERE email = ? OR phone = ? LIMIT 1', [strtolower($login), $login]);
    }

    /** Is this email used by another user? ($exceptId = the current user when editing a profile) */
    public static function emailTaken(string $email, int $exceptId = 0): bool
    {
        return (bool) self::scalar('SELECT COUNT(*) FROM users WHERE email = ? AND id <> ?', [strtolower($email), $exceptId]);
    }

    public static function phoneTaken(string $phone, int $exceptId = 0): bool
    {
        return (bool) self::scalar('SELECT COUNT(*) FROM users WHERE phone = ? AND id <> ?', [$phone, $exceptId]);
    }

    public static function countCustomers(): int
    {
        return (int) self::scalar('SELECT COUNT(*) FROM users WHERE role = ?', [self::ROLE_CUSTOMER]);
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function changePassword(string $newPassword): void
    {
        $this->update(['password_hash' => password_hash($newPassword, PASSWORD_DEFAULT)]);
    }
}
