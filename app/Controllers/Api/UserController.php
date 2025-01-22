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
        /**
        @var User $users
        */
        $users = User::all();

        return $this->success($users, "User list retrieved");
    }

    public function store(Request $request): array
    {
        $data = $this->handleValidation(function () use ($request) {
            return $request->validate([
                "name" => "required|string|min:4",
            ]);
        });

        $user = new User();
        $user->fill($data);
        $user->save();

        return $this->success($user, "User created successfully");
    }
}
