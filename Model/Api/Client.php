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
        $content = preg_match('/application\/.*json/', $request->getContentType())
            ? $this->json->serialize($request->getBody())
            : $request->getBody();
        $url = $this->getApiUrl($request) . $request->getUri();
        $context = stream_context_create([
            'http' => [
                'method' => $request->getMethod(),
                'header' => implode("\r\n", $this->prepareHeaders($token, $request)),
                'content' => $content,
                'ignore_errors' => true,
                'timeout' => 120
            ]
        ]);

        if (!$this->config->isDebugModeEnabled()) {
            return $this->fileGetContents($url, $context);
        }

        $requestId = uniqid('', true);
        $this->logger->debug('ALLEGRO API HTTP REQUEST ' . $requestId . ': ' . $request->getMethod() . ' ' . $request->getUri() . $content);
        $response = $this->fileGetContents($url, $context);
        $this->logger->debug('ALLEGRO API HTTP RESPONSE ' . $requestId . ': ' . $response);

        return $response;
    }

    /**
     * @param string $url
     * @param $context
     * @return false|string
     */
    private function fileGetContents(string $url, $context)
    {
        return file_get_contents($url, false, $context);
    }

}
