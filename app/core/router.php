<?php

class Router
{
    private array $routes;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    public function dispatch(string $method, string $uri): void
    {
        $method = strtoupper($method);
        $path = $this->normalizePath($uri);

        foreach ($this->routes as $route) {
            if (!is_array($route) || count($route) < 3) continue;

            [$routeMethod, $routePath, $handler] = $route;

            if ($method !== strtoupper((string)$routeMethod)) continue;

            $params = $this->match((string)$routePath, $path);
            if ($params !== null) {
                $this->callHandler((string)$handler, $params);
                return;
            }
        }

        http_response_code(404);
        echo '404 - page introuvable';
    }

    private function normalizePath(string $uri): string
    {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        $path = '/' . trim($path, '/');
        return $path === '//' ? '/' : $path;
    }

    private function match(string $routePath, string $path): ?array
    {
        $routePath = $this->normalizePath($routePath);

        if ($routePath === $path) return [];

        $paramNames = [];
        $regex = preg_replace_callback(
            '/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/',
            function ($m) use (&$paramNames) {
                $paramNames[] = $m[1];
                return '([^\/]+)';
            },
            $routePath
        );

        $regex = '#^' . $regex . '$#';

        if (!preg_match($regex, $path, $matches)) return null;

        array_shift($matches);

        $params = [];
        foreach ($paramNames as $i => $name) {
            $params[$name] = $matches[$i] ?? null;
        }

        return $params;
    }

    private function callHandler(string $handler, array $params = []): void
    {
        // Format : "Controller@action" ou "Admin/Controller@action"
        if (!preg_match('#^[A-Za-z0-9/_-]+@[A-Za-z_][A-Za-z0-9_]*$#', $handler)) {
            http_response_code(500);
            exit('handler invalide');
        }

        [$controller, $action] = explode('@', $handler, 2);

        // Sécurise le chemin du controller (pas de ..)
        if (str_contains($controller, '..')) {
            http_response_code(500);
            exit('controller invalide');
        }

        // Normalise les séparateurs
        $controller = str_replace('\\', '/', $controller);

        
        $controllerFile = APP_ROOT . '/app/controllers/' . $controller . '.php';

        if (!is_file($controllerFile)) {
            http_response_code(500);
            exit("controller introuvable : {$controller}");
        }

        require_once $controllerFile;

        
        $parts = explode('/', $controller);
        $class = (string)end($parts);

        if (!class_exists($class)) {
            http_response_code(500);
            exit("classe controller introuvable : {$class}");
        }

        $instance = new $class();

        if (!is_callable([$instance, $action])) {
            http_response_code(500);
            exit("méthode introuvable : {$action}");
        }

        $ref = new ReflectionMethod($instance, $action);
        if ($ref->getNumberOfParameters() >= 1) {
            $instance->$action($params);
        } else {
            $instance->$action();
        }
    }
}