<?php

namespace Microframe\Framework;

use DannyXCII\DependencyInjectionComponent\DependencyContainer;
use DannyXCII\DependencyInjectionComponent\DependencyManager;
use DannyXCII\DependencyInjectionComponent\Exception\NotFoundException;
use DannyXCII\HttpComponent\Request;
use DannyXCII\HttpComponent\StreamBuilder;
use DannyXCII\HttpComponent\Web\WebServerUri;
use DannyXCII\RoutingComponent\Router;

class Microframe
{
    private DependencyContainer $container;
    private DependencyManager $dependencyManager;
    private Router $router;

    /**
     * @throws \ReflectionException|NotFoundException|\Throwable
     */
    public function __construct()
    {
        $this->container = new DependencyContainer();
        $this->dependencyManager = new DependencyManager($this->container);
        $this->router = new Router($this->container);

        $this->load();
        $this->run();
    }

    /**
     * @return void
     *
     * @throws NotFoundException
     */
    private function load(): void
    {
        $this->dependencyManager->loadDependenciesFromFile(sprintf('%s/config/services.yaml', dirname(__DIR__, 3)));
        $this->router->loadRoutesFromFile(sprintf('%s/config/routes.yaml', dirname(__DIR__, 3)));
    }

    /**
     * @return void
     * @throws \ReflectionException|\Throwable
     */
    private function run(): void
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