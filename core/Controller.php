<?php

namespace Core;

class Controller
{
    protected string $snippet;
    protected static $_render;

    public function getComponentFile(string $component): string
    {
        if (!file_exists(Define::DIRECTORIES['components'] . $component . '.php')) {
            return false;
        }
        return Define::DIRECTORIES['components'] . $component . '.php';
    }

    public function getModel(string $model): object
    {
        $modelClass = Define::NAMESPACES['models'] . $model . 'Model';
        return new $modelClass();
    }

    private function getMethodParams(\ReflectionMethod $method): string
    {
        return $method->getDocComment();
    }

    private function getRoute(string $methodParams): string
    {
        preg_match('/@route\s+([^\s]+)/', $methodParams, $routeMatch);
    
        if (!isset($routeMatch[1])) {
            throw new \Exception('Invalid route in method docblock');
        }
    
        return trim($routeMatch[1]);
    }

    private function getParam(string $methodParams): string
    {
        $validParams = ['view', 'data'];
        preg_match('/@(\w+)/', $methodParams, $matches);
        $type = $matches[1] ?? null;
        if (!in_array($type, $validParams)) {
            throw new \Exception("Invalid method parameter: $type");
        }
        return $type;
    }

    private function getInclude(string $methodParams): ?string
    {
        preg_match('/@component\s+(.*)/', $methodParams, $includeMatch);
        $include = isset($includeMatch[1]) ? trim($includeMatch[1]) : null;
        return $this->getComponentFile($include);
    }

    private function getHttpMethod($methodParams): string
    {
        $validParams = ['GET', 'POST', 'PUT', 'DELETE'];
        if (preg_match('/@method\s+(.*)/', $methodParams, $methodMatch)) {
            $method = isset($methodMatch[1]) ? trim($methodMatch[1]) : 'GET';
            if (!in_array($method, $validParams)) {
                throw new \Exception('Invalid HTTP method');
            }
            return $method;
        }
    }

    private function getMiddleware(string $methodParams): ?string
    {
        preg_match('/@middleware\s+(.*)/', $methodParams, $middlewareMatch);
        return isset($middlewareMatch[1]) ? trim($middlewareMatch[1]) : null;
    }

    public function classRequest(Router $router): void
    {
        foreach (glob(Define::DIRECTORIES['controllers'] . '*.php') as $controllerFile) {
            require_once $controllerFile;
            $className = basename($controllerFile, '.php');
            $classReflection = new \ReflectionClass(Define::NAMESPACES['controllers'] . $className);
            $shortClassName = $classReflection->getShortName();

            $include = null;

            foreach ($classReflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
                if ($method->class !== $classReflection->getName() || $method->getName() === '__construct') {
                    continue;
                }

                $methodParams = $this->getMethodParams($method);
                $methodBody = $this->getMethodBody($method);

                $route = $this->getRoute($methodParams);
                $param = $this->getParam($methodParams, 'view') ?? $this->getParam($methodParams, 'data');
                $httpMethod = $this->getHttpMethod($methodParams);
                $middleware = $this->getMiddleware($methodParams) ?? null;

                if (strpos($methodBody, '$this->render(') !== false) {
                    $include = null;
                } else {
                    if ($param === 'view') {
                        $include = $this->getInclude($methodParams);
                    } else {
                        $include = null;
                    }
                }

                $router->addRoute($route, $method->getName(), $className, $httpMethod, $include, $middleware);
            }
        }
    }
    
    private function getMethodBody(\ReflectionMethod $method): string
    {
        $startLine = $method->getStartLine();
        $endLine = $method->getEndLine();
        $fileName = $method->getFileName();
        $fileLines = file($fileName);
    
        return implode('', array_slice($fileLines, $startLine - 1, $endLine - $startLine + 1));
    }

    protected function render($component, $data = [])
    {
        $componentFile = Define::DIRECTORIES['components'] . $component . '.php';
        $layoutFile = Define::LAYOUT_FILE;

        if (!file_exists($componentFile)) {
            throw new \Exception("View file not found: $componentFile");
        }

        ob_start();
        include $componentFile;
        $content = ob_get_clean();

        ob_start();
        $engine = new TemplateEngine($data);
        $content = $engine->render($componentFile);
        $renderedContent = ob_get_clean();

        if (!file_exists($layoutFile)) {
            throw new \Exception("Layout file not found: $layoutFile");
        }

        ob_start();
        include $layoutFile;
        echo self::$_render = ob_get_clean();
    }

    protected function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }
}