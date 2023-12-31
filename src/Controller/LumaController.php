<?php

namespace Luma\Framework\Controller;

use Luma\HttpComponent\Response;
use Luma\HttpComponent\StreamBuilder;
use Latte\Bridges\Tracy\TracyExtension;
use Latte\Engine;

class LumaController
{
    private Engine $templateEngine;

    public function __construct()
    {
        $this->templateEngine = new Engine();
        $this->templateEngine->addExtension(new TracyExtension());
        $this->templateEngine->setTempDirectory(sprintf('%s/cache/views', dirname(__DIR__, 5)));
    }

    /**
     * @param string $data
     *
     * @return Response
     */
    protected function respond(string $data): Response
    {
        return new Response(
            200,
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
    protected function render(string $templatePath, array $data): Response
    {
        $templatePath = str_contains($templatePath, '.latte')
            ? $templatePath
            : sprintf('%s.latte', $templatePath);
        $templatePath = sprintf('%s/views/%s', dirname(__DIR__, 5), $templatePath);

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