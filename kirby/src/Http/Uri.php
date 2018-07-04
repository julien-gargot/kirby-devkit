<?php

namespace Kirby\Http;

use Throwable;
use Kirby\Exception\InvalidArgumentException;
use Kirby\Toolkit\Properties;
use Kirby\Toolkit\Str;

/**
 * Uri builder class
 *
 * @package   Kirby Http
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      http://getkirby.com
 * @copyright Bastian Allgeier
 * @license   MIT
 */
class Uri
{
    use Properties;

    /**
     * The fragment after the hash
     *
     * @var string|false
     */
    protected $fragment;

    /**
     * The host address
     *
     * @var string
     */
    protected $host;

    /**
     * The optional password for basic authentication
     *
     * @var string|false
     */
    protected $password;

    /**
     * The optional path
     *
     * @var Path
     */
    protected $path;

    /**
     * The optional port number
     *
     * @var int|false
     */
    protected $port;

    /**
     * All original properties
     *
     * @var array
     */
    protected $props;

    /**
     * The optional query string without leading ?
     *
     * @var Query
     */
    protected $query;

    /**
     * https or http
     *
     * @var string
     */
    protected $scheme = 'http';

    /**
     * The optional username for basic authentication
     *
     * @var string|false
     */
    protected $username;

    /**
     * Magic caller to access all properties
     *
     * @param string $property
     * @param array $arguments
     * @return mixed
     */
    public function __call(string $property, array $arguments = [])
    {
        return $this->$property ?? null;
    }

    /**
     * Make sure that cloning also clones
     * the path and query objects
     *
     * @return void
     */
    public function __clone()
    {
        $this->path  = clone $this->path;
        $this->query = clone $this->query;
    }

    /**
     * Creates a new URI object
     *
     * @param array
     */
    public function __construct($props = [])
    {
        if (is_string($props) === true) {
            $props = parse_url($props);
            $props['username'] = $props['user'] ?? null;
            $props['password'] = $props['pass'] ?? null;
        }

        $this->setProperties($this->props = $props);
    }

    /**
     * Magic getter
     *
     * @param string $property
     * @return mixed
     */
    public function __get(string $property)
    {
        return $this->$property ?? null;
    }

    /**
     * Magic setter
     *
     * @param string $property
     * @param mixed $value
     */
    public function __set(string $property, $value)
    {
        if (method_exists($this, 'set' . $property) === true) {
            $this->{'set' . $property}($value);
        }
    }

    /**
     * Converts the URL object to string
     *
     * @return string
     */
    public function __toString(): string
    {
        try {
            return $this->toString();
        } catch (Throwable $e) {
            error_log($e);
            return '';
        }
    }

    /**
     * Returns the auth details (username:password)
     *
     * @return string|null
     */
    public function auth()
    {
        $auth = trim($this->username . ':' . $this->password);
        return $auth !== ':' ? $auth : null;
    }

    /**
     * Returns the base url (scheme + host)
     * without trailing slash
     *
     * @return string
     */
    public function base()
    {
        if (empty($this->host) === true || $this->host === '/') {
            return null;
        }

        $auth = $this->auth();
        $base = $this->scheme ? $this->scheme . '://' : '';

        if ($auth !== null) {
            $base .= $auth . '@';
        }

        $base .= $this->host;

        if ($this->port !== null && in_array($this->port, [80, 443]) === false) {
            $base .= ':' . $this->port;
        }

        return $base;
    }

    /**
     * @param array $props
     * @param boolean $forwarded
     * @return self
     */
    public static function current(array $props = [], bool $forwarded = false): self
    {
        $uri = parse_url(Server::get('REQUEST_URI'));
        $url = new static(array_merge([
            'scheme' => Server::https() === true ? 'https' : 'http',
            'host'   => Server::host($forwarded),
            'port'   => Server::port($forwarded),
            'path'   => $uri['path'] ?? null,
            'query'  => $uri['query'] ?? null,
        ], $props));

        return $url;
    }

    /**
     * @return boolean
     */
    public function hasFragment(): bool
    {
        return empty($this->fragment) === false;
    }

