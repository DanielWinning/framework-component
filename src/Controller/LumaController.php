<?php

namespace Luma\Framework\Controller;

use Luma\HttpComponent\Response;
use Luma\HttpComponent\StreamBuilder;
use Latte\Bridges\Tracy\TracyExtension;
use Latte\Engine;

class LumaController
{
    private Engine $templateEngine;
    private static string $templateDirectory;
    private static string $cacheDirectory;

    public function __construct()
    {
        $this->templateEngine = new Engine();
        $this->templateEngine->addExtension(new TracyExtension());
        $this->templateEngine->setTempDirectory(static::$cacheDirectory);
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
     * @param int $statusCode
     *
     * @return Response
     */
    protected function respond(string $data, int $statusCode = 200): Response
    {
        return new Response(
            $statusCode,
            'OK',
            [
                'Content-Type' => 'text/html',
                'Content-Length' => strlen($data),
            ],
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

        return $this->respond($this->templateEngine->renderToString($templatePath, $data));
    }

    /**
     * @param array|object $data
     *
     * @return Response
     */
    protected function json(array|object $data): Response
    {
        return $this->respond(json_encode($data))->withHeader('Content-Type', 'application/json');
    }
}