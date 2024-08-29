<?php

namespace Luma\Framework\Middleware;

use Luma\AuroraDatabase\Utils\Collection;
use Luma\HttpComponent\Request;
use Luma\HttpComponent\Response;

class MiddlewareHandler
{
    private Collection $middleware;

    public function __construct()
    {
        $this->middleware = new Collection();
    }

    /**
     * @param MiddlewareInterface $middleware
     *
     * @return void
     */
    public function addMiddleware(MiddlewareInterface $middleware): void
    {
        $this->middleware->add($middleware);
    }

    /**
     * @param Request $request
     * @param Response $response
     *
     * @return Response
     */
    public function handle(Request $request, Response $response): Response
    {
        $handler = array_reduce(
            array_reverse($this->middleware->toArray()),
            fn ($next, $middleware) => fn ($request, $response) => $middleware->handle($request, $response, $next),
            fn ($request, $response) => $response
        );

        return $handler($request, $response);
    }
}