<?php
namespace App\Controllers\Api;

use App\Models\User;
use Base\Controllers\BaseApiController;
use Base\Core\ContainerAwareTrait;
use Base\Helpers\HtmlPaginationHelper;
use Base\Interfaces\RequestInterface as Request;
use Base\Interfaces\ViewInterface;
use Base\Router\Http\Response;

class UserController extends BaseApiController
{
    use ContainerAwareTrait;

    public function index(Request $request): array|string
    {
        /**
         * Resolve the ViewInterface instance from the DI container.
         *
         * @var ViewInterface $view
         */
        $view = $this->resolve(ViewInterface::class);

        $page = $request->query("page", 1);
        $perPage = $request->query("per_page");
        $users = User::paginate($perPage, $page);

        $paginationHelper = (new HtmlPaginationHelper())
            ->setCurrentPage($page)
            ->setTotalPages($users["pagination"]["totalPages"]);

        $paginationHtml = $paginationHelper->render();

        $data = [
            "users" => $users["data"],
            "pagination" => $paginationHtml,
        ];

        return $view->render("users.index", $data);
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
