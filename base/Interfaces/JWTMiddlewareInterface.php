<?php
namespace Base\Interfaces;

interface JWTMiddlewareInterface
{
    public function handle($request, $next);
}
