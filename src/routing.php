<?php

class Router
{
    private $routes = [];

    public function addRoute($method, $route, $controller, $action)
    {
        $this->routes[] = [
            'method' => $method,
            'route' => $route,
            'controller' => $controller,
            'action' => $action
        ];
    }

    public function dispatch($requestMethod, $requestUri)
    {
        foreach ($this->routes as $route) {
            if ($route['method'] === $requestMethod && $route['route'] === $requestUri) {
                $controllerName = $route['controller'];
                $actionName = $route['action'];

                require_once BASE_PATH . '/controllers/' . $controllerName . '.php';

                $controller = new $controllerName();
                $controller->$actionName();
                return;
            }
        }

        http_response_code(404);
        echo "404 Not Found";
    }
}
