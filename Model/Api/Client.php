<?php

declare(strict_types=1);

namespace Macopedia\Allegro\Model\Api;

use Macopedia\Allegro\Api\Data\TokenInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Macopedia\Allegro\Logger\Logger;
use Macopedia\Allegro\Model\Configuration;

/**
 * Class Client
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
     * Client constructor.
     * @param Json $json
     * @param Logger $logger
     * @param Configuration $config
     */
    public function __construct(
        Json $json,
        Logger $logger,
        Configuration $config
    ) {
        $this->json = $json;
        $this->logger = $logger;
        $this->config = $config;
    }

    /**
     * @param TokenInterface $token
     * @param Request $request
     * @return mixed
     * @throws ClientResponseException
     * @throws ClientResponseErrorException
     */
    public function sendRequest(TokenInterface $token, Request $request)
    {
        $json = $this->sendHttpRequest($token, $request);

        $response = $this->json->unserialize($json);

        if (!$response) {
            throw new ClientResponseException(__('Error while receiving response from Allegro API'));
        }

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
            'Authorization: Bearer ' . $token->getAccessToken(),
            'Accept: ' . $request->getAcceptType() ?: Request::TYPE_BETA,
            'Content-Type: ' . $request->getContentType() ?: Request::TYPE_BETA
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
     */
    private function sendHttpRequest(TokenInterface $token, Request $request)
    {
        $isJson = preg_match('/application\/.*json/', $request->getContentType());
        $content = $isJson ? $this->json->serialize($request->getBody()) : $request->getBody();
        $options = [
            'http' => [
                'method' => $request->getMethod(),
                'header' => implode("\r\n", $this->prepareHeaders($token, $request)),
                'content' => $content,
                'ignore_errors' => true,
                'timeout' => 120
            ]
        ];
        $context = stream_context_create($options);

        $requestId = uniqid('', true);

        if ($this->config->isDebugModeEnabled()) {
            $this->logger->debug($requestId . ': ' . $request->getMethod() . ' ' . $request->getUri() . $content);
        }

        $response = file_get_contents($this->getApiUrl($request) . $request->getUri(), false, $context);

        if ($this->config->isDebugModeEnabled()) {
            $this->logger->debug($requestId . ': ' . $response);
        }

        return $response;
    }
}
