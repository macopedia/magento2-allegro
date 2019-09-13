<?php

namespace Macopedia\Allegro\Model\ResourceModel;

use Macopedia\Allegro\Logger\Logger;
use Macopedia\Allegro\Model\Api\Client;
use Macopedia\Allegro\Model\Api\ClientException;
use Macopedia\Allegro\Model\Api\ClientResponseErrorException;
use Macopedia\Allegro\Model\Api\ClientResponseException;
use Macopedia\Allegro\Model\Api\Request;
use Macopedia\Allegro\Model\Api\TokenProvider;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Base class for resource models to send requests for Allegro API
 */
abstract class AbstractResource
{
    const REQUEST_GET = 'GET';
    const REQUEST_POST = 'POST';
    const REQUEST_PUT = 'PUT';

    const SANDBOX_CONFIG_KEY = 'allegro/general/sandbox';

    /** @var ScopeConfigInterface */
    private $scopeConfig;

    /** @var Client */
    private $client;

    /** @var TokenProvider */
    private $tokenProvider;

    /** @var Logger */
    private $logger;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param Client $client
     * @param TokenProvider $tokenProvider
     * @param Logger $logger
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Client $client,
        TokenProvider $tokenProvider,
        Logger $logger
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->client = $client;
        $this->tokenProvider = $tokenProvider;
        $this->logger = $logger;
    }

    /**
     * @param string $uri
     * @param array $params
     * @param bool $isBeta
     * @return array
     * @throws ClientException
     * @throws ClientResponseException
     * @throws ClientResponseErrorException
     */
    protected function requestGet($uri, array $params = [], $isBeta = false)
    {
        return $this->sendRequest($uri, self::REQUEST_GET, $params, $isBeta);
    }

    /**
     * @param string $uri
     * @param array $params
     * @param bool $isBeta
     * @return array
     * @throws ClientException
     * @throws ClientResponseException
     * @throws ClientResponseErrorException
     */
    protected function requestPost($uri, array $params = [], $isBeta = false)
    {
        return $this->sendRequest($uri, self::REQUEST_POST, $params, $isBeta);
    }

    /**
     * @param $uri
     * @param array $params
     * @return array
     * @throws ClientException
     * @throws ClientResponseException
     * @throws ClientResponseErrorException
     */
    protected function requestPut($uri, array $params = [])
    {
        return $this->sendRequest($uri, self::REQUEST_PUT, $params);
    }

    /**
     * @param $uri
     * @param array $params
     * @return Request
     */
    private function request($uri, array $params)
    {
        return $this->getRequest()
            ->setUri($uri)
            ->setIsSandbox($this->isSandbox($this->isSandbox()))
            ->setBody($params);
    }

    /**
     * @param $uri
     * @param $method
     * @param array $params
     * @param bool $isBeta
     * @return mixed
     * @throws ClientException
     * @throws ClientResponseException
     * @throws ClientResponseErrorException
     */
    private function sendRequest($uri, $method, array $params = [], $isBeta = false)
    {
        $token = $this->tokenProvider->getCurrent();

        $request = $this->request($uri, $params)->setMethod($method);

        if ($isBeta) {
            $request->setContentBeta();
        } else {
            $request->setContentPublic();
        }

        return $this->client->sendRequest($token, $request);
    }

    /**
     * @return bool
     */
    private function isSandbox()
    {
        return (bool)$this->scopeConfig->getValue(self::SANDBOX_CONFIG_KEY);
    }

    private function getRequest()
    {
        return new Request();
    }
}
