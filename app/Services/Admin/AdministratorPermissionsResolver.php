<?php

namespace App\Services\Admin;

use App\Models\User;

class AdministratorPermissionsResolver
{
    /**
     * Resolve permissions for a given administrator user.
     *
     * @param User $user
     * @return array
     */
    public function resolve(User $user): array
    {
        $permissions = [];

        // If user object has custom permissions defined (e.g. JSON column or method), collect them.
        if (isset($user->permissions) && is_array($user->permissions)) {
            $permissions = $user->permissions;
        } elseif (isset($user->permissions) && is_string($user->permissions)) {
            $decoded = json_decode($user->permissions, true);
            if (is_array($decoded)) {
                $permissions = $decoded;
            }
        }

        // Fallback to default admin permissions if none resolved
        if (empty($permissions)) {
            $permissions = config('admin_auth.default_permissions', ['admin.*']);
        }

        // Ensure unique, re-indexed array of strings
        $normalized = array_values(array_unique(array_map('strval', $permissions)));

        return $normalized;
    }
}
