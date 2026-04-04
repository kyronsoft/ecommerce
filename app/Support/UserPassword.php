<?php

namespace App\Support;

use App\Models\User;

class UserPassword
{
    public static function verifyAndUpgrade(User $user, string $plainPassword): bool
    {
        $storedPassword = (string) $user->password;
        $normalizedPassword = trim($storedPassword);

        if ($normalizedPassword === '') {
            return false;
        }

        $passwordInfo = password_get_info($normalizedPassword);
        $usesSupportedHash = ($passwordInfo['algoName'] ?? 'unknown') !== 'unknown';

        if ($usesSupportedHash) {
            if (! password_verify($plainPassword, $normalizedPassword)) {
                return false;
            }

            if (($passwordInfo['algoName'] ?? 'unknown') !== 'bcrypt') {
                // Rehash legacy algorithms with the application's current hasher.
                $user->forceFill(['password' => $plainPassword])->save();
            } elseif ($normalizedPassword !== $storedPassword) {
                $user->newQuery()
                    ->whereKey($user->getKey())
                    ->update(['password' => $normalizedPassword]);
            }

            return true;
        }

        if (! hash_equals($normalizedPassword, $plainPassword)) {
            return false;
        }

        // Migrate old plain-text passwords to a secure hash after first valid login.
        $user->forceFill(['password' => $plainPassword])->save();

        return true;
    }
}
