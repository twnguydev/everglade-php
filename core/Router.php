<?php

namespace Core;

class Router
{
    private Request $request;
    private Database $database;
    protected static $_render;
    public array $routes = [];

    public function __construct()
    {
        $this->request = new Request();
        $this->database = new Database();
    }

    public function addRoute(string $pattern, string $action, string $controller, string $method, ?string $component = null, ?string $middleware = null): void
    {
        $pattern = $this->setTypeParamsRoute($pattern);

        $this->routes[$pattern] = [
            'action' => $action,
            'controller' => $controller,
            'method' => $method,
            'component' => $component,
            'middleware' => $middleware
        ];
    }

    public function setTypeParamsRoute(string $route): string
    {
        $route = preg_replace('/{id}/', '(?<id>(?!0)[0-9]+)', $route);
        $route = preg_replace('/{str}/', '(?<str>[a-zA-Z]+)', $route);
        $route = preg_replace('/{slug}/', '(?<slug>[a-zA-Z0-9-]+)', $route);
        $route = preg_replace('/{uuid}/', '(?<uuid>[a-f0-9-]+)', $route);
        return $route;
    }

    private function extractParams(string $action, object $controller, array $matches): array
    {
        $method = new \ReflectionMethod($controller, $action);
        $params = [];

        foreach ($method->getParameters() as $param) {
            $paramName = $param->getName();

            if (isset($matches[$paramName])) {
                $params[] = $matches[$paramName];
            } else {
                $params[] = null;
            }
        }

        return $params;
    }

    public function routeRequest(): void
    {
        $uri = $this->request->server('REQUEST_URI');
        $method = $this->request->server('REQUEST_METHOD');
        $routeMatched = false;

        foreach ($this->routes as $pattern => $route) {
            if (preg_match("#^$pattern$#", $uri, $matches) && $route['method'] === $method) {
                array_shift($matches);
                $controllerName = $route['controller'];
                $action = $route['action'];

                $controllerClass = Define::NAMESPACES['controllers'] . $controllerName;

                if (class_exists($controllerClass)) {
                    $controller = new $controllerClass();

                    if (isset($route['middleware'])) {
                        $middlewareClass = $route['middleware'];
                        $middlewareClass = Define::NAMESPACES['middlewares'] . $middlewareClass;
                        $middleware = new $middlewareClass();
                        $handle = $middleware->handle('protectedRoute');

                        if ($route['middleware'] !== 'Auth') {
                            $middlewareClass = Define::NAMESPACES['middlewares'] . 'Auth';
                            $middleware = new $middlewareClass();
                            $handle = $middleware->handle('protectedRoute');
                        }
                    } else {
                        if ($this->database->getTable('user')) {
                            $middlewareClass = Define::NAMESPACES['middlewares'] . 'Auth';
                            $middleware = new $middlewareClass();
                            $handle = $middleware->handle();
                        }
                    }

                    $params = $this->extractParams($action, $controller, $matches);
                    $data = $controller->$action(...$params);

                    if (isset($route['component'])) {
                        $app = new App();
                        $isUserLogged = $this->request->getSession('isUserLoggedIn') ?? false;
                        $userData = $this->request->getSession('user') ?? null;
                        $userId = $userData ? $userData->getId() : null;

                        ob_start();
                        $navbarEngine = new TemplateEngine(['access' => $isUserLogged, 'user_id' => $userId]);
                        $navbar = $navbarEngine->render(Define::DIRECTORIES['components'] . 'navbar.php');
                        $renderedNavbar = ob_get_clean();

                        ob_start();
                        $footerEngine = new TemplateEngine(['date' => date('Y')]);
                        $footer = $footerEngine->render(Define::DIRECTORIES['components'] . 'footer.php');
                        $renderedFooter = ob_get_clean();

                        if (!file_exists($route['component'])) {
                            $route['component'] = Define::DIRECTORIES['components'] . $route['component'] . '.php';
                            if (!file_exists($route['component'])) {
                                throw new \Exception("Component file not found: " . $route['component']);
                            }
                        }

                        ob_start();
                        $engine = new TemplateEngine($data);
                        $content = $engine->render($route['component']);
                        $renderedContent = ob_get_clean();

                        include Define::LAYOUT_FILE;
                    }

                    $routeMatched = true;
                    break;
                } else {
                    throw new \Exception("Controller class not found: $controllerClass");
                }
            }
        }

        if (!$routeMatched) {
            throw new \Exception('The requested route has no match');
        }
    }

