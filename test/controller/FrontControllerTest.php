<?php

namespace transitguide\api\test\controller;

use \transitguide\api\test\TestHelper as TestHelper;

class FrontControllerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Provides test values to test_addPath
     */
    public function provide_addPath(): array
    {
        $cases = [];

        foreach (TestHelper::getStrings() as $testName => $testValue) {
            $cases["{$testName} test"] = [
                'path' => $testValue,
                'config' => ['controller' => 'controller'],
                'expectedResult' => true,
            ];
        }

        $cases['Config without controller'] = [
            'path' => 'path',
            'config' => ['blah' => 'blah'],
            'expectedResult' => false,
        ];
        $cases['Path already exists'] = [
            'path' => 'path',
            'config' => ['controller' => 'controller'],
            'expectedResult' => false,
        ];

        return $cases;
    }

    /**
     * @covers FrontController::addPath
     * @dataProvider provide_addPath
     */
    public function test_addPath(string $path, array $config, bool $expectedResult)
    {
        $controller = new \transitguide\api\controller\FrontController();

        // Add a path to the controller that will be present for all tests
        $controller->addPath('path', ['controller' => 'controller']);

        // Add the test path to the controller
        $actualResult = $controller->addPath($path, $config);

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function provide_getControllerName(): array
    {
        $testPaths = [];
        $cases = [];

        foreach (TestHelper::getStrings() as $testName => $testValue) {
            $testPaths[$testName] = ['controller' => $testValue];
            $cases["{$testName} test"] = [
                'paths' => $testPaths,
                'testPath' => $testName,
                'expectedResult' => $testValue,
            ];
        }

        $cases['No paths test'] = [
            'paths' => null,
            'testPath' => 'path',
            'expectedResult' => false,
        ];

        $cases['Empty paths array test'] = [
            'paths' => [],
            'testPath' => 'path',
            'expectedResult' => false,
        ];

        $cases['Undefined path test'] = [
            'paths' => $testPaths,
            'testPath' => 'undefined',
            'expectedResult' => false,
        ];

        return $cases;
    }

    /**
     * @covers FrontController::getControllerName
     * @dataProvider provide_getControllerName
     */
    public function test_getControllerName($paths, string $testPath, $expectedResult)
    {
        $controller = new \transitGuide\api\controller\FrontController();

        if (is_array($paths)) {
            foreach($paths as $path => $config) {
                $controller->addPath($path, $config);
            }
        }

        $actualResult = $controller->getControllerName($testPath);

        $this->assertEquals($expectedResult, $actualResult);
    }
}
