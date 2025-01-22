<?php
namespace Base\Authorization;

use Base\Interfaces\RBACManagerInterface;

class RBACManager implements RBACManagerInterface
{
    public function assignRoleToUser(int $userId, int $roleId): void
    {
        // Add a row in the user_roles table.
    }

    public function hasPermission(int $userId, string $permission): bool
    {
        // Check if the user's roles have the required permission.
        return true;
    }

    public function getUserRoles(int $userId): array
    {
        // Return a list of roles assigned to the user.
        return [];
    }
}
