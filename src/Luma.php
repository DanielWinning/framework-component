<?php

namespace Luma\Framework;

use Luma\AuroraDatabase\DatabaseConnection;
use Luma\AuroraDatabase\Model\Aurora;
use Luma\AuroraDatabase\Utils\Collection;
use Luma\DependencyInjectionComponent\DependencyContainer;
use Luma\DependencyInjectionComponent\DependencyManager;
use Luma\DependencyInjectionComponent\Exception\NotFoundException;
use Luma\Framework\Controller\LumaController;
use Luma\Framework\Middleware\MiddlewareHandler;
use Luma\Framework\Middleware\MiddlewareInterface;
use Luma\HttpComponent\Request;
use Luma\HttpComponent\Response;
use Luma\RoutingComponent\Router;
use Luma\SecurityComponent\Authentication\Interface\AuthenticatorInterface;
use Luma\SecurityComponent\Authentication\Interface\UserInterface;
use Luma\SecurityComponent\Authentication\Interface\UserProviderInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Yaml\Yaml;
use Tracy\Debugger;

final class Luma
{
    private DependencyContainer $container;
    private DependencyManager $dependencyManager;
    private Router $router;
    private string $configDirectory;
    private string $cacheDirectory;

    protected static array $config;
    public static Collection $providers;
    private MiddlewareHandler $middlewareHandler;

    /**
     * @throws NotFoundException|\Throwable
     */
    public function __construct(string $configDirectory, string $templateDirectory, string $cacheDirectory)
    {
        $this->container = new DependencyContainer();
        $this->dependencyManager = new DependencyManager($this->container);
        $this->router = new Router($this->container);
        $this->middlewareHandler = new MiddlewareHandler();
        $this->cacheDirectory = $cacheDirectory;
        $this->setConfig($configDirectory);
        Debugger::enable(self::getConfigParam('app.mode') === 'production');
        Debugger::$logDirectory = sprintf('%s/log', dirname($configDirectory));
        Luma::$providers = new Collection();
        LumaController::setDirectories($templateDirectory, sprintf('%s/views', $cacheDirectory));

        $this->load();
    }

    /**
     * @param Request $request
     *
     * @return ResponseInterface
     *
     * @throws \ReflectionException|\Throwable
     */
    public function run(Request $request): ResponseInterface
    {
        $response = $this->middlewareHandler->handle($request, new Response());

        if ($response->getStatusCode() === 200) {
            $response = $this->router->handleRequest($request);
        }

        http_response_code($response->getStatusCode());
        header(sprintf('HTTP/%s %s %s', $response->getProtocolVersion(), $response->getStatusCode(), $response->getReasonPhrase()));

        foreach ($response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header(sprintf('%s: %s', $name, $value), false);
            }
        }

        return $response;
    }

    /**
     * @return UserProviderInterface|null
     */
    public static function getUserProvider(): ?UserProviderInterface
    {
        return Luma::$providers->find(function ($provider) {
            return $provider instanceof UserProviderInterface;
        });
    }

    /**
     * @return AuthenticatorInterface|null
     */
    public static function getAuthenticator(): ?AuthenticatorInterface
    {
        return Luma::$providers->find(function ($provider) {
            return $provider instanceof AuthenticatorInterface;
        });
    }

    /**
     * @return UserInterface|null
     */
    public static function getLoggedInUser(): ?UserInterface
    {
        $userProvider = Luma::getUserProvider();

        return $userProvider?->getUserFromSession();
    }

    /**
     * @param string $parameterName
     *
     * @return mixed
     */
    public static function getConfigParam(string $parameterName): mixed
    {
        return Luma::$config[$parameterName] ?? null;
    }

    /**
     * @return void
     *
     * @throws \Exception|NotFoundException
     */
    private function load(): void
    {
        Aurora::createQueryPanel();
        $this->establishDatabaseConnection();
        $this->dependencyManager
            ->loadDependenciesFromFile(sprintf('%s/services.yaml', $this->configDirectory));
        $this->router
            ->loadRoutesFromFile(sprintf('%s/routes.yaml', $this->configDirectory));
        $this->loadSecurityProviders();
        $this->loadMiddleware();

        if (self::getLoggedInUser()) {
            self::getLoggedInUser()::refresh();
        }
    }

    /**
     * @param string $configDirectory
     *
     * @return void
     */
    private function setConfig(string $configDirectory): void
    {
        $this->configDirectory = $configDirectory;
        $cachedConfigDirectory = sprintf('%s/config', $this->cacheDirectory);
        $cachedConfigPath = sprintf('%s/config.php', $cachedConfigDirectory);

        if (file_exists($cachedConfigPath)) {
            Luma::$config = require $cachedConfigPath;

            return;
        }

        $configYamlPath = sprintf('%s/%s', $this->configDirectory, 'config.yaml');

        if (file_exists($configYamlPath) && file_get_contents($configYamlPath) !== '') {
            Luma::$config = Yaml::parseFile($configYamlPath);

            if (!file_exists($cachedConfigDirectory)) {
                mkdir($cachedConfigDirectory);
            }

            $configPhp = sprintf('<?php return %s;', var_export(Luma::$config, true));
            file_put_contents($cachedConfigPath, $configPhp);

            return;
        }

        Luma::$config = [];
    }

    /**
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

    private function loadSecurityProviders(): void
    {
        $providersPath = sprintf('%s/security.php', $this->configDirectory);

        if (!file_exists($providersPath)) {
            return;
        }

        $providers = require $providersPath;

        foreach ($providers as $provider => $arguments) {
            if (class_exists($provider)) {
                Luma::$providers->add(new $provider(...$arguments));
            }
        }
    }

    private function loadMiddleware(): void
    {
        $middlewarePath = sprintf('%s/middleware.php', $this->configDirectory);

        if (!file_exists($middlewarePath)) {
            return;
        }

        $middleware = require $middlewarePath;

        foreach ($middleware as $mw) {
            if ($mw instanceof MiddlewareInterface) {
                $this->middlewareHandler->addMiddleware($mw);
            }
        }
    }
}