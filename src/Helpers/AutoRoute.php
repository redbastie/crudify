<?php

namespace Redbastie\Crudify\Helpers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use ReflectionClass;

class AutoRoute
{
    public static function controller($path, $controller)
    {
        $httpMethods = ['any', 'get', 'post', 'put', 'patch', 'delete'];
        $reflection = new ReflectionClass($controller);

        foreach ($reflection->getMethods() as $method) {
            $httpMethod = explode('_', Str::snake($method->name))[0];

            if (in_array($httpMethod, $httpMethods) && $method->class == $reflection->name) {
                $methodName = Str::snake(Str::replaceFirst($httpMethod, '', $method->name), '-');
                $methodPath = $path . ($methodName != 'index' ? '/' . $methodName : null);
                $name = trim(str_replace('/', '.', $path) . '.' . $methodName, '.');
                $parameters = [];

                foreach ($method->getParameters() as $parameter) {
                    $parameterClass = $parameter->getClass();

                    if (!$parameterClass || $parameterClass->isSubclassOf('Illuminate\Database\Eloquent\Model')) {
                        $parameters[] = '{' . $parameter->name . ($parameter->isOptional() ? '?' : null) . '}';
                    }
                }

                if ($parameters) {
                    $methodPath .= '/' . implode('/', $parameters);
                }

                Route::$httpMethod($methodPath, [$reflection->name, $method->name])->name($name);
            }
        }
    }
}
