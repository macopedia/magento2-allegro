<?php

namespace Macopedia\Allegro\Model\Api;

use Macopedia\Allegro\Api\Data\TokenInterface;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * Class Client
 */
class Client
{
    const CONTENT_TYPE_PUBLIC = 'application/vnd.allegro.public.v1+json';
    const CONTENT_TYPE_BETA = 'application/vnd.allegro.beta.v1+json';
    const API_URL = 'https://api.allegro.pl/';
    const SANDBOX_API_URL = 'https://api.allegro.pl.allegrosandbox.pl/';

    /** @var Json */
    private $json;

    /**
     * Client constructor.
     * @param Json $json
     */
    public function __construct(Json $json)
    {
        $this->json = $json;
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
        $url = $this->getApiUrl($request) . $request->getUri();
        $headers = $this->prepareHeaders($token, $request);

        $json = $this->sendHttpRequest($url, $request->getMethod(), $headers, $request->getBody());

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
        $contentType = $request->getContentType();
        $contentType = $contentType ? $contentType : self::CONTENT_TYPE_BETA;

        return [
            'Authorization: Bearer ' . $token->getAccessToken(),
            'Accept: ' . $contentType,
            'Content-Type: ' . $contentType
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
     * @param string $url
     * @param string $method
     * @param array $headers
     * @param string $data
     * @return bool|string
     */
    private function sendHttpRequest($url, $method, $headers = [], $data = '')
    {
        $options = [
            'http' => [
                'method' => $method,
                'header' => implode("\r\n", $headers),
                'content' => $this->json->serialize($data),
                'ignore_errors' => true
            ]
        ];
        $context = stream_context_create($options);

        return file_get_contents($url, false, $context);
    }
}
