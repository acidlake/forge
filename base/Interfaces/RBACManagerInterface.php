<?php
namespace Base\Interfaces;

interface RBACManagerInterface
{
    public function assignRoleToUser(int $userId, int $roleId): void;
    public function hasPermission(int $userId, string $permission): bool;
    public function getUserRoles(int $userId): array;
}
