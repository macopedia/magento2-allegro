<?php

namespace Macopedia\Allegro\Model\Api;

/**
 * Request data class
 */
class Request
{
    const CONTENT_TYPE_PUBLIC = 'application/vnd.allegro.public.v1+json';
    const CONTENT_TYPE_BETA = 'application/vnd.allegro.beta.v1+json';

    /** @var string */
    private $uri;

    /** @var array|null */
    private $body;

    /** @var array|null */
    private $query;

    /** @var string */
    private $contentType;

    /** @var string */
    private $method;

    /** @var bool */
    private $isSandbox;

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     * @return Request
     * @throws \InvalidArgumentException
     */
    public function setUri($uri)
    {
        $this->uri = $this->prepareUri($uri);
        return $this;
    }

    /**
     * @return array|null
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param array|null $body
     * @return Request
     */
    public function setBody(array $body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param array|null $query
     * @return Request
     */
    public function setQuery($query)
    {
        $this->query = $query;
        return $this;
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @param string $contentType
     * @return Request
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
        return $this;
    }

    /**
     * @return $this
     */
    public function setContentPublic()
    {
        $this->contentType = self::CONTENT_TYPE_PUBLIC;
        return $this;
    }

    /**
     * @return $this
     */
    public function setContentBeta()
    {
        $this->contentType = self::CONTENT_TYPE_BETA;
        return $this;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     * @return Request
     */
    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSandbox()
    {
        return $this->isSandbox;
    }

    /**
     * @param bool $isSandbox
     * @return Request
     */
    public function setIsSandbox($isSandbox)
    {
        $this->isSandbox = $isSandbox;
        return $this;
    }

    /**
     * @param string $uri
     * @return bool|string
     * @throws \InvalidArgumentException
     */
    public function prepareUri($uri)
    {
        if ('/' === substr($uri, 0, 1)) {
            return substr($uri, 1);
        }
        if ('http' === substr($uri, 0, 4)) {
            throw new \InvalidArgumentException('Please provide just URI, eg. sale/offer-additional-services/groups');
        }

        return $uri;
    }
}
