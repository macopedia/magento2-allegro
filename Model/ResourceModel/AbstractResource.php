<?php

namespace Macopedia\Allegro\Model\ResourceModel;

use Macopedia\Allegro\Logger\Logger;
use Macopedia\Allegro\Model\Api\Auth\Data\TokenDecoder;
use Macopedia\Allegro\Model\Api\Auth\Data\TokenDecoderException;
use Macopedia\Allegro\Model\Api\Client;
use Macopedia\Allegro\Model\Api\ClientException;
use Macopedia\Allegro\Model\Api\ClientResponseErrorException;
use Macopedia\Allegro\Model\Api\ClientResponseException;
use Macopedia\Allegro\Model\Api\Request;
use Macopedia\Allegro\Model\Api\TokenProvider;
use Macopedia\Allegro\Model\Cache\Type;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * Base class for resource models to send requests for Allegro API
 */
abstract class AbstractResource
{
    const REQUEST_GET  = 'GET';
    const REQUEST_POST = 'POST';
    const REQUEST_PUT  = 'PUT';

    const SANDBOX_CONFIG_KEY = 'allegro/general/sandbox';

    /** @var ScopeConfigInterface */
    private $scopeConfig;

    /** @var Client */
    private $client;

    /** @var TokenProvider */
    private $tokenProvider;

    /** @var TokenDecoder */
    private $tokenDecoder;

    /** @var Logger */
    private $logger;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param Client               $client
     * @param TokenProvider        $tokenProvider
     * @param Logger               $logger
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Client $client,
        TokenProvider $tokenProvider,
        TokenDecoder $tokenDecoder,
        Logger $logger
    ) {
        $this->scopeConfig   = $scopeConfig;
        $this->client        = $client;
        $this->tokenProvider = $tokenProvider;
        $this->tokenDecoder  = $tokenDecoder;
        $this->logger        = $logger;
    }

    /**
     * @return string|null
     * @throws ClientException
     * @throws ClientResponseErrorException
     * @throws ClientResponseException
     */
    public function getCurrentUserId(): ?string
    {
        $token = $this->tokenProvider->getCurrent();
        try {
            return $this->tokenDecoder->getSellerId($token);
        } catch (TokenDecoderException $e) {
            throw new ClientException(__('Could not decode token'), $e);
        }
    }

    /**
     * @param string $uri
     * @param array  $params
     * @param bool   $isBeta
     * @return array
     * @throws ClientException
     * @throws ClientResponseException
     * @throws ClientResponseErrorException
     */
    protected function requestGet($uri, array $params = [], $isBeta = false)
    {
        $response = $this->sendRequest($uri, self::REQUEST_GET, $params, $isBeta);
        return $response;
    }

    /**
     * @param string $uri
     * @param array  $params
     * @param bool   $isBeta
     * @return array
     * @throws ClientException
     * @throws ClientResponseException
     * @throws ClientResponseErrorException
     */
    protected function cachedRequestGet($uri, array $params = [], $isBeta = false)
    {
        \Magento\Framework\Profiler::start(__CLASS__ . '::' . __METHOD__ . '::' . $uri);
        $identifier = sha1($this->serializer()->serialize([$uri, self::REQUEST_GET, $params, $isBeta]));
        if ($this->cache()->test($identifier)) {
            $response = $this->serializer()->unserialize($this->cache()->load($identifier));
        } else {
            $response = $this->sendRequest($uri, self::REQUEST_GET, $params, $isBeta);
            $this->cache()->save($this->serializer()->serialize($response), $identifier);
        }
        \Magento\Framework\Profiler::stop(__CLASS__ . '::' . __METHOD__ . '::' . $uri);
        return $response;
    }

    /**
     * @param string $uri
     * @param array  $params
     * @param bool   $isBeta
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
     * @param       $uri
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
     * @param       $uri
     * @param array $params
     * @return Request
     */
    private function request($uri, array $params)
    {
        return $this->getRequest()
            ->setUri($uri)
            ->setIsSandbox($this->isSandbox())
            ->setBody($params);
    }

    /**
     * @param string $uri
     * @param string $method
     * @param array  $params
     * @param bool   $isBeta
     * @return mixed
     * @throws ClientException
     * @throws ClientResponseException
     * @throws ClientResponseErrorException
     */
    private function sendRequest($uri, $method, array $params = [], $isBeta = false)
    {
        \Magento\Framework\Profiler::start(__CLASS__ . '::' . __METHOD__);
        $token = $this->tokenProvider->getCurrent();

        $request = $this->request($uri, $params)->setMethod($method);

        if ($isBeta) {
            $request->setIsBeta();
        } else {
            $request->setIsPublic();
        }

        $response = $this->client->sendRequest($token, $request);

        \Magento\Framework\Profiler::stop(__CLASS__ . '::' . __METHOD__);
        return $response;
    }

    /**
     * @param string $uri
     * @param string $method
     * @param mixed  $data
     * @param string $mimeType
     * @param bool   $isBeta
     * @return mixed
     * @throws ClientException
     * @throws ClientResponseErrorException
     * @throws ClientResponseException
     */
    protected function sendRawRequest($uri, $method, $data, $mimeType, $isBeta = false)
    {
        \Magento\Framework\Profiler::start(__CLASS__ . '::' . __METHOD__);
        $token = $this->tokenProvider->getCurrent();

        $request = $this->getRequest()
            ->setUri($uri)
            ->setIsSandbox($this->isSandbox())
            ->setRawBody($data)
            ->setMethod($method)
            ->setAcceptType($isBeta ? Request::TYPE_BETA : Request::TYPE_PUBLIC)
            ->setContentType($mimeType);

        $response = $this->client->sendRequest($token, $request);

        \Magento\Framework\Profiler::stop(__CLASS__ . '::' . __METHOD__);
        return $response;
    }

    /**
     * @return bool
     */
    private function isSandbox()
    {
        return (bool)$this->scopeConfig->getValue(self::SANDBOX_CONFIG_KEY);
    }

    /**
     * @return Request
     */
    private function getRequest()
    {
        return new Request();
    }

    /**
     * @return \Magento\Framework\App\ObjectManager
     */
    private function objectManager()
    {
        return \Magento\Framework\App\ObjectManager::getInstance();
    }

    /**
     * @return Type
     */
    private function cache()
    {
        return $this->objectManager()->get(Type::class);
    }

    /**
     * @return Json
     */
    private function serializer()
    {
        return $this->objectManager()->get(Json::class);
    }
}
