<?php

namespace --resource;

use Base\Controllers\BaseApiController;
use Base\Interfaces\RequestInterface as Request;
use Base\Interfaces\ViewInterface;

class TestController extends BaseApiController
{
        /**
     * Display a listing of the resource.
     *
     * @param ViewInterface $view
     * @return string
     */
    public function index(ViewInterface $view): string
    {
        return $view->render('default.index', [
            'title' => 'Resource Index',
            'message' => 'This is a resource index method.',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): array
    {
        // TODO: Implement store method
        return [];
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): array
    {
        // TODO: Implement show method
        return [];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): array
    {
        // TODO: Implement update method
        return [];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): bool
    {
        // TODO: Implement destroy method
        return false;
    }
}