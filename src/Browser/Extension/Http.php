<?php

namespace Zenstruck\Browser\Extension;

use PHPUnit\Framework\Assert as PHPUnit;
use Zenstruck\Browser\Extension\Http\HttpOptions;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
trait Http
{
    private ?HttpOptions $defaultHttpOptions = null;

    /**
     * @param HttpOptions|array $options
     *
     * @return static
     */
    final public function setDefaultHttpOptions($options): self
    {
        $this->defaultHttpOptions = HttpOptions::create($options);

        return $this;
    }

    /**
     * @param HttpOptions|array $options HttpOptions::DEFAULT_OPTIONS
     *
     * @return static
     */
    final public function request(string $method, string $url, $options = []): self
    {
        if ($this->defaultHttpOptions) {
            $options = $this->defaultHttpOptions->merge($options);
        }

        $this->makeRequest($method, $url, HttpOptions::create($options));

        return $this;
    }

    /**
     * @see request()
     *
     * @return static
     */
    final public function get(string $url, $options = []): self
    {
        return $this->request('GET', $url, $options);
    }

    /**
     * @see request()
     *
     * @return static
     */
    final public function post(string $url, $options = []): self
    {
        return $this->request('POST', $url, $options);
    }

    /**
     * @see request()
     *
     * @return static
     */
    final public function put(string $url, $options = []): self
    {
        return $this->request('PUT', $url, $options);
    }

    /**
     * @see request()
     *
     * @return static
     */
    final public function delete(string $url, $options = []): self
    {
        return $this->request('DELETE', $url, $options);
    }

    /**
     * @return static
     */
    final public function assertStatus(int $expected): self
    {
        return $this->wrapMinkExpectation(
            fn() => $this->webAssert()->statusCodeEquals($expected)
        );
    }

    /**
     * @return static
     */
    final public function assertSuccessful(): self
    {
        PHPUnit::assertTrue($this->response()->isSuccessful(), "Expected successful status code (2xx), [{$this->response()->statusCode()}] received.");

        return $this;
    }

    /**
     * @return static
     */
    final public function assertRedirected(): self
    {
        PHPUnit::assertTrue($this->response()->isRedirect(), "Expected redirect status code (3xx), [{$this->response()->statusCode()}] received.");

        return $this;
    }

    /**
     * @return static
     */
    final public function assertHeaderEquals(string $header, string $expected): self
    {
        return $this->wrapMinkExpectation(
            fn() => $this->webAssert()->responseHeaderEquals($header, $expected)
        );
    }

    /**
     * @return static
     */
    final public function assertHeaderContains(string $header, string $expected): self
    {
        return $this->wrapMinkExpectation(
            fn() => $this->webAssert()->responseHeaderContains($header, $expected)
        );
    }

    abstract protected function makeRequest(string $method, string $url, HttpOptions $options): void;
}
