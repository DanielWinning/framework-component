<?php

use PHPUnit\Framework\TestCase;

class LumaTest extends TestCase
{
    protected function setUp(): void
    {

    }

    public function testItCreatesAnInstanceOfLuma()
    {
        $routeConfig = fopen(sprintf('%s/%s', sys_get_temp_dir(), 'routes.yaml'), 'w');
        fwrite($routeConfig, \Symfony\Component\Yaml\Yaml::dump(['routes' => ['path' => '/', 'handler' => ['TestController', 'testMethod']]]));
        fclose($routeConfig);
        $serviceConfig = fopen(sprintf('%s/%s', sys_get_temp_dir(), 'services.yaml'), 'w');
        fwrite($serviceConfig, \Symfony\Component\Yaml\Yaml::dump([]));
        fclose($serviceConfig);

        $this->assertInstanceOf(\Luma\Framework\Luma::class, new \Luma\Framework\Luma(sys_get_temp_dir()));

        unlink(sprintf('%s/%s', sys_get_temp_dir(), 'routes.yaml'));
        unlink(sprintf('%s/%s', sys_get_temp_dir(), 'services.yaml'));
    }
}