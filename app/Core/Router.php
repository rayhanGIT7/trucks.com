<?php

namespace App\Core;

/**
 * Very small router: connects a URL to a controller method.
 *
 *   $router->get('/trucks/{id}', [TruckController::class, 'show']);
 *
 * {id} matches a number and is passed to the controller method.
 */
class Router
{
    private array $routes = [];

    /** A normal page (GET). */
    public function get(string $path, array $action): void
    {
        $this->addRoute('GET', $path, $action, false);
    }

    /** A normal form submit (POST). Needs the CSRF token. */
    public function post(string $path, array $action): void
    {
        $this->addRoute('POST', $path, $action, false);
    }

    /**
     * A POST URL called by another website (SSLCommerz).
     * No session and no CSRF check, because the request does not come from our own form.
     */
    public function callback(string $path, array $action): void
    {
        $this->addRoute('POST', $path, $action, true);
    }

    private function addRoute(string $method, string $path, array $action, bool $isCallback): void
    {
        // Turn "/trucks/{id}" into the regular expression "#^/trucks/(\d+)$#"
        $pattern = '#^' . str_replace('{id}', '(\d+)', $path) . '$#';

        $this->routes[] = [
            'method'     => $method,
            'pattern'    => $pattern,
            'controller' => $action[0],
            'function'   => $action[1],
            'isCallback' => $isCallback,
        ];
    }

    /** Find the route for this request and run its controller method. */
    public function dispatch(string $method, string $path): void
    {
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            if (!preg_match($route['pattern'], $path, $matches)) {
                continue;
            }

            if (!$route['isCallback']) {
                Session::start();
                if ($method === 'POST' && !Session::isValidCsrf($_POST['_token'] ?? '')) {
                    throw new HttpException(419);
                }
            }

            $controller = new $route['controller']();
            $function = $route['function'];

            if (isset($matches[1])) {
                $controller->$function((int) $matches[1]); // URL has an {id}
            } else {
                $controller->$function();
            }
            return;
        }

        throw new HttpException(404);
    }
}
