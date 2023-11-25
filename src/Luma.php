<?php

namespace Luma\Framework;

use Luma\DependencyInjectionComponent\DependencyContainer;
use Luma\DependencyInjectionComponent\DependencyManager;
use Luma\DependencyInjectionComponent\Exception\NotFoundException;
use Luma\HttpComponent\Request;
use Luma\RoutingComponent\Router;

class Luma
{
    private DependencyContainer $container;
    private DependencyManager $dependencyManager;
    private Router $router;
    private string $configDir;

    /**
     * @throws NotFoundException|\Throwable
     */
    public function __construct(string $configDir)
    {
        $this->container = new DependencyContainer();
        $this->dependencyManager = new DependencyManager($this->container);
        $this->router = new Router($this->container);
        $this->configDir = $configDir;

        $this->load();
    }

    /**
     * @return void
     *
     * @throws NotFoundException
     */
    private function load(): void
    {
        $this->dependencyManager
            ->loadDependenciesFromFile(sprintf('%s/services.yaml', $this->configDir));
        $this->router
            ->loadRoutesFromFile(sprintf('%s/routes.yaml', $this->configDir));
    }

    /**
     * @param Request $request
     *
     * @return void
     *
     * @throws \ReflectionException|\Throwable
     */
    public function run(Request $request): void
    {
        $response = $this->router->handleRequest($request);

        echo $response->getBody()->getContents();
    }
}