<?php

namespace Zenstruck\Browser;

use Behat\Mink\Session;
use Zenstruck\Browser\Response\HtmlResponse;
use Zenstruck\Browser\Response\JsonResponse;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class Response
{
    private Session $session;

    final public function __construct(Session $session)
    {
        $this->session = $session;
    }

    final public function statusCode(): int
    {
        return $this->session->getStatusCode();
    }

    final public static function createFor(Session $session): self
    {
        $contentType = $session->getResponseHeader('content-type');

        if (str_contains($contentType, 'json')) {
            return new JsonResponse($session);
        }

        if (str_contains($contentType, 'html')) {
            return new HtmlResponse($session);
        }

        return new self($session);
    }

    public function body()
    {
        return $this->session->getPage()->getContent();
    }

    final public function raw(): string
    {
        return "{$this->rawMetadata()}\n{$this->rawBody()}";
    }

    final public function isSuccessful(): bool
    {
        return $this->statusCode() >= 200 && $this->statusCode() < 300;
    }

    final public function isRedirect(): bool
    {
        return $this->statusCode() >= 300 && $this->statusCode() < 400;
    }

    /**
     * @return mixed
     */
    public function find(string $selector)
    {
        throw new \LogicException('Find cannot be used on this response type.');
    }

    final protected function session(): Session
    {
        return $this->session;
    }

    protected function rawMetadata(): string
    {
        $ret = "URL: {$this->session->getCurrentUrl()} ({$this->statusCode()})\n\n";

        foreach ($this->session->getResponseHeaders() as $header => $values) {
            foreach ($values as $value) {
                $ret .= "{$header}: {$value}\n";
            }
        }

        return $ret;
    }

    protected function rawBody(): string
    {
        return $this->body();
    }
}
