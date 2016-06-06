<?php

namespace Surfnet\StepupBundle\Value;

use Surfnet\StepupBundle\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;

final class CookieSettings
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $domain;

    /**
     * @var string
     */
    private $expire;

    /**
     * @var string
     */
    private $path;

    /**
     * @var bool
     */
    private $secure;
    /**
     * @var bool
     */
    private $httpOnly;

    /**
     * @param string $name
     * @param string $domain
     * @param string $expire
     * @param string $path
     * @param bool $secure
     * @param bool $httpOnly
     */
    public function __construct($name, $domain, $expire, $path, $secure, $httpOnly)
    {
        if (!is_string($name)) {
            throw InvalidArgumentException::invalidType('string', 'name', $name);
        }
        if (empty($name)) {
            throw new InvalidArgumentException('Empty name provided to ' . __CLASS__);
        }

        $this->name = $name;

        if (!is_string($domain)) {
            throw InvalidArgumentException::invalidType('string', 'domain', $domain);
        }
        if (empty($domain)) {
            throw new InvalidArgumentException(sprintf('Empty domain provided to ' . __CLASS__));
        }

        $this->domain = $domain;

        if (!is_integer($expire)) {
            throw InvalidArgumentException::invalidType('integer', 'expire', $expire);
        }

        $this->expire = $expire;

        if (!is_string($path)) {
            throw InvalidArgumentException::invalidType('string', 'path', $path);
        }

        $this->path = $path;

        if (!is_bool($secure)) {
            throw InvalidArgumentException::invalidType('bool', 'secure', $secure);
        }

        $this->secure = $secure;

        if (!is_bool($httpOnly)) {
            throw InvalidArgumentException::invalidType('bool', 'httpOnly', $httpOnly);
        }

        $this->httpOnly = $httpOnly;
    }

    /**
     * The the value for this cookie from the request, null if doesn't exist.
     *
     * @param Request $request
     * @return string|null
     */
    public function value(Request $request)
    {
        return $request->cookies->get($this->name);
    }

    public function toCookie($value)
    {
        return new Cookie(
            $this->name,
            $value,
            $this->expire,
            $this->path,
            $this->domain,
            $this->secure,
            $this->httpOnly
        );
    }
}
