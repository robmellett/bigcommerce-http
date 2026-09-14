<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Tests\Requests;

use PHPUnit\Framework\Attributes\DataProvider;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;
use RobMellett\BigcommerceHttp\Tests\TestCase;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use SplFileInfo;

/**
 * A structural sweep over every Request class in src/Requests: rather than
 * hand-writing a test per endpoint, it instantiates each class with dummy
 * constructor arguments and asserts it defines a valid HTTP method and a
 * resolvable, absolute-path endpoint.
 */
class RequestClassesTest extends TestCase
{
    #[DataProvider('requestClasses')]
    public function test_request_class_defines_a_method_and_endpoint(string $class): void
    {
        $this->assertTrue(
            is_subclass_of($class, Request::class),
            "{$class} must extend ".Request::class
        );

        $request = $this->instantiate($class);

        $this->assertInstanceOf(Method::class, $request->getMethod());

        $endpoint = $request->resolveEndpoint();

        $this->assertNotSame('', $endpoint, "{$class}::resolveEndpoint() must not be empty");
        $this->assertStringStartsWith('/', $endpoint, "{$class}::resolveEndpoint() must start with a leading slash");
    }

    /**
     * @return array<string, array{0: class-string<Request>}>
     */
    public static function requestClasses(): array
    {
        $directory = dirname(__DIR__, 2).'/src/Requests';

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS)
        );

        $classes = [];

        /** @var SplFileInfo $file */
        foreach ($iterator as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $relativePath = substr($file->getPathname(), strlen($directory) + 1);
            $class = 'RobMellett\\BigcommerceHttp\\Requests\\'.str_replace(['/', '.php'], ['\\', ''], $relativePath);

            $classes[$class] = [$class];
        }

        ksort($classes);

        return $classes;
    }

    /**
     * @param  class-string<Request>  $class
     */
    private function instantiate(string $class): Request
    {
        $reflection = new ReflectionClass($class);
        $constructor = $reflection->getConstructor();

        if ($constructor === null) {
            /** @var Request */
            return $reflection->newInstance();
        }

        $arguments = array_map(
            fn (ReflectionParameter $parameter) => $this->valueForParameter($parameter),
            $constructor->getParameters()
        );

        /** @var Request */
        return $reflection->newInstanceArgs($arguments);
    }

    private function valueForParameter(ReflectionParameter $parameter): mixed
    {
        $type = $parameter->getType();

        if (! $type instanceof ReflectionNamedType) {
            return null;
        }

        return match ($type->getName()) {
            'int' => 1,
            'float' => 1.0,
            'bool' => true,
            'array' => [],
            default => 'test',
        };
    }
}
