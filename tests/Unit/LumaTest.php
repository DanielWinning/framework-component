<?php

namespace Luma\Tests\Unit;

use Luma\DependencyInjectionComponent\Exception\NotFoundException;
use Luma\Framework\Controller\LumaController;
use Luma\Framework\Luma;
use Luma\HttpComponent\Request;
use Luma\HttpComponent\StreamBuilder;
use Luma\HttpComponent\Web\WebServerUri;
use Luma\Tests\Controllers\TestController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

class LumaTest extends TestCase
{
    private Luma $testClass;

    /**
     * @return void
     *
     * @throws NotFoundException|\ReflectionException|\Throwable
     */
    protected function setUp(): void
    {
        $routeConfig = fopen(sprintf('%s/%s', sys_get_temp_dir(), 'routes.yaml'), 'w');
        fwrite(
            $routeConfig,
            Yaml::dump([
                'routes' => [
                    [
                        'path' => '/',
                        'handler' => [TestController::class, 'index']
                    ]
                ]
            ])
        );
        fclose($routeConfig);

        $serviceConfig = fopen(sprintf('%s/%s', sys_get_temp_dir(), 'services.yaml'), 'w');
        fwrite($serviceConfig, Yaml::dump([]));
        fclose($serviceConfig);

        $this->testClass = new Luma(sys_get_temp_dir());
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        unlink(sprintf('%s/%s', sys_get_temp_dir(), 'routes.yaml'));
        unlink(sprintf('%s/%s', sys_get_temp_dir(), 'services.yaml'));
    }

    /**
     * @return void
     *
     * @throws \Throwable
     */
    public function testItCreatesAnInstanceOfLuma(): void
    {
        $this->assertInstanceOf(Luma::class, $this->testClass);
    }

    /**
     * @return void
     *
     * @throws \ReflectionException|\Throwable
     */
    public function testItRuns(): void
    {
        $_SERVER['HTTPS'] = 'on';
        $_SERVER['HTTP_HOST'] = 'localhost';
        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $request = $this->createMock(Request::class);
        $request->method('getMethod')->willReturn($_SERVER['REQUEST_METHOD']);
        $request->method('getUri')->willReturn(WebServerUri::generate());
        $request->method('getHeaders')->willReturn($headers);
        $request->method('getBody')->willReturn(StreamBuilder::build(''));

        $this->expectOutputString('Index');
        $this->testClass->run($request);
    }
}