    /**
     * @return boolean
     */
    public function hasPath(): bool
    {
        return $this->path()->isNotEmpty();
    }

    /**
     * @return boolean
     */
    public function hasQuery(): bool
    {
        return $this->query()->isNotEmpty();
    }

    /**
     * Tries to convert the internationalized host
     * name to the human-readable UTF8 representation
     *
     * @return self
     */
    public function idn(): self
    {
        if (empty($this->host) === false) {
            $this->setHost(Idn::decode($this->host));
        }
        return $this;
    }

    /**
     * Creates an Uri object for the URL to the index.php
     * or any other executed script.
     *
     * @param array $props
     * @param bool $forwarded
     * @return string
     */
    public static function index(array $props = [], bool $forwarded = false): self
    {
        if (Server::cli() === true) {
            $path = null;
        } else {
            $path = trim(dirname(Server::get('SCRIPT_NAME')), '/');
        }

        if ($path === '.') {
            $path = null;
        }

        return static::current(array_merge($props, [
            'path'     => $path,
            'query'    => null,
            'fragment' => null,
        ]), $forwarded);
    }


    /**
     * Checks if the host exists
     *
     * @return bool
     */
    public function isAbsolute(): bool
    {
        return empty($this->host) === false;
    }

    /**
     * @param  string $scheme
     * @return self
     */
    public function setScheme(string $scheme = null): self
    {
        if ($scheme !== null && in_array($scheme, ['http', 'https', 'ftp']) === false) {
            throw new InvalidArgumentException('Invalid URL scheme: ' . $scheme);
        }

        $this->scheme = $scheme;
        return $this;
    }

    /**
     * @param  string $host
     * @return self
     */
    public function setHost(string $host = null): self
    {
        $this->host = $host;
        return $this;
    }

    /**
     * @param  int|null $port
     * @return self
     */
    public function setPort(int $port = null): self
    {
        if ($port === 0) {
            $port = null;
        }

        if ($port !== null) {
            if ($port < 1 || $port > 65535) {
                throw new InvalidArgumentException('Invalid port format: ' . $port);
            }
        }

        $this->port = $port;
        return $this;
    }

    /**
     * @param  string|null $username
     * @return self
     */
    public function setUsername(string $username = null): self
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @param  string|null $password
     * @return self
     */
    public function setPassword(string $password = null): self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @param  string|array|null $path
     * @return self
     */
    public function setPath($path = null): self
    {
        $this->path = is_a($path, Path::class) ? $path : new Path($path);

        if (($this->props['trailingSlash'] ?? false) === true) {
            $this->path->trailingSlash(true);
        }

        return $this;
    }

    /**
     * @param  string|array|null $query
     * @return self
     */
    public function setQuery($query = null): self
    {
        $this->query = is_a($query, Query::class) ? $query : new Query($query);
        return $this;
    }

    /**
     * @param  string|null $fragment
     * @return self
     */
    public function setFragment(string $fragment = null)
    {
        $this->fragment = $fragment ? ltrim($fragment, '#') : null;
        return $this;
    }

    /**
     * Converts the Url object to an array
     *
     * @return array
     */
    public function toArray(): array
    {
        $array = [];

        foreach ($this->propertyData as $key => $value) {
            $value = $this->$key;

            if (is_object($value) === true) {
                $value = $value->toArray();
            }

            $array[$key] = $value;
        }

        return $array;
    }

    public function toJson(...$arguments): string
    {
        return json_encode($this->toArray(), ...$arguments);
    }

    /**
     * Returns the full URL as string
     *
     * @return string
     */
    public function toString(): string
    {
        $url   = $this->base();
        $trail = true;

        if (empty($url) === true) {
            $url   = '/';
            $trail = false;
        }

        $url .= $this->path->toString($trail);
        $url .= $this->query->toString(true);

        if (empty($this->fragment) === false) {
            $url .= '#' . $this->fragment;
        }

        return $url;
    }

    /**
     * Tries to convert a URL with an internationalized host
     * name to the machine-readable Punycode representation
     *
     * @return self
     */
    public function unIdn(): self
    {
        if (empty($this->host) === false) {
            $this->setHost(Idn::encode($this->host));
        }
        return $this;
    }
}
