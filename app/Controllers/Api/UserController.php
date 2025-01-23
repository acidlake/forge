<?php
namespace App\Controllers\Api;

use App\Models\User;
use Base\Controllers\BaseApiController;
use Base\Core\ContainerAwareTrait;
use Base\Interfaces\RequestInterface as Request;

class UserController extends BaseApiController
{
    use ContainerAwareTrait;

    public function index(): array
    {
        $page = 1;
        $perPage = 10;
        $users = User::paginate($perPage, $page);

        return $this->paginatedSuccess($users, "User list retrieved");
    }

    public function store(Request $request): array
    {
        $data = $this->handleValidation(function () use ($request) {
            return $request->validate([
                "name" => "required|string|min:4",
            ]);
        });

        $user = new User();
        $newUser = $user->save($data);

        return $this->success($newUser, "User created successfully");
    }
}
