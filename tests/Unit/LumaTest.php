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
use PHPUnit\Framework\TestCase;

class LumaTest extends TestCase
{
    private Luma $testClass;
    private string $configDirectory;

    /**
     * @return void
     *
     * @throws NotFoundException|\ReflectionException|\Throwable
     */
    protected function setUp(): void
    {
        $this->configDirectory = dirname(__DIR__) . '/config';
        (Dotenv::createImmutable($this->configDirectory))->safeLoad();
        $this->testClass = new Luma($this->configDirectory);
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

        $newLuma = new Luma($this->configDirectory);

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
        $_SERVER['HTTPS'] = 'on';
        $_SERVER['HTTP_HOST'] = 'localhost';
        $_SERVER['REQUEST_URI'] = $data['path'];
        $_SERVER['REQUEST_METHOD'] = $data['method'];

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $request = $this->createMock(Request::class);
        $request->method('getMethod')->willReturn($_SERVER['REQUEST_METHOD']);
        $request->method('getUri')->willReturn(WebServerUri::generate());
        $request->method('getHeaders')->willReturn($headers);
        $request->method('getBody')->willReturn(StreamBuilder::build(''));

        $this->expectOutputString($expectedOutput);
        $this->testClass->run($request);
    }

    /**
     * @return void
     */
    public function testDatabaseInteraction(): void
    {
        $articles = Article::all();

        $this->assertIsArray($articles);
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
                'expected' => 'Index',
            ],
            [
                'data' => [
                    'path' => '/json',
                    'method' => 'GET',
                ],
                'expected' => '{"title":"JSON Response"}',
            ]
        ];
    }
}