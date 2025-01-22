<?php
namespace App\Controllers\Api;

use App\Models\User;
use Base\Controllers\BaseApiController;

class UserController extends BaseApiController
{
    public function index(): array
    {
        /**
        @var User $users
        */
        $users = User::all();

        return $this->success($users, "User list retrieved");
    }

    public function store(): array
    {
        // $data = request()->validate([
        //     "name" => "required|string",
        //     "email" => "required|email|unique:users",
        //     "password" => "required|min:8",
        // ]);

        // $user = User::new($data);

        //return $this->success($user, "User created successfully");
    }
}
