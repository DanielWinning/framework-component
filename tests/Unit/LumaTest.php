<?php

namespace Luma\Tests\Unit;

use Dotenv\Dotenv;
use Luma\DependencyInjectionComponent\Exception\NotFoundException;
use Luma\Framework\Luma;
use Luma\HttpComponent\Request;
use Luma\HttpComponent\StreamBuilder;
use Luma\HttpComponent\Web\WebServerUri;
use Luma\Tests\Classes\Article;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class LumaTest extends TestCase
{
    private Luma $testClass;

    private string $cacheDirectory;
    private string $configDirectory;
    private string $templateDirectory;

    /**
     * @return void
     *
     * @throws NotFoundException|\ReflectionException|\Throwable
     */
    protected function setUp(): void
    {
        $this->cacheDirectory = dirname(__DIR__) . '/cache';
        $this->configDirectory = dirname(__DIR__) . '/config';
        $this->templateDirectory = dirname(__DIR__) . '/views';

        $cachedConfig = sprintf('%s/config/config.php', $this->cacheDirectory);

        if (file_exists($cachedConfig)) {
            unlink($cachedConfig);
        }

        (Dotenv::createImmutable($this->configDirectory))->safeLoad();

        $this->testClass = new Luma($this->configDirectory, $this->templateDirectory, $this->cacheDirectory);
    }

    /**
     * @return void
     *
     * @throws \Throwable
     */
    public function testItCreatesAnInstanceOfLuma(): void
    {
        $this->assertInstanceOf(Luma::class, $this->testClass);

        unset($_ENV['DATABASE_HOST']);

        $newLuma = new Luma($this->configDirectory, $this->templateDirectory, $this->cacheDirectory);

        $this->assertInstanceOf(Luma::class, $newLuma);
    }

    /**
     * @param array $data
     * @param string $expectedOutput
     *
     * @return void
     *
     * @throws Exception|\ReflectionException|\Throwable
     *
     * @dataProvider runDataProvider
     */
    public function testItRuns(array $data, string $expectedOutput): void
    {
        $request = $this->setupRequest($data['path'], $data['method']);

        $this->expectOutputString($expectedOutput);
        $this->testClass->run($request);
    }

    /**
     * @param string $path
     *
     * @return void
     *
     * @throws Exception|\ReflectionException|\Throwable
     *
     * @dataProvider renderDataProvider
     */
    public function testItRenders(string $path): void
    {
        $request = $this->setupRequest($path, 'GET');

        $this->expectOutputString('<h1>Hello, Render Test!</h1>');
        $this->testClass->run($request);
    }

    /**
     * @return void
     */
    public function testDatabaseInteraction(): void
    {
        if (isset($_ENV['DATABASE_HOST'])) {
            $articles = Article::all();

            $this->assertIsArray($articles);
        } else {
            $this->expectNotToPerformAssertions();
        }
    }

    /**
     * @return void
     */
    public function testGetConfigParam(): void
    {
        $this->assertEquals('Test string', Luma::getConfigParam('test_string'));
        $this->assertEquals(1, Luma::getConfigParam('test_integer'));
    }

    /**
     * @param string $path
     * @param string $method
     *
     * @return Request|MockObject
     *
     * @throws Exception
     */
    private function setupRequest(string $path, string $method): MockObject|Request
    {
        $_SERVER['HTTPS'] = 'on';
        $_SERVER['HTTP_HOST'] = 'localhost';
        $_SERVER['REQUEST_URI'] = $path;
        $_SERVER['REQUEST_METHOD'] = $method;

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $request = $this->createMock(Request::class);
        $request->method('getMethod')->willReturn($_SERVER['REQUEST_METHOD']);
        $request->method('getUri')->willReturn(WebServerUri::generate());
        $request->method('getHeaders')->willReturn($headers);
        $request->method('getBody')->willReturn(StreamBuilder::build(''));

        return $request;
    }

    /**
     * @return array[]
     */
    public static function runDataProvider(): array
    {
        return [
            [
                'data' => [
                    'path' => '/',
                    'method' => 'GET',
                ],
                'expectedOutput' => 'Index',
            ],
            [
                'data' => [
                    'path' => '/json',
                    'method' => 'GET',
                ],
                'expectedOutput' => '{"title":"JSON Response"}',
            ]
        ];
    }

    /**
     * @return array[]
     */
    public static function renderDataProvider(): array
    {
        return [
            [
                'path' => '/render-test',
            ],
            [
                'path' => '/render-with-extension-test',
            ]
        ];
    }
}