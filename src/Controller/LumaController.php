<?php

namespace Luma\Framework\Controller;

use Luma\Framework\Luma;
use Luma\HttpComponent\Response;
use Luma\HttpComponent\StreamBuilder;
use Latte\Bridges\Tracy\TracyExtension;
use Latte\Engine;
use Luma\SecurityComponent\Authentication\Interface\UserInterface;
use Tracy\Debugger;

class LumaController
{
    private Engine $templateEngine;
    private static string $templateDirectory;
    private static string $cacheDirectory;
    private array $errors = [];
    private ?UserInterface $currentUser;

    public function __construct()
    {
        $this->templateEngine = new Engine();
        $this->templateEngine->addExtension(new TracyExtension());
        $this->templateEngine->setTempDirectory(static::$cacheDirectory);
        $this->currentUser = Luma::getLoggedInUser();
    }

    /**
     * Used internally to set the base directories for all controllers. There is no need to call this method within a
     * Luma application.
     *
     * @param string $templateDirectory
     * @param string $cacheDirectory
     *
     * @return void
     */
    public static function setDirectories(string $templateDirectory, string $cacheDirectory): void
    {
        static::$templateDirectory = $templateDirectory;
        static::$cacheDirectory = $cacheDirectory;
    }

    /**
     * @param string $data
     * @param string $contentType
     * @param int $statusCode
     *
     * @return Response
     */
    protected function respond(string $data, string $contentType = 'text/html', int $statusCode = 200): Response
    {
        $responseHeaders = [
            'Content-Type' => $contentType,
        ];

        if (!Debugger::isEnabled()) {
            $responseHeaders['Content-Length'] = strlen($data);
        }

        return new Response(
            $statusCode,
            'OK',
            $responseHeaders,
            StreamBuilder::build($data)
        );
    }

    /**
     * @param string $templatePath
     * @param array $data
     *
     * @return Response
     */
    protected function render(string $templatePath, array $data = []): Response
    {
        $templatePath = str_contains($templatePath, '.latte')
            ? $templatePath
            : sprintf('%s.latte', $templatePath);
        $templatePath = sprintf('%s/%s', static::$templateDirectory, $templatePath);

        if (!isset($data['errors'])) {
            $data['errors'] = $this->getErrors();
        }

        if (!isset($data['user'])) {
            $data['user'] = $this->getLoggedInUser();
        }

        return $this->respond($this->templateEngine->renderToString($templatePath, $data));
    }

    /**
     * @param array|object $data
     *
     * @return Response
     */
    protected function json(array|object $data): Response
    {
        return $this->respond(json_encode($data), 'application/json');
    }

    /**
     * @param string $error
     *
     * @return void
     */
    protected function addError(string $error): void
    {
        $this->errors[] = $error;
    }

    /**
     * @return array
     */
    protected function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return UserInterface|null
     */
    protected function getLoggedInUser(): ?UserInterface
    {
        return $this->currentUser;
    }
}