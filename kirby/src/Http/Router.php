<?php

namespace Kirby\Http;

use Exception;

/**
 * @package   Kirby Http
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      http://getkirby.com
 * @copyright Bastian Allgeier
 * @license   MIT
 */
class Router
{

    /**
     * Store for the current route,
     * if one can be found
     *
     * @var Route|null
     */
    protected $route;

    /**
     * All registered routes, sorted by
     * their request method. This makes
     * it faster to find the right route
     * later.
     *
     * @var array
     */
    protected $routes = [
        'GET'     => [],
        'HEAD'    => [],
        'POST'    => [],
        'PUT'     => [],
        'DELETE'  => [],
        'CONNECT' => [],
        'OPTIONS' => [],
        'TRACE'   => [],
        'PATCH'   => [],
    ];

    /**
     * Creates a new router object and
     * registers all the given routes
     *
     * @param array $routes
     */
    public function __construct(array $routes = [])
    {
        foreach ($routes as $props) {
            if (isset($props['pattern'], $props['action']) === false) {
                throw new Exception('Invalid route parameters');
            }

            $methods  = array_map('trim', explode('|', strtoupper($props['method'] ?? 'GET')));
            $patterns = is_array($props['pattern']) === false ? [$props['pattern']] : $props['pattern'];

            if ($methods === ['ALL']) {
                $methods = array_keys($this->routes);
            }

            foreach ($methods as $method) {
                foreach ($patterns as $pattern) {
                    $this->routes[$method][$pattern] = new Route($pattern, $method, $props['action'], $props);
                }
            }
        }
    }

    /**
     * Calls the Router by path and method.
     * This will try to find a Route object
     * and then call the Route action with
     * the appropriate arguments and a Result
     * object.
     *
     * @param  string $path
     * @param  string $method
     * @return mixed
     */
    public function call(string $path = '', string $method = 'GET')
    {
        return $this
            ->find($path, $method)
            ->action()
            ->call($this->route, ...$this->route->arguments());
    }

    /**
     * Finds a Route object by path and method
     * The Route's arguments method is used to
     * find matches and return all the found
     * arguments in the path.
     *
     * @param  string $path
     * @param  string $method
     * @return Result|null
     */
    public function find(string $path, string $method)
    {
        if (isset($this->routes[$method]) === false) {
            throw new Exception('Invalid routing method: ' . $method);
        }

        // remove leading and trailing slashes
        $path = trim($path, '/');

        // direct access to home routes
        if ($path === '') {
            if (isset($this->routes[$method]['']) === true) {
                return $this->route = $this->routes[$method][''];
            }

            if (isset($this->routes[$method]['/']) === true) {
                return $this->route = $this->routes[$method]['/'];
            }
        } else {
            foreach ($this->routes[$method] as $pattern => $route) {
                $arguments = $route->parse($pattern, $path);

                if ($arguments !== false) {
                    return $this->route = $route;
                }
            }
        }

        throw new Exception('No route found for path: "' . $path . '" and request method: "' . $method . '"');
    }

    /**
     * Returns the current route.
     * This will only return something,
     * once Router::find() has been called
     * and only if a route was found.
     *
     * @return Route|null
     */
    public function route()
    {
        return $this->route;
    }
}
