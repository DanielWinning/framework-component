<?php

namespace Luma\Framework;

use DannyXCII\DependencyInjectionComponent\DependencyContainer;
use DannyXCII\DependencyInjectionComponent\DependencyManager;
use DannyXCII\DependencyInjectionComponent\Exception\NotFoundException;
use DannyXCII\HttpComponent\Request;
use DannyXCII\HttpComponent\StreamBuilder;
use DannyXCII\HttpComponent\Web\WebServerUri;
use DannyXCII\RoutingComponent\Router;

class Luma
{
    private DependencyContainer $container;
    private DependencyManager $dependencyManager;
    private Router $router;
    private string $configDir;

    /**
     * @throws \ReflectionException|NotFoundException|\Throwable
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
        $this->dependencyManager->loadDependenciesFromFile(sprintf('%s/services.yaml', $this->configDir));
        $this->router->loadRoutesFromFile(sprintf('%s/routes.yaml', $this->configDir));
    }

    /**
     * @return void
     *
     * @throws \ReflectionException|\Throwable
     */
    public function run(): void
    {
        echo $this->router->handleRequest(
            new Request(
                $_SERVER['REQUEST_METHOD'],
                WebServerUri::generate(),
                getallheaders(),
                StreamBuilder::build('')
            )
        )->getBody()->getContents();
    }
}