    public function dynamicRouteRequest(): void
    {
        $uri = $this->request->server('REQUEST_URI');
        $routeMatched = false;
        $uriParts = [];
    
        if ($uri === '/') {
            $controllerClass = Define::NAMESPACES['controllers'] . 'Home';
            $action = 'home';
        } else {
            $uri = rtrim($uri, '/');
            $uri = filter_var($uri, FILTER_SANITIZE_URL);
            $uri = explode('?', $uri)[0];
            $uriParts = explode('/', $uri);
        
            if (isset($uriParts[1])) {
                $controllerName = ucfirst($uriParts[1]);
                $action = $uriParts[2] ?? $uriParts[1];
        
                if ($uriParts[1] === 'admin') {
                    $controllerClass = Define::NAMESPACES['controllers'] . 'Admin\\' . $controllerName;
                } elseif ($uriParts[1] === 'auth') {
                    if (isset($uriParts[2])) {
                        if ($uriParts[2] === 'signup' || $uriParts[2] === 'logout') {
                            $action = $uriParts[2];
                        } else {
                            throw new \Exception("Action not found: {$uriParts[2]}, in controller: \\App\\Controller\\Auth.");
                        }
                    } else {
                        $action = 'login';
                    }
                    $controllerClass = Define::NAMESPACES['controllers'] . 'Auth';
                } else {
                    $controllerClass = Define::NAMESPACES['controllers'] . $controllerName;
                }
            } else {
                throw new \Exception("Controller not found.");
            }
        }
    
        if (class_exists($controllerClass)) {
            $controller = new $controllerClass();
    
            if (method_exists($controller, $action . 'Action')) {
                $params = array_slice($uriParts, 3);
    
                $data = $controller->{$action . 'Action'}(...$params);
    
                if (file_exists(Define::DIRECTORIES['components'] . $action . '.php')) {
                    $app = new App();
                    $isUserLogged = $this->request->getSession('isUserLoggedIn') ?? false;
                    $userData = $this->request->getSession('user') ?? null;
                    $userId = $userData ? $userData->getId() : null;

                    ob_start();
                    $navbarEngine = new TemplateEngine(['access' => $isUserLogged, 'user_id' => $userId]);
                    $navbar = $navbarEngine->render(Define::DIRECTORIES['components'] . 'navbar.php');
                    $renderedNavbar = ob_get_clean();

                    ob_start();
                    $footerEngine = new TemplateEngine(['date' => date('Y')]);
                    $footer = $footerEngine->render(Define::DIRECTORIES['components'] . 'footer.php');
                    $renderedFooter = ob_get_clean();
    
                    ob_start();
                    $engine = new TemplateEngine($data);
                    $content = $engine->render(Define::DIRECTORIES['components'] . $action . '.php');
                    $renderedContent = ob_get_clean();
    
                    echo $renderedContent;
                    include Define::LAYOUT_FILE;
    
                    $routeMatched = true;
                } else {
                    throw new \Exception("View not found: $action");
                }
            } else {
                throw new \Exception("Action not found: $action, in controller: $controllerClass");
            }
        } else {
            throw new \Exception("Controller not found: $controllerClass");
        }
    
        if (!$routeMatched) {
            throw new \Exception('The requested route has no match');
        }
    }    
}