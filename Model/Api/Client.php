<?php

declare(strict_types=1);

namespace Macopedia\Allegro\Model\Api;

use GuzzleHttp\Exception\GuzzleException;
use Macopedia\Allegro\Api\Data\TokenInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Macopedia\Allegro\Logger\Logger;
use Macopedia\Allegro\Model\Configuration;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientFactory;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ResponseFactory;
use Magento\Framework\Webapi\Rest\Request as MagentoRequest;

/**
 * Processes API requests and responses
 */
class Client
{
    const API_URL = 'https://api.allegro.pl/';
    const SANDBOX_API_URL = 'https://api.allegro.pl.allegrosandbox.pl/';

    /** @var Json */
    private $json;

    /** @var Logger */
    private $logger;

    /** @var Configuration */
    private $config;

    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * @var ClientFactory
     */
    private $clientFactory;

    /**
     * Client constructor.
     * @param Json $json
     * @param Logger $logger
     * @param Configuration $config
     * @param ClientFactory $clientFactory
     * @param ResponseFactory $responseFactory
     */
    public function __construct(
        Json $json,
        Logger $logger,
        Configuration $config,
        ClientFactory $clientFactory,
        ResponseFactory $responseFactory
    ) {
        $this->json = $json;
        $this->logger = $logger;
        $this->config = $config;
        $this->clientFactory = $clientFactory;
        $this->responseFactory = $responseFactory;
    }

    /**
     * @param TokenInterface $token
     * @param Request $request
     * @return mixed
     * @throws ClientResponseErrorException
     * @throws ClientResponseException
     */
    public function sendRequest(TokenInterface $token, Request $request)
    {
        try {
            $json = $this->sendHttpRequest($token, $request);
        } catch (GuzzleException $e) {
            throw new ClientResponseException(__('Error while receiving response from Allegro API'), $e, $e->getCode());
        }
        $response = $this->json->unserialize($json);

        if (isset($response['errors'])) {
            $errors = [];
            foreach ($response['errors'] as $error) {
                $errors[] = $error['message'] != '' ? $error['message'] : $error['userMessage'];
            }
            throw new ClientResponseErrorException(
                __(
                    'Errors returned in received from Allegro API response: %1',
                    implode(' ', $errors)
                )
            );
        }

        return $response;
    }

    /**
     * @param TokenInterface $token
     * @param Request $request
     * @return array
     */
    private function prepareHeaders(TokenInterface $token, Request $request)
    {
        return [
            'Authorization' => 'Bearer ' . $token->getAccessToken(),
            'Accept' => $request->getAcceptType() ?: Request::TYPE_BETA,
            'Content-Type' => $request->getContentType() ?: Request::TYPE_BETA
        ];
    }

    /**
     * @param Request $request
     * @return string
     */
    private function getApiUrl(Request $request)
    {
        if ($request->isSandbox()) {
            return self::SANDBOX_API_URL;
        }

        return self::API_URL;
    }

    /**
     * @param TokenInterface $token
     * @param Request $request
     * @return bool|string
     * @throws GuzzleException
     */
    private function sendHttpRequest(TokenInterface $token, Request $request)
    {
        $params['headers'] = $this->prepareHeaders($token, $request);

        $content = preg_match('/image\/.*jpeg/', $request->getContentType())
            ? 'body'
            : 'json';

        if ($request->getMethod() !== MagentoRequest::HTTP_METHOD_GET) {
            $params[$content] = $request->getBody();
        }
        $client = $this->clientFactory->create([
            'config' =>
                [
                    'base_uri' => $this->getApiUrl($request),
                    'timeout' => 120
                ],
        ]);

        $response = $client->request($request->getMethod(), $request->getUri(), $params);

        if (!$this->config->isDebugModeEnabled()) {
            return $response->getBody()->getContents();
        }

        $requestId = uniqid('', true);
        $this->logger->debug('ALLEGRO API HTTP REQUEST ' . $requestId . ': ' . $request->getMethod() . ' ' . $request->getUri() . $this->json->serialize($request->getMethod()));//phpcs:ignore
        $response = $response->getBody()->getContents();
        $this->logger->debug('ALLEGRO API HTTP RESPONSE ' . $requestId . ': ' . $response);

        return $response;
    }
}
