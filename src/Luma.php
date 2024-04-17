<?php

namespace Luma\Framework;

use Luma\AuroraDatabase\DatabaseConnection;
use Luma\AuroraDatabase\Model\Aurora;
use Luma\DependencyInjectionComponent\DependencyContainer;
use Luma\DependencyInjectionComponent\DependencyManager;
use Luma\DependencyInjectionComponent\Exception\NotFoundException;
use Luma\Framework\Controller\LumaController;
use Luma\HttpComponent\Request;
use Luma\HttpComponent\Response;
use Luma\RoutingComponent\Router;

class Luma
{
    private DependencyContainer $container;
    private DependencyManager $dependencyManager;
    private Router $router;

    private string $configDirectory;
    private array $providers = [];

    /**
     * @throws NotFoundException|\Throwable
     */
    public function __construct(string $configDirectory, string $templateDirectory, string $cacheDirectory)
    {
        $this->container = new DependencyContainer();
        $this->dependencyManager = new DependencyManager($this->container);
        $this->router = new Router($this->container);
        $this->configDirectory = $configDirectory;
        LumaController::setDirectories($templateDirectory, $cacheDirectory);

        $this->load();
    }

    /**
     * @return void
     *
     * @throws \Exception|NotFoundException
     */
    private function load(): void
    {
        $this->establishDatabaseConnection();
        $this->dependencyManager
            ->loadDependenciesFromFile(sprintf('%s/services.yaml', $this->configDirectory));
        $this->router
            ->loadRoutesFromFile(sprintf('%s/routes.yaml', $this->configDirectory));

        // Load Providers and Middleware
        $this->loadProviders();
    }

    /**
     * @return void
     *
     * @throws \Exception
     */
    private function establishDatabaseConnection(): void
    {
        $databaseCredentialsAreSet = isset($_ENV['DATABASE_HOST'])
            && isset($_ENV['DATABASE_PORT'])
            && isset($_ENV['DATABASE_USER'])
            && isset($_ENV['DATABASE_PASSWORD']);

        if (!$databaseCredentialsAreSet) {
            return;
        }

        Aurora::setDatabaseConnection(
            new DatabaseConnection(
                sprintf(
                    '%s:host=%s;port=%s;',
                    $_ENV['DATABASE_DRIVER'] ?? 'mysql',
                    $_ENV['DATABASE_HOST'],
                    $_ENV['DATABASE_PORT']
                ),
                $_ENV['DATABASE_USER'],
                $_ENV['DATABASE_PASSWORD']
            )
        );
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

        http_response_code($response->getStatusCode());
        header(sprintf('HTTP/%s %s %s', $response->getProtocolVersion(), $response->getStatusCode(), $response->getReasonPhrase()));

        foreach ($response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header(sprintf('%s: %s', $name, $value), false);
            }
        }

        $this->echoResponse($response);
    }

    /**
     * @param Response $response
     *
     * @return void
     */
    private function echoResponse(Response $response): void
    {
        echo $response->getBody()->getContents();
    }

    /**
     * @return void
     */
    private function loadProviders(): void
    {
        $providers = require_once $this->configDirectory . '/providers.php';

        foreach ($providers as $provider => $arguments) {
            if (class_exists($provider)) {
                $this->providers[] = new $provider(...$arguments);
            }
        }
    }
}