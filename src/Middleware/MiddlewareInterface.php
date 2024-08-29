<?php

namespace Luma\Framework\Middleware;

use Luma\HttpComponent\Request;
use Luma\HttpComponent\Response;

interface MiddlewareInterface
{
    public function handle(Request $request, Response $response, callable $next): Response;
}