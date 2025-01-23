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
        $users = User::all();

        return $this->success($users, "User list retrieved");
    }

    public function store(Request $request, User $user): array
    {
        $data = $this->handleValidation(function () use ($request) {
            return $request->validate([
                "name" => "required|string|min:4",
            ]);
        });

        $newUser = $user->save($data);

        return $this->success($newUser, "User created successfully");
    }
